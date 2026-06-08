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
                ->description('Tổng số tiền đã thanh toán')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
            
            Stat::make('Đơn gọi món mới', Order::where('status', 'new')->count())
                ->description('Đơn gọi món chưa được xử lý')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('danger'),
            
            Stat::make('Lịch đặt bàn chờ duyệt', Booking::where('status', 'pending')->count())
                ->description('Khách vừa đặt bàn mới')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),

            Stat::make('Bàn đang phục vụ', Table::where('status', 'occupied')->count())
                ->description('Số bàn hiện đang có khách')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
        ];
    }
}
