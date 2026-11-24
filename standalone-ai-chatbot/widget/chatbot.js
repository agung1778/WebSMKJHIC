/**
 * ==========================================================================
 * STANDALONE AI CHATBOT WIDGET (Vanilla JS)
 * Drop-in script: Cukup include script ini di website mana pun!
 * ==========================================================================
 */

(function () {
    // Default configuration
    const config = Object.assign({
        endpoint: '/api/chat.php', // URL backend endpoint
        title: 'AI Assistant',
        subtitle: 'Online • Siap membantu',
        avatarText: '🤖',
        welcomeMessage: 'Halo! Ada yang bisa saya bantu terkait produk atau layanan kami? 😊',
        placeholder: 'Ketik pesan Anda di sini...',
        storageKey: 'standalone_chatbot_history',
        maxHistory: 8
    }, window.AiChatbotConfig || {});

    let isOpen = false;
    let isWaiting = false;
    let chatHistory = [];

    // Simple markdown renderer for chatbot replies
    function renderMarkdown(text) {
        if (!text) return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // Bold (**text**)
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // Italic (*text*)
        escaped = escaped.replace(/\*(.*?)\*/g, '<em>$1</em>');
        // Bullet points (* or -)
        escaped = escaped.replace(/^[•\-\*]\s+(.*)$/gm, '<li>$1</li>');
        escaped = escaped.replace(/(<li>.*<\/li>)/s, '<ul>$1</ul>');
        // New lines
        escaped = escaped.replace(/\n\n/g, '<p></p>');
        escaped = escaped.replace(/\n/g, '<br>');

        return escaped;
    }

    function initChatbot() {
        // Create Launcher Button
        const launcher = document.createElement('button');
        launcher.className = 'ai-widget-launcher';
        launcher.setAttribute('aria-label', 'Buka Chat AI');
        launcher.innerHTML = `
            <svg class="icon-chat" viewBox="0 0 24 24">
                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/>
            </svg>
            <svg class="icon-close" viewBox="0 0 24 24">
                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
            </svg>
        `;

        // Create Chatbox Window
        const windowEl = document.createElement('div');
        windowEl.className = 'ai-widget-window';
        windowEl.innerHTML = `
            <div class="ai-widget-header">
                <div class="ai-widget-header-title">
                    <div class="ai-widget-header-avatar">${config.avatarText}</div>
                    <div class="ai-widget-header-text">
                        <h3>${config.title}</h3>
                        <span>${config.subtitle}</span>
                    </div>
                </div>
                <button class="ai-widget-close-btn" aria-label="Tutup Chat">
                    <svg style="width:20px;height:20px;fill:currentColor" viewBox="0 0 24 24">
                        <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                    </svg>
                </button>
            </div>
            <div class="ai-widget-messages" id="aiWidgetMessages"></div>
            <div class="ai-widget-input-area">
                <input type="text" class="ai-widget-input" id="aiWidgetInput" placeholder="${config.placeholder}">
                <button class="ai-widget-send-btn" id="aiWidgetSendBtn" aria-label="Kirim Pesan" disabled>
                    <svg viewBox="0 0 24 24">
                        <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(launcher);
        document.body.appendChild(windowEl);

        const messagesContainer = windowEl.querySelector('#aiWidgetMessages');
        const inputEl = windowEl.querySelector('#aiWidgetInput');
        const sendBtn = windowEl.querySelector('#aiWidgetSendBtn');
        const closeBtn = windowEl.querySelector('.ai-widget-close-btn');

        // Toggle open/close
        function toggleChat() {
            isOpen = !isOpen;
            if (isOpen) {
                launcher.classList.add('active');
                windowEl.classList.add('open');
                inputEl.focus();
                scrollToBottom();
            } else {
                launcher.classList.remove('active');
                windowEl.classList.remove('open');
            }
        }

        launcher.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);

        function scrollToBottom() {
            setTimeout(() => {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }, 50);
        }

        function getTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        }

        function appendMessage(sender, text) {
            const msgEl = document.createElement('div');
            msgEl.className = `ai-message ${sender}`;
            const time = getTime();

            msgEl.innerHTML = `
                <div class="ai-message-bubble">
                    ${sender === 'bot' ? renderMarkdown(text) : text.replace(/</g, '&lt;')}
                </div>
                <span class="ai-message-time">${time}</span>
            `;

            messagesContainer.appendChild(msgEl);
            scrollToBottom();
        }

        function showTypingIndicator() {
            const typingEl = document.createElement('div');
            typingEl.className = 'ai-typing-indicator';
            typingEl.id = 'aiTypingIndicator';
            typingEl.innerHTML = `
                <div class="ai-typing-dot"></div>
                <div class="ai-typing-dot"></div>
                <div class="ai-typing-dot"></div>
            `;
            messagesContainer.appendChild(typingEl);
            scrollToBottom();
        }

        function removeTypingIndicator() {
            const el = document.getElementById('aiTypingIndicator');
            if (el) el.remove();
        }

        // Send message handler
        async function sendMessage() {
            const question = inputEl.value.trim();
            if (!question || isWaiting) return;

            // Clear input & disable button
            inputEl.value = '';
            sendBtn.disabled = true;
            isWaiting = true;

            appendMessage('user', question);
            showTypingIndicator();

            try {
                const response = await fetch(config.endpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: question,
                        history: chatHistory.slice(-config.maxHistory)
                    })
                });

                const data = await response.json();
                removeTypingIndicator();

                const botReply = data.reply || data.answer || 'Maaf, belum bisa merespons saat ini.';
                appendMessage('bot', botReply);

                // Update conversation history
                chatHistory.push({ role: 'user', message: question });
                chatHistory.push({ role: 'assistant', message: botReply });
            } catch (err) {
                removeTypingIndicator();
                appendMessage('bot', 'Koneksi terputus atau terjadi kesalahan sistem. Silakan coba lagi ya 🙏');
                console.error('Chatbot error:', err);
            } finally {
                isWaiting = false;
                sendBtn.disabled = inputEl.value.trim() === '';
                inputEl.focus();
            }
        }

        // Input listeners
        inputEl.addEventListener('input', () => {
            sendBtn.disabled = inputEl.value.trim() === '' || isWaiting;
        });

        inputEl.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        sendBtn.addEventListener('click', sendMessage);

        // Initial welcome message
        if (config.welcomeMessage) {
            appendMessage('bot', config.welcomeMessage);
        }
    }

    // Auto-init when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChatbot);
    } else {
        initChatbot();
    }
})();
