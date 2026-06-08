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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('table_id')
                    ->relationship('table', 'name')
                    ->label('Bàn')
                    ->required(),
                Forms\Components\TextInput::make('total_price')
                    ->label('Tổng tiền')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái món')
                    ->options([
                        'new' => 'Mới đặt',
                        'preparing' => 'Đang chuẩn bị',
                        'served' => 'Đã phục vụ',
                        'cancelled' => 'Đã hủy',
                    ])
                    ->required()
                    ->default('new'),
                Forms\Components\Select::make('payment_status')
                    ->label('Trạng thái thanh toán')
                    ->options([
                        'pending' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                    ])
                    ->required()
                    ->default('pending'),
                Forms\Components\Textarea::make('notes')
                    ->label('Ghi chú')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('table.name')
                    ->label('Bàn')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Tổng tiền')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái món')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Mới đặt',
                        'preparing' => 'Đang chuẩn bị',
                        'served' => 'Đã phục vụ',
                        'cancelled' => 'Đã hủy',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Thanh toán')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Chưa thanh toán',
                        'paid' => 'Đã thanh toán',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày đặt')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
