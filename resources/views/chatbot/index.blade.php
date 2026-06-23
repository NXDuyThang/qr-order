<x-layouts.app>
    <!-- Header Spacing -->
    <div class="h-[110px] bg-[#040810]"></div>

    <section class="py-12 md:py-20 px-6 md:px-[120px] bg-[#02050A] min-h-[calc(100vh-110px)] relative flex justify-center">
        <!-- Background Elements -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-10 pointer-events-none"></div>

        <div class="w-full max-w-4xl bg-[#0a0e17] rounded-xl border border-primary/20 shadow-2xl flex flex-col relative z-10 overflow-hidden" style="height: 700px; max-height: 80vh;">
            
            <!-- Chat Header -->
            <div class="h-20 bg-[#0d131f] border-b border-primary/20 flex items-center px-8 justify-between shrink-0">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center border border-primary/30">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-white font-medium text-lg tracking-wide uppercase">Chuyên Gia Dinh Dưỡng AI</h2>
                        <p class="text-primary/70 text-xs tracking-wider">Luôn sẵn sàng hỗ trợ bạn</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                    </span>
                </div>
            </div>

            <!-- Chat Messages Area -->
            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6 scrollbar-thin scrollbar-thumb-primary/20 scrollbar-track-transparent">
                
                <!-- Initial Welcome Message -->
                <div class="flex justify-start">
                    <div class="flex gap-4 max-w-[85%]">
                        <div class="w-10 h-10 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                            <span class="text-primary text-xs font-bold">AI</span>
                        </div>
                        <div class="bg-[#111827] border border-gray-800 text-gray-200 p-4 rounded-2xl rounded-tl-sm text-[15px] leading-relaxed shadow-sm">
                            <p>Xin chào! Tôi là Chuyên gia dinh dưỡng AI của Nhà Hàng Ẩm Thực Việt.</p>
                            <p class="mt-2">Để tôi có thể tư vấn bữa ăn phù hợp nhất, bạn vui lòng cho tôi biết <strong>chiều cao</strong> và <strong>cân nặng</strong> của bạn nhé!</p>
                        </div>
                    </div>
                </div>

                @if(isset($chatHistory) && count($chatHistory) > 0)
                    @foreach($chatHistory as $msg)
                        @if($msg['role'] === 'user')
                            <div class="flex justify-end">
                                <div class="bg-primary text-[#02050A] p-4 rounded-2xl rounded-tr-sm text-[15px] leading-relaxed shadow-md max-w-[85%] font-medium">
                                    {{ $msg['content'] }}
                                </div>
                            </div>
                        @else
                            <div class="flex justify-start">
                                <div class="flex gap-4 max-w-[85%]">
                                    <div class="w-10 h-10 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                                        <span class="text-primary text-xs font-bold">AI</span>
                                    </div>
                                    <div class="bg-[#111827] border border-gray-800 text-gray-200 p-4 rounded-2xl rounded-tl-sm text-[15px] leading-relaxed shadow-sm prose prose-invert prose-p:my-1 prose-ul:my-1 prose-li:my-0">
                                        {!! Str::markdown($msg['content']) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- Typing Indicator (Hidden by default) -->
                <div id="typing-indicator" class="hidden flex justify-start">
                    <div class="flex gap-4 max-w-[85%]">
                        <div class="w-10 h-10 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                            <span class="text-primary text-xs font-bold">AI</span>
                        </div>
                        <div class="bg-[#111827] border border-gray-800 p-4 rounded-2xl rounded-tl-sm flex items-center gap-1">
                            <div class="w-2 h-2 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 0ms"></div>
                            <div class="w-2 h-2 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 150ms"></div>
                            <div class="w-2 h-2 bg-primary/60 rounded-full animate-bounce" style="animation-delay: 300ms"></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Chat Input Area -->
            <div class="p-4 md:p-6 bg-[#0d131f] border-t border-primary/20 shrink-0">
                <form id="chat-form" class="relative flex items-center">
                    @csrf
                    <input type="text" id="chat-input" placeholder="Nhập chiều cao, cân nặng hoặc câu hỏi của bạn..." 
                        class="w-full bg-[#040810] border border-gray-800 focus:border-primary text-white rounded-full pl-6 pr-16 py-4 outline-none transition-colors text-[15px] placeholder-gray-600 shadow-inner" autocomplete="off" required>
                    
                    <button type="submit" id="send-btn" class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 bg-primary hover:bg-primary/90 text-[#02050A] rounded-full flex items-center justify-center transition-transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
                <div class="text-center mt-3">
                    <span class="text-gray-600 text-[11px] font-light tracking-wide">Trí tuệ nhân tạo có thể mắc sai lầm. Hãy kiểm tra lại các thông tin quan trọng.</span>
                </div>
            </div>

        </div>
    </section>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chatForm = document.getElementById('chat-form');
            const chatInput = document.getElementById('chat-input');
            const chatMessages = document.getElementById('chat-messages');
            const typingIndicator = document.getElementById('typing-indicator');
            const sendBtn = document.getElementById('send-btn');

            // Scroll to bottom
            function scrollToBottom() {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
            scrollToBottom();

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
                <div class="flex justify-end">
                    <div class="bg-primary text-[#02050A] p-4 rounded-2xl rounded-tr-sm text-[15px] leading-relaxed shadow-md max-w-[85%] font-medium">
                        ${escapeHtml(text)}
                    </div>
                </div>`;
                typingIndicator.insertAdjacentHTML('beforebegin', html);
            }

            function appendBotMessage(text, isError = false) {
                // Parse markdown
                const parsedText = marked.parse(text);
                
                const html = `
                <div class="flex justify-start">
                    <div class="flex gap-4 max-w-[85%]">
                        <div class="w-10 h-10 rounded-full bg-primary/20 shrink-0 flex items-center justify-center border border-primary/30 mt-1">
                            <span class="text-primary text-xs font-bold">AI</span>
                        </div>
                        <div class="bg-[#111827] border ${isError ? 'border-red-500/50' : 'border-gray-800'} text-gray-200 p-4 rounded-2xl rounded-tl-sm text-[15px] leading-relaxed shadow-sm prose prose-invert prose-p:my-1 prose-ul:my-1 prose-li:my-0">
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
    @endpush
</x-layouts.app>
