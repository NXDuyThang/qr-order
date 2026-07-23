<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $modelLabel = 'Đơn hàng';
    protected static ?string $pluralModelLabel = 'Quản lý Đơn hàng';
    protected static ?string $navigationGroup = 'Nhà hàng';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['manager', 'admin', 'waiter', 'chef']) || auth()->user()->is_admin;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Thông tin Đơn hàng')
                    ->description('Quản lý thông tin đơn gọi món tại bàn.')
                    ->schema([
                        Forms\Components\Select::make('table_id')
                    ->relationship('table', 'name')
                    ->label('Bàn')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Tổng tiền')
                    ->formatStateUsing(fn ($state) => number_format(($state ?? 0) * 1000, 0, ',', '.') . ' VNĐ')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái món')
                    ->options([
                        'new' => 'Mới đặt',
                        'ready' => 'Nấu xong',
                        'served' => 'Đã phục vụ',
                        'completed' => 'Hoàn tất',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->required()
                    ->default('new')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->options([
                        'pending' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                    ])
                    ->required()
                    ->default('pending')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->poll('3s')
            ->defaultSort('updated_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('table.name')
                    ->label('Bàn')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Khách hàng')
                    ->sortable()
                    ->searchable()
                    ->default('Khách vãng lai'),
                Tables\Columns\ViewColumn::make('items_list')
                    ->label('Danh sách món ăn')
                    ->view('filament.tables.columns.order-items'),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Tổng tiền')
                    ->formatStateUsing(fn ($state) => number_format($state * 1000, 0, ',', '.') . ' VNĐ')
                    ->sortable()
                    ->visible(fn () => auth()->check() && (auth()->user()->is_admin || in_array(auth()->user()->role, ['manager', 'admin']))),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái món')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'ready' => 'warning',
                        'served' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Mới đặt',
                        'ready' => 'Nấu xong',
                        'served' => 'Đã giao',
                        'completed' => 'Hoàn tất',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Thanh toán')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                        default => $state,
                    })
                    ->searchable()
                    ->visible(fn () => auth()->check() && (auth()->user()->is_admin || in_array(auth()->user()->role, ['manager', 'admin']))),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đặt')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Ngày cập nhật')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('prepareAll')
                    ->label('Nấu')
                    ->icon('heroicon-o-fire')
                    ->color('warning')
                    ->visible(fn (Order $record) => $record->items()->where('status', 'new')->exists() && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin'])))
                    ->action(function (Order $record) {
                        $record->items()->where('status', 'new')->update(['status' => 'preparing']);
                        if ($record->status === 'new') {
                            $record->update(['status' => 'preparing']);
                        }
                    }),

                Tables\Actions\Action::make('readyAll')
                    ->label('Xong')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->items()->where('status', 'preparing')->exists() && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin'])))
                    ->action(function (Order $record) {
                        $record->items()->where('status', 'preparing')->update(['status' => 'ready']);
                        $allReady = $record->items()->whereNotIn('status', ['ready', 'served', 'completed', 'cancelled'])->count() === 0;
                        if ($allReady) {
                            $record->update(['status' => 'ready']);
                        }
                    }),

                Tables\Actions\Action::make('serveAll')
                    ->label('Phục vụ')
                    ->icon('heroicon-o-arrow-right')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->items()->where('status', 'ready')->exists() && (auth()->user()->is_admin || in_array(auth()->user()->role, ['waiter', 'admin'])))
                    ->action(function (Order $record) {
                        $record->items()->where('status', 'ready')->update(['status' => 'served']);
                        $allServed = $record->items()->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;
                        if ($allServed && $record->status !== 'completed') {
                            $record->update(['status' => 'served']);
                        }
                    }),
                    
                Tables\Actions\Action::make('confirmPayment')
                    ->label('Xác nhận Thanh toán')
                    ->icon('heroicon-o-currency-dollar')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'served' && $record->payment_status === 'pending' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['manager', 'admin'])))
                    ->action(function (Order $record) {
                        $record->update(['payment_status' => 'paid', 'status' => 'completed']);
                        $record->items()->whereNotIn('status', ['cancelled'])->update(['status' => 'completed']);
                        
                        // Check if the table has any other pending orders
                        if ($record->table_id) {
                            $hasPendingOrders = \App\Models\Order::where('table_id', $record->table_id)
                                ->where('payment_status', 'pending')
                                ->exists();
                                
                            if (!$hasPendingOrders) {
                                \App\Models\Table::where('id', $record->table_id)->update(['status' => 'available']);
                            }
                        }
                    })
                    ->successNotificationTitle('Đã xác nhận thanh toán thành công'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        return auth()->user()->is_admin || in_array(auth()->user()->role, ['admin', 'manager']);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['items.food']);
    }
}
