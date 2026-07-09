<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        <!-- Form nhập liệu -->
        <x-filament::section>
            <form wire:submit="generateContent" class="flex flex-col gap-2 w-full">
                <label for="dishName" class="block text-sm font-medium whitespace-nowrap">Tên món ăn / Khuyến mãi</label>
                <div class="flex flex-col md:flex-row gap-4 w-full items-stretch">
                    <input type="text" id="dishName" wire:model="dishName" class="flex-grow rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600" placeholder="Ví dụ: Phở bò Nam Định" required>
                    <div class="flex-shrink-0 w-full md:w-auto flex">
                        <x-filament::button type="submit" class="w-full h-full" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="generateContent">✨ Tạo nội dung AI</span>
                            <span wire:loading wire:target="generateContent">⏳ Đang xử lý...</span>
                        </x-filament::button>
                    </div>
                </div>
            </form>
        </x-filament::section>

        <!-- Kết quả -->
        @if($generatedTitle || $generatedContent)
            <div class="flex justify-center mt-8">
                <div class="w-full max-w-2xl bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <!-- Header giống Facebook/Instagram -->
                    <div class="p-5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary-500 to-purple-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            ✨
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">AI Marketing Assistant</h4>
                            <p class="text-xs text-gray-500">Bài đăng được đề xuất • 🌐</p>
                        </div>
                    </div>
                    
                    <!-- Nội dung bài viết -->
                    <div class="px-5 pb-4 space-y-3">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $generatedTitle }}</h3>
                        <div class="prose dark:prose-invert max-w-none whitespace-pre-wrap text-gray-800 dark:text-gray-200 text-base leading-relaxed">
                            {{ $generatedContent }}
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    @if($generatedImageUrl)
                        <div class="w-full bg-gray-100 dark:bg-gray-900">
                            <img src="{{ $generatedImageUrl }}" alt="AI Generated Image" class="w-full aspect-square object-cover border-y border-gray-100 dark:border-gray-700">
                        </div>
                        
                        <!-- Footer tương tác -->
                        <div class="p-4 bg-gray-50 dark:bg-gray-800/50 flex justify-center">
                            <a href="{{ $generatedImageUrl }}" target="_blank" class="inline-flex items-center gap-2 text-primary-600 dark:text-primary-400 hover:text-primary-700 font-medium text-base transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Xem và tải ảnh gốc (1080x1080)
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
