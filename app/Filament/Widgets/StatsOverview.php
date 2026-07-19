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
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->chart([7, 10, 13, 15, 12, 16, 20])
                ->color('success')
                ->icon('heroicon-o-banknotes'),
            
            Stat::make('Đơn gọi món mới', Order::where('status', 'new')->count())
                ->description('Đơn gọi món chưa được xử lý')
                ->descriptionIcon('heroicon-m-fire')
                ->chart([2, 5, 3, 8, 4, 9, Order::where('status', 'new')->count()])
                ->color('danger')
                ->icon('heroicon-o-bell-alert'),
            
            Stat::make('Lịch đặt bàn chờ duyệt', Booking::where('status', 'pending')->count())
                ->description('Khách vừa đặt bàn mới')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([1, 2, 1, 3, 2, 4, Booking::where('status', 'pending')->count()])
                ->color('warning')
                ->icon('heroicon-o-calendar-days'),

            Stat::make('Bàn đang phục vụ', Table::where('status', 'occupied')->count())
                ->description('Số bàn hiện đang có khách')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([10, 12, 14, 15, 13, 16, Table::where('status', 'occupied')->count()])
                ->color('primary')
                ->icon('heroicon-o-users'),
        ];
    }
}
