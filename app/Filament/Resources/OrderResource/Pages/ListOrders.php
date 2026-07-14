<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    
    public function getTabs(): array
    {
        return [
            'all' => \Filament\Resources\Components\Tab::make('Tất cả'),
            'kitchen' => \Filament\Resources\Components\Tab::make('Nhà Bếp (Mới đặt)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'new')),
            'waiter' => \Filament\Resources\Components\Tab::make('Phục vụ (Sẵn sàng)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'ready')),
            'served' => \Filament\Resources\Components\Tab::make('Đã giao (Chờ thanh toán)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'served')->where('payment_status', 'pending')),
        ];
    }
}
