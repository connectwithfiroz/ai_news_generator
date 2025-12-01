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

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('fetchNews')
                ->label('Fetch News')
                ->color('success')
                ->icon('heroicon-o-play')
                ->form([
                    Forms\Components\Select::make('category')
                        ->label('Choose Category')
                        ->options([
                            'top_stories' => 'Top Stories',
                            'technology' => 'Technology',
                            'hatke' => 'hatke',
                        ])
                        ->required(),
                ])
                ->action(function ($data) {

                    $response = app(\App\Http\Controllers\NewsController::class)
                        ->fetchInshortNewsAndStore(['category' => $data['category']]);

                    $status = $response->getData()->status ?? false;

                    if (!$status) {
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

}
