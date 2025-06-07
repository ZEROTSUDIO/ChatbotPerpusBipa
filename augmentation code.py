# @title Dataset Augmentation for Indonesian NLP - Improved Version
import pandas as pd
import random
import re
import nltk
import torch
import time
import os
import json
import matplotlib.pyplot as plt
import numpy as np
from nltk.corpus import wordnet
from transformers import AutoTokenizer, AutoModelForSeq2SeqLM
from deep_translator import GoogleTranslator
from collections import defaultdict, Counter
from tqdm import tqdm
import Levenshtein as lev  # For better text difference calculation
augmentation_method_counts = defaultdict(int)
# Download WordNet data (if not already downloaded)
nltk.download('wordnet', quiet=True)
nltk.download('omw-1.4', quiet=True)

# =========[ KONFIGURASI ]=========
# After your drive mount and FName setup, add:
DATASET_SAVE_PATH = f"{MODEL_SAVE_PATH}/dataset"
# Create directories if they don't exist
os.makedirs(DATASET_SAVE_PATH, exist_ok=True)
os.makedirs(f"{DATASET_SAVE_PATH}/reports", exist_ok=True)

INPUT_FILE = "/content/ChatbotPerpusBipa/train.xlsx" # @param {"type":"string"}
DATA_TYPE = "train" # @param ["train", "val", "test"]
TARGET_SAMPLES_PER_CLASS = 1000    # @param {type:"integer"} Target jumlah sampel per kelas
NOISE_INTENSITY = 0.7             # @param {type:"number"} Control how aggressive augmentations are (0.1-1.0)
USE_PARAPHRASE_MODEL = True       # @param {type:"boolean"} Aktifkan atau matikan paraphrase
USE_BACK_TRANSLATION = True       # @param {type:"boolean"} Aktifkan atau matikan back-translation
MIN_AUGMENTATIONS_PER_SAMPLE = 2  # @param {type:"integer"} Minimum augmentasi per sampel asli
MAX_AUGMENTATIONS_PER_SAMPLE = 20  # @param {type:"integer"} Maximum augmentasi per sampel asli (reduced from 10)
BATCH_SIZE = 16                   # @param {type:"integer"} Untuk batch processing
PARAPHRASE_RATIO = 0.4            # @param {type:"number"} Maksimal 40% dari total augmentasi adalah paraphrase
REGULAR_AUG_RATIO = 0.6           # @param {type:"number"} Minimal 60% dari total augmentasi adalah metode lain

def load_from_json(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        return json.load(f)

def load_all_dictionaries():
    all_dicts = load_from_json('/content/ChatbotPerpusBipa/kamus.json')

    id_synonyms = all_dicts['id_synonyms']
    common_slang = all_dicts['common_slang']
    intent_slang = all_dicts['intent_slang']
    phonetic_dict = all_dicts['phonetic_dict']
    protected_intent_words = all_dicts['protected_intent_words']

    return id_synonyms, common_slang, intent_slang, phonetic_dict, protected_intent_words

# Option 2:
id_synonyms, common_slang, intent_slang, phonetic_dict, protected_intent_words = load_all_dictionaries()

# =========[ READ & VALIDATE FILE ]=========
def read_dataset(file_path):
    """Membaca dataset dari file CSV atau XLSX"""
    print(f"Loading dataset: {file_path}")

    file_ext = os.path.splitext(file_path)[1].lower()

    if file_ext == '.xlsx':
        print(f"Detected Excel file: {file_path}")
        df = pd.read_excel(file_path)
        # Konversi ke CSV untuk kompatibilitas
        csv_path = file_path.replace('.xlsx', '.csv')
        df.to_csv(csv_path, index=False)
        print(f"Converted Excel file to CSV: {csv_path}")
    elif file_ext == '.csv':
        print(f"Detected CSV file: {file_path}")
        df = pd.read_csv(file_path)
    else:
        raise ValueError(f"Format file tidak didukung: {file_ext}. Harap gunakan file CSV atau XLSX.")

    df = df.dropna()
    print(f"Dataset dimuat dengan {len(df)} baris")

    return df

# =========[ INITIALIZE PARAPHRASE MODEL IF NEEDED ]=========
def initialize_paraphrase_model():
    """Initialize paraphrase model if enabled"""
    if USE_PARAPHRASE_MODEL:
        print("Loading paraphrase model...")
        start_time = time.time()
        tokenizer = AutoTokenizer.from_pretrained("Wikidepia/IndoT5-base-paraphrase")
        model = AutoModelForSeq2SeqLM.from_pretrained("Wikidepia/IndoT5-base-paraphrase")

        # Move model to GPU if available
        device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')
        model = model.to(device)
        print(f"Model loaded in {time.time() - start_time:.2f} seconds. Using device: {device}")
        return model, tokenizer
    return None, None

# =========[ AUGMENTATION METHODS ]=========
def get_better_synonym(word):
    """Get synonym from custom dictionary or return the original word"""
    word_lower = word.lower()
    if word_lower in id_synonyms:
        synonyms = id_synonyms[word_lower]
        return random.choice(synonyms)
    return word

def replace_with_synonym(sentence):
    """Replace words with synonyms while preserving capitalization"""
    words = sentence.split()
    new_words = []

    # Limit the number of words to replace to avoid excessive changes
    num_to_replace = min(2, max(1, int(len(words) * 0.2)))
    indices_to_replace = random.sample(range(len(words)), k=min(num_to_replace, len(words)))

    for i, word in enumerate(words):
        if i in indices_to_replace:
            synonym = get_better_synonym(word)
            # Preserve capitalization
            if word and word[0].isupper() and synonym:
                synonym = synonym[0].upper() + synonym[1:]
            new_words.append(synonym)
        else:
            new_words.append(word)

    return " ".join(new_words)

def back_translate(sentence):
    """Translate to English and back to Indonesian with safety checks"""
    if not USE_BACK_TRANSLATION:
        return sentence

    # Skip very short sentences
    if len(sentence.split()) < 3:
        return sentence

    try:
        # First to English
        translated = GoogleTranslator(source='id', target='en').translate(sentence)
        # Then back to Indonesian
        back_translated = GoogleTranslator(source='en', target='id').translate(translated)

        # Safety checks
        if back_translated and len(back_translated.split()) >= len(sentence.split()) * 0.7:
            # Calculate how different the result is
            similarity = 1 - (lev.distance(sentence.lower(), back_translated.lower()) / max(len(sentence), len(back_translated)))
            # If too different or too similar, return original
            if similarity < 0.3 or similarity > 0.9:
                return sentence
            return back_translated
        return sentence
    except Exception:
        return sentence

def add_typo(sentence):
    """Add a single typo by replacing a character, with reduced probability"""
    # Skip for very short sentences or with low global noise setting
    if len(sentence) < 10 or random.random() > NOISE_INTENSITY:
        return sentence

    chars = list(sentence)
    if len(chars) > 3:
        # Try to find a good character to modify (not first or last character)
        candidates = [i for i in range(1, len(chars)-1) if chars[i].isalpha()]
        if candidates:
            idx = random.choice(candidates)
            # Get neighboring letters on keyboard for more realistic typos
            keyboard_neighbors = {
                'q': 'wsa', 'w': 'qeasd', 'e': 'wrsdf', 'r': 'etdfg',
                't': 'ryfgh', 'y': 'tughj', 'u': 'yihjk', 'i': 'uojkl',
                'o': 'ipkl', 'p': 'ol',
                'a': 'qwszx', 's': 'awedcxz', 'd': 'serfcvx', 'f': 'drtgvbc',
                'g': 'ftyhvbn', 'h': 'gyujbnm', 'j': 'huiknm', 'k': 'jiolm',
                'l': 'kop',
                'z': 'asx', 'x': 'zsdc', 'c': 'xdfv', 'v': 'cfgb',
                'b': 'vghn', 'n': 'bhjm', 'm': 'njk'
            }
            char = chars[idx].lower()
            if char in keyboard_neighbors:
                chars[idx] = random.choice(keyboard_neighbors[char])

    return "".join(chars)

def random_deletion(sentence, p=0.1):  # Reduced probability from 0.2
    """Delete words with probability p"""
    words = sentence.split()

    # Don't delete from short sentences
    if len(words) <= 4:
        return sentence

    # Don't delete too many words
    max_deletions = max(1, int(len(words) * 0.1))
    deletion_count = 0

    new_words = []
    for word in words:
        if random.uniform(0, 1) > p or deletion_count >= max_deletions:
            new_words.append(word)
        else:
            deletion_count += 1

    # Make sure we don't delete everything
    if not new_words:
        return sentence

    return " ".join(new_words)

def random_swap(sentence, n=1):
    """Swap n pairs of words"""
    words = sentence.split()
    if len(words) < 4:  # Don't swap in very short sentences
        return sentence

    # Limit swaps to just 1 for shorter sentences
    if len(words) < 8:
        n = 1

    for _ in range(min(n, len(words)//3)):  # Reduced number of swaps
        idx1, idx2 = random.sample(range(len(words)), 2)
        words[idx1], words[idx2] = words[idx2], words[idx1]

    return " ".join(words)

def phonetic_augmentation(sentence):
    """Apply phonetic substitutions common in Indonesian chat"""
    words = sentence.split()
    new_words = []

    # Limit substitutions to maintain readability
    max_substitutions = min(2, max(1, int(len(words) * 0.2)))
    substitution_count = 0

    for word in words:
        word_lower = word.lower()
        if word_lower in phonetic_dict and substitution_count < max_substitutions:
            new_word = random.choice(phonetic_dict[word_lower])
            # Preserve capitalization
            if word and word[0].isupper():
                new_word = new_word[0].upper() + new_word[1:]
            new_words.append(new_word)
            substitution_count += 1
        else:
            new_words.append(word)

    return " ".join(new_words)

def apply_slang_typo(text, intent, intensity=1.0):
    """Apply slang replacements with controllable intensity"""
    # Combine common slang with intent-specific slang
    slang_dict = common_slang.copy()
    if intent in intent_slang:
        slang_dict.update(intent_slang[intent])

    # Create regex patterns from the slang dictionary
    patterns = {
        re.compile(rf'\b{k}\b', re.IGNORECASE): v for k, v in slang_dict.items()
    }

    # Apply only a few patterns based on intensity and text length
    max_replacements = min(2, max(1, int(len(text.split()) * 0.2)))
    patterns_to_use = random.sample(
        list(patterns.items()),
        k=min(max_replacements, int(len(patterns) * min(0.3, intensity * 0.5)))
    )

    for pattern, replacement in patterns_to_use:
        text = pattern.sub(replacement, text)

    return text

def character_noise(text, intensity=1.0):
    """Add typos like character swaps, insertions, deletions with reduced intensity"""
    # Skip for very short texts
    if len(text) < 10 or random.random() > NOISE_INTENSITY:
        return text

    chars = list(text)
    # Significantly reduce swap probability
    swap_prob = min(0.03, intensity * 0.01)  # Lower from 0.1 to 0.03

    # Limit to just one or two swaps per sentence
    max_swaps = min(1, int(len(chars) * 0.05))
    swap_count = 0

    for i in range(len(chars)-1):
        if random.random() < swap_prob and swap_count < max_swaps:
            # Don't swap punctuation or spaces
            if chars[i].isalpha() and chars[i+1].isalpha():
                chars[i], chars[i+1] = chars[i+1], chars[i]
                swap_count += 1

    return ''.join(chars)

def add_common_phrase(sentence):
    """Add a common Indonesian chat phrase with probability control"""
    # Skip for longer sentences or with probability
    if len(sentence.split()) > 8 or random.random() > 0.33:  # Only 30% chance
        return sentence

    common_phrases = ["sih", "ya", "dong", "cuy", "bro", "lah", "deh", "nigga", "min"]
    return sentence + " " + random.choice(common_phrases)

def short_text_augmentation(text, intent):
    """Special augmentation for very short texts like greetings and goodbyes"""
    # For very short texts, add filler words or expressions
    fillers = {
        'greeting': ['', ' ya', ' kak', ' min', ' gan', ' bro', ' sis', ' admin', '!'],
        'goodbye': ['', ' ya', ' kak', ' min', ' semuanya', '!'],
        'confirm': ['', ' kok', ' dong', ' banget', ' sih', ' tentu', ' lah', '!'],
        'denied': ['', ' sih', ' kok', ' ah', ' deh', ' lah', '!'],
    }

    if intent in fillers and len(text.split()) <= 3:
        # Add just one filler
        if random.random() < 0.7:  # 70% chance to add filler
            text += random.choice(fillers[intent])

    return text

def validate_augmentation(original, augmented):
    """Validate if augmentation is reasonable with stricter requirements"""
    # Skip if no change
    if augmented.lower() == original.lower():
        return False

    # Calculate word count difference
    orig_words = original.split()
    aug_words = augmented.split()

    # Check if length is reasonable
    if len(aug_words) < len(orig_words) * 0.6 or len(aug_words) > len(orig_words) * 1.4:
        return False

    # Calculate text similarity using Levenshtein distance
    normalized_distance = lev.distance(original.lower(), augmented.lower()) / max(len(original), len(augmented))
    # If too similar or too different, reject
    if normalized_distance < 0.03 or normalized_distance > 0.5:
        return False

    # Check for excessive non-standard characters
    non_indo_pattern = re.compile(r'[^a-zA-Z0-9\s.,?!\'"-:;()[\]{}]')
    if len(non_indo_pattern.findall(augmented)) > 3:
        return False

    # Check if individual words have been mangled too much
    if len(orig_words) == len(aug_words):
        word_changes = 0
        for i in range(len(orig_words)):
            # Check word edit distance
            if len(orig_words[i]) > 3 and lev.distance(orig_words[i], aug_words[i]) > len(orig_words[i]) * 0.5:
                word_changes += 1

        # Reject if too many words changed significantly
        if word_changes / len(orig_words) > 0.4:
            return False

    return True

def batch_paraphrase(model, tokenizer, sentences, batch_size=BATCH_SIZE):
    """Process paraphrasing in batches"""
    if not sentences or model is None or tokenizer is None:
        return []

    device = next(model.parameters()).device
    results = []

    for i in range(0, len(sentences), batch_size):
        batch = sentences[i:i+batch_size]
        inputs = tokenizer(["paraphrase: " + text + " </s>" for text in batch],
                         padding='longest', truncation=True, max_length=128,
                         return_tensors="pt").to(device)

        with torch.no_grad():  # Disable gradient calculation for inference
            outputs = model.generate(
                input_ids=inputs["input_ids"],
                attention_mask=inputs["attention_mask"],
                max_length=128,
                do_sample=True,
                top_k=200,  # Reduce from 200 to 120 for more conservative output
                top_p=0.98,
                temperature=NOISE_INTENSITY + 0.3, # Added temperature control
                early_stopping=False,
                num_return_sequences=min(3, BATCH_SIZE // len(batch))
            )

        decoded = [tokenizer.decode(outputs[j], skip_special_tokens=True)
                  for j in range(len(outputs))]
        results.extend(decoded)

    return results

# Combined augmentation strategies
def augment_text_with_tracking(text, intent, intensity=1.0):
    """Apply multiple augmentation techniques with method tracking"""
    # Scale intensity by global noise setting
    intensity = intensity * NOISE_INTENSITY

	# Global method usage control - tambahkan ini
    global augmentation_method_counts
    total_augmentations = sum(augmentation_method_counts.values())

    # Jika common_phrase sudah terlalu banyak, kurangi probabilitasnya
    common_phrase_ratio = augmentation_method_counts.get('common_phrase', 0) / max(1, total_augmentations)
    if common_phrase_ratio > 0.25:  # Jika lebih dari 25%
        # Kurangi drastis kemungkinan common_phrase dipilih
        skip_common_phrase = True
    else:
        skip_common_phrase = False

    # Protect intent-critical words
    protected = []
    if intent in protected_intent_words:
        for word in protected_intent_words[intent]:
            pattern = re.compile(rf'\b{word}\b', re.IGNORECASE)
            for match in pattern.finditer(text):
                placeholder = f"__PROTECTED_{len(protected)}__"
                text = text[:match.start()] + placeholder + text[match.end():]
                protected.append((placeholder, match.group(0)))

    # Available methods - reorder by safety
    # Available methods - reorder by safety
    methods = {
        'synonym': replace_with_synonym,
        'back_translate': back_translate if USE_BACK_TRANSLATION else None,
        'slang': lambda t: apply_slang_typo(t, intent, intensity),
        'common_phrase': add_common_phrase if not skip_common_phrase else None,  # Conditional
        'short_text': lambda t: short_text_augmentation(t, intent),
        'swap': random_swap,
        'deletion': random_deletion,
        'phonetic': phonetic_augmentation,
        'char_noise': lambda t: character_noise(t, intensity * 0.5),
        'typo': add_typo
    }

    # Remove None methods
    methods = {k: v for k, v in methods.items() if v is not None}

    # Choose augmentation methods based on text length and intent
    text_length = len(text.split())

    if text_length <= 3:
        method_choices = ['slang', 'short_text', 'synonym', 'common_phrase']
        num_methods = min(2, int(intensity * 2))
    else:
        # Kurangi frekuensi common_phrase dengan menghilangkan duplikasi
        method_choices = ['synonym', 'synonym', 'back_translate', 'slang', 'slang',
                          'swap', 'deletion', 'phonetic', 'char_noise', 'common_phrase']  # Pindah ke akhir
        num_methods = min(2, int(intensity * 1.5))

    method_choices = [m for m in method_choices if m in methods]

    if method_choices:
        # Weighted selection untuk mengurangi dominasi method tertentu
        method_weights = {
            'synonym': 3, 'back_translate': 2, 'slang': 2, 'swap': 2,
            'deletion': 2, 'phonetic': 2, 'char_noise': 1,
            'common_phrase': 1,  # Kurangi weight common_phrase
            'short_text': 2
        }

        # Filter method_choices berdasarkan weight
        weighted_choices = []
        for method in method_choices:
            weight = method_weights.get(method, 1)
            weighted_choices.extend([method] * weight)

        # Sample dengan replacement untuk menghindari dominasi
        selected_methods = []
        for _ in range(min(num_methods, len(method_choices))):
            if weighted_choices:
                method = random.choice(weighted_choices)
                selected_methods.append(method)
                # Remove beberapa instance untuk mengurangi kemungkinan dipilih lagi
                weighted_choices = [m for m in weighted_choices if m != method or random.random() > 0.7]
    else:
        selected_methods = []

    # Apply selected methods and track usage
    result = text
    methods_used = []
    for method_name in selected_methods:
        if method_name in methods:
            method = methods[method_name]
            old_result = result
            result = method(result)

            # Track if method actually changed the text
            if result != old_result:
                methods_used.append(method_name)
                augmentation_method_counts[method_name] += 1

    # Restore protected words
    for placeholder, original in protected:
        result = result.replace(placeholder, original)

    return result, methods_used

# Modifikasi fungsi augment_data untuk menggunakan tracking
def augment_data_with_tracking(text, intent):
    """Generate multiple augmentations with method tracking"""
    methods = [
        ('synonym_replacement', replace_with_synonym),
        ('back_translation', back_translate if USE_BACK_TRANSLATION else None),
        ('combined_augmentation', lambda t: augment_text_with_tracking(t, intent, 1.0)[0])
    ]

    methods = [(name, m) for name, m in methods if m is not None]

    augmented = set()
    for method_name, method in methods:
        try:
            if method_name == 'combined_augmentation':
                result, _ = augment_text_with_tracking(text, intent, 1.0)
            else:
                result = method(text)
                if result != text:
                    augmentation_method_counts[method_name] += 1

            if validate_augmentation(text, result):
                augmented.add(result)
        except Exception as e:
            print(f"Error applying {method_name}: {str(e)}")
            continue

    return list(augmented)

# Fungsi untuk visualisasi metode augmentasi
def plot_augmentation_methods():
    """Plot distribusi penggunaan metode augmentasi"""
    if not augmentation_method_counts:
        print("Tidak ada data metode augmentasi untuk divisualisasikan")
        return

    # Siapkan data
    methods = list(augmentation_method_counts.keys())
    counts = list(augmentation_method_counts.values())

    # Buat plot
    plt.figure(figsize=(12, 8))

    # Bar plot
    plt.subplot(2, 1, 1)
    bars = plt.bar(methods, counts, color='lightcoral', alpha=0.7)
    plt.title('Jumlah Data yang Dihasilkan per Metode Augmentasi', fontsize=14, fontweight='bold')
    plt.xlabel('Metode Augmentasi')
    plt.ylabel('Jumlah Data')
    plt.xticks(rotation=45, ha='right')
    plt.grid(axis='y', linestyle='--', alpha=0.3)

    # Tambahkan nilai di atas bar
    for bar, count in zip(bars, counts):
        plt.text(bar.get_x() + bar.get_width()/2, bar.get_height() + max(counts)*0.01,
                str(count), ha='center', va='bottom', fontweight='bold')

    # Pie chart
    plt.subplot(2, 1, 2)
    colors = plt.cm.Set3(range(len(methods)))
    wedges, texts, autotexts = plt.pie(counts, labels=methods, autopct='%1.1f%%',
                                       colors=colors, startangle=90)
    plt.title('Persentase Kontribusi Metode Augmentasi', fontsize=14, fontweight='bold')

    # Perbaiki tampilan
    for autotext in autotexts:
        autotext.set_color('white')
        autotext.set_fontweight('bold')

    plt.tight_layout()
    plt.savefig(f"{DATASET_SAVE_PATH}/reports/augmentation_methods_distribution.png", dpi=300, bbox_inches='tight')
    plt.show()

    # Print statistik detail
    print("\n" + "="*50)
    print("STATISTIK METODE AUGMENTASI")
    print("="*50)

    total_augmented = sum(counts)
    print(f"Total data augmentasi: {total_augmented}")
    print("\nDetail per metode:")

    # Sort berdasarkan jumlah (descending)
    sorted_methods = sorted(augmentation_method_counts.items(), key=lambda x: x[1], reverse=True)

    for method, count in sorted_methods:
        percentage = (count / total_augmented) * 100 if total_augmented > 0 else 0
        print(f"  {method:20s}: {count:6d} data ({percentage:5.1f}%)")

    print("="*50)

def balance_samples(results_by_intent, target_samples_per_class, original_counts):
    """
    Balance samples by intent with preference toward reaching TARGET_SAMPLES_PER_CLASS
    """
    balanced_results = defaultdict(list)

    for intent, samples in results_by_intent.items():
        orig_count = original_counts.get(intent, 0)
        current_count = len(samples)

        # Keep all original data
        original_data = samples[:orig_count]
        balanced_results[intent].extend(original_data)

        # Get augmented samples (everything after original data)
        augmented_data = samples[orig_count:]

        # Calculate how many we need
        remaining_slots = target_samples_per_class - orig_count

        if remaining_slots > 0:
            # If we have enough augmented samples
            if len(augmented_data) >= remaining_slots:
                # Randomize selection
                random.shuffle(augmented_data)
                # Add what we need
                balanced_results[intent].extend(augmented_data[:remaining_slots])
            else:
                # If we don't have enough, add all augmented samples
                balanced_results[intent].extend(augmented_data)
                # And duplicate some if needed (to reach closer to target)
                shortage = remaining_slots - len(augmented_data)
                if shortage > 0 and len(augmented_data) > 0:
                    # Add duplicates of existing augmentations to help reach target
                    extras = random.choices(augmented_data, k=min(shortage, len(augmented_data) * 2))
                    balanced_results[intent].extend(extras)

    return balanced_results

def plot_distribution(data, title):
    """Plot distribution of samples by intent"""
    plt.figure(figsize=(10, 5))
    data['intent'].value_counts().sort_index().plot(kind='bar', color='skyblue')
    plt.title(title)
    plt.xlabel("Intent")
    plt.ylabel("Jumlah Sampel")
    plt.xticks(rotation=45)
    plt.grid(axis='y', linestyle='--', alpha=0.5)
    plt.tight_layout()
    filename = title.lower().replace(" ", "_").replace("distribution", "dist")
    plt.savefig(f"{DATASET_SAVE_PATH}/reports/{filename}.png", dpi=300, bbox_inches='tight')
    plt.show()

# ADD: New function to save comprehensive report
def save_augmentation_report(final_df, original_df, time_taken, output_path):
    """Save a comprehensive text report of the augmentation process"""
    report_path = f"{DATASET_SAVE_PATH}/reports/augmentation_report.txt"

    with open(report_path, 'w', encoding='utf-8') as f:
        f.write("="*60 + "\n")
        f.write("DATASET AUGMENTATION REPORT\n")
        f.write("="*60 + "\n\n")

        # Original statistics
        f.write("ORIGINAL DATASET:\n")
        f.write("-" * 20 + "\n")
        original_counts = Counter(original_df['intent'])
        for intent, count in original_counts.items():
            f.write(f"  {intent}: {count}\n")
        f.write(f"Total original samples: {len(original_df)}\n\n")

        # Final statistics
        f.write("FINAL DATASET:\n")
        f.write("-" * 20 + "\n")
        final_counts = Counter(final_df['intent'])
        for intent, count in final_counts.items():
            orig = original_counts.get(intent, 0)
            added = count - orig
            f.write(f"  {intent}: {count} total ({orig} original + {added} augmented)\n")
        f.write(f"Total final samples: {len(final_df)}\n\n")

        # Method statistics
        f.write("AUGMENTATION METHODS USED:\n")
        f.write("-" * 30 + "\n")
        total_augmented = sum(augmentation_method_counts.values())
        for method, count in sorted(augmentation_method_counts.items(), key=lambda x: x[1], reverse=True):
            percentage = (count / total_augmented) * 100 if total_augmented > 0 else 0
            f.write(f"  {method:20s}: {count:6d} ({percentage:5.1f}%)\n")

        f.write(f"\nProcessing time: {time_taken:.2f} seconds\n")
        f.write(f"Augmentation ratio: {len(final_df) / len(original_df):.2f}x\n")

    print(f"Comprehensive report saved to: {report_path}")

# =========[ MAIN PROCESS ]=========
# Add target method ratios at module level
TARGET_METHOD_RATIOS = {
    'synonym': 0.25, 'back_translate': 0.15, 'slang': 0.15,
    'swap': 0.12, 'deletion': 0.10, 'phonetic': 0.10,
    'char_noise': 0.08, 'common_phrase': 0.05
}

def get_adaptive_weights(base_weights, current_counts, total_target):
    """Adjust weights based on current usage to maintain balance"""
    adjusted_weights = base_weights.copy()
    total_current = sum(current_counts.values())
    
    if total_current > 0:
        for method in adjusted_weights:
            current_ratio = current_counts.get(method, 0) / total_current
            target_ratio = base_weights[method]
            
            # Reduce weight if method is overused
            if current_ratio > target_ratio * 1.5:
                adjusted_weights[method] *= 0.5
            # Increase weight if method is underused
            elif current_ratio < target_ratio * 0.5:
                adjusted_weights[method] *= 1.5
    
    return adjusted_weights

def get_adaptive_method_selection(text_length, intensity, current_counts, total_augmentations, methods):
    """Get methods with adaptive weighting based on current distribution"""
    
    if text_length <= 3:
        base_weights = {'slang': 0.4, 'short_text': 0.3, 'synonym': 0.2, 'common_phrase': 0.1}
        num_methods = min(2, int(intensity * 2))
    else:
        base_weights = TARGET_METHOD_RATIOS.copy()
        num_methods = min(2, int(intensity * 1.5))
    
    # Apply adaptive weighting
    if total_augmentations > 50:  # Only adjust after some data
        adjusted_weights = get_adaptive_weights(base_weights, current_counts, total_augmentations)
    else:
        adjusted_weights = base_weights
    
    # Select methods based on adjusted weights
    available_methods = [m for m in adjusted_weights.keys() if m in methods]
    if available_methods:
        weights = [adjusted_weights[m] for m in available_methods]
        weights_sum = sum(weights)
        if weights_sum > 0:
            normalized_weights = [w/weights_sum for w in weights]
            try:
                selected = np.random.choice(
                    available_methods, 
                    size=min(num_methods, len(available_methods)), 
                    p=normalized_weights,
                    replace=False
                )
                return list(selected)
            except ValueError:
                # Fallback to random selection
                return random.sample(available_methods, min(num_methods, len(available_methods)))
    
    return []

def validate_method_distribution(target_ratios, actual_counts, tolerance=0.1):
    """Check if method distribution is within acceptable range"""
    total = sum(actual_counts.values())
    if total == 0:
        return True
    
    for method, target_ratio in target_ratios.items():
        actual_ratio = actual_counts.get(method, 0) / total
        if abs(actual_ratio - target_ratio) > tolerance:
            return False
    return True

def controlled_paraphrase_generation(texts_with_quotas, model, tokenizer):
    """Generate paraphrases with better control over quantity and quality"""
    results = []
    
    for text, quota in texts_with_quotas:
        if quota <= 0:
            continue
            
        try:
            # Generate multiple candidates
            candidates = batch_paraphrase(model, tokenizer, [text], batch_size=1)
            valid_paraphrases = []
            
            for para in candidates:
                if len(valid_paraphrases) >= quota:
                    break
                if para and validate_augmentation(text, para):
                    valid_paraphrases.append(para)
            
            results.extend(valid_paraphrases)
            
        except Exception as e:
            print(f"Error in controlled paraphrase generation: {e}")
            continue
    
    return results

def main():
    """Main process for dataset augmentation with improved distribution control"""
    # Set up file paths based on chosen data type
    if DATA_TYPE == "train":
        OUTPUT_FILE = "train.csv"
        OUTPUT_FILES = f"{DATASET_SAVE_PATH}/train_augmented.csv"
        output_files = OUTPUT_FILES
        input_file = INPUT_FILE
        output_file = OUTPUT_FILE
    elif DATA_TYPE == "val":
        OUTPUT_FILE = "val.csv"
        input_file = INPUT_FILE.replace("train", "val")
        output_file = OUTPUT_FILE.replace("train", "val")
    elif DATA_TYPE == "test":
        OUTPUT_FILE = "test.csv"
        input_file = INPUT_FILE.replace("train", "test")
        output_file = OUTPUT_FILE.replace("train", "test")
    else:
        input_file = INPUT_FILE
        output_file = OUTPUT_FILE

    # Read the dataset
    df = read_dataset(input_file)

    # Initialize paraphrase model if enabled
    model, tokenizer = initialize_paraphrase_model()

    # Count original samples per intent
    intent_counts = Counter(df['intent'])
    print("Original class distribution:")
    for intent, count in intent_counts.items():
        print(f"  {intent}: {count}")

    # Calculate augmentation factors for balancing
    augmentation_factors = {}
    for intent, count in intent_counts.items():
        if count >= TARGET_SAMPLES_PER_CLASS:
            augmentation_factors[intent] = 1  # Minimum factor
        else:
            factor = max(1, min(10, TARGET_SAMPLES_PER_CLASS / count))
            augmentation_factors[intent] = factor

    print("\nAugmentation factors:")
    for intent, factor in augmentation_factors.items():
        print(f"  {intent}: {factor:.2f}x")

    # Start augmentation process
    print("Starting balanced augmentation with improved distribution control...")
    start_time = time.time()

    augmented_results = defaultdict(list)
    paraphrase_candidates = defaultdict(list)

    # First, add all original data
    for _, row in df.iterrows():
        intent = row['intent']
        text = row['text']
        augmented_results[intent].append(text)

    # Initialize available methods for adaptive selection
    available_methods = {
        'synonym': replace_with_synonym,
        'back_translate': back_translate if USE_BACK_TRANSLATION else None,
        'slang': lambda t, intent=None: apply_slang_typo(t, intent or 'general', 1.0),
        'common_phrase': add_common_phrase,
        'short_text': lambda t, intent=None: short_text_augmentation(t, intent or 'general'),
        'swap': random_swap,
        'deletion': random_deletion,
        'phonetic': phonetic_augmentation,
        'char_noise': lambda t: character_noise(t, 1.0),
        'typo': add_typo
    }
    
    # Remove None methods
    available_methods = {k: v for k, v in available_methods.items() if v is not None}

    # Process each intent with controlled augmentation
    for intent, factor in augmentation_factors.items():
        original_count = intent_counts[intent]
        intent_df = df[df['intent'] == intent]

        print(f"\nProcessing intent '{intent}' with factor {factor:.2f}x")

        for _, row in tqdm(intent_df.iterrows(), desc=f"Augmenting '{intent}'", total=len(intent_df)):
            text = row['text']

            # Calculate needed augmentations for this sample
            num_augmentations = max(
                MIN_AUGMENTATIONS_PER_SAMPLE,
                min(MAX_AUGMENTATIONS_PER_SAMPLE, int(factor * 2.0))
            )

            # Calculate balanced allocation
            paraphrase_quota = int(num_augmentations * PARAPHRASE_RATIO) if USE_PARAPHRASE_MODEL else 0
            regular_quota = num_augmentations - paraphrase_quota

            # Add to paraphrase candidates with quota
            if USE_PARAPHRASE_MODEL and paraphrase_quota > 0:
                paraphrase_candidates[intent].append((text, paraphrase_quota))

            # Regular augmentations with adaptive method selection
            attempts = 0
            augmentations_created = 0
            max_attempts = regular_quota * 4

            while augmentations_created < regular_quota and attempts < max_attempts:
                attempts += 1
                
                # Get current augmentation counts for adaptive selection
                total_augmentations = sum(augmentation_method_counts.values())
                
                # Check distribution balance periodically
                if total_augmentations > 0 and total_augmentations % 100 == 0:
                    is_balanced = validate_method_distribution(
                        TARGET_METHOD_RATIOS, 
                        augmentation_method_counts, 
                        tolerance=0.15
                    )
                    if not is_balanced and total_augmentations % 500 == 0:  # Less frequent logging
                        print(f"  Adjusting method selection for better balance at {total_augmentations} augmentations...")

                # Use adaptive method selection
                text_length = len(text.split())
                intensity = min(1.0, NOISE_INTENSITY + (factor - 1) * 0.2)
                
                selected_methods = get_adaptive_method_selection(
                    text_length, intensity, augmentation_method_counts, 
                    total_augmentations, available_methods
                )

                if not selected_methods:
                    # Fallback to random selection
                    method_choices = list(available_methods.keys())
                    if len(text.split()) <= 3:
                        method_choices = [m for m in method_choices if m in ['slang', 'short_text', 'synonym', 'common_phrase']]
                    selected_methods = random.sample(method_choices, min(2, len(method_choices)))

                # Apply selected methods
                aug_text = text
                methods_used = []
                
                for method_name in selected_methods:
                    if method_name in available_methods:
                        method = available_methods[method_name]
                        old_text = aug_text
                        
                        try:
                            # Apply method with appropriate parameters
                            if method_name in ['slang', 'short_text']:
                                aug_text = method(aug_text, intent)
                            else:
                                aug_text = method(aug_text)
                            
                            # Track if method actually changed the text
                            if aug_text != old_text:
                                methods_used.append(method_name)
                                augmentation_method_counts[method_name] += 1
                                
                        except Exception as e:
                            print(f"Error applying {method_name}: {e}")
                            aug_text = old_text  # Revert on error

                # Validate and add augmentation
                if (aug_text.lower() != text.lower() and 
                    validate_augmentation(text, aug_text) and 
                    methods_used):  # Ensure at least one method was applied
                    
                    augmented_results[intent].append(aug_text)
                    augmentations_created += 1

        print(f"  Created {sum(len(augmented_results[intent]) - intent_counts[intent] for intent in [intent])} regular augmentations for '{intent}'")

    # Process paraphrases with controlled generation
    if USE_PARAPHRASE_MODEL and model is not None:
        print("\nApplying controlled paraphrase generation...")
        
        for intent, text_quota_pairs in paraphrase_candidates.items():
            if not text_quota_pairs:
                continue
                
            print(f"  Processing {len(text_quota_pairs)} texts for intent '{intent}'")
            
            # Use controlled paraphrase generation
            paraphrased = controlled_paraphrase_generation(text_quota_pairs, model, tokenizer)
            
            # Add to results and track
            for para in paraphrased:
                augmented_results[intent].append(para)
                augmentation_method_counts['batch_paraphrase'] += 1
            
            print(f"    Added {len(paraphrased)} controlled paraphrases")

    # Balance the data
    print("\nBalancing final dataset...")
    balanced_data = balance_samples(augmented_results, TARGET_SAMPLES_PER_CLASS, intent_counts)
    
    # Final balancing to target size with method diversity preservation
    target_class_size = TARGET_SAMPLES_PER_CLASS

    for intent in balanced_data:
        current_samples = balanced_data[intent]
        orig_count = intent_counts.get(intent, 0)

        if len(current_samples) > target_class_size:
            # Keep all original data
            original_data = current_samples[:orig_count]
            augmented_data = current_samples[orig_count:]

            # Randomly select augmented data to keep
            needed = target_class_size - orig_count
            if needed > 0 and augmented_data:
                random.shuffle(augmented_data)
                balanced_data[intent] = original_data + augmented_data[:needed]
            else:
                balanced_data[intent] = original_data[:target_class_size]
        elif len(current_samples) < target_class_size:
            # Keep all data if under target
            balanced_data[intent] = current_samples

    # Create final balanced dataframe
    rows = []
    for intent, texts in balanced_data.items():
        for text in texts:
            rows.append({"intent": intent, "text": text})

    final_df = pd.DataFrame(rows)

    # Print final statistics with distribution analysis
    print("\nFinal dataset statistics:")
    final_counts = Counter(final_df['intent'])
    for intent, count in final_counts.items():
        orig = intent_counts.get(intent, 0)
        added = count - orig
        print(f"  {intent}: {count} total ({orig} original + {added} augmented)")

    # Save to file
    final_df.to_csv(output_file, index=False)
    if DATA_TYPE == "train":
        final_df.to_csv(output_files, index=False)
    print(f"\nSaved balanced dataset to {output_file}")
    
    # Save method statistics
    stats_path = f"{DATASET_SAVE_PATH}/reports/method_statistics.json"
    with open(stats_path, 'w', encoding='utf-8') as f:
        json.dump(dict(augmentation_method_counts), f, indent=2, ensure_ascii=False)
    print(f"Method statistics saved to: {stats_path}")

    # Enhanced method distribution analysis
    print(f"\nMethod distribution analysis:")
    total_augmented_only = sum(augmentation_method_counts.values())
    if total_augmented_only > 0:
        # Show detailed statistics
        for method, count in sorted(augmentation_method_counts.items(), key=lambda x: x[1], reverse=True):
            percentage = (count / total_augmented_only) * 100
            print(f"  {method:20s}: {count:6d} ({percentage:5.1f}%)")

        # Distribution quality check
        is_well_distributed = validate_method_distribution(
            TARGET_METHOD_RATIOS, 
            augmentation_method_counts, 
            tolerance=0.2  # More lenient for final check
        )
        
        print(f"\n  Distribution quality: {'GOOD' if is_well_distributed else 'NEEDS IMPROVEMENT'}")
        
        # Show target vs actual ratios
        print(f"\n  Target vs Actual Ratios:")
        for method, target_ratio in TARGET_METHOD_RATIOS.items():
            actual_count = augmentation_method_counts.get(method, 0)
            actual_ratio = actual_count / total_augmented_only if total_augmented_only > 0 else 0
            deviation = abs(actual_ratio - target_ratio)
            status = "✓" if deviation <= 0.2 else "✗"
            print(f"    {method:15s}: {actual_ratio:.3f} (target: {target_ratio:.3f}, dev: {deviation:.3f}) {status}")

        # Check for specific issues
        paraphrase_count = augmentation_method_counts.get('batch_paraphrase', 0)
        paraphrase_pct = (paraphrase_count / total_augmented_only) * 100 if total_augmented_only > 0 else 0
        if paraphrase_pct > 50:
            print(f"  WARNING: Paraphrase dominates at {paraphrase_pct:.1f}%")
        else:
            print(f"  Good balance: Paraphrase at {paraphrase_pct:.1f}%")

    # Plot distributions and methods
    try:
        print("\nGenerating visualizations...")
        plot_augmentation_methods()
        plot_distribution(df, "Original Distribution")
        plot_distribution(final_df, "Augmented Distribution")
    except Exception as e:
        print(f"Error generating visualizations: {e}")

    # Calculate final statistics and generate report
    original_total = len(df)
    augmented_total = len(final_df)
    time_taken = time.time() - start_time
    
    # Save comprehensive report
    save_augmentation_report(final_df, df, time_taken, output_file)

    print(f"\nSummary:")
    print(f"  Original samples: {original_total}")
    print(f"  Final samples: {augmented_total}")
    print(f"  Added samples: {augmented_total - original_total}")
    print(f"  Augmentation ratio: {augmented_total / original_total:.2f}x")
    print(f"  Processing time: {time_taken:.2f} seconds")
    print(f"  Method distribution quality: {'GOOD' if is_well_distributed else 'NEEDS IMPROVEMENT'}")

    return final_df

if __name__ == "__main__":
    main()