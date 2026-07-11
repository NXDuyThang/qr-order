<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TimekeepingDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationGroup = 'Cá nhân';
    protected static ?string $title = 'Điểm danh cá nhân';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin || auth()->user()->role === 'manager';
    }

    protected static string $view = 'filament.pages.timekeeping-dashboard';

    public $todayRecord;

    public function mount()
    {
        $this->todayRecord = \App\Models\Timekeeping::where('user_id', auth()->id())
            ->whereDate('date', today())
            ->first();

        // Tự động cập nhật không đi làm nếu quá 9h
        if (!$this->todayRecord && now()->format('H:i') > '09:00') {
            $this->todayRecord = \App\Models\Timekeeping::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'status' => 'absent',
            ]);
        }
    }

    public function checkIn()
    {
        if (!$this->todayRecord) {
            $now = now();
            $time = $now->format('H:i');

            if ($time > '09:00') {
                \Filament\Notifications\Notification::make()
                    ->title('Đã quá giờ điểm danh. Bạn được ghi nhận là không đi làm hôm nay.')
                    ->danger()
                    ->send();
                return;
            }

            if ($time <= '08:00') {
                $status = 'present';
                $checkInTime = $now->copy()->setTime(7, 40, 0);
            } else {
                $status = 'late';
                $checkInTime = $now;
            }
            
            $this->todayRecord = \App\Models\Timekeeping::create([
                'user_id' => auth()->id(),
                'date' => today(),
                'check_in' => $checkInTime,
                'status' => $status,
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Điểm danh thành công')
                ->success()
                ->send();
        }
    }

    public function checkOut()
    {
        if ($this->todayRecord && !$this->todayRecord->check_out) {
            $now = now();
            
            if ($this->todayRecord->check_in) {
                $checkInTime = \Carbon\Carbon::parse($this->todayRecord->check_in);
                if ($checkInTime->diffInHours($now) < 6) {
                    \Filament\Notifications\Notification::make()
                        ->title('Bạn phải làm việc tối thiểu 6 tiếng mới được phép check-out.')
                        ->danger()
                        ->send();
                    return;
                }
            }

            $this->todayRecord->update([
                'check_out' => $now,
            ]);
            
            \Filament\Notifications\Notification::make()
                ->title('Check-out thành công')
                ->success()
                ->send();
        }
    }
}
