<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'QR Order Website') }}</title>
        
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex flex-col min-h-screen">
        
        <!-- Header -->
        <header class="fixed w-full top-0 z-50 border-b border-gray-800 bg-dark/90 backdrop-blur-sm transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ url('/') }}" class="font-serif text-3xl font-bold tracking-widest text-primary">
                            <span class="text-white">L</span>AUR.
                        </a>
                    </div>
                    
                    <!-- Main Menu -->
                    <nav class="hidden md:flex space-x-8">
                        <a href="{{ url('/') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Trang Chủ</a>
                        <a href="{{ url('/restaurant') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Nhà Hàng</a>
                        <a href="{{ url('/menu') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Thực Đơn</a>
                        <a href="{{ url('/booking') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Đặt Bàn</a>
                        <a href="{{ url('/vietnamese-cuisine') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Ẩm Thực Việt</a>
                        <a href="{{ url('/contact') }}" class="text-[11px] font-semibold tracking-[0.2em] uppercase hover:text-primary transition-colors pb-1">Liên Hệ</a>
                    </nav>

                    <!-- Hamburger Icon -->
                    <div class="flex items-center">
                        <button type="button" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-grow pt-20">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="border-t border-gray-800 py-12 text-center text-sm tracking-wider">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </footer>
    </body>
</html>
