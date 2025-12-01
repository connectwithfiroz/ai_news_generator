<?php

namespace App\Filament\Resources\NewsResource\Pages;

use App\Filament\Resources\NewsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Forms;
class ListNews extends ListRecords
{
    protected static string $resource = NewsResource::class;



public function getHeaderActions(): array
{
    return [
        Action::make('fetchNews')
            ->label('Fetch News')
            ->form([
                Forms\Components\Select::make('category')
                    ->label('Choose Category')
                    ->options([
                        'top_stories' => 'Top Stories',
                        'technology'  => 'Technology',
                        'national'    => 'National',
                        'sports'      => 'Sports',
                    ])
                    ->required(),
            ])
            ->action(function ($data) {

                $response = app(\App\Http\Controllers\NewsController::class)
                    ->fetchInshortNewsAndStore($data['category']);

                $status = $response->getData()->status ?? false;

                if (! $status) {
                    Notification::make()
                        ->title('Error Fetching News')
                        ->body($response->getData()->message ?? 'Something went wrong.')
                        ->danger()
                        ->send();

                } else {
                    Notification::make()
                        ->title('Success')
                        ->body("News fetched successfully for category: {$data['category']}")
                        ->success()
                        ->send();
                }
            }),
    ];
}


    private function runCustomControllerMethod()
    {

        // Call your custom controller method here
        // For example:
        $response = app(\App\Http\Controllers\NewsController::class)->fetchInshortNewsAndStore();
        $responseData = $response->getData();
        $response_status = $responseData->status ?? false;

        if (!$response_status) {
            // Show error notification
            Notification::make()
                ->title('Error Fetching News')
                ->body($responseData->message ?? 'An error occurred while fetching news.')
                ->danger()
                ->send();
            return;
        } else {
            // Show success notification
            Notification::make()
                ->title('Inshort News Fetched')
                ->body('Inshort news fetched and stored successfully.')
                ->success()
                ->send();
        }
    }
}
