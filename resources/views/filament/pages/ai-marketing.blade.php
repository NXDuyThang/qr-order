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
            <x-filament::section class="relative overflow-hidden">
                <div class="flex flex-col gap-6">
                    <div class="w-full space-y-4 relative group" x-data="{
                        copyText() {
                            const text = $refs.content.innerText;
                            navigator.clipboard.writeText(text).then(() => {
                                new FilamentNotification()
                                    .title('Đã copy nội dung!')
                                    .success()
                                    .send();
                            });
                        }
                    }">
                        <div class="flex justify-between items-start gap-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $generatedTitle }}</h3>
                            <x-filament::button size="sm" color="gray" icon="heroicon-o-clipboard-document" x-on:click="copyText">
                                Copy nội dung
                            </x-filament::button>
                        </div>
                        <div x-ref="content" class="prose max-w-none dark:prose-invert whitespace-pre-wrap text-gray-700 dark:text-gray-300">
                            {{ $generatedContent }}
                        </div>
                    </div>
                    @if($generatedImageUrl)
                        <div class="w-full flex flex-col items-center border-t border-gray-200 dark:border-gray-700 pt-6 mt-6" x-data="{
                            copyImage() {
                                fetch('{{ $generatedImageUrl }}')
                                    .then(response => response.blob())
                                    .then(blob => {
                                        const item = new ClipboardItem({ [blob.type]: blob });
                                        navigator.clipboard.write([item]).then(() => {
                                            new FilamentNotification()
                                                .title('Đã copy hình ảnh!')
                                                .success()
                                                .send();
                                        });
                                    })
                                    .catch(err => {
                                        console.error(err);
                                        new FilamentNotification()
                                            .title('Không thể copy hình trực tiếp, vui lòng nhấp chuột phải và chọn Copy Image')
                                            .danger()
                                            .send();
                                    });
                            }
                        }">
                            <div class="flex justify-between w-full max-w-2xl items-end mb-4">
                                <p class="text-sm text-gray-500 italic">Hình ảnh minh họa AI tạo:</p>
                                <x-filament::button size="sm" color="gray" icon="heroicon-o-photo" x-on:click="copyImage">
                                    Copy hình ảnh
                                </x-filament::button>
                            </div>
                            <img src="{{ $generatedImageUrl }}" alt="AI Generated Image" class="max-w-full md:max-w-2xl rounded-lg shadow-md border border-gray-200 dark:border-gray-700 mx-auto">
                            <a href="{{ $generatedImageUrl }}" target="_blank" class="block mt-4 text-center text-primary-600 hover:underline text-sm">Xem kích thước đầy đủ</a>
                        </div>
                    @endif
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
