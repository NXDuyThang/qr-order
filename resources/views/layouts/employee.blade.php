<!DOCTYPE html>
<html lang="en" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light'))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employee Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="employee-layout bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased min-h-screen transition-colors duration-300" x-data="{ sidebarOpen: true }">
    
    <!-- Navbar -->
    <nav class="bg-white dark:bg-gray-800 shadow-sm p-4 flex justify-between items-center sticky top-0 z-50 transition-colors duration-300">
        <div class="flex items-center gap-4">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                <svg x-show="sidebarOpen" class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                <svg x-show="!sidebarOpen" x-cloak class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
            <div class="font-bold text-xl text-primary-600 dark:text-primary-400">
                {{ config('app.name') }} - @yield('role_name')
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <button @click="darkMode = !darkMode" class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <svg x-show="!darkMode" class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                <svg x-show="darkMode" x-cloak class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </button>
            <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition">Đăng xuất</button>
            </form>
        </div>
    </nav>

    <!-- Header Section (Optional) -->
    <div class="max-w-7xl mx-auto mt-6 px-4">
        @yield('header')
    </div>

    <!-- Layout Container -->
    <div class="max-w-7xl mx-auto mt-4 px-4 pb-8 flex flex-col md:flex-row gap-6 items-stretch">
        
        <!-- Sidebar / Taskbar (Left) -->
        <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full opacity-0 w-0" x-transition:enter-end="translate-x-0 opacity-100 w-full md:w-80" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0 opacity-100 w-full md:w-80" x-transition:leave-end="-translate-x-full opacity-0 w-0" class="flex-shrink-0 origin-left">
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-5 sticky top-24 transition-colors duration-300 w-full md:w-80 h-full flex flex-col">
                <h3 class="font-bold text-gray-800 dark:text-gray-100 text-lg border-b dark:border-gray-700 pb-2 mb-4">Thanh Công Cụ</h3>
                
                <ul class="space-y-2 flex-grow">
                    <li>
                        <a href="{{ auth()->user()->role === 'chef' ? route('chef.dashboard') : route('waiter.dashboard') }}" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition">
                            🏠 Trang chủ Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.leave_history') }}" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition">
                            📅 Lịch sử Nghỉ Phép
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.timekeeping_history') }}" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition">
                            🕒 Lịch sử Điểm Danh
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.my_salary') }}" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 hover:text-primary-600 dark:hover:text-primary-400 transition">
                            💰 Xem Tiền Lương
                        </a>
                    </li>
                </ul>

                @if(isset($remainingLeave))
                <div class="mt-6 border-t dark:border-gray-700 pt-4">
                    <h4 class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mb-2">Thông tin nghỉ phép năm</h4>
                    <div class="bg-blue-50 dark:bg-gray-700 rounded p-3 text-sm">
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-600 dark:text-gray-300">Tổng quỹ phép:</span>
                            <span class="font-bold dark:text-gray-100">12 ngày</span>
                        </div>
                        <div class="flex justify-between mb-1">
                            <span class="text-gray-600 dark:text-gray-300">Đã dùng:</span>
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $usedDays ?? (12 - $remainingLeave) }} ngày</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-300">Còn lại:</span>
                            <span class="font-bold text-green-600 dark:text-green-400">{{ $remainingLeave }} ngày</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </aside>

        <!-- Main Content (Right) -->
        <main class="flex-1 w-full min-w-0 transition-all duration-300 flex flex-col">
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
