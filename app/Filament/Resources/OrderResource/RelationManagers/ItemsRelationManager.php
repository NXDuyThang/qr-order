<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('food_id')
                    ->relationship('food', 'name')
                    ->label('Món ăn')
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->label('Số lượng')
                    ->numeric()
                    ->required()
                    ->default(1),
                Forms\Components\TextInput::make('unit_price')
                    ->label('Đơn giá')
                    ->numeric()
                    ->suffix('VNĐ')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('food_id')
            ->columns([
                Tables\Columns\TextColumn::make('food.name')
                    ->label('Món ăn'),
                Tables\Columns\TextColumn::make('total_prep_time')
                    ->label('T.gian chuẩn bị (phút)')
                    ->state(function ($record) {
                        if (!$record->food || !$record->food->preparation_time) return null;
                        return $record->food->preparation_time * $record->quantity;
                    }),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Số lượng'),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label('Đơn giá')
                    ->formatStateUsing(fn ($state) => number_format($state * 1000, 0, ',', '.') . ' VNĐ'),
                Tables\Columns\TextColumn::make('total')
                    ->label('Thành tiền')
                    ->state(function ($record) {
                        return number_format($record->quantity * $record->unit_price * 1000, 0, ',', '.') . ' VNĐ';
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'primary',
                        'preparing' => 'warning',
                        'ready' => 'info',
                        'served' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Mới gọi',
                        'preparing' => 'Đang nấu',
                        'ready' => 'Nấu xong',
                        'served' => 'Đã phục vụ',
                        'cancelled' => 'Đã huỷ',
                        default => $state,
                    }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('prepare')
                    ->label('Nấu')
                    ->icon('heroicon-o-fire')
                    ->color('warning')
                    ->visible(fn ($record) => $record->status === 'new' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin'])))
                    ->action(function ($record) {
                        $record->update(['status' => 'preparing']);
                        if ($record->order && $record->order->status === 'new') {
                            $record->order->update(['status' => 'preparing']);
                        }
                    }),
                    
                Tables\Actions\Action::make('ready')
                    ->label('Xong')
                    ->icon('heroicon-o-check')
                    ->color('info')
                    ->visible(fn ($record) => $record->status === 'preparing' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['chef', 'admin'])))
                    ->action(function ($record) {
                        $record->update(['status' => 'ready']);
                        if ($record->order) {
                            $allReady = $record->order->items()->whereNotIn('status', ['ready', 'served', 'completed', 'cancelled'])->count() === 0;
                            if ($allReady) {
                                $record->order->update(['status' => 'ready']);
                            }
                        }
                    }),
                    
                Tables\Actions\Action::make('serve')
                    ->label('Phục vụ')
                    ->icon('heroicon-o-arrow-right')
                    ->color('success')
                    ->visible(fn ($record) => $record->status === 'ready' && (auth()->user()->is_admin || in_array(auth()->user()->role, ['waiter', 'admin'])))
                    ->action(function ($record) {
                        $record->update(['status' => 'served']);
                        if ($record->order) {
                            $allServed = $record->order->items()->whereNotIn('status', ['served', 'completed', 'cancelled'])->count() === 0;
                            if ($allServed && $record->order->status !== 'completed') {
                                $record->order->update(['status' => 'served']);
                            }
                        }
                    }),
                    
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->poll('3s');
    }
}
