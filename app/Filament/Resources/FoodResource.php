<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FoodResource\Pages;
use App\Filament\Resources\FoodResource\RelationManagers;
use App\Models\Food;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FoodResource extends Resource
{
    protected static ?string $model = Food::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $modelLabel = 'Món ăn';
    protected static ?string $pluralModelLabel = 'Quản lý Món ăn';
    protected static ?string $navigationGroup = 'Thực đơn';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin || auth()->user()->role === 'manager';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Danh mục')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->label('Tên món ăn')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->label('Đường dẫn (slug)')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Mô tả')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->label('Giá bán')
                    ->required()
                    ->numeric()
                    ->suffix('VNĐ'),
                Forms\Components\TextInput::make('preparation_time')
                    ->label('Thời gian chuẩn bị (phút)')
                    ->numeric()
                    ->nullable(),
                Forms\Components\FileUpload::make('image')
                    ->label('Hình ảnh')
                    ->image()
                    ->disk('public')
                    ->directory('images')
                    ->preserveFilenames(),
                Forms\Components\Toggle::make('is_available')
                    ->label('Còn hàng')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Danh mục')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tên món ăn')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Đường dẫn (slug)')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Giá bán')
                    ->formatStateUsing(fn ($state) => number_format($state * 1000, 0, ',', '.') . ' VNĐ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('preparation_time')
                    ->label('Thời gian (phút)')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Hình ảnh')
                    ->disk('public'),
                Tables\Columns\IconColumn::make('is_available')
                    ->label('Còn hàng')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Ngày tạo')
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
            'index' => Pages\ListFood::route('/'),
            'create' => Pages\CreateFood::route('/create'),
            'edit' => Pages\EditFood::route('/{record}/edit'),
        ];
    }
}
