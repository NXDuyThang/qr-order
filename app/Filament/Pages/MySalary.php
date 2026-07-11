<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Carbon\Carbon;

class MySalary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Cá nhân';
    protected static ?string $title = 'Tiền Lương Của Tôi';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.my-salary';

    public $month;
    public $year;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function getSalaryData()
    {
        $user = auth()->user();
        $baseSalary = $user->base_salary ?? 0;
        
        $leaveDays = 0;
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($query) {
                $query->whereMonth('start_date', $this->month)
                        ->whereYear('start_date', $this->year)
                        ->orWhere(function ($q) {
                            $q->whereMonth('end_date', $this->month)
                            ->whereYear('end_date', $this->year);
                        });
            })
            ->get();
            
        foreach ($leaveRequests as $req) {
            if (Carbon::parse($req->start_date)->month == $this->month) {
                $leaveDays += Carbon::parse($req->start_date)->diffInDays(Carbon::parse($req->end_date)) + 1;
            }
        }
        
        $dailyWage = $baseSalary / 26;
        $deduction = $dailyWage * $leaveDays;
        $netSalary = max(0, $baseSalary - $deduction);
        
        return [
            'base_salary' => $baseSalary,
            'leave_days' => $leaveDays,
            'deduction' => $deduction,
            'net_salary' => $netSalary,
        ];
    }
}
