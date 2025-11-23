<?php

namespace App\Filament\Resources\NewsMediastackItemResource\Pages;

use App\Filament\Resources\NewsMediastackItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsMediastackItems extends ListRecords
{
    protected static string $resource = NewsMediastackItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
