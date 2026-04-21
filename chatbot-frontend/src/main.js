import './style.css';
import { getBotResponse } from './responses.js';

const analyzeApiUrl = 'https://ZEROTSUDIOS-chatbot-bipa-api2.hf.space/api/analyze';
const recommendApiUrl = 'https://ZEROTSUDIOS-chatbot-bipa-api2.hf.space/api/recommend';

let waitingForRecommendation = false;
let wait_confirmation = false;
let userName = localStorage.getItem('chatUserName') || '';
let chatHistory = JSON.parse(localStorage.getItem('chatHistory') || '[]');

// DOM Elements
const nameModalOverlay = document.getElementById('name-modal-overlay');
const nameForm = document.getElementById('name-form');
const usernameInput = document.getElementById('username-input');
const app = document.getElementById('app');
const profileName = document.getElementById('profile-name');
const chatContainer = document.getElementById('chat-container');
const chatForm = document.getElementById('chat-form');
const messageInput = document.getElementById('message');
const submitBtn = document.getElementById('submit-btn');
const suggestionsContainer = document.getElementById('chat-suggestions-container');
const toggleGuideBtn = document.getElementById('toggleGuideBtn');
const chatGuidePanel = document.getElementById('chatGuidePanel');
const closeGuideBtn = document.getElementById('closeGuideBtn');
const scrollToBottomBtn = document.getElementById('scrollToBottomBtn');
const clearChatBtn = document.getElementById('clear-chat-btn');
const changeNameBtn = document.getElementById('change-name-btn');

function init() {
    if (!userName) {
        nameModalOverlay.classList.remove('hidden');
    } else {
        startChat();
    }
}

nameForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const val = usernameInput.value.trim();
    if (val) {
        userName = val;
        localStorage.setItem('chatUserName', userName);
        nameModalOverlay.classList.add('hidden');
        startChat();
    }
});

changeNameBtn.addEventListener('click', () => {
    localStorage.removeItem('chatUserName');
    location.reload();
});

clearChatBtn.addEventListener('click', () => {
    if(confirm('Yakin ingin menghapus semua riwayat chat Anda?')) {
        chatHistory = [];
        saveHistory();
        chatContainer.innerHTML = '';
        renderWelcomeMessage();
        showSuggestions();
    }
});

function startChat() {
    profileName.textContent = userName;
    app.classList.remove('opacity-0');
    renderHistory();
    setupEventListeners();
}

function saveHistory() {
    localStorage.setItem('chatHistory', JSON.stringify(chatHistory));
}

function scrollToBottom() {
    chatContainer.scrollTop = chatContainer.scrollHeight;
}

function getTimestamp() {
    const now = new Date();
    return now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function appendUserMessage(message, timestamp) {
    const html = `
        <div class="flex items-end justify-end space-x-3 message-container">
            <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%] relative">
                <p class="text-right font-bold handwriting text-lg">${userName}</p>
                <p>${message}</p>
                <div class="timestamp text-right">${timestamp}</div>
            </div>
            <div class="w-10 h-10 rounded-full bg-blue-100 border-2 border-black flex-shrink-0 flex items-center justify-center">
                <i class="fas fa-user"></i>
            </div>
        </div>
    `;
    chatContainer.insertAdjacentHTML('beforeend', html);
    scrollToBottom();
}

function appendBotMessage(responseHtml, intentData = null, isTyping = false) {
    if(!isTyping && document.getElementById('typing-indicator')) {
        document.getElementById('typing-indicator').remove();
    }

    const timestamp = getTimestamp();
    let detailHtml = '';

    if (intentData) {
        detailHtml = `
            <div class="absolute top-2 right-2">
                <button class="detail-toggle-btn text-gray-500 hover:text-black focus:outline-none">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>
            <div class="intent-detail hidden mt-2 text-sm border-t border-gray-300 pt-2 bg-gray-50 p-2 rounded">
                <p><strong>Intent:</strong> ${intentData.intent || '-'}</p>
                <p><strong>Confidence:</strong> ${intentData.confidence?.toFixed(4) || '-'}</p>
            </div>
        `;
    }

    const html = `
        <div class="flex items-start space-x-3 message-container" ${isTyping ? 'id="typing-indicator"' : ''}>
            <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-black flex-shrink-0 flex items-center justify-center">
                <i class="fas fa-book"></i>
            </div>
            <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%] relative">
                <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                <div>${responseHtml}</div>
                ${detailHtml}
                <div class="timestamp">${isTyping ? '' : timestamp}</div>
            </div>
        </div>
    `;
    
    chatContainer.insertAdjacentHTML('beforeend', html);
    scrollToBottom();
}

function showTyping() {
    appendBotMessage('Mengetik<span class="typing-dots">...</span>', null, true);
    
    // Animate typing dots
    let dots = 0;
    const typingInterval = setInterval(() => {
        const dotsSpan = document.querySelector('.typing-dots');
        if (!dotsSpan) {
            clearInterval(typingInterval);
            return;
        }
        dots = (dots + 1) % 4;
        dotsSpan.textContent = '.'.repeat(dots);
    }, 500);
}

function formatRecommendations(high, low) {
    let output = "<strong>Buku yang paling relevan untuk Anda:</strong><br><br>";

    if (!high || high.length === 0) {
        output += "<p>Maaf, tidak ada rekomendasi buku yang ditemukan untuk kriteria tersebut.</p>";
        return output;
    }

    high.forEach((book, index) => {
        const relevance = book.relevance_score * 100;
        const year = book.year || 'Tahun tidak diketahui';
        output += `
        <div class='book-recommendation mb-3'>
            <strong>${index + 1}. ${book.title}</strong><br>
            Penulis: ${book.author}<br>
            Kategori: ${book.category}<br>
            Tahun: ${year}<br>
            <p class="text-sm mt-1"><em>Deskripsi:</em> ${book.description}</p>
            Relevansi: ${relevance.toFixed(0)}%<br>
        </div>`;
    });

    if (low && low.length > 0) {
        output += `<details class="mt-2"><summary class="cursor-pointer text-blue-600 font-semibold mb-2">📚 Lihat rekomendasi tambahan</summary>`;
        low.forEach((book, index) => {
            const relevance = book.relevance_score * 100;
            const year = book.year || 'Tahun tidak diketahui';
            output += `
            <div class='book-recommendation mb-3 ml-4 border-l-2 pl-3 border-gray-300'>
                <strong>${index + 1 + high.length}. ${book.title}</strong><br>
                Penulis: ${book.author}<br>
                Kategori: ${book.category}<br>
                Tahun: ${year}<br>
                <p class="text-sm mt-1"><em>Deskripsi:</em> ${book.description}</p>
                Relevansi: ${relevance.toFixed(0)}%<br>
            </div>`;
        });
        output += `</details>`;
    }
    
    output += "<p class='mt-3'>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>";
    return output;
}

function hideSuggestions() {
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'none';
    }
}

function showSuggestions() {
    if (suggestionsContainer) {
        suggestionsContainer.style.display = 'flex';
        const wrapper = suggestionsContainer.querySelector('.suggestions-wrapper');
        const suggestions = [
            "halo selamat pagi",
            "Permisi, perpusnya buka jam berapa ya?",
            "bagaimana cara menjadi anggota?",
            "saya ingin pinjam buku",
            "Bisa carikan saya buku?",
            "Apa saja fasilitas disini?"
        ];
        
        // Shuffle and pick 3
        const shuffled = suggestions.sort(() => 0.5 - Math.random());
        const selected = shuffled.slice(0, 3);
        
        wrapper.innerHTML = '';
        selected.forEach(text => {
            const div = document.createElement('div');
            div.className = 'chat-suggestion bg-white/90 p-3 rounded-lg border-2 border-black max-w-[80%] hover:bg-gray-50';
            div.innerHTML = `<p class="handwriting">${text}</p>`;
            div.addEventListener('click', () => {
                messageInput.value = text;
                chatForm.dispatchEvent(new Event('submit'));
            });
            wrapper.appendChild(div);
        });
    }
}

function renderWelcomeMessage() {
    const html = `
        <div class="flex items-start space-x-3 message-container">
            <div class="w-10 h-10 rounded-full bg-gray-100 border-2 border-black flex-shrink-0 flex items-center justify-center">
                <i class="fas fa-book"></i>
            </div>
            <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%] relative">
                <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                <p>Halo ${userName}, Ada yang bisa saya bantu?</p>
                <div class="timestamp">Hari ini, ${getTimestamp()}</div>
            </div>
        </div>
    `;
    chatContainer.insertAdjacentHTML('beforeend', html);
}

function renderHistory() {
    chatContainer.innerHTML = '';
    renderWelcomeMessage();
    
    if (chatHistory.length === 0) {
        showSuggestions();
    } else {
        hideSuggestions();
        chatHistory.forEach(chat => {
            appendUserMessage(chat.user_message, chat.timestamp);
            appendBotMessage(chat.bot_response, chat.intentData);
        });
    }
    scrollToBottom();
}

async function processRecommendation(message) {
    showTyping();
    const timestamp = getTimestamp();
    
    try {
        const response = await fetch(recommendApiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: message,
                top_n: 10,
                threshold: 0.4
            })
        });

        if (!response.ok) throw new Error('Recommendation API response error');
        
        const data = await response.json();
        
        const high = data.high_recommendations || [];
        const low = data.low_recommendations || [];
        const formattedResponse = formatRecommendations(high, low);
        
        appendBotMessage(formattedResponse);
        
        chatHistory.push({
            user_message: message,
            bot_response: formattedResponse,
            timestamp: timestamp
        });
        saveHistory();

        waitingForRecommendation = false;
        wait_confirmation = true;

    } catch (error) {
        console.error("Recommend error:", error);
        appendBotMessage("Terjadi kesalahan saat merekomendasikan buku. Silakan coba lagi.");
        waitingForRecommendation = false;
        wait_confirmation = false;
    }
}

async function processIntent(message) {
    showTyping();
    const timestamp = getTimestamp();
    
    try {
        const response = await fetch(analyzeApiUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ text: message })
        });

        if (!response.ok) throw new Error('Analyze API response error');
        
        const data = await response.json();
        
        let intent = data.intent ? data.intent.toLowerCase() : 'unknown';
        if (data.is_ood) {
            intent = 'unknown';
        }

        const botOutput = getBotResponse(intent, userName, { wait_confirmation });
        const intentData = {
            intent: data.intent,
            confidence: data.confidence,
            energy: data.energy_score,
            probabilities: data.class_probabilities
        };

        appendBotMessage(botOutput.response, intentData);

        chatHistory.push({
            user_message: message,
            bot_response: botOutput.response,
            intentData,
            timestamp: timestamp
        });
        saveHistory();

        // Handle next action
        if (botOutput.next_action === 'wait_book_recommendation') {
            waitingForRecommendation = true;
            wait_confirmation = false;
        } else if (botOutput.next_action === 'confirmation') {
            waitingForRecommendation = true;
            wait_confirmation = true;
        } else {
            waitingForRecommendation = false;
            wait_confirmation = false;
        }

    } catch (error) {
        console.error("Analyze error:", error);
        appendBotMessage("Terjadi kesalahan saat menghubungi server.");
    }
}

function setupEventListeners() {
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (document.getElementById('typing-indicator')) return; // Block input while bot typing

        const message = messageInput.value.trim();
        if (!message) return;

        hideSuggestions();
        appendUserMessage(message, getTimestamp());
        messageInput.value = '';

        if (waitingForRecommendation) {
            await processRecommendation(message);
        } else {
            await processIntent(message);
        }
    });

    // Intent details toggle delegation
    chatContainer.addEventListener('click', (e) => {
        const btn = e.target.closest('.detail-toggle-btn');
        if (btn) {
            const detailWrapper = btn.parentElement.nextElementSibling;
            if (detailWrapper && detailWrapper.classList.contains('intent-detail')) {
                detailWrapper.classList.toggle('hidden');
            }
        }
    });

    // Guide Panel
    toggleGuideBtn.addEventListener('click', () => {
        chatGuidePanel.classList.toggle('hidden');
    });

    closeGuideBtn.addEventListener('click', () => {
        chatGuidePanel.classList.add('hidden');
    });

    // Scroll button visibility
    chatContainer.addEventListener('scroll', () => {
        const nearBottom = chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight < 100;
        if(nearBottom) {
            scrollToBottomBtn.classList.add('hidden');
        } else {
            scrollToBottomBtn.classList.remove('hidden');
        }
    });

    scrollToBottomBtn.addEventListener('click', () => {
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: 'smooth'
        });
    });
}

// Initialize on App Load
init();
