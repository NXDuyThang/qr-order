<x-filament-panels::page>
    <div class="flex flex-col gap-6">
        <!-- Form nhập liệu -->
        <x-filament::section>
            <form wire:submit="generateContent" class="flex flex-col gap-4 w-full" x-data="{ showAdvanced: false }">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label for="dishName" class="block text-sm font-medium mb-1">Tên món ăn / Sản phẩm *</label>
                        <input type="text" id="dishName" wire:model="dishName" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600" placeholder="Ví dụ: Phở bò Nam Định" required>
                    </div>
                    
                    <div class="md:col-span-2">
                        <button type="button" @click="showAdvanced = !showAdvanced" class="text-primary-600 dark:text-primary-400 text-sm font-medium hover:underline flex items-center gap-1 transition-colors">
                            <span x-text="showAdvanced ? 'Ẩn tùy chọn nâng cao' : 'Hiện tùy chọn nâng cao'"></span>
                            <svg x-show="!showAdvanced" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" /></svg>
                            <svg x-show="showAdvanced" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4" style="display: none;"><path fill-rule="evenodd" d="M14.78 11.78a.75.75 0 0 1-1.06 0L10 8.06l-3.72 3.72a.75.75 0 0 1-1.06-1.06l4.25-4.25a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>

                    <div x-show="showAdvanced" x-collapse class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="promotion" class="block text-sm font-medium mb-1">Khuyến mãi / Ưu đãi (Tùy chọn)</label>
                            <input type="text" id="promotion" wire:model="promotion" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600" placeholder="Ví dụ: Giảm 20% cho bàn 4 người">
                        </div>

                        <div>
                            <label for="platform" class="block text-sm font-medium mb-1">Nền tảng đăng tải</label>
                            <select id="platform" wire:model="platform" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
                                <option value="facebook">Facebook</option>
                                <option value="instagram">Instagram</option>
                                <option value="tiktok">TikTok</option>
                            </select>
                        </div>

                        <div>
                            <label for="style" class="block text-sm font-medium mb-1">Phong cách viết</label>
                            <select id="style" wire:model="style" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
                                <option value="hấp dẫn, sinh động">Hấp dẫn, Sinh động</option>
                                <option value="hài hước, vui nhộn">Hài hước, Vui nhộn</option>
                                <option value="kể chuyện (storytelling)">Kể chuyện (Storytelling)</option>
                                <option value="chuyên nghiệp, sang trọng">Chuyên nghiệp, Sang trọng</option>
                                <option value="gần gũi, thân thiện">Gần gũi, Thân thiện</option>
                            </select>
                        </div>

                        <div>
                            <label for="length" class="block text-sm font-medium mb-1">Độ dài bài viết</label>
                            <select id="length" wire:model="length" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
                                <option value="ngắn gọn (khoảng 50-100 từ)">Ngắn gọn (50-100 từ)</option>
                                <option value="vừa phải (khoảng 150-200 từ)">Vừa phải (150-200 từ)</option>
                                <option value="chi tiết (khoảng 300 từ)">Chi tiết (300 từ)</option>
                            </select>
                        </div>

                        <div>
                            <label for="targetAudience" class="block text-sm font-medium mb-1">Khách hàng mục tiêu</label>
                            <select id="targetAudience" wire:model="targetAudience" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600">
                                <option value="chung">Tất cả mọi người</option>
                                <option value="giới trẻ, gen Z">Giới trẻ, Gen Z</option>
                                <option value="gia đình có trẻ nhỏ">Gia đình có trẻ nhỏ</option>
                                <option value="dân văn phòng">Dân văn phòng</option>
                                <option value="cặp đôi hẹn hò">Cặp đôi hẹn hò</option>
                            </select>
                        </div>
                    </div>

                    <div class="md:col-span-2 flex mt-2">
                        <x-filament::button type="submit" class="w-full h-[42px]" wire:loading.attr="disabled">
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
                            <x-filament::button size="sm" color="gray" icon="heroicon-o-clipboard-document" x-on:click="copyText" title="Copy nội dung" class="!px-2">
                                <span class="sr-only">Copy nội dung</span>
                            </x-filament::button>
                        </div>
                        <div x-ref="content" class="text-gray-700 dark:text-gray-300">
                            <div class="prose max-w-none dark:prose-invert whitespace-pre-wrap">{{ $generatedContent }}</div>
                            @if($generatedHashtags)
                                <div class="mt-4 text-primary-600 font-medium">{{ $generatedHashtags }}</div>
                            @endif
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
                                <x-filament::button size="sm" color="gray" icon="heroicon-o-photo" x-on:click="copyImage" title="Copy hình ảnh" class="!px-2">
                                    <span class="sr-only">Copy hình ảnh</span>
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
