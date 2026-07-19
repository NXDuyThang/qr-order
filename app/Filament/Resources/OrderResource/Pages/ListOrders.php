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
        $user = auth()->user();
        $tabs = [];

        if ($user->is_admin || in_array($user->role, ['admin', 'manager'])) {
            $tabs['all'] = \Filament\Resources\Components\Tab::make('Tất cả');
            $tabs['kitchen'] = \Filament\Resources\Components\Tab::make('Nhà Bếp (Mới đặt)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'new'));
            $tabs['waiter'] = \Filament\Resources\Components\Tab::make('Phục vụ (Sẵn sàng)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'ready'));
            $tabs['served'] = \Filament\Resources\Components\Tab::make('Đã giao (Chờ thanh toán)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'served')->where('payment_status', 'pending'));
        } elseif ($user->role === 'kitchen') {
            $tabs['kitchen'] = \Filament\Resources\Components\Tab::make('Nhà Bếp (Mới đặt)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'new'));
        } elseif ($user->role === 'staff') {
            $tabs['waiter'] = \Filament\Resources\Components\Tab::make('Phục vụ (Sẵn sàng)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'ready'));
            $tabs['served'] = \Filament\Resources\Components\Tab::make('Đã giao (Chờ thanh toán)')
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->where('status', 'served')->where('payment_status', 'pending'));
        } else {
            $tabs['all'] = \Filament\Resources\Components\Tab::make('Tất cả');
        }

        return $tabs;
    }
}
