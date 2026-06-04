<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#040810]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laurent – Elegant Restaurant Theme</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Welcome to Laurent - Elegant Restaurant Theme. Discover our innovative drinks, gourmet culinary arts, and experience an unforgettable fine dining atmosphere.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@200;300;400;600;700&family=Mrs+Saint+Delafield&family=Miniver&family=Open+Sans+Condensed:wght@300;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Josefin Sans', sans-serif;
            background-color: #040810;
        }
        .font-script-tagline {
            font-family: 'Mrs Saint Delafield', cursive;
        }
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
        /* Thin lines like Laurent theme layout */
        .grid-line-vertical {
            width: 1px;
            height: 100%;
            background-color: rgba(0, 119, 187, 0.2); /* #0077bb */
        }
        .grid-line-horizontal {
            height: 1px;
            width: 100%;
            background-color: rgba(0, 119, 187, 0.2); /* #0077bb */
        }
        /* Outlined luxury title like in screenshot */
        .text-outline-luxury {
            color: transparent;
            -webkit-text-stroke: 1.2px rgba(255, 255, 255, 0.95);
            letter-spacing: 0.12em;
        }
        .text-outline-primary {
            color: transparent;
            -webkit-text-stroke: 1.2px #0077bb;
            letter-spacing: 0.12em;
        }
        /* Slow rotation zoom effect on background images */
        .bg-zoom {
            transition: transform 12s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .slide-active .bg-zoom {
            transform: scale(1.06) rotate(0.5deg);
        }
        .section-title-deco {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            letter-spacing: 0.3em;
            font-size: 24px;
            color: #0077bb;
        }
        .section-title-deco::before, .section-title-deco::after {
            content: "";
            width: 30px;
            height: 10px;
            background-image: url('data:image/svg+xml;utf8,<svg width="30" height="10" viewBox="0 0 30 10" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0 5L5 0L10 5L5 10L0 5Z" fill="%230077bb"/><path d="M10 5L15 0L20 5L15 10L10 5Z" fill="%230077bb"/><path d="M20 5L25 0L30 5L25 10L20 5Z" fill="%230077bb"/></svg>');
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.7;
        }
        .read-more-btn {
            border-bottom: 1px solid rgba(0, 119, 187, 0.5);
            padding-bottom: 4px;
            transition: all 0.3s ease;
        }
        .read-more-btn:hover {
            border-bottom-color: #0077bb;
            color: #0077bb;
        }
    </style>
</head>
<body class="text-white antialiased selection:bg-primary selection:text-white overflow-x-hidden">

    <!-- 2 Vertical Background Grid Lines -->
    <div class="fixed inset-0 pointer-events-none z-[0] flex justify-center items-center w-full px-6 md:px-[60px]">
        <div class="w-full h-full border-x border-primary/30"></div>
    </div>

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 px-6 md:px-[60px] h-[110px] flex items-center bg-[#040810]/60 backdrop-blur-md border-b border-primary/20">
        <div class="w-full flex items-center justify-between mx-auto">
            <!-- Brand Logo -->
            <a href="#" class="flex items-center group pointer-events-auto pr-4 pl-4">
                <svg class="w-[45px] h-[45px] text-primary transition-transform duration-500 group-hover:scale-105" viewBox="0 0 50 50" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="square" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 12 L14 28 L26 28" />
                    <path d="M20 18 L20 34 L32 34" />
                    <path d="M26 24 L26 40 L38 40" />
                </svg>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-[45px] font-light">
                <a href="{{ url('/') }}" class="text-[11px] uppercase tracking-[0.45em] text-primary border-b border-primary/60 pb-1 pointer-events-auto transition-colors duration-300">Trang Chủ</a>
                <a href="{{ url('/restaurant') }}" class="text-[11px] uppercase tracking-[0.45em] text-gray-400 hover:text-white pointer-events-auto transition-colors duration-300">Nhà Hàng</a>
                <a href="{{ url('/vietnamese-cuisine') }}" class="text-[11px] uppercase tracking-[0.45em] text-gray-400 hover:text-white pointer-events-auto transition-colors duration-300">Ẩm Thực Việt</a>
                <a href="{{ url('/booking') }}" class="text-[11px] uppercase tracking-[0.45em] text-gray-400 hover:text-white pointer-events-auto transition-colors duration-300">Đặt Bàn</a>
                <a href="{{ url('/contact') }}" class="text-[11px] uppercase tracking-[0.45em] text-gray-400 hover:text-white pointer-events-auto transition-colors duration-300">Liên Hệ</a>
            </nav>

            <!-- Hamburger menu icon -->
            <button id="menu-btn" class="flex items-center justify-center group pointer-events-auto w-[45px] h-[110px] pr-4 focus:outline-none" aria-label="Open Menu">
                <svg class="w-[32px] h-[32px] text-white group-hover:text-primary transition-colors duration-300" viewBox="0 0 40 40" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="square" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 14 L24 14" />
                    <path d="M8 19 L24 19" />
                    <path d="M16 24 L32 24" />
                    <path d="M16 29 L32 29" />
                </svg>
            </button>
        </div>
    </header>

    <main class="relative w-full z-10 flex flex-col">

        <!-- Hero Slider Section -->
        <section class="relative w-full h-screen overflow-hidden">
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

        <!-- Footer Info Section -->
        <footer class="relative w-full py-24 bg-transparent z-20 border-t border-primary/20 flex flex-col items-center justify-center text-center">
            <div class="mb-8">
                <!-- Logo marks -->
                <svg class="w-10 h-10 text-primary mx-auto" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M30 20 L30 80 L70 80" />
                    <path d="M40 30 L40 70 L60 70" />
                    <path d="M50 40 L50 60 L60 60" />
                </svg>
            </div>
            <p class="text-[14px] text-gray-300 font-light mb-2 tracking-wider">
                Nhà Hàng Ẩm Thực Việt, 71 Lê Lợi, Quận 1
            </p>
            <p class="text-[14px] text-gray-300 font-light mb-2 tracking-wider">
                Thành phố Hồ Chí Minh, 0909-123-456, contact@laurent.com
            </p>
            <p class="text-[14px] text-gray-300 font-light mb-12 tracking-wider">
                Mở cửa: 09:00 sáng - 10:00 tối
            </p>
            
            <div class="flex flex-col gap-4 items-center">
                <a href="#" class="text-[12px] text-gray-400 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1 inline-block">Facebook</a>
                <a href="#" class="text-[12px] text-gray-400 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1 inline-block">Instagram</a>
                <a href="#" class="text-[12px] text-gray-400 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1 inline-block">Trip Advisor</a>
            </div>
        </footer>

    </main>

    <!-- Floating Widgets (Right Side) -->
    <div class="fixed right-0 top-[40%] transform -translate-y-1/2 z-[60] hidden md:flex flex-col gap-3 pointer-events-auto shadow-2xl">
        <!-- Widget 1: RELATED -->
        <button id="related-btn" class="flex flex-col items-center justify-center bg-[#ef2853] hover:bg-[#d11a43] text-white w-[60px] h-[60px] md:w-[75px] md:h-[75px] transition-all duration-300 focus:outline-none">
            <svg class="w-6 h-6 mb-1 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
            </svg>
            <span class="text-[9px] tracking-widest font-bold uppercase mt-1">Liên quan</span>
        </button>
        
        <!-- Widget 2: BUY NOW -->
        <a href="#" class="flex flex-col items-center justify-center bg-white hover:bg-gray-100 text-gray-800 w-[60px] h-[60px] md:w-[75px] md:h-[75px] transition-all duration-300">
            <svg class="w-6 h-6 mb-1 text-[#0077bb]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
            </svg>
            <span class="text-[9px] tracking-widest font-bold uppercase mt-1">Mua ngay</span>
        </a>
    </div>

    <!-- Related Sidebar (Redesigned) -->
    <div id="related-sidebar" class="fixed top-0 right-0 h-full w-[85vw] sm:w-[350px] bg-[#040810] border-l border-primary/20 z-[110] transform translate-x-full transition-transform duration-500 shadow-2xl flex flex-col pointer-events-auto">
        <!-- Close Button -->
        <button id="close-related-btn" class="absolute top-[28px] right-[24px] text-gray-400 hover:text-primary transition-colors focus:outline-none z-[120]" aria-label="Close">
            <svg class="w-8 h-8 font-thin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <!-- Header -->
        <div class="p-8 pb-4 border-b border-primary/20 mt-4">
            <h3 class="font-script-tagline text-[40px] text-primary mb-1 leading-none">Khám phá</h3>
            <h4 class="text-[11px] font-semibold tracking-[0.3em] text-white uppercase mt-2">MÓN PHỔ BIẾN</h4>
        </div>
        
        <!-- List -->
        <div class="flex-1 overflow-y-auto px-8 py-6 flex flex-col gap-8 pb-20 custom-scrollbar">
            <!-- Item 1 -->
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/pho-bo.png" alt="Phở Bò" class="w-16 h-16 object-cover bg-gray-800">
                <div class="flex-grow">
                    <h5 class="text-[12px] font-semibold text-white tracking-[0.1em] uppercase group-hover:text-primary transition-colors">Phở Bò Đặc Biệt</h5>
                    <span class="text-primary text-[12px] mt-1 block font-medium">$65</span>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/banh-mi.png" alt="Bánh Mì" class="w-16 h-16 object-cover bg-gray-800 border border-primary/20 filter brightness-75 group-hover:brightness-100 transition-all">
                <div class="flex flex-col flex-1">
                    <h5 class="text-gray-200 font-medium text-[14px] group-hover:text-primary transition-colors tracking-wide">Bánh Mì Thịt Nướng</h5>
                    <span class="text-gray-400 text-[10px] uppercase tracking-[0.1em] mb-1">Món Ăn Nhanh</span>
                    <span class="text-primary font-semibold text-[13px]">$25.00</span>
                </div>
            </div>
            <!-- Item 3 -->
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/tra-tac.jpg" alt="Trà Tắc" class="w-16 h-16 object-cover bg-gray-800 border border-primary/20 filter brightness-75 group-hover:brightness-100 transition-all">
                <div class="flex flex-col flex-1">
                    <h5 class="text-gray-200 font-medium text-[14px] group-hover:text-primary transition-colors tracking-wide">Trà Tắc</h5>
                    <span class="text-gray-400 text-[10px] uppercase tracking-[0.1em] mb-1">Đồ Uống</span>
                    <span class="text-primary font-semibold text-[13px]">$15.00</span>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <a href="#" class="absolute bottom-0 left-0 right-0 bg-[#040810] border-t border-primary/20 text-white py-5 text-center text-[11px] font-medium tracking-[0.3em] uppercase hover:bg-primary transition-colors backdrop-blur-md">
            XEM TOÀN BỘ THỰC ĐƠN <span class="ml-1">↗</span>
        </a>
    </div>

    <!-- Right Info Sidebar (Hamburger Menu) -->
    <div id="side-menu" class="fixed top-0 right-0 h-full w-[85vw] sm:w-[350px] bg-[#040810]/95 backdrop-blur-md z-[100] transform translate-x-full transition-transform duration-500 px-10 py-16 flex flex-col pointer-events-auto border-l border-primary/20">
        <button id="close-menu-btn" class="absolute top-[32px] right-[24px] text-gray-400 hover:text-primary transition-colors focus:outline-none" aria-label="Close Menu">
            <svg class="w-8 h-8 font-thin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="mt-12 mb-8">
            <svg class="w-10 h-10 text-primary" viewBox="0 0 100 100" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M30 20 L30 80 L70 80" />
                <path d="M40 30 L40 70 L60 70" />
                <path d="M50 40 L50 60 L60 60" />
            </svg>
        </div>

        <div class="flex flex-col gap-3 mb-12">
            <p class="text-[13px] text-gray-300 font-light tracking-widest">Laurent Fine dining,</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">107 Duncan Avenue, New York</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">914-309-7030,</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">Open: 09:00 am - 01:00 pm</p>
        </div>

        <div class="flex flex-col gap-4 items-start">
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Facebook</a>
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Instagram</a>
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Trip Advisor</a>
        </div>
    </div>

    <!-- Overlays -->
    <div id="related-overlay" class="fixed inset-0 bg-black/40 z-[105] hidden pointer-events-auto opacity-0 transition-opacity duration-500 backdrop-blur-sm"></div>
    <div id="menu-overlay" class="fixed inset-0 bg-black/40 z-[90] hidden pointer-events-auto opacity-0 transition-opacity duration-500 backdrop-blur-sm"></div>

    <!-- Script Logic -->
    <script>
        // Slider Logic
        let currentSlide = 0;
        const totalSlides = 2;
        let slideInterval;

        function updateSlideClasses() {
            for (let i = 0; i < totalSlides; i++) {
                const slide = document.getElementById(`slide-${i}`);
                const dot = document.getElementById(`dot-${i}`);
                
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

        function nextSlide() { currentSlide = (currentSlide + 1) % totalSlides; updateSlideClasses(); resetInterval(); }
        function prevSlide() { currentSlide = (currentSlide - 1 + totalSlides) % totalSlides; updateSlideClasses(); resetInterval(); }
        function goToSlide(slideIndex) { currentSlide = slideIndex; updateSlideClasses(); resetInterval(); }
        function startInterval() { slideInterval = setInterval(nextSlide, 8000); }
        function resetInterval() { clearInterval(slideInterval); startInterval(); }

        document.addEventListener('DOMContentLoaded', () => {
            updateSlideClasses();
            startInterval();

            // Menu Sidebar Toggle
            const menuBtn = document.getElementById('menu-btn');
            const closeMenuBtn = document.getElementById('close-menu-btn');
            const sideMenu = document.getElementById('side-menu');
            const menuOverlay = document.getElementById('menu-overlay');

            function openMenu() {
                sideMenu.classList.remove('translate-x-full');
                menuOverlay.classList.remove('hidden');
                setTimeout(() => menuOverlay.classList.remove('opacity-0'), 10);
            }
            function closeMenu() {
                sideMenu.classList.add('translate-x-full');
                menuOverlay.classList.add('opacity-0');
                setTimeout(() => menuOverlay.classList.add('hidden'), 500);
            }

            menuBtn.addEventListener('click', openMenu);
            closeMenuBtn.addEventListener('click', closeMenu);
            menuOverlay.addEventListener('click', closeMenu);

            // Related Sidebar Toggle
            const relatedBtn = document.getElementById('related-btn');
            const relatedSidebar = document.getElementById('related-sidebar');
            const relatedOverlay = document.getElementById('related-overlay');
            const closeRelatedBtn = document.getElementById('close-related-btn');

            function openRelated() {
                relatedSidebar.classList.remove('translate-x-full');
                relatedOverlay.classList.remove('hidden');
                setTimeout(() => relatedOverlay.classList.remove('opacity-0'), 10);
            }
            function closeRelated() {
                relatedSidebar.classList.add('translate-x-full');
                relatedOverlay.classList.add('opacity-0');
                setTimeout(() => relatedOverlay.classList.add('hidden'), 500);
            }

            relatedBtn.addEventListener('click', openRelated);
            if (closeRelatedBtn) closeRelatedBtn.addEventListener('click', closeRelated);
            relatedOverlay.addEventListener('click', closeRelated);
        });
    </script>
</body>
</html>
