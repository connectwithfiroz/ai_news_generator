<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\Action::make('fetchInshortNews')
                ->label('Fetch Inshort News')
                ->color('success')
                ->icon('heroicon-o-play')
                ->action(fn () => $this->runCustomControllerMethod()),
        ];
    }

    private function runCustomControllerMethod()
    {
        
        // Call your custom controller method here
        // For example:
        $response = app(\App\Http\Controllers\NewsController::class)->fetchInshortNewsAndStore();
        $responseData = $response->getData();
        $response_status = $responseData->status ?? false;
        
        if(!$response_status){   
            // Show error notification
            Notification::make()
                ->title('Error Fetching News')
                ->body($responseData->message ?? 'An error occurred while fetching news.')
                ->danger()
                ->send();
            return;
        }else{
            // Show success notification
            Notification::make()
                ->title('Inshort News Fetched')
                ->body('Inshort news fetched and stored successfully.')
                ->success()
                ->send();
        }
    }
}
