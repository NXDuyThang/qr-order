<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Timekeeping;
use Carbon\Carbon;

class TimekeepingHistory extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Cá nhân';
    protected static ?string $title = 'Lịch Sử Điểm Danh';
    protected static ?int $navigationSort = 4;

    protected static string $view = 'filament.pages.timekeeping-history';

    public $month;
    public $year;

    public function mount()
    {
        $this->month = request()->input('month', now()->month);
        $this->year = request()->input('year', now()->year);
    }

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['chef', 'waiter', 'admin', 'manager']);
    }

    protected function getViewData(): array
    {
        $records = Timekeeping::where('user_id', auth()->id())
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->get();
            
        $totalHours = 0;
        $calendarData = [];

        foreach ($records as $record) {
            $day = Carbon::parse($record->date)->day;
            $calendarData[$day] = $record->status;

            if ($record->check_in && $record->check_out) {
                $hours = Carbon::parse($record->check_in)->diffInMinutes($record->check_out) / 60;
                if (Carbon::parse($record->check_out)->format('H:i') >= '13:00') {
                    $hours -= 1;
                }
                $hours = max(0, round($hours, 2));
                $totalHours += $hours;
            }
        }
        
        $date = Carbon::create($this->year, $this->month, 1);
        $daysInMonth = $date->daysInMonth;
        $startDayOfWeek = $date->dayOfWeekIso;

        return compact('records', 'totalHours', 'calendarData', 'daysInMonth', 'startDayOfWeek');
    }
}
