<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Filament\Resources\TableResource\RelationManagers;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TableResource extends Resource
{
    protected static ?string $model = TableModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tên bàn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'available' => 'Trống',
                        'occupied' => 'Đang phục vụ',
                        'reserved' => 'Đã đặt',
                    ])
                    ->required()
                    ->default('available'),
                Forms\Components\Placeholder::make('qr_code_image')
                    ->label('Mã QR Đặt Món')
                    ->content(function ($record) {
                        if (! $record) {
                            return 'Vui lòng lưu bàn trước để xem mã QR.';
                        }
                        $url = url('/order?table_id=' . $record->id);
                        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($url);
                        return new \Illuminate\Support\HtmlString('<img src="' . $qrUrl . '" alt="QR Code" style="margin-top: 10px;" /> <br><a href="'.$qrUrl.'" download="table_'.$record->id.'_qr.png" target="_blank" class="text-primary-600 underline mt-2 inline-block" style="color: #0077bb;">Tải xuống (In)</a>');
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên bàn')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('qr_code_image')
                    ->label('Mã QR')
                    ->state(function ($record) {
                        $url = url('/order?table_id=' . $record->id);
                        return 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($url);
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'available' => 'Trống',
                        'occupied' => 'Đang phục vụ',
                        'reserved' => 'Đã đặt',
                        default => $state,
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }
}
