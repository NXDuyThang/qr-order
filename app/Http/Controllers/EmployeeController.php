<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function chefDashboard()
    {
        return view('employee.chef_dashboard', $this->getDashboardData());
    }

    public function waiterDashboard()
    {
        return view('employee.waiter_dashboard', $this->getDashboardData());
    }

    private function getDashboardData()
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

    public function checkIn(Request $request)
    {
        $todayRecord = \App\Models\Timekeeping::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        if (!$todayRecord) {
            $now = now();
            $time = $now->format('H:i');

            if ($time > '09:00') {
                return back()->with('error', 'Đã quá giờ điểm danh. Bạn được ghi nhận là không đi làm hôm nay.');
            }

            if ($time <= '08:00') {
                $status = 'present';
                $checkInTime = $now->copy()->setTime(7, 40, 0);
            } else {
                $status = 'late';
                $checkInTime = $now;
            }
            
            \App\Models\Timekeeping::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'check_in' => $checkInTime,
                'status' => $status,
            ]);
            
            return back()->with('success', 'Điểm danh thành công!');
        }
        
        return back()->with('error', 'Bạn đã điểm danh hôm nay rồi.');
    }

    public function checkOut(Request $request)
    {
        $todayRecord = \App\Models\Timekeeping::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        if ($todayRecord && !$todayRecord->check_out) {
            $now = now();
            
            if ($todayRecord->check_in) {
                $checkInTime = \Carbon\Carbon::parse($todayRecord->check_in);
                if ($checkInTime->diffInHours($now) < 6) {
                    return back()->with('error', 'Bạn phải làm việc tối thiểu 6 tiếng mới được phép check-out.');
                }
            }

            $statusUpdate = ['check_out' => $now];
            
            // Nếu về trước 17:00
            if ($now->format('H:i') < '17:00') {
                if ($todayRecord->status === 'present') {
                    $statusUpdate['status'] = 'early_leave';
                } elseif ($todayRecord->status === 'late') {
                    $statusUpdate['status'] = 'late_early';
                }
            }
            
            $todayRecord->update($statusUpdate);
            
            return back()->with('success', 'Check-out thành công!');
        }
        
        return back()->with('error', 'Không thể check-out.');
    }

    public function submitLeaveRequest(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:' . now()->addDays(2)->format('Y-m-d'),
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
        ]);

        $start = \Carbon\Carbon::parse($request->start_date);
        $end = \Carbon\Carbon::parse($request->end_date);
        $newLeaveDays = $start->diffInDays($end) + 1;
        
        $currentYear = now()->year;
        $activeRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->whereYear('start_date', $currentYear)
            ->whereIn('status', ['approved', 'pending'])
            ->get();
            
        $usedDays = 0;
        foreach($activeRequests as $req) {
            $usedDays += \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
        }
        
        if ($usedDays + $newLeaveDays > 12) {
            $remaining = max(0, 12 - $usedDays);
            return back()->with('error', "Bạn chỉ còn $remaining ngày phép trong năm nay (bao gồm cả các đơn đang chờ duyệt). Không thể xin thêm $newLeaveDays ngày.");
        }

        \App\Models\LeaveRequest::create([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Đã gửi yêu cầu nghỉ phép.');
    }

    public function leaveHistory()
    {
        $currentYear = now()->year;
        
        // Calculate remaining
        $activeRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->whereYear('start_date', $currentYear)
            ->whereIn('status', ['approved', 'pending'])
            ->get();
            
        $usedDays = 0;
        foreach($activeRequests as $req) {
            $usedDays += \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
        }
        $remainingLeave = max(0, 12 - $usedDays);

        $leaveRequests = \App\Models\LeaveRequest::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('employee.leave_history', compact('leaveRequests', 'remainingLeave', 'usedDays'));
    }

    public function mySalary(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $user = auth()->user();
        
        $baseSalary = $user->base_salary ?? 0;
        
        $leaveDays = 0;
        $leaveRequests = \App\Models\LeaveRequest::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where(function ($query) use ($month, $year) {
                $query->whereMonth('start_date', $month)
                      ->whereYear('start_date', $year)
                      ->orWhere(function ($q) use ($month, $year) {
                          $q->whereMonth('end_date', $month)
                            ->whereYear('end_date', $year);
                      });
            })
            ->get();
            
        foreach ($leaveRequests as $req) {
            if (\Carbon\Carbon::parse($req->start_date)->month == $month) {
                $leaveDays += \Carbon\Carbon::parse($req->start_date)->diffInDays(\Carbon\Carbon::parse($req->end_date)) + 1;
            }
        }
        
        $dailyWage = $baseSalary / 26;
        $deduction = $dailyWage * $leaveDays;
        $netSalary = max(0, $baseSalary - $deduction);
        
        // Cần truyền biến remainingLeave cho sidebar
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

        return view('employee.my_salary', compact('month', 'year', 'baseSalary', 'leaveDays', 'deduction', 'netSalary', 'remainingLeave', 'usedDays'));
    }

    public function timekeepingHistory(Request $request)
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        
        $records = \App\Models\Timekeeping::where('user_id', auth()->id())
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->get();
            
        // Sidebar data
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
        
        return view('employee.timekeeping_history', compact('records', 'month', 'year', 'remainingLeave', 'usedDays'));
    }
}
