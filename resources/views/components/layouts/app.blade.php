<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="bg-[#040810]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order-QR - Hệ thống gọi món thông minh</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Trải nghiệm gọi món nhanh chóng và tiện lợi tại bàn với Order-QR. Khám phá thực đơn đa dạng và tận hưởng không gian ẩm thực tuyệt vời.">
    
    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="Order-QR - Hệ thống gọi món thông minh">
    <meta property="og:description" content="Trải nghiệm gọi món nhanh chóng và tiện lợi tại bàn với Order-QR. Khám phá thực đơn đa dạng và tận hưởng không gian ẩm thực tuyệt vời.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@200;300;400;500;600;700&family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Jost:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #040810;
        }
        .font-script-tagline {
            font-family: 'Great Vibes', cursive;
        }
        /* Thin lines like Laurent theme layout */
        .grid-line-vertical {
            width: 1px;
            height: 100%;
            background-color: rgba(0, 119, 187, 0.2);
        }
        .grid-line-horizontal {
            height: 1px;
            width: 100%;
            background-color: rgba(0, 119, 187, 0.2);
        }
        /* Outlined luxury title */
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
    @stack('styles')
</head>
<body class="text-white antialiased selection:bg-primary selection:text-white overflow-x-hidden">

    <!-- 2 Vertical Background Grid Lines -->
    <div class="fixed inset-0 pointer-events-none z-[0] flex justify-center items-center w-full px-6 md:px-[60px]">
        <div class="w-full h-full border-x border-primary/30"></div>
    </div>

    <!-- Header Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 px-6 md:px-[60px] h-[110px] flex items-center bg-[#040810]/60 backdrop-blur-md border-b border-primary/20 transition-all duration-300">
        <div class="w-full flex items-center justify-between mx-auto">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center group pointer-events-auto pr-4 pl-4">
                <svg class="w-[45px] h-[45px] text-primary transition-transform duration-500 group-hover:scale-105" viewBox="0 0 50 50" fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="square" xmlns="http://www.w3.org/2000/svg">
                    <path d="M14 12 L14 28 L26 28" />
                    <path d="M20 18 L20 34 L32 34" />
                    <path d="M26 24 L26 40 L38 40" />
                </svg>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-[45px] font-light">
                <a href="{{ url('/') }}" class="text-[11px] uppercase tracking-[0.45em] {{ request()->is('/') ? 'text-primary border-b border-primary/60 pb-1' : 'text-gray-400 hover:text-white' }} pointer-events-auto transition-colors duration-300">Trang Chủ</a>
                <a href="{{ url('/restaurant') }}" class="text-[11px] uppercase tracking-[0.45em] {{ request()->is('restaurant') ? 'text-primary border-b border-primary/60 pb-1' : 'text-gray-400 hover:text-white' }} pointer-events-auto transition-colors duration-300">Nhà Hàng</a>
                <a href="{{ url('/menu') }}" class="text-[11px] uppercase tracking-[0.45em] {{ request()->is('menu') ? 'text-primary border-b border-primary/60 pb-1' : 'text-gray-400 hover:text-white' }} pointer-events-auto transition-colors duration-300">Thực Đơn</a>
                <a href="{{ url('/booking') }}" class="text-[11px] uppercase tracking-[0.45em] {{ request()->is('booking') ? 'text-primary border-b border-primary/60 pb-1' : 'text-gray-400 hover:text-white' }} pointer-events-auto transition-colors duration-300">Đặt Bàn</a>
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
        {{ $slot }}
    </main>

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

    <!-- Floating Widgets (Right Side) -->
    <div class="fixed right-0 top-[40%] transform -translate-y-1/2 z-[60] hidden md:flex flex-col gap-3 pointer-events-auto shadow-2xl">
        <button id="related-btn" class="flex flex-col items-center justify-center bg-[#ef2853] hover:bg-[#d11a43] text-white w-[60px] h-[60px] md:w-[75px] md:h-[75px] transition-all duration-300 focus:outline-none">
            <svg class="w-6 h-6 mb-1 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"></path>
            </svg>
            <span class="text-[9px] tracking-widest font-bold uppercase mt-1">Liên quan</span>
        </button>
        <a href="{{ route('menu') }}" class="flex flex-col items-center justify-center bg-white hover:bg-gray-100 text-gray-800 w-[60px] h-[60px] md:w-[75px] md:h-[75px] transition-all duration-300">
            <svg class="w-6 h-6 mb-1 text-[#0077bb]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"></path>
            </svg>
            <span class="text-[9px] tracking-widest font-bold uppercase mt-1">Thực Đơn</span>
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
            <p class="text-[13px] text-gray-300 font-light tracking-widest">Nhà Hàng Ẩm Thực Việt,</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">71 Lê Lợi, Quận 1</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">0909-123-456,</p>
            <p class="text-[13px] text-gray-300 font-light tracking-widest">Mở cửa: 09:00 sáng - 10:00 tối</p>
        </div>

        <div class="flex flex-col gap-4 items-start">
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Facebook</a>
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Instagram</a>
            <a href="#" class="text-[12px] text-gray-300 hover:text-primary tracking-[0.2em] transition-colors border-b border-primary/40 pb-1">Trip Advisor</a>
        </div>
    </div>

    <!-- Related Sidebar -->
    <div id="related-sidebar" class="fixed top-0 right-0 h-full w-[85vw] sm:w-[350px] bg-[#040810] border-l border-primary/20 z-[110] transform translate-x-full transition-transform duration-500 shadow-2xl flex flex-col pointer-events-auto">
        <button id="close-related-btn" class="absolute top-[28px] right-[24px] text-gray-400 hover:text-primary transition-colors focus:outline-none z-[120]" aria-label="Close">
            <svg class="w-8 h-8 font-thin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="p-8 pb-4 border-b border-primary/20 mt-4">
            <h3 class="font-script-tagline text-[40px] text-primary mb-1 leading-none">Khám phá</h3>
            <h4 class="text-[11px] font-semibold tracking-[0.3em] text-white uppercase mt-2">MÓN PHỔ BIẾN</h4>
        </div>
        
        <div class="flex-1 overflow-y-auto px-8 py-6 flex flex-col gap-8 pb-20 custom-scrollbar">
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/pho-bo.png" alt="Phở Bò" class="w-16 h-16 object-cover bg-gray-800 border border-primary/20 filter brightness-75 group-hover:brightness-100 transition-all">
                <div class="flex-grow">
                    <h5 class="text-[12px] font-semibold text-white tracking-[0.1em] uppercase group-hover:text-primary transition-colors">Phở Bò Đặc Biệt</h5>
                    <span class="text-primary text-[12px] mt-1 block font-medium">$65</span>
                </div>
            </div>
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/banh-mi.png" alt="Bánh Mì" class="w-16 h-16 object-cover bg-gray-800 border border-primary/20 filter brightness-75 group-hover:brightness-100 transition-all">
                <div class="flex flex-col flex-1">
                    <h5 class="text-gray-200 font-medium text-[14px] group-hover:text-primary transition-colors tracking-wide">Bánh Mì Thịt Nướng</h5>
                    <span class="text-primary font-semibold text-[13px] mt-1">$25</span>
                </div>
            </div>
            <div class="group cursor-pointer flex gap-4 items-center">
                <img src="/images/tra-tac.jpg" alt="Trà Tắc" class="w-16 h-16 object-cover bg-gray-800 border border-primary/20 filter brightness-75 group-hover:brightness-100 transition-all">
                <div class="flex flex-col flex-1">
                    <h5 class="text-gray-200 font-medium text-[14px] group-hover:text-primary transition-colors tracking-wide">Trà Tắc</h5>
                    <span class="text-primary font-semibold text-[13px] mt-1">$15</span>
                </div>
            </div>
        </div>
        
        <a href="{{ route('menu') }}" class="absolute bottom-0 left-0 right-0 bg-[#040810] border-t border-primary/20 text-white py-5 text-center text-[11px] font-medium tracking-[0.3em] uppercase hover:bg-primary transition-colors backdrop-blur-md">
            XEM TOÀN BỘ THỰC ĐƠN <span class="ml-1">↗</span>
        </a>
    </div>

    <!-- Overlays -->
    <div id="related-overlay" class="fixed inset-0 bg-black/40 z-[105] hidden pointer-events-auto opacity-0 transition-opacity duration-500 backdrop-blur-sm"></div>
    <div id="menu-overlay" class="fixed inset-0 bg-black/40 z-[90] hidden pointer-events-auto opacity-0 transition-opacity duration-500 backdrop-blur-sm"></div>

    <!-- Script Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
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

            if(menuBtn) menuBtn.addEventListener('click', openMenu);
            if(closeMenuBtn) closeMenuBtn.addEventListener('click', closeMenu);
            if(menuOverlay) menuOverlay.addEventListener('click', closeMenu);

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

            if(relatedBtn) relatedBtn.addEventListener('click', openRelated);
            if(closeRelatedBtn) closeRelatedBtn.addEventListener('click', closeRelated);
            if(relatedOverlay) relatedOverlay.addEventListener('click', closeRelated);
        });
    </script>
    @stack('scripts')
</body>
</html>
