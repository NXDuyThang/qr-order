@extends('layouts.employee')

@section('title', 'Waiter Dashboard - NKS QR Order')
@section('role_name', 'Phục Vụ')

@section('header')
    <div class="mb-2">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Xin chào, {{ auth()->user()->name }}! 💁‍♂️</h1>
        <p class="text-gray-600 dark:text-gray-400">Chúc bạn một ngày làm việc hiệu quả.</p>
    </div>
@endsection

@section('content')

    <!-- Gọi component dùng chung cho chấm công & nghỉ phép -->
    @include('employee.partials.timekeeping_leave')


@endsection
