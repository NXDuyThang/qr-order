<x-layouts.app>
    <div class="bg-[#0a0f16] min-h-screen text-white pb-20 font-sans pt-[110px]">
        
        <!-- Header Strip -->
        <div class="flex flex-col md:flex-row justify-between items-center py-8 md:py-12 px-8 md:px-[60px] lg:px-[120px] border-b border-primary/20">
            <div class="text-primary tracking-[0.3em] uppercase font-medium text-[15px] md:text-[18px] mb-4 md:mb-0">
                {{ $food->name }}
            </div>
            <div class="text-gray-500 text-[12px] md:text-[13px] tracking-widest flex items-center">
                <a href="{{ route('welcome') }}" class="hover:text-primary transition-colors">Trang chủ</a> 
                <span class="mx-3">»</span> 
                <a href="{{ route('vietnamese_cuisine') }}" class="hover:text-primary transition-colors">Ẩm thực Việt</a> 
                <span class="mx-3">»</span> 
                <span class="text-white">{{ $food->name }}</span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row border-b border-primary/20 relative">
            
            <!-- Left Column: Images -->
            <div class="w-full lg:w-[60%] xl:w-[65%] border-r border-primary/20 flex flex-col relative group">
                <!-- Main Image -->
                @php
                    $imageUrl = '/images/default-food.jpg';
                    if (!empty($food->image)) {
                        if (str_starts_with($food->image, '/') || str_starts_with($food->image, 'http')) {
                            $imageUrl = $food->image;
                        } else {
                            $imageUrl = Storage::url($food->image);
                        }
                    }
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $food->name }}" class="w-full h-auto min-h-[500px] object-cover border-b border-primary/20">
            </div>

            <!-- Right Column: Details -->
            <div class="w-full lg:w-[40%] xl:w-[35%] p-8 md:p-12 lg:p-16 xl:p-20 relative flex flex-col justify-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-serif text-primary mb-8 tracking-[0.15em] uppercase leading-tight">
                    HƯƠNG VỊ<br>ĐẬM ĐÀ
                </h2>
                
                <!-- Mô tả món ăn -->
                <div class="text-gray-400 font-light leading-loose mb-12 text-[14px] md:text-[15px] text-justify">
                    {!! nl2br(e($food->description ?? 'Đang cập nhật mô tả cho món ăn này. Chắc chắn đây sẽ là một trải nghiệm ẩm thực tuyệt vời mang đậm hương vị truyền thống, đánh thức mọi giác quan của bạn.')) !!}
                </div>

                <div class="space-y-6">
                    <div class="flex items-start gap-4 text-[13px] tracking-[0.2em]">
                        <span class="text-primary uppercase w-32 shrink-0">Danh mục:</span>
                        <span class="text-white">{{ $food->category->name ?? 'Món ngon' }}</span>
                    </div>
                    
                    <div class="flex items-start gap-4 text-[13px] tracking-[0.2em]">
                        <span class="text-primary uppercase w-32 shrink-0">Giá:</span>
                        <span class="text-white">{{ number_format($food->price * 1000, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>

                <!-- Floating Buttons from screenshot (pink and white on the right edge) -->
                <!-- We place them absolute to the right edge of this column to mimic the sticky side buttons -->
                <div class="hidden lg:flex flex-col absolute -right-[48px] top-1/2 -translate-y-1/2 z-50 shadow-2xl">
                    <a href="{{ route('vietnamese_cuisine') }}" class="w-12 h-12 bg-[#ff3366] flex justify-center items-center text-white hover:bg-[#e62e5c] transition-colors" title="Quay lại danh sách">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </a>
                    <a href="{{ route('order_at_table') }}" class="w-12 h-12 bg-white flex justify-center items-center text-black hover:bg-gray-200 transition-colors" title="Đặt món">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Navigation Area -->
        <div class="flex justify-between items-center py-16 px-10 md:px-[60px] lg:px-[120px]">
            <a href="{{ route('vietnamese_cuisine') }}" class="text-gray-600 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <a href="{{ route('vietnamese_cuisine') }}" class="text-gray-600 hover:text-primary transition-colors flex flex-col gap-1.5 items-center justify-center w-10 h-10 group">
                <div class="grid grid-cols-2 gap-1.5">
                    <div class="w-1.5 h-1.5 border border-current group-hover:bg-primary transition-colors"></div>
                    <div class="w-1.5 h-1.5 border border-current group-hover:bg-primary transition-colors"></div>
                    <div class="w-1.5 h-1.5 border border-current group-hover:bg-primary transition-colors"></div>
                    <div class="w-1.5 h-1.5 border border-current group-hover:bg-primary transition-colors"></div>
                </div>
            </a>

            <a href="{{ route('vietnamese_cuisine') }}" class="text-gray-600 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
        
        <!-- Footer / Contact Info snippet from bottom of third screenshot -->
        <div class="max-w-xl mx-auto text-center py-10 flex flex-col items-center justify-center gap-8 mt-4 relative">
            <!-- Deco lines -->
            <div class="relative w-12 h-12 flex items-center justify-center">
                <div class="absolute border-l border-b border-primary w-8 h-8 -ml-4 -mb-4"></div>
                <div class="absolute border-l border-b border-primary w-6 h-6 -ml-2 -mb-2 opacity-70"></div>
                <div class="absolute border-l border-b border-primary w-4 h-4 opacity-40"></div>
            </div>
            
            <div class="text-white text-[12px] md:text-[13px] tracking-widest leading-loose font-light">
                QR Order Restaurant & Fine dining, Hanoi<br>
                0123 456 789, reservations@qrorder.com<br>
                Open: 09:00 am - 10:00 pm
            </div>
            
            <!-- Scroll to top button -->
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="w-12 h-12 rounded-full border border-primary/30 text-primary flex justify-center items-center hover:bg-primary hover:text-white transition-colors mt-8 group">
                <svg class="w-4 h-4 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 15l7-7 7 7"></path></svg>
            </button>
        </div>

    </div>
</x-layouts.app>
