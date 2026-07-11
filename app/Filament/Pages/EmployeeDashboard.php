<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EmployeeDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $navigationGroup = 'Cá nhân';
    protected static ?string $title = 'Trang Chủ';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.employee-dashboard';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['chef', 'waiter', 'admin', 'manager']);
    }

    protected function getViewData(): array
    {
        $todayRecord = \App\Models\Timekeeping::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        // Tự động cập nhật không đi làm nếu quá 9h
        if (!$todayRecord && now()->format('H:i') > '09:00') {
            $todayRecord = \App\Models\Timekeeping::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'status' => 'absent',
            ]);
        }
            
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $currentYear = now()->year;
        $activeRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->whereYear('start_date', $currentYear)
            ->whereIn('status', ['approved', 'pending'])
            ->get();
            
        $usedDays = 0;
        foreach($activeRequests as $req) {
            $usedDays += \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
        }
        $remainingLeave = max(0, 12 - $usedDays);

        return compact('todayRecord', 'leaveRequests', 'remainingLeave');
    }
}
