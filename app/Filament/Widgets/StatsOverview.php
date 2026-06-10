<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Order;
use App\Models\Table;
use App\Models\Booking;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Tổng doanh thu', number_format(Order::where('payment_status', 'paid')->sum('total_price') * 1000, 0, ',', '.') . ' VNĐ')
                ->description(new \Illuminate\Support\HtmlString('<span class="whitespace-nowrap">Tổng số tiền đã thanh toán</span>'))
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            
            Stat::make('Đơn gọi món mới', Order::where('status', 'new')->count())
                ->description(new \Illuminate\Support\HtmlString('<span class="whitespace-nowrap">Đơn gọi món chưa được xử lý</span>'))
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('danger'),
            
            Stat::make('Lịch đặt bàn chờ duyệt', Booking::where('status', 'pending')->count())
                ->description(new \Illuminate\Support\HtmlString('<span class="whitespace-nowrap">Khách vừa đặt bàn mới</span>'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Bàn đang phục vụ', Table::where('status', 'occupied')->count())
                ->description(new \Illuminate\Support\HtmlString('<span class="whitespace-nowrap">Số bàn hiện đang có khách</span>'))
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
