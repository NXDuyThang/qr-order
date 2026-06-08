<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative h-[80vh] flex">
        <!-- Slider (Left) -->
        <div class="w-full md:w-2/3 h-full relative">
            <img src="/images/pho-bo.png" alt="Delicious Food" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/30"></div>
        </div>
        
        <!-- Booking Form (Right) -->
        <div class="hidden md:flex w-1/3 bg-[#0a0f16] h-full flex-col justify-center items-center px-12 border-l border-primary/20">
            <div class="text-center mb-10 w-full">
                <h3 class="font-script-tagline text-[35px] text-primary mb-1">Đặt Bàn</h3>
                <h2 class="section-title-deco mb-6">GIỮ CHỖ NGAY</h2>
            </div>
            
            <form action="{{ route('booking') }}" method="GET" class="w-full space-y-6">
                <!-- Guests -->
                <div class="relative w-full">
                    <select name="guests" class="w-full h-[60px] bg-[#040810] border border-primary/30 px-6 text-[16px] tracking-[0.1em] text-white appearance-none focus:outline-none focus:border-primary transition-colors cursor-pointer">
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
                <div>
                    <input type="date" name="date" required min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onclick="this.showPicker()" class="w-full h-[60px] bg-[#040810] border border-primary/30 px-6 text-white text-[16px] tracking-[0.1em] focus:outline-none focus:border-primary transition-colors [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
                </div>
                <!-- Time -->
                <div>
                    <input type="time" name="time" required value="19:00" onclick="this.showPicker()" class="w-full h-[60px] bg-[#040810] border border-primary/30 px-6 text-white text-[16px] tracking-[0.1em] focus:outline-none focus:border-primary transition-colors [&::-webkit-calendar-picker-indicator]:invert cursor-pointer">
                </div>
                <button type="submit" class="w-full h-[60px] inline-flex items-center justify-center px-12 border border-primary text-white text-[13px] tracking-[0.3em] uppercase hover:bg-primary transition-colors duration-300 mt-4 cursor-pointer whitespace-nowrap">
                    ĐẶT BÀN NGAY
                </button>
            </form>
        </div>
    </section>

    <!-- Specialties Section -->
    <section class="py-24 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center">
                <!-- Text -->
                <div class="w-full md:w-1/2 pr-0 md:pr-12 mb-12 md:mb-0 text-center md:text-left">
                    <span class="text-primary italic font-serif text-xl">Gợi Ý Cho Bạn</span>
                    <h2 class="text-4xl font-serif tracking-[0.2em] mt-2 mb-8 uppercase text-white">
                        Đặc Sản
                    </h2>
                    <p class="text-gray-400 mb-8 leading-relaxed">
                        Khám phá hương vị đích thực của ẩm thực Việt Nam với những món ăn đặc sản được chế biến từ nguyên liệu tươi ngon nhất, mang đậm bản sắc văn hóa truyền thống.
                    </p>
                    <a href="{{ route('menu') }}" class="inline-block border border-gray-600 hover:border-primary hover:text-primary text-white py-3 px-8 tracking-[0.2em] text-xs font-semibold uppercase transition-colors">
                        XEM TẤT CẢ
                    </a>
                </div>
                <!-- Image -->
                <div class="w-full md:w-1/2">
                    <img src="/images/story-1.jpg" alt="Đặc Sản Việt Nam" class="w-full h-[500px] object-cover shadow-2xl rounded-sm transition-all duration-700">
                </div>
            </div>
        </div>
    </section>

    <!-- Category Grid -->
    <section class="w-full flex flex-wrap">
        @foreach($categories as $category)
        <div class="w-full md:w-1/4 h-[50vh] relative group overflow-hidden cursor-pointer bg-[#0a0f16]">
            <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-contain p-6 transition-transform duration-700 group-hover:scale-110">
            <div class="absolute inset-0 bg-black/60 group-hover:bg-black/30 transition-colors duration-500"></div>
            <div class="absolute inset-0 flex flex-col justify-center items-center opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-dark/80 m-4">
                <h3 class="text-primary font-serif tracking-[0.2em] uppercase text-xl mb-2">{{ $category->name }}</h3>
            </div>
        </div>
        @endforeach
    </section>

    <!-- Special Menu Section -->
    <section class="py-24 border-b border-gray-800 bg-darker">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-primary italic font-serif text-xl">Món ngon tuần này</span>
                <h2 class="text-4xl font-serif tracking-[0.2em] mt-2 uppercase text-white">
                    Thực Đơn Của Chúng Tôi
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-12">
                @foreach($specialFoods as $food)
                <div class="flex items-center group">
                    <img src="{{ $food->image }}" alt="{{ $food->name }}" class="w-20 h-20 object-cover rounded-full border border-gray-700 p-1 mr-6">
                    <div class="flex-grow">
                        <div class="flex justify-between items-baseline mb-1">
                            <h4 class="font-serif tracking-widest text-lg text-white group-hover:text-primary transition-colors uppercase">{{ $food->name }}</h4>
                            <span class="text-primary font-serif text-lg">{{ number_format($food->price * 1000, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="w-full border-b border-gray-800 mb-2"></div>
                        <p class="text-sm text-gray-500">{{ $food->description }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="text-center mt-16">
                <a href="{{ route('menu') }}" class="inline-block border border-gray-600 hover:border-primary hover:text-primary text-white py-3 px-8 tracking-[0.2em] text-xs font-semibold uppercase transition-colors">
                    XEM TẤT CẢ
                </a>
            </div>
        </div>
    </section>
</x-layouts.app>
