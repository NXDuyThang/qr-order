<x-layouts.app>

    @push('styles')
    <style>
        /* Slide transition animations */
        .slide-content {
            transition: opacity 1.2s cubic-bezier(0.4, 0, 0.2, 1), transform 1.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .slide-inactive {
            opacity: 0;
            transform: scale(1.03);
            pointer-events: none;
        }
        .slide-active {
            opacity: 1;
            transform: scale(1);
            pointer-events: auto;
        }
        /* Slow rotation zoom effect on background images */
        .bg-zoom {
            transition: transform 12s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .slide-active .bg-zoom {
            transform: scale(1.06) rotate(0.5deg);
        }
    </style>
    @endpush

    <!-- Hero Slider Section -->
    <section class="relative w-full h-[100vh] mt-[-110px] overflow-hidden">
        <!-- Slide 1 -->
        <div id="slide-0" class="slide-content absolute inset-0 slide-active">
            <div class="bg-zoom absolute inset-0 bg-cover bg-center duration-[12000ms]" style="background-image: url('https://images.unsplash.com/photo-1568644396922-5c3bfae12521?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-6 md:px-24 z-20">
                <span class="font-script-tagline text-[54px] md:text-[68px] lg:text-[76px] text-primary mb-1 select-none leading-none">thưởng thức những thức uống tuyệt hảo</span>
                <h1 class="font-light text-[40px] md:text-[65px] lg:text-[85px] leading-tight select-none uppercase tracking-[0.05em] flex items-center gap-4">
                    <span class="text-white/20 font-extralight select-none">-</span>
                    <span class="text-outline-luxury">Đồ Uống</span>
                    <span class="text-outline-primary">Thượng Hạng</span>
                    <span class="text-white/20 font-extralight select-none">-</span>
                </h1>
                <p class="mt-6 text-sm md:text-base text-gray-300 font-light leading-relaxed max-w-2xl text-center px-4 tracking-wide">
                    Từ những món ăn tinh tế đến những ly cocktail tuyệt vời, nhà hàng chúng tôi đảm bảo sẽ làm hài lòng vị giác của bạn.
                </p>
                <div class="mt-10">
                    <a href="{{ url('/menu') }}" class="inline-block px-10 py-4 border border-primary/40 text-[10px] uppercase tracking-[0.45em] font-medium text-white hover:bg-primary hover:border-primary transition-all duration-500 pointer-events-auto">
                        XEM THÊM
                    </a>
                </div>
            </div>
        </div>

        <!-- Slide 2 -->
        <div id="slide-1" class="slide-content absolute inset-0 slide-inactive">
            <div class="bg-zoom absolute inset-0 bg-cover bg-center duration-[12000ms]" style="background-image: url('https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?auto=format&fit=crop&w=1920&q=80');"></div>
            <div class="absolute inset-0 bg-black/60"></div>
            <div class="absolute inset-0 flex flex-col justify-center items-center text-center px-6 md:px-24 z-20">
                <span class="font-script-tagline text-[54px] md:text-[68px] lg:text-[76px] text-primary mb-1 select-none leading-none">thưởng thức tinh hoa ẩm thực</span>
                <h1 class="font-light text-[40px] md:text-[65px] lg:text-[85px] leading-tight select-none uppercase tracking-[0.05em] flex items-center gap-4">
                    <span class="text-white/20 font-extralight select-none">-</span>
                    <span class="text-outline-luxury">Tinh Hoa</span>
                    <span class="text-outline-primary">Ẩm Thực</span>
                    <span class="text-white/20 font-extralight select-none">-</span>
                </h1>
                <p class="mt-6 text-sm md:text-base text-gray-300 font-light leading-relaxed max-w-2xl text-center px-4 tracking-wide">
                    Những tuyệt tác ẩm thực được chế biến từ nguyên liệu tươi ngon nhất, bởi các đầu bếp chuyên nghiệp.
                </p>
                <div class="mt-10">
                    <a href="{{ url('/menu') }}" class="inline-block px-10 py-4 border border-primary/40 text-[10px] uppercase tracking-[0.45em] font-medium text-white hover:bg-primary hover:border-primary transition-all duration-500 pointer-events-auto">
                        KHÁM PHÁ THỰC ĐƠN
                    </a>
                </div>
            </div>
        </div>

        <!-- Slider Arrows -->
        <div class="absolute inset-y-0 left-0 w-[60px] flex items-center justify-center z-30">
            <button onclick="prevSlide()" class="text-primary hover:text-white hover:scale-110 transition-all duration-300 pointer-events-auto group">
                <svg class="w-10 h-10 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            </button>
        </div>
        <div class="absolute inset-y-0 right-0 w-[60px] flex items-center justify-center z-30">
            <button onclick="nextSlide()" class="text-primary hover:text-white hover:scale-110 transition-all duration-300 pointer-events-auto group">
                <svg class="w-10 h-10 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path></svg>
            </button>
        </div>

        <!-- Slide Indicators -->
        <div class="absolute bottom-[40px] left-1/2 transform -translate-x-1/2 z-30 flex items-center gap-[40px] px-6 py-2.5">
            <button onclick="goToSlide(0)" id="dot-0" class="text-[12px] font-semibold tracking-[0.25em] transition-all duration-300 text-primary scale-110 pointer-events-auto relative">
                1<span class="absolute left-0 right-0 -bottom-1 h-[1px] bg-primary"></span>
            </button>
            <button onclick="goToSlide(1)" id="dot-1" class="text-[12px] font-semibold tracking-[0.25em] transition-all duration-300 text-gray-500 hover:text-white pointer-events-auto">2</button>
        </div>
    </section>

    <!-- Our Tips Section -->
    <section class="relative w-full py-32 px-6 md:px-[120px] bg-transparent z-20">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-16">
            <!-- Images Left -->
            <div class="w-full lg:w-1/2 flex gap-6 justify-center relative">
                <img src="/images/banh-mi.png" alt="Bánh Mì" class="w-[45%] object-cover object-center aspect-[2/3] filter brightness-75 hover:brightness-100 transition-all duration-500 rounded-sm">
                <img src="/images/bun-rieu.jpg" alt="Bún Riêu" class="w-[45%] object-cover object-center aspect-[2/3] filter brightness-75 hover:brightness-100 transition-all duration-500 mt-12 rounded-sm">
            </div>
            <!-- Text Right -->
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-start text-center lg:text-left">
                <h3 class="font-script-tagline text-[40px] md:text-[50px] text-primary mb-2">Triết lý ẩm thực</h3>
                <h2 class="section-title-deco mb-8">BÍ QUYẾT</h2>
                <p class="text-gray-300 font-light leading-relaxed mb-10 max-w-md text-[15px]">
                    Ẩm thực không chỉ là việc chế biến các món ăn, mà còn là một nghệ thuật mang lại những trải nghiệm khó quên cho thực khách. Mỗi món ăn là một câu chuyện độc đáo.
                </p>
                <a href="#" class="read-more-btn text-[11px] uppercase tracking-[0.3em] font-medium text-white hover:text-primary pointer-events-auto">
                    XEM THÊM
                </a>
            </div>
        </div>
    </section>

    <!-- From Our Menu Section -->
    <section class="relative w-full py-24 md:py-32 px-12 md:px-[120px] bg-transparent z-20">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 md:mb-20">
                <h3 class="font-script-tagline text-[40px] md:text-[50px] text-primary mb-2">Lựa chọn tuyệt hảo</h3>
                <h2 class="section-title-deco">TỪ THỰC ĐƠN</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-24 gap-y-0 md:gap-y-12">
                <!-- Column 1 -->
                <div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">PHỞ BÒ ĐẶC BIỆT</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$65</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Bánh phở mềm, nước dùng thanh ngọt, thịt bò tươi</p>
                    </div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">BÚN RIÊU CUA</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$55</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Bún riêu cua đồng, chả lụa, đậu hũ chiên, huyết, ốc</p>
                    </div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">CƠM TẤM SƯỜN BÌ</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$70</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Sườn nướng mật ong, bì lợn dai, chả trứng, nước mắm</p>
                    </div>
                </div>
                
                <!-- Column 2 -->
                <div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">BÁNH MÌ THỊT NƯỚNG</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$25</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Bánh mì giòn, thịt nướng, patê, chả lụa, rau thơm</p>
                    </div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">BÚN ĐẬU MẮM TÔM</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$60</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Bún lá, đậu hũ chiên, thịt luộc, chả cốm, mắm tôm</p>
                    </div>
                    <div class="mb-12 md:mb-8 text-left">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-baseline mb-3 md:mb-2">
                            <h4 class="text-[15px] tracking-[0.2em] uppercase font-medium text-primary mb-2 md:mb-0">TRÀ TẮC</h4>
                            <div class="hidden md:block flex-grow border-b border-primary/40 mx-4 relative top-[-4px]"></div>
                            <span class="text-[15px] tracking-[0.1em] text-primary font-medium">$15</span>
                        </div>
                        <p class="text-[14px] text-gray-300 font-light leading-relaxed md:pr-0">Trà tắc chua ngọt mát lạnh, giải nhiệt ngày hè</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('menu') }}" class="inline-block px-10 py-4 border border-primary/40 text-[10px] uppercase tracking-[0.45em] font-medium text-white hover:bg-primary hover:border-primary hover:shadow-lg hover:shadow-primary/30 transition-all duration-500 pointer-events-auto">
                    XEM TẤT CẢ
                </a>
            </div>
        </div>
    </section>

    <!-- Our Best Specialties -->
    <section class="relative w-full py-32 px-6 md:px-[120px] bg-transparent z-20">
        <div class="max-w-6xl mx-auto flex flex-col lg:flex-row items-center justify-between gap-16">
            <!-- Image Left -->
            <div class="w-full lg:w-1/2 flex justify-center">
                <img src="/images/pho-bo.png" alt="Phở Bò" class="w-full max-w-md object-cover object-center aspect-square filter brightness-90 hover:brightness-100 transition-all duration-500 rounded-full border-8 border-primary/20">
            </div>
            <!-- Text Right -->
            <div class="w-full lg:w-1/2 flex flex-col items-center lg:items-center text-center">
                <h3 class="font-script-tagline text-[40px] md:text-[50px] text-primary mb-2">Gợi ý của bếp trưởng</h3>
                <h2 class="section-title-deco mb-8">MÓN NGON<br>ĐẶC SẢN</h2>
                <p class="text-gray-300 font-light leading-relaxed mb-10 max-w-md text-[15px]">
                    Hãy nếm thử những món ăn làm nên tên tuổi của nhà hàng, sự kết hợp hoàn hảo giữa kỹ thuật nấu ăn hiện đại và nguyên liệu truyền thống.
                </p>
                <a href="{{ route('menu') }}" class="inline-block px-10 py-4 border border-primary/40 text-[10px] uppercase tracking-[0.45em] font-medium text-white hover:bg-primary hover:border-primary transition-all duration-500 pointer-events-auto">
                    XEM TẤT CẢ
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="relative w-full flex flex-col md:flex-row h-auto md:h-[600px] bg-transparent z-20 border-y border-primary/20">
        <!-- Left Side -->
        <div class="w-full md:w-1/2 flex flex-col items-center justify-center p-12 md:p-24 text-center bg-[#040810]/90">
            <svg class="w-12 h-12 text-primary mb-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
            </svg>
            <p class="font-script-tagline text-[28px] md:text-[34px] leading-relaxed text-gray-200 mb-8 max-w-lg">
                Hương vị tuyệt hảo, không gian sang trọng và phục vụ chu đáo. Trải nghiệm ẩm thực tại đây luôn khiến tôi ấn tượng và muốn quay lại.
            </p>
            <h4 class="text-[13px] tracking-[0.2em] uppercase text-primary mb-1">NGUYỄN MINH KHANG</h4>
            <span class="text-[13px] text-gray-400 font-light tracking-widest">Chuyên Gia Ẩm Thực</span>
        </div>
        <!-- Right Side Image -->
        <div class="w-full md:w-1/2 h-[400px] md:h-full flex items-center justify-center bg-[#040810] py-8">
            <div class="relative w-full h-[80%] max-w-[400px] aspect-square rounded-2xl shadow-2xl overflow-hidden p-2 bg-[#111518] mx-auto">
                <img src="/images/bun-dau-mam-tom.jpg" class="w-full h-full object-contain rounded-xl" alt="Bún Đậu Mắm Tôm">
            </div>
        </div>
    </section>

    <!-- About Us (Our Story) Section -->
    <section class="relative w-full py-32 px-6 md:px-[60px] lg:px-[120px] bg-transparent z-20">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-20">
                <h3 class="font-script-tagline text-[40px] md:text-[50px] text-primary mb-2">Câu chuyện của chúng tôi</h3>
                <h2 class="section-title-deco mb-8">VỀ CHÚNG TÔI</h2>
                <p class="text-gray-300 font-light leading-relaxed max-w-xl mx-auto text-[15px]">
                    Khởi nguồn từ tình yêu với ẩm thực truyền thống, chúng tôi xây dựng một không gian nơi mỗi bữa ăn đều là một hành trình kết nối văn hóa và cảm xúc.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Image 1 -->
                <div class="aspect-[3/4]">
                    <img src="/images/story-1.jpg" alt="Không gian nhà hàng 1" class="w-full h-full object-cover filter brightness-75 hover:brightness-100 transition-all duration-500 rounded-sm">
                </div>
                <!-- Image 2 (Center) -->
                <div class="aspect-[3/4]">
                    <img src="/images/story-2.jpg" alt="Không gian nhà hàng 2" class="w-full h-full object-cover filter brightness-75 hover:brightness-100 transition-all duration-500 rounded-sm">
                </div>
                <!-- Image 3 -->
                <div class="aspect-[3/4]">
                    <img src="/images/story-3.jpg" alt="Không gian nhà hàng 3" class="w-full h-full object-cover filter brightness-75 hover:brightness-100 transition-all duration-500 rounded-sm">
                </div>
            </div>
        </div>
    </section>

    <section class="relative w-full py-24 px-6 md:px-[120px] bg-transparent z-20 border-t border-primary/20">
        <form action="{{ route('booking') }}" method="GET" class="max-w-5xl mx-auto flex flex-col md:flex-row gap-6 justify-center items-center">
            <!-- Guests -->
            <div class="relative w-full md:w-64">
                <select name="guests" class="w-full border border-primary/30 py-3 px-4 appearance-none bg-[#040810]/80 backdrop-blur-sm text-[13px] tracking-[0.1em] text-white hover:border-primary transition-colors focus:outline-none focus:border-primary">
                    <option value="1">1 Người</option>
                    <option value="2" selected>2 Người</option>
                    <option value="3">3 Người</option>
                    <option value="4">4 Người</option>
                    <option value="5">5+ Người</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none">
                    <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
            <!-- Date -->
            <div class="relative w-full md:w-64">
                <input type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border border-primary/30 py-3 px-4 bg-[#040810]/80 backdrop-blur-sm text-[13px] tracking-[0.1em] text-gray-300 hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert">
            </div>
            <!-- Time -->
            <div class="relative w-full md:w-64">
                <input type="time" name="time" required value="19:00" class="w-full border border-primary/30 py-3 px-4 bg-[#040810]/80 backdrop-blur-sm text-[13px] tracking-[0.1em] text-gray-300 hover:border-primary transition-colors focus:outline-none focus:border-primary [&::-webkit-calendar-picker-indicator]:invert">
            </div>
            <!-- Button -->
            <button type="submit" class="w-full md:w-auto px-10 py-3.5 border border-primary text-[11px] uppercase tracking-[0.45em] font-medium text-white hover:bg-primary transition-all duration-300 bg-transparent cursor-pointer">
                ĐẶT BÀN NGAY
            </button>
        </form>
        <div class="max-w-5xl mx-auto mt-4 flex justify-start pl-0 md:pl-2">
            <span class="text-gray-500 text-[11px] font-light tracking-wide">*Cung cấp bởi QR Order</span>
        </div>
    </section>

    @push('scripts')
    <script>
        // Slider Logic
        let currentSlide = 0;
        const totalSlides = 2;
        let slideInterval;

        function updateSlideClasses() {
            for (let i = 0; i < totalSlides; i++) {
                const slide = document.getElementById(`slide-${i}`);
                const dot = document.getElementById(`dot-${i}`);
                
                if (slide && dot) {
                    if (i === currentSlide) {
                        slide.classList.remove('slide-inactive');
                        slide.classList.add('slide-active');
                        dot.classList.add('text-primary', 'scale-110');
                        dot.classList.remove('text-gray-500');
                        if (!dot.querySelector('span')) {
                            const underline = document.createElement('span');
                            underline.className = 'absolute left-0 right-0 -bottom-1 h-[1px] bg-primary';
                            dot.appendChild(underline);
                        }
                    } else {
                        slide.classList.remove('slide-active');
                        slide.classList.add('slide-inactive');
                        dot.classList.remove('text-primary', 'scale-110');
                        dot.classList.add('text-gray-500');
                        const underline = dot.querySelector('span');
                        if (underline) {
                            dot.removeChild(underline);
                        }
                    }
                }
            }
        }

        function nextSlide() { currentSlide = (currentSlide + 1) % totalSlides; updateSlideClasses(); resetInterval(); }
        function prevSlide() { currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; updateSlideClasses(); resetInterval(); }
        function goToSlide(slideIndex) { currentSlide = slideIndex; updateSlideClasses(); resetInterval(); }
        function startInterval() { slideInterval = setInterval(nextSlide, 8000); }
        function resetInterval() { clearInterval(slideInterval); startInterval(); }

        document.addEventListener('DOMContentLoaded', () => {
            updateSlideClasses();
            startInterval();
        });
    </script>
    @endpush

</x-layouts.app>
