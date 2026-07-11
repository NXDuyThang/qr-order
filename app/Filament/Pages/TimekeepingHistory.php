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
        return in_array(auth()->user()->role, ['chef', 'waiter']);
    }

    protected function getViewData(): array
    {
        $records = Timekeeping::where('user_id', auth()->id())
            ->whereMonth('date', $this->month)
            ->whereYear('date', $this->year)
            ->orderBy('date', 'desc')
            ->get();
            
        return compact('records');
    }
}
