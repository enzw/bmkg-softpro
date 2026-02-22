<!-- Floating Chatbot Button - Image Only -->
<div class="relative">
    <button id="open-chatbot-btn"
        class="fixed bottom-8 right-8 z-40 hover:scale-110 transition-transform duration-300 cursor-pointer focus:outline-none group"
        title="Tanya MegaBot">
        <img src="{{ asset('images/floatingBot.png') }}" alt="Chatbot"
            class="w-24 h-24 object-contain drop-shadow-lg">
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
            <div class="bg-gray-900 dark:bg-white text-white dark:text-gray-900 text-xs font-semibold px-3 py-2 rounded-lg whitespace-nowrap">
                Tanya MegaBot
            </div>
            <div class="absolute top-full right-3 w-2 h-2 bg-gray-900 dark:bg-white transform rotate-45"></div>
        </div>
    </button>
</div>

<!-- Chatbot Modal - Admin Style -->
<div id="chatbot-modal"
    class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden flex items-end sm:items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-[2.5rem] border border-gray-100 dark:border-gray-700 shadow-sm w-full sm:max-w-md h-[85vh] sm:h-[650px] flex flex-col overflow-hidden transform transition-all duration-300"
        id="chatbot-container">

        <!-- Header Section -->
        <div class="p-6 border-b border-gray-50 dark:border-gray-700 flex items-center justify-between bg-gray-50/30 dark:bg-gray-900/10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white dark:bg-gray-700 flex items-center justify-center p-1">
                    <img src="{{ asset('images/floatingBot.png') }}" alt="Bot" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-base font-bold text-gray-900 dark:text-white uppercase tracking-tight">MegaBot</h2>
                    <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-widest">Siap membantu anda</p>
                </div>
            </div>
            <button id="close-chatbot-btn"
                class="px-4 py-2 rounded-xl bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-800/50 transition-all font-bold text-[10px] uppercase tracking-widest flex items-center justify-center w-10 h-10"
                title="Tutup">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Messages Area -->
        <div id="chatbot-messages"
            class="flex-1 overflow-y-auto p-6 space-y-4 bg-white dark:bg-gray-800">
            <!-- Welcome Message -->
            <div class="flex gap-3 animate-fade-in">
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/floatingBot.png') }}" alt="Bot" class="w-8 h-8 object-contain">
                </div>
                <div class="flex-1 flex-col">
                    <div class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm border border-gray-100 dark:border-gray-700">
                        <p class="text-sm dark:text-gray-100 text-gray-700 leading-relaxed">Halo 👋 Ada yang bisa saya bantu? Tanyakan tentang layanan BMKG.</p>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2 ml-0">Sekarang</p>
                </div>
            </div>
        </div>

        <!-- Input Area -->
        <div class="border-t border-gray-50 dark:border-gray-700 p-6 bg-white dark:bg-gray-800">
            <form id="chatbot-form" class="flex gap-3">
                <input type="text" id="chatbot-input"
                    class="flex-1 px-6 py-3 rounded-2xl border border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/40 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500/20 focus:border-green-500 transition-all font-medium text-sm"
                    placeholder="Ketik pertanyaan..."
                    autocomplete="off">
                <button type="submit"
                    class="px-6 py-3 bg-green-600 hover:bg-green-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 text-white rounded-2xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center font-semibold text-sm"
                    id="send-btn"
                    title="Kirim">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out;
    }

    #chatbot-messages::-webkit-scrollbar {
        width: 6px;
    }

    #chatbot-messages::-webkit-scrollbar-track {
        background: transparent;
    }

    #chatbot-messages::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    #chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .dark #chatbot-messages::-webkit-scrollbar-thumb {
        background: #475569;
    }

    .dark #chatbot-messages::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Markdown Styling */
    .chatbot-message p {
        margin: 0.5rem 0;
        line-height: 1.5;
    }

    .chatbot-message h1,
    .chatbot-message h2,
    .chatbot-message h3 {
        font-weight: 700;
        margin: 0.75rem 0 0.5rem 0;
        line-height: 1.3;
    }

    .chatbot-message h1 {
        font-size: 1.25rem;
    }

    .chatbot-message h2 {
        font-size: 1.1rem;
    }

    .chatbot-message h3 {
        font-size: 1rem;
    }

    .chatbot-message ul,
    .chatbot-message ol {
        margin: 0.5rem 0;
        padding-left: 1.5rem;
        list-style: inherit;
    }

    .chatbot-message ul {
        list-style-type: disc;
    }

    .chatbot-message ol {
        list-style-type: decimal;
    }

    .chatbot-message li {
        margin: 0.25rem 0;
        line-height: 1.5;
    }

    .chatbot-message blockquote {
        border-left: 3px solid #059669;
        padding-left: 1rem;
        margin: 0.5rem 0;
        opacity: 0.8;
        font-style: italic;
    }

    .dark .chatbot-message blockquote {
        border-left-color: #10b981;
    }

    .chatbot-message code {
        background-color: rgba(0, 0, 0, 0.06);
        padding: 0.125rem 0.375rem;
        border-radius: 0.25rem;
        font-family: 'Courier New', monospace;
        font-size: 0.875em;
    }

    .dark .chatbot-message code {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .chatbot-message pre {
        background-color: #f3f4f6;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1rem;
        overflow-x: auto;
        margin: 0.5rem 0;
    }

    .dark .chatbot-message pre {
        background-color: #1f2937;
        border-color: #374151;
    }

    .chatbot-message pre code {
        background: none;
        padding: 0;
        font-size: 0.875rem;
        line-height: 1.5;
    }

    .chatbot-message a {
        color: #059669;
        text-decoration: underline;
        hover: #047857;
    }

    .dark .chatbot-message a {
        color: #10b981;
    }

    .chatbot-message strong {
        font-weight: 700;
    }

    .chatbot-message em {
        font-style: italic;
    }

    .chatbot-message table {
        border-collapse: collapse;
        width: 100%;
        margin: 0.5rem 0;
    }

    .chatbot-message thead {
        background-color: #f9fafb;
    }

    .dark .chatbot-message thead {
        background-color: #111827;
    }

    .chatbot-message th,
    .chatbot-message td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
        text-align: left;
    }

    .dark .chatbot-message th,
    .dark .chatbot-message td {
        border-color: #374151;
    }

    /* Typing Indicator Animation */
    .typing-indicator {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .typing-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background-color: #4b5563;
        animation: typing 1.4s infinite;
        display: inline-block;
    }

    .typing-dot:nth-child(2) {
        animation-delay: 0.2s;
    }

    .typing-dot:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes typing {
        0%, 60%, 100% {
            transform: translateY(0);
            opacity: 0.6;
        }
        30% {
            transform: translateY(-8px);
            opacity: 1;
        }
    }

    .dark .typing-dot {
        background-color: #cbd5e1;
    }

    /* Star Rating Styles */
    .star-rating {
        display: flex;
        gap: 0.25rem;
    }

    .star-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.25rem;
        padding: 0;
        margin: 0;
        line-height: 1;
        transition: all 0.2s ease;
    }

    .star-btn:hover {
        transform: scale(1.2);
    }

    .star-btn:disabled {
        cursor: not-allowed;
    }

    /* Mobile responsive */
    @media (max-width: 640px) {
        #chatbot-modal.modal-open {
            animation: slide-up 0.3s ease-out;
        }
    }

    @keyframes slide-up {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    // Configure marked options for better rendering
    marked.setOptions({
        breaks: true,
        gfm: true, // GitHub Flavored Markdown
    });

    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('chatbot-modal');
        const openBtn = document.getElementById('open-chatbot-btn');
        const closeBtn = document.getElementById('close-chatbot-btn');
        const form = document.getElementById('chatbot-form');
        const input = document.getElementById('chatbot-input');
        const messagesContainer = document.getElementById('chatbot-messages');
        const sendBtn = document.getElementById('send-btn');

        // Generate unique session ID for chatbot conversation
        const sessionId = `chatbot-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;

        // Rate limiting: prevent spam requests
        let lastMessageTime = 0;
        const COOLDOWN_MS = 2000; // 2 second cooldown between messages

        // Open modal
        openBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('modal-open');
            setTimeout(() => input.focus(), 300);
        });

        // Close modal
        closeBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('modal-open');
        });

        // Close on background click
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('modal-open');
            }
        });

        // Send message
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const message = input.value.trim();
            if (!message) return;

            // Check cooldown to prevent rate limiting
            const now = Date.now();
            const timeSinceLastMessage = now - lastMessageTime;
            
            if (timeSinceLastMessage < COOLDOWN_MS) {
                const remainingMs = COOLDOWN_MS - timeSinceLastMessage;
                const remainingSeconds = Math.ceil(remainingMs / 1000);
                addBotMessage(`⏳ Silakan tunggu ${remainingSeconds} detik sebelum mengirim pesan berikutnya (rate limit API).`, null);
                return;
            }

            // Clear input
            input.value = '';
            lastMessageTime = now;

            // Add user message to chat
            addUserMessage(message);

            // Show typing indicator
            const typingId = showTypingIndicator();

            // Disable send button
            sendBtn.disabled = true;
            input.disabled = true;

            try {
                // Send to Laravel API endpoint (which calls Gemini API)
                const response = await fetch('/api/chatbot/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();

                // Remove typing indicator
                removeTypingIndicator(typingId);

                if (data.success) {
                    addBotMessage(data.message, message);
                } else {
                    addBotMessage(data.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.', message);
                }

            } catch (error) {
                console.error('Error:', error);
                // Remove typing indicator
                removeTypingIndicator(typingId);
                addBotMessage('❌ Gagal menghubungi server chatbot. Silakan coba lagi atau hubungi administrator.', message);
            } finally {
                sendBtn.disabled = false;
                input.disabled = false;
                input.focus();
            }
        });

        function addUserMessage(text) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 justify-end animate-fade-in';
            messageDiv.innerHTML = `
                <div class="flex-1 flex justify-end">
                    <div class="bg-green-600 text-white rounded-2xl rounded-tr-none px-4 py-3 shadow-sm max-w-xs">
                        <p class="text-sm break-words leading-relaxed">${escapeHtml(text)}</p>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user text-gray-600 dark:text-gray-400 text-xs"></i>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        function addBotMessage(markdown, userMessage = null) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 animate-fade-in';
            
            // Parse markdown to HTML
            const htmlContent = marked.parse(markdown);
            const messageId = 'bot-msg-' + Date.now();
            
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/floatingBot.png') }}" alt="Bot" class="w-10 h-10 object-contain">
                </div>
                <div class="flex-1 flex-col">
                    <div class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="chatbot-message text-sm dark:text-gray-100 text-gray-700 break-words leading-relaxed">
                            ${htmlContent}
                        </div>
                    </div>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-xs text-gray-400 dark:text-gray-500">Apakah jawaban ini membantu?</span>
                        <div class="flex gap-1 star-rating" data-message-id="${messageId}">
                            <button class="star-btn text-gray-300 dark:text-gray-500 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors" data-rating="1" title="Tidak membantu">★</button>
                            <button class="star-btn text-gray-300 dark:text-gray-500 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors" data-rating="2" title="Kurang membantu">★</button>
                            <button class="star-btn text-gray-300 dark:text-gray-500 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors" data-rating="3" title="Cukup membantu">★</button>
                            <button class="star-btn text-gray-300 dark:text-gray-500 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors" data-rating="4" title="Membantu">★</button>
                            <button class="star-btn text-gray-300 dark:text-gray-500 hover:text-yellow-400 dark:hover:text-yellow-400 transition-colors" data-rating="5" title="Sangat membantu">★</button>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Sekarang</p>
                </div>
            `;
            messageDiv.setAttribute('data-user-message', userMessage || '');
            messageDiv.setAttribute('data-bot-response', markdown);
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            
            // Add star rating event listeners
            const starRating = messageDiv.querySelector('.star-rating');
            const starBtns = starRating.querySelectorAll('.star-btn');
            
            // Hover effect - light up stars up to hovered star
            starBtns.forEach((btn, index) => {
                btn.addEventListener('mouseenter', () => {
                    starBtns.forEach((b, i) => {
                        if (i <= index) {
                            b.classList.remove('text-gray-300', 'dark:text-gray-500');
                            b.classList.add('text-yellow-400', 'dark:text-yellow-300');
                        } else {
                            b.classList.remove('text-yellow-400', 'dark:text-yellow-300');
                            b.classList.add('text-gray-300', 'dark:text-gray-500');
                        }
                    });
                });
            });

            // Reset stars on mouse leave
            starRating.addEventListener('mouseleave', () => {
                starBtns.forEach(b => {
                    if (!b.disabled) {
                        b.classList.remove('text-yellow-400', 'dark:text-yellow-300');
                        b.classList.add('text-gray-300', 'dark:text-gray-500');
                    }
                });
            });

            // Click event - auto send rating to database
            starBtns.forEach((btn, index) => {
                btn.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const rating = btn.getAttribute('data-rating');
                    const ratingText = ['Tidak membantu', 'Kurang membantu', 'Cukup membantu', 'Membantu', 'Sangat membantu'];
                    
                    // Mark selected stars as active - permanently
                    starBtns.forEach((b, i) => {
                        if (i < rating) {
                            b.classList.remove('text-gray-300', 'dark:text-gray-500');
                            b.classList.add('text-yellow-400', 'dark:text-yellow-300');
                        } else {
                            b.classList.remove('text-yellow-400', 'dark:text-yellow-300');
                            b.classList.add('text-gray-300', 'dark:text-gray-500');
                        }
                    });
                    
                    // Disable all buttons after rating
                    starBtns.forEach(b => {
                        b.disabled = true;
                        b.classList.add('cursor-not-allowed', 'opacity-50');
                    });
                    
                    // Get message and response from messageDiv attributes
                    const userMsg = messageDiv.getAttribute('data-user-message');
                    const botResp = messageDiv.getAttribute('data-bot-response');
                    
                    // Show thank you message
                    const thankYouDiv = document.createElement('div');
                    thankYouDiv.className = 'mt-2 text-xs text-green-600 dark:text-green-400 font-semibold';
                    thankYouDiv.textContent = `✓ Rating ${rating} bintang untuk "${ratingText[rating-1]}" telah tersimpan.`;
                    starRating.parentElement.replaceChild(thankYouDiv, starRating);
                    
                    // Auto-send rating to server with message and response
                    try {
                        const response = await fetch('/api/chatbot/rate', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({ 
                                session_id: sessionId,
                                rating: rating,
                                message_id: messageId,
                                message: userMsg,
                                bot_response: botResp
                            })
                        });
                        const result = await response.json();
                        if (!result.success) {
                            console.error('Failed to save rating:', result);
                            thankYouDiv.textContent = `✗ Gagal menyimpan rating. Coba lagi.`;
                        }
                    } catch (err) {
                        console.error('Error sending rating:', err);
                        thankYouDiv.textContent = `✗ Error: Tidak bisa mengirim rating.`;
                    }
                });
            });
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function showTypingIndicator() {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex gap-3 animate-fade-in';
            const typingId = 'typing-' + Date.now();
            messageDiv.id = typingId;
            
            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <img src="{{ asset('images/floatingBot.png') }}" alt="Bot" class="w-10 h-10 object-contain">
                </div>
                <div class="flex-1 flex-col">
                    <div class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl rounded-tl-none px-4 py-3 shadow-sm border border-gray-100 dark:border-gray-700 w-fit">
                        <div class="typing-indicator">
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                            <span class="typing-dot"></span>
                        </div>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
            return typingId;
        }

        function removeTypingIndicator(typingId) {
            const typingElement = document.getElementById(typingId);
            if (typingElement) {
                typingElement.remove();
            }
        }

        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                modal.classList.add('hidden');
                modal.classList.remove('modal-open');
            }
        });
    });
</script>
