@php
    $chatHistory = Session::get('chat_history', []);
@endphp

<style>
    /* Robust styles for chatbot in case Tailwind doesn't compile */
    #chatbot-window {
        background-color: #0f172a; /* Slate 900 */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(0, 119, 187, 0.3);
    }
    #chatbot-header {
        background-color: #1e293b; /* Slate 800 */
        border-bottom: 1px solid rgba(0, 119, 187, 0.2);
        padding: 0 20px;
        height: 80px;
    }
    #chat-messages {
        background-color: #020617; /* Slate 950 */
        padding: 16px;
    }
    #chatbot-input-area {
        background-color: #1e293b; /* Slate 800 */
        border-top: 1px solid rgba(0, 119, 187, 0.2);
        padding: 16px;
    }
    #chat-input {
        width: 100%;
        background-color: #040810;
        border: 1px solid #1f2937;
        color: white;
        border-radius: 9999px;
        padding: 12px 48px 12px 16px;
        outline: none;
        font-size: 14px;
        box-sizing: border-box;
    }
    #chat-input:focus {
        border-color: #0077bb;
    }
    .bot-bubble {
        background-color: #1e293b;
        border: 1px solid #334155;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.5;
        color: #e2e8f0;
    }
    .bot-bubble p, .bot-bubble ul, .bot-bubble li {
        margin-top: 6px;
        margin-bottom: 6px;
        font-size: 14px;
    }
    .user-bubble {
        background-color: #0077bb;
        color: #ffffff;
        padding: 12px 16px;
        font-size: 14px;
        line-height: 1.5;
    }
    .chat-message {
        margin-bottom: 16px;
    }
    #send-btn {
        background-color: #0077bb;
        color: #ffffff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        border: none;
        cursor: pointer;
        transition: transform 0.2s;
    }
    #send-btn:hover {
        background-color: #005f9e;
        transform: translateY(-50%) scale(1.05);
    }
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<!-- Chatbot Floating Button -->
<button id="chatbot-toggle-btn" style="position: fixed; bottom: 24px; right: 24px; z-index: 99999; width: 60px; height: 60px;" class="bg-primary text-white rounded-full flex items-center justify-center shadow-2xl hover:scale-105 transition-transform duration-300">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
    </svg>
</button>

<!-- Chatbot Window -->
<div id="chatbot-window" style="position: fixed; bottom: 100px; right: 24px; z-index: 99999; width: 400px; height: 650px; max-height: 80vh;" class="rounded-2xl flex-col overflow-hidden transition-all duration-300 transform translate-y-4 opacity-0 hidden">
    
    <!-- Chat Header -->
    <div id="chatbot-header" class="flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center border border-primary/30">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <div>
                <h2 class="text-white font-medium text-[15px] tracking-wide uppercase">Chuyên Gia Dinh Dưỡng</h2>
                <p class="text-primary/70 text-[10px] tracking-wider">Trí tuệ nhân tạo (AI)</p>
            </div>
        </div>
        <button id="chatbot-close-btn" class="text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Chat Messages Area -->
    <div id="chat-messages" class="flex-1 overflow-y-auto scrollbar-thin scrollbar-thumb-primary/20 scrollbar-track-transparent relative">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-10 pointer-events-none"></div>
        
        <!-- Initial Welcome Message -->
        <div class="flex justify-start relative z-10 chat-message">
            <div class="flex gap-3 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                    <span class="text-primary text-[10px] font-bold">AI</span>
                </div>
                <div class="bot-bubble text-gray-200 p-3 rounded-2xl rounded-tl-sm text-[14px] leading-relaxed shadow-sm">
                    <p>Xin chào! Tôi là Chuyên gia dinh dưỡng AI của Nhà Hàng Ẩm Thực Việt.</p>
                    <p class="mt-2">Để tôi có thể tư vấn bữa ăn phù hợp nhất, bạn vui lòng cho tôi biết <strong>chiều cao</strong> và <strong>cân nặng</strong> của bạn nhé!</p>
                </div>
            </div>
        </div>

        @if(isset($chatHistory) && count($chatHistory) > 0)
            @foreach($chatHistory as $msg)
                @if($msg['role'] === 'user')
                    <div class="flex justify-end relative z-10 chat-message">
                        <div class="user-bubble p-3 rounded-2xl rounded-tr-sm text-[14px] leading-relaxed shadow-md max-w-[85%] font-medium">
                            {{ $msg['content'] }}
                        </div>
                    </div>
                @else
                    <div class="flex justify-start relative z-10 chat-message">
                        <div class="flex gap-3 max-w-[85%]">
                            <div class="w-8 h-8 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                                <span class="text-primary text-[10px] font-bold">AI</span>
                            </div>
                            <div class="bot-bubble text-gray-200 p-3 rounded-2xl rounded-tl-sm text-[14px] leading-relaxed shadow-sm prose prose-invert prose-p:my-1 prose-ul:my-1 prose-li:my-0 prose-sm">
                                {!! Str::markdown($msg['content']) !!}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @endif

        <!-- Typing Indicator (Hidden by default) -->
        <div id="typing-indicator" class="hidden flex justify-start relative z-10">
            <div class="flex gap-3 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                    <span class="text-primary text-[10px] font-bold">AI</span>
                </div>
                <div class="bot-bubble p-3 rounded-2xl rounded-tl-sm flex items-center gap-1">
                    <div class="w-1.5 h-1.5 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                    <div class="w-1.5 h-1.5 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                    <div class="w-1.5 h-1.5 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Suggestions -->
    <div class="px-4 pb-3 pt-3 bg-[#1e293b] border-t border-white/5 flex flex-col gap-2">
        <button type="button" class="suggestion-btn text-[12px] text-left bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/30 hover:bg-primary hover:text-white transition-colors w-full">Gợi ý món ăn ngon</button>
        <button type="button" class="suggestion-btn text-[12px] text-left bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/30 hover:bg-primary hover:text-white transition-colors w-full">Tư vấn giảm cân</button>
        <button type="button" class="suggestion-btn text-[12px] text-left bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/30 hover:bg-primary hover:text-white transition-colors w-full">Món chay hôm nay</button>
        <button type="button" class="suggestion-btn text-[12px] text-left bg-primary/10 text-primary px-4 py-2 rounded-lg border border-primary/30 hover:bg-primary hover:text-white transition-colors w-full">Đồ uống giải nhiệt</button>
    </div>

    <!-- Chat Input Area -->
    <div id="chatbot-input-area" class="shrink-0 pt-2">
        <form id="chat-form" class="relative flex items-center">
            @csrf
            <input type="text" id="chat-input" placeholder="Nhập câu hỏi..." autocomplete="off" required>
            
            <button type="submit" id="send-btn" class="disabled:opacity-50 disabled:pointer-events-none">
                <svg style="width: 16px; height: 16px; margin-left: 2px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const chatWindow = document.getElementById('chatbot-window');
        const toggleBtn = document.getElementById('chatbot-toggle-btn');
        const closeBtn = document.getElementById('chatbot-close-btn');
        
        const chatForm = document.getElementById('chat-form');
        const chatInput = document.getElementById('chat-input');
        const chatMessages = document.getElementById('chat-messages');
        const typingIndicator = document.getElementById('typing-indicator');
        const sendBtn = document.getElementById('send-btn');

        // Toggle chat window
        toggleBtn.addEventListener('click', () => {
            if (chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden');
                // Allow browser to render display:block before adding opacity/transform classes
                setTimeout(() => {
                    chatWindow.classList.remove('opacity-0', 'translate-y-4');
                    chatWindow.classList.add('flex'); // Add flex to make it column
                    scrollToBottom();
                    chatInput.focus();
                }, 10);
            } else {
                closeChat();
            }
        });

        closeBtn.addEventListener('click', closeChat);

        function closeChat() {
            chatWindow.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                chatWindow.classList.add('hidden');
                chatWindow.classList.remove('flex');
            }, 300);
        }

        // Scroll to bottom
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Handle suggestion clicks
        const suggestionBtns = document.querySelectorAll('.suggestion-btn');
        suggestionBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                chatInput.value = this.innerText;
                // Optionally Auto-submit
                chatForm.dispatchEvent(new Event('submit'));
            });
        });

        // Handle form submit
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const message = chatInput.value.trim();
            if (!message) return;

            // Disable input and button
            chatInput.value = '';
            chatInput.disabled = true;
            sendBtn.disabled = true;

            // Append user message to UI
            appendUserMessage(message);
            
            // Show typing indicator
            typingIndicator.classList.remove('hidden');
            scrollToBottom();

            try {
                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ message: message })
                });

                const data = await response.json();
                
                // Hide typing
                typingIndicator.classList.add('hidden');

                if (data.success) {
                    appendBotMessage(data.message);
                } else {
                    appendBotMessage('❌ ' + data.message, true);
                }
            } catch (error) {
                typingIndicator.classList.add('hidden');
                appendBotMessage('❌ Lỗi kết nối đến máy chủ. Vui lòng kiểm tra lại.', true);
            } finally {
                chatInput.disabled = false;
                sendBtn.disabled = false;
                chatInput.focus();
                scrollToBottom();
            }
        });

        function appendUserMessage(text) {
            const html = `
            <div class="flex justify-end relative z-10 chat-message">
                <div class="user-bubble p-3 rounded-2xl rounded-tr-sm text-[14px] leading-relaxed shadow-md max-w-[85%] font-medium">
                    ${escapeHtml(text)}
                </div>
            </div>`;
            typingIndicator.insertAdjacentHTML('beforebegin', html);
        }

        function appendBotMessage(text, isError = false) {
            // Parse markdown
            const parsedText = marked.parse(text);
            
            const html = `
            <div class="flex justify-start relative z-10 chat-message">
                <div class="flex gap-3 max-w-[85%]">
                    <div class="w-8 h-8 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                        <span class="text-primary text-[10px] font-bold">AI</span>
                    </div>
                    <div class="bot-bubble ${isError ? 'border-red-500' : ''} text-gray-200 p-3 rounded-2xl rounded-tl-sm text-[14px] leading-relaxed shadow-sm prose prose-invert prose-p:my-1 prose-ul:my-1 prose-li:my-0 prose-sm">
                        ${parsedText}
                    </div>
                </div>
            </div>`;
            typingIndicator.insertAdjacentHTML('beforebegin', html);
        }

        function escapeHtml(unsafe) {
            return unsafe
                 .replace(/&/g, "&amp;")
                 .replace(/</g, "&lt;")
                 .replace(/>/g, "&gt;")
                 .replace(/"/g, "&quot;")
                 .replace(/'/g, "&#039;");
         }
    });
</script>
