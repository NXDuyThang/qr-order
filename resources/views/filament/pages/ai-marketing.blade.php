<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        <!-- Form nhập liệu -->
        <x-filament::section>
            <form wire:submit="generateContent" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 w-full">
                    <label for="dishName" class="block text-sm font-medium mb-1">Tên món ăn / Khuyến mãi</label>
                    <input type="text" id="dishName" wire:model="dishName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600" placeholder="Ví dụ: Phở bò Nam Định" required>
                </div>
                
                <x-filament::button type="submit" class="w-full md:w-auto h-[42px]" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="generateContent">✨ Tạo nội dung AI</span>
                    <span wire:loading wire:target="generateContent">⏳ Đang xử lý...</span>
                </x-filament::button>
            </form>
        </x-filament::section>

        <!-- Kết quả -->
        @if($generatedTitle || $generatedContent)
            <x-filament::section class="relative overflow-hidden">
                <div class="flex flex-col md:flex-row gap-6">
                    <div class="flex-1 space-y-4">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $generatedTitle }}</h3>
                        <div class="prose dark:prose-invert whitespace-pre-wrap text-gray-700 dark:text-gray-300">
                            {{ $generatedContent }}
                        </div>
                    </div>
                    @if($generatedImageUrl)
                        <div class="w-full md:w-2/5 flex-shrink-0">
                            <p class="text-sm text-gray-500 mb-2 italic text-center md:text-left">Hình ảnh minh họa AI tạo:</p>
                            <img src="{{ $generatedImageUrl }}" alt="AI Generated Image" class="w-full rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                            <a href="{{ $generatedImageUrl }}" target="_blank" class="block mt-2 text-center text-primary-600 hover:underline text-sm">Xem kích thước đầy đủ</a>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
