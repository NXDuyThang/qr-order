<x-layouts.app>
    <!-- Hero Banner -->
    <section class="relative w-full h-[60vh] mt-[-110px] overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=1920&q=80');"></div>
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-6 pt-[110px] z-20">
            <div class="flex flex-col items-center">
                <span class="font-script-tagline text-[70px] md:text-[100px] text-primary leading-none select-none transform -rotate-3">Hương Vị</span>
                <span class="font-script-tagline text-[70px] md:text-[100px] text-white leading-none select-none transform -rotate-3 ml-24 mt-[-20px]">Đích Thực</span>
            </div>
        </div>
    </section>

    <!-- Main Menu Content -->
    <section class="relative w-full py-24 md:py-32 px-6 md:px-[60px] lg:px-[120px] bg-[#040810] z-20">
        <!-- Header -->
        <div class="max-w-4xl mx-auto text-center mb-24">
            <h3 class="font-script-tagline text-[30px] md:text-[40px] text-primary mb-4">Lựa chọn hàng đầu</h3>
            <div class="flex items-center justify-center gap-6 mb-6">
                <span class="w-12 h-[1px] bg-primary/40 hidden md:block"></span>
                <h2 class="text-[30px] md:text-[45px] uppercase tracking-[0.3em] text-white font-light">THỰC ĐƠN</h2>
                <span class="w-12 h-[1px] bg-primary/40 hidden md:block"></span>
            </div>
            <p class="text-gray-400 font-light leading-relaxed text-[14px] max-w-2xl mx-auto">
                Khám phá những hương vị độc đáo được tuyển chọn kỹ lưỡng từ các nền ẩm thực hàng đầu. Mỗi món ăn là một tác phẩm nghệ thuật dành riêng cho bạn.
            </p>
        </div>

        <!-- Menu Categories List -->
        <div class="max-w-7xl mx-auto flex flex-col gap-32">
            @foreach($categories as $index => $category)
                @php
                    // Alternate layout based on even/odd index
                    $isEven = $index % 2 == 0;
                @endphp

                <div class="flex flex-col {{ $isEven ? 'lg:flex-row' : 'lg:flex-row-reverse' }} gap-16 lg:gap-24 items-center">
                    
                    <!-- Menu Items Column -->
                    <div class="w-full lg:w-1/2">
                        <!-- Category Title -->
                        <div class="text-center {{ $isEven ? 'lg:text-left' : 'lg:text-right' }} mb-16">
                            <h3 class="font-script-tagline text-[32px] text-primary mb-3">Hương vị</h3>
                            <div class="flex items-center justify-center {{ $isEven ? 'lg:justify-start' : 'lg:justify-end' }} gap-4">
                                <span class="w-8 h-[1px] bg-primary/50"></span>
                                <h2 class="text-[26px] uppercase tracking-[0.2em] text-white font-medium">{{ $category->name }}</h2>
                                <span class="w-8 h-[1px] bg-primary/50"></span>
                            </div>
                        </div>

                        <!-- Food Items -->
                        <div class="flex flex-col gap-10">
                            @foreach($category->food as $food)
                                <div>
                                    <div class="flex items-baseline justify-between mb-3 gap-4">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('product.detail', $food->slug) }}" class="text-[14px] md:text-[15px] tracking-[0.15em] uppercase font-medium text-white hover:text-primary transition-colors">
                                                {{ $food->name }}
                                            </a>
                                            <!-- Heart Icon -->
                                            <button class="w-6 h-6 flex items-center justify-center text-red-500 hover:scale-110 transition-transform btn-wishlist" data-id="{{ $food->id }}">
                                                @php
                                                    $isWishlisted = Auth::check() && $food->isWishlistedBy(Auth::user());
                                                @endphp
                                                <svg class="w-4 h-4 heart-icon" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                            </button>
                                        </div>
                                        <div class="flex-grow border-b border-primary/30 relative top-[-6px]"></div>
                                        <span class="text-[15px] tracking-[0.1em] text-primary font-medium whitespace-nowrap">{{ number_format($food->price * 1000, 0, ',', '.') }} VNĐ</span>
                                    </div>
                                    @if($food->description)
                                        <p class="text-[13px] text-gray-400 font-light leading-relaxed {{ $isEven ? 'text-left' : 'text-left lg:text-right' }} pr-4 {{ $isEven ? '' : 'lg:pr-0 lg:pl-4' }}">
                                            {{ $food->description }}
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Image Column with Geometric Pattern -->
                    <div class="w-full lg:w-1/2 flex justify-center relative">
                        <!-- Decorative geometric pattern background -->
                        <div class="absolute w-full h-full z-0 
                            {{ $isEven ? '-right-6 -bottom-6' : '-left-6 -top-6' }}"
                             style="background-image: url(&quot;data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M20 0L40 20L20 40L0 20L20 0z' fill='none' stroke='%230077bb' stroke-width='1' stroke-opacity='0.25'/%3E%3C/svg%3E&quot;); max-width: 80%; max-height: 80%;">
                        </div>
                        
                        <div class="relative z-10 w-[85%] aspect-square overflow-hidden bg-[#0a0f18] shadow-2xl">
                            <img src="{{ $category->image ?? '/images/default-food.jpg' }}" alt="{{ $category->name }}" 
                                 class="w-full h-full object-cover filter brightness-75 hover:brightness-100 hover:scale-105 transition-all duration-700">
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </section>
    <section class="relative w-full py-24 px-6 md:px-[120px] bg-transparent z-20 border-t border-primary/20">
        <form action="{{ route('booking') }}" method="GET" class="max-w-5xl mx-auto flex flex-col md:flex-row gap-6 justify-center items-center">
            <!-- Guests -->
            <div class="relative w-full md:w-64">
                <select name="guests" class="w-full h-[60px] border border-primary/30 px-6 appearance-none bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary cursor-pointer">
                    <option value="1">1 Người</option>
                    <option value="2" selected>2 Người</option>
                    <option value="3">3 Người</option>
                    <option value="4">4 Người</option>
                    <option value="5">5+ Người</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-6 pointer-events-none">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <!-- Date -->
            <div class="relative w-full md:w-64">
                <input type="date" name="date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onclick="this.showPicker()" class="w-full h-[60px] border border-primary/30 px-6 bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
            </div>
            <!-- Time -->
            <div class="relative w-full md:w-64">
                <input type="time" name="time" required value="19:00" onclick="this.showPicker()" class="w-full h-[60px] border border-primary/30 px-6 bg-[#040810]/80 backdrop-blur-sm text-[16px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
            </div>
            <!-- Button -->
            <button type="submit" class="w-full md:w-auto h-[60px] px-12 border border-primary text-[13px] uppercase tracking-[0.3em] font-medium text-white hover:bg-primary transition-all duration-300 bg-transparent cursor-pointer flex items-center justify-center whitespace-nowrap">
                ĐẶT BÀN NGAY
            </button>
        </form>
        <div class="max-w-5xl mx-auto mt-4 flex justify-start pl-0 md:pl-2">
            <span class="text-gray-500 text-[11px] font-light tracking-wide">*Cung cấp bởi QR Order</span>
        </div>
    </section>
</x-layouts.app>
