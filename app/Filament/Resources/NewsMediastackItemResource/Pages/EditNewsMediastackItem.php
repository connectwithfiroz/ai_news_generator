<?php

namespace App\Filament\Resources\NewsMediastackItemResource\Pages;

use App\Filament\Resources\NewsMediastackItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewsMediastackItem extends EditRecord
{
    protected static string $resource = NewsMediastackItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
