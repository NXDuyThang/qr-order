<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PayrollReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Bảng Tính Lương';
    protected static ?string $title = 'Bảng Tính Lương Nhân Viên';
    protected static ?string $navigationGroup = 'Quản lý nhân sự';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.payroll-report';

    public $month;
    public $year;

    public function mount()
    {
        $this->month = now()->month;
        $this->year = now()->year;
    }

    public function getPayrollData()
    {
        $users = \App\Models\User::whereIn('role', ['manager', 'chef', 'waiter'])->get();
        
        $data = [];
        foreach ($users as $user) {
            $baseSalary = $user->base_salary ?? 0;
            
            // Lấy tổng số ngày nghỉ đã duyệt trong tháng đang chọn
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
                // Đơn giản hóa: Nếu nằm trong tháng thì tính số ngày
                // (Thực tế phức tạp hơn nếu phép bắc cầu qua 2 tháng, ở đây giả sử tính gọn theo start_date tháng đó)
                if (\Carbon\Carbon::parse($req->start_date)->month == $this->month) {
                    $leaveDays += \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
                }
            }
            
            $dailyWage = $baseSalary / 26;
            $deduction = $dailyWage * $leaveDays;
            $netSalary = max(0, $baseSalary - $deduction);
            
            $data[] = [
                'user' => $user,
                'base_salary' => $baseSalary,
                'leave_days' => $leaveDays,
                'deduction' => $deduction,
                'net_salary' => $netSalary,
            ];
        }
        
        return $data;
    }
}
