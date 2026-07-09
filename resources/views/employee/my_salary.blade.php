@extends('layouts.employee')

@section('title', 'Xem Tiền Lương - NKS QR Order')
@section('role_name', auth()->user()->role === 'chef' ? 'Đầu Bếp' : 'Phục Vụ')

@section('header')
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
            <svg class="w-7 h-7 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Bảng Lương Của Tôi
        </h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Chi tiết lương thực nhận hàng tháng.</p>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        
        <!-- Bộ lọc tháng/năm -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <form method="GET" action="{{ route('employee.my_salary') }}" class="flex items-end gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tháng</label>
                    <select name="month" class="block w-32 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>Tháng {{ $m }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Năm</label>
                    <select name="year" class="block w-32 rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm">
                        @for($y = now()->year - 2; $y <= now()->year; $y++)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Năm {{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="bg-primary-600 hover:bg-primary-500 text-white font-medium py-2 px-4 rounded-lg shadow text-sm transition">
                    Xem lương
                </button>
            </form>
        </div>

        <!-- Chi tiết lương -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">
                    Lương Tháng {{ $month }}/{{ $year }}
                </h3>
                
                <div class="space-y-4">
                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Lương cơ bản:</span>
                        <span class="font-bold text-gray-900 dark:text-gray-100 text-lg">{{ number_format($baseSalary, 0, ',', '.') }} VNĐ</span>
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Số ngày nghỉ phép (đã duyệt):</span>
                        @if($leaveDays > 0)
                            <span class="font-bold text-red-600 dark:text-red-400">{{ $leaveDays }} ngày</span>
                        @else
                            <span class="font-bold text-gray-900 dark:text-gray-100">0 ngày</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <span class="text-gray-600 dark:text-gray-300 font-medium">Tiền trừ do nghỉ phép:</span>
                        <span class="font-bold text-red-600 dark:text-red-400">- {{ number_format($deduction, 0, ',', '.') }} VNĐ</span>
                    </div>

                    <div class="mt-6 border-t dark:border-gray-700 pt-4 flex justify-between items-center bg-green-50 dark:bg-green-900/30 p-4 rounded-lg border border-green-200 dark:border-green-800">
                        <span class="text-green-800 dark:text-green-300 font-bold text-lg uppercase">Lương thực nhận:</span>
                        <span class="font-black text-green-700 dark:text-green-400 text-2xl">{{ number_format($netSalary, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/50 p-4 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 italic">
                * Công thức tính tiền trừ = (Lương cơ bản / 26 ngày) x Số ngày nghỉ phép đã duyệt trong tháng. <br>
                * Chỉ tính các đơn xin nghỉ phép đã được Quản lý duyệt.
            </div>
        </div>
    </div>
@endsection
