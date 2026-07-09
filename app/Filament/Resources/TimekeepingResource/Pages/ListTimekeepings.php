<?php

namespace App\Filament\Resources\TimekeepingResource\Pages;

use App\Filament\Resources\TimekeepingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTimekeepings extends ListRecords
{
    protected static string $resource = TimekeepingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
