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

    public function updateItemStatus($itemId, $status)
    {
        $item = \App\Models\OrderItem::find($itemId);
        if ($item) {
            $item->update(['status' => $status]);
            
            // Update order status if necessary
            if ($item->order) {
                if ($status === 'preparing' && $item->order->status === 'new') {
                    $item->order->update(['status' => 'preparing']);
                }
                
                if ($status === 'ready') {
                    $allReady = $item->order->items()->whereNotIn('status', ['ready', 'served', 'completed', 'cancelled'])->count() === 0;
                    if ($allReady) {
                        $item->order->update(['status' => 'ready']);
                    }
                }
                
                if ($status === 'served') {
                    $allServed = $item->order->items()->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;
                    if ($allServed && $item->order->status !== 'completed') {
                        $item->order->update(['status' => 'served']);
                    }
                }
            }
        }
    }

    public function remindKitchen($itemId)
    {
        $item = \App\Models\OrderItem::find($itemId);
        if ($item && $item->order) {
            \Filament\Notifications\Notification::make()
                ->title('Nhắc nhở từ Quản lý')
                ->body('Vui lòng đẩy nhanh tiến độ món: ' . $item->food->name . ' (Bàn ' . $item->order->table->name . ')')
                ->warning()
                ->sendToDatabase(\App\Models\User::whereIn('role', ['chef', 'admin'])->get());
                
            \Filament\Notifications\Notification::make()
                ->title('Đã gửi nhắc nhở')
                ->body('Đã gửi nhắc nhở đến bộ phận bếp thành công.')
                ->success()
                ->send();
        }
    }
}
