<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\LeaveRequest;
use Carbon\Carbon;

class LeaveHistory extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Cá nhân';
    protected static ?string $title = 'Lịch Sử Nghỉ Phép';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.leave-history';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['chef', 'waiter']);
    }

    protected function getViewData(): array
    {
        $currentYear = now()->year;
        
        $activeRequests = LeaveRequest::where('user_id', auth()->id())
            ->whereYear('start_date', $currentYear)
            ->whereIn('status', ['approved', 'pending'])
            ->get();
            
        $usedDays = 0;
        foreach($activeRequests as $req) {
            $usedDays += Carbon::parse($req->start_date)->diffInDays(Carbon::parse($req->end_date)) + 1;
        }
        $remainingLeave = max(0, 12 - $usedDays);

        $leaveRequests = LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return compact('leaveRequests', 'remainingLeave', 'usedDays');
    }
}
