<x-filament-panels::page>
    @if(session('success'))
        <div class="mb-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Xin chào, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600 dark:text-gray-400">Chúc bạn một ngày làm việc hiệu quả. (Vai trò: {{ auth()->user()->role === 'chef' ? 'Đầu Bếp' : 'Phục Vụ' }})</p>
    </div>

    @include('employee.partials.timekeeping_leave')
</x-filament-panels::page>
