<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Models\LeaveRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LeaveRequestResource extends Resource
{
    protected static ?string $model = LeaveRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Nhân sự';
    protected static ?string $modelLabel = 'Nghỉ phép';
    protected static ?string $pluralModelLabel = 'Yêu cầu nghỉ phép';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (auth()->user()->is_admin || auth()->user()->role === 'manager') {
            return $query;
        }
        return $query->where('user_id', auth()->id());
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
                Forms\Components\DatePicker::make('start_date')
                    ->label('Ngày bắt đầu')
                    ->required()
                    ->minDate(now()->addDays(2)), // At least 48h from now
                Forms\Components\DatePicker::make('end_date')
                    ->label('Ngày kết thúc')
                    ->required()
                    ->afterOrEqual('start_date'),
                Forms\Components\Textarea::make('reason')
                    ->label('Lý do')
                    ->maxLength(65535),
                Forms\Components\Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        'pending' => 'Chờ duyệt',
                        'approved' => 'Đã duyệt',
                        'rejected' => 'Từ chối',
                    ])
                    ->default('pending')
                    ->disabled(fn () => !auth()->user()->is_admin && auth()->user()->role !== 'manager')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nhân viên')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Từ ngày')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Đến ngày')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('approver.name')
                    ->label('Người duyệt')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Duyệt')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => auth()->id(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending' && (auth()->user()->is_admin || auth()->user()->role === 'manager')),
                Tables\Actions\Action::make('reject')
                    ->label('Từ chối')
                    ->icon('heroicon-o-x-mark')
                    ->color('danger')
                    ->action(function (LeaveRequest $record) {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => auth()->id(),
                        ]);
                    })
                    ->requiresConfirmation()
                    ->visible(fn (LeaveRequest $record) => $record->status === 'pending' && (auth()->user()->is_admin || auth()->user()->role === 'manager')),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListLeaveRequests::route('/'),
        ];
    }
}
