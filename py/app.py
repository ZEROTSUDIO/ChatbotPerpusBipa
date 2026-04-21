from flask import Flask, request, jsonify
from flask_cors import CORS
from transformers import AutoModelForSequenceClassification, AutoTokenizer
from sentence_transformers import SentenceTransformer
from sklearn.metrics.pairwise import cosine_similarity
import torch
import numpy as np
import pickle
import os
import json
import logging
import re
import nltk
from nltk.corpus import stopwords
from nltk.stem import WordNetLemmatizer

# Download necessary NLTK resources
nltk.download('stopwords', quiet=True)
nltk.download('punkt', quiet=True)
nltk.download('wordnet', quiet=True)

# Configure logging
logging.basicConfig(level=logging.INFO,
                    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

app = Flask(__name__)
CORS(app)

# Global variables and constants
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
INTENT_MODEL_PATH = os.path.join(BASE_DIR, "model")
RECOMMENDER_MODEL_PATH = os.path.join(BASE_DIR, "recommender_model")

intent_model = None
intent_tokenizer = None
intent_classes = None
intent_thresholds = None
recommender = None
recommender_model_loaded = False

class BookRecommender:
    def __init__(self, model_name='all-minilm-l6-v2'):
        self.model_name = model_name
        self.model = None
        self.book_embeddings = None
        self.df = None
        self.stop_words = set(stopwords.words('english'))
        self.lemmatizer = WordNetLemmatizer()

    def preprocess_text(self, text):
        if not isinstance(text, str):
            return ""
        text = text.lower()
        text = re.sub(r'[^\w\s]', ' ', text)
        tokens = nltk.word_tokenize(text)
        tokens = [self.lemmatizer.lemmatize(word) for word in tokens if word not in self.stop_words]
        return ' '.join(tokens)

    def load_model(self, folder_path=RECOMMENDER_MODEL_PATH):
        try:
            if not os.path.exists(folder_path):
                return False
            with open(os.path.join(folder_path, "config.pkl"), 'rb') as f:
                config = pickle.load(f)
            self.model_name = config['model_name']
            self.model = SentenceTransformer(os.path.join(folder_path, "sentence_transformer"))
            with open(os.path.join(folder_path, "book_embeddings.pkl"), 'rb') as f:
                self.book_embeddings = pickle.load(f)
            with open(os.path.join(folder_path, "books_data.pkl"), 'rb') as f:
                self.df = pickle.load(f)
            return True
        except Exception as e:
            logger.error(f"Error loading model: {str(e)}", exc_info=True)
            return False

    def recommend_books(self, user_query, top_n=5, include_description=True):
        if self.model is None or self.book_embeddings is None or self.df is None:
            return []
        try:
            processed_query = self.preprocess_text(user_query)
            user_embedding = self.model.encode([processed_query])
            similarities = cosine_similarity(user_embedding, self.book_embeddings)[0]
            similar_books_idx = np.argsort(similarities)[-top_n:][::-1]
            recommendations = []
            for i, idx in enumerate(similar_books_idx):
                book_data = {
                    'title': self.df.iloc[idx].get('Title', ''),
                    'author': self.df.iloc[idx].get('Authors', ''),
                    'category': self.df.iloc[idx].get('Category', ''),
                    'year': self.df.iloc[idx].get('Publish Date (Year)', ''),
                    'description': self.df.iloc[idx].get('Description', '')[:197] + "..." if include_description and 'Description' in self.df.columns else '',
                    'relevance_score': float(similarities[idx]),
                    'rank': i + 1
                }
                recommendations.append(book_data)
            return recommendations
        except Exception as e:
            logger.error(f"Error generating recommendations: {str(e)}", exc_info=True)
            return []


def load_ood_thresholds(model_path):
    threshold_path = os.path.join(model_path, "ood_thresholds.json")
    if os.path.exists(threshold_path):
        with open(threshold_path, "r") as f:
            return json.load(f)
    return {"energy_threshold": 0.0, "msp_threshold": 0.5}


def load_intent_resources():
    global intent_model, intent_tokenizer, intent_classes, intent_thresholds
    try:
        intent_model = AutoModelForSequenceClassification.from_pretrained("ZEROTSUDIOS/Bipa-Classification")
        intent_tokenizer = AutoTokenizer.from_pretrained("ZEROTSUDIOS/Bipa-Classification")
        with open(os.path.join(INTENT_MODEL_PATH, "intent_classes.pkl"), "rb") as f:
            intent_classes = pickle.load(f)
        intent_thresholds = load_ood_thresholds(INTENT_MODEL_PATH)
        return True
    except Exception as e:
        logger.error(f"Failed to load intent resources: {str(e)}", exc_info=True)
        return False


def predict_intent(text, method='combined'):
    inputs = intent_tokenizer(text, return_tensors="pt", padding=True, truncation=True, max_length=512)
    with torch.no_grad():
        outputs = intent_model(**inputs)
        logits = outputs.logits
    probs = torch.nn.functional.softmax(logits, dim=-1)
    max_prob, pred_idx = torch.max(probs, dim=-1)
    energy = -torch.logsumexp(logits, dim=-1)
    is_ood = False
    if method == 'energy':
        is_ood = energy.item() > intent_thresholds['energy_threshold']
    elif method == 'msp':
        is_ood = max_prob.item() < intent_thresholds['msp_threshold']
    elif method == 'combined':
        is_ood = (energy.item() > intent_thresholds['energy_threshold']) and (max_prob.item() < intent_thresholds['msp_threshold'])
    return {
        "intent": intent_classes[pred_idx.item()],
        "is_ood": is_ood,
        "confidence": max_prob.item(),
        "energy_score": energy.item()
    }


@app.route('/api/analyze', methods=['POST'])
def analyze():
    if not request.is_json:
        return jsonify({"error": "Request must be JSON"}), 400
    data = request.get_json()
    text = data.get('text')
    method = data.get('method', 'combined')
    result = predict_intent(text, method)
    return jsonify(result)


@app.route('/api/recommend', methods=['POST'])
def recommend():
    global recommender_model_loaded
    if not recommender_model_loaded:
        return jsonify({"error": "Recommendation model not loaded."}), 503
    data = request.get_json()
    query = data.get('query')
    top_n = data.get('top_n', 5)
    include_description = data.get('include_description', True)
    threshold = data.get('threshold', 0.5)
    if not query:
        return jsonify({"error": "Missing query."}), 400
    recommendations = recommender.recommend_books(query, top_n=top_n, include_description=include_description)
    high_score = [rec for rec in recommendations if rec['relevance_score'] >= threshold]
    low_score = [rec for rec in recommendations if rec['relevance_score'] < threshold]
    return jsonify({
        "query": query,
        "threshold": threshold,
        "high_recommendations": high_score,
        "low_recommendations": low_score,
        "total_count": len(recommendations),
        "high_count": len(high_score),
        "low_count": len(low_score)
    })


if __name__ == '__main__':
    load_intent_resources()
    recommender = BookRecommender()
    recommender_model_loaded = recommender.load_model()
    port = int(os.environ.get('PORT', 5000))
    app.run(host='0.0.0.0', port=port, debug=False, use_reloader=False)
