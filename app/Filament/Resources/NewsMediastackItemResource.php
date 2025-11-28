<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsMediastackItemResource\Pages;
use App\Filament\Resources\NewsMediastackItemResource\RelationManagers;
use App\Models\NewsMediastackItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;



class NewsMediastackItemResource extends Resource
{
    protected static ?string $model = NewsMediastackItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'News';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),

                Tables\Columns\TextColumn::make('response.title')
                    ->label('Title')
                    ->limit(50)
                    ->searchable(),

                Tables\Columns\ImageColumn::make('response.image')
                    ->label('Image')
                    ->size(40)
                    ->square(),

                Tables\Columns\BadgeColumn::make('process_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'summarized',
                        'success' => 'image_ready',
                        'primary' => 'published',
                    ])
                    ->getStateUsing(function ($record) {
                        if (empty($record->summarize_response))
                            return 'pending';

                        if (!empty($record->summarize_response) && empty($record->local_image_path))
                            return 'summarized';

                        if (
                            $record->local_image_path &&
                            !$record->published_at_whatsapp &&
                            !$record->published_at_facebook &&
                            !$record->published_at_linkedin
                        )
                            return 'image_ready';

                        return 'published';
                    }),

                Tables\Columns\TextColumn::make('published_at_whatsapp')->label('WhatsApp')->dateTime(),
                Tables\Columns\TextColumn::make('published_at_facebook')->label('Facebook')->dateTime(),
                Tables\Columns\TextColumn::make('published_at_linkedin')->label('LinkedIn')->dateTime(),
            ])

            ->actions([

                Tables\Actions\Action::make('summarize')
                    ->visible(fn($record) => empty($record->summarize_response))
                    ->action(
                        fn($record) =>
                        app(\App\Services\AiServiceGemini::class)->summarizeAndSave($record)
                    )
                    ->color('warning'),

                Tables\Actions\Action::make('generateImage')
                    ->visible(
                        fn($record) =>
                        !empty($record->summarize_response) &&
                        empty($record->local_image_path)
                    )
                    ->action(
                        function($record) {
                            // redirect()->route('generate-image-with-browsershot', [
                            //     'title' => $record->response['title'] ?? '',
                            //     'description' => $record->summarize_response ?? '',
                            //     'image' => $record->response['image'] ?? '',
                            //     'category' => $record->response['category'] ?? 'GENERAL',
                            //     'source' => $record->response['source'] ?? 'FREEPRESSJOURNAL.IN',
                            //     'flag' => 1,// indicate redirect to Filament
                            // ]);
                            $image_generate_response = app(\App\Services\ImageService::class)->generate($record);
                            dd($image_generate_response);   
                            if(!$image_generate_response['status']){
                                //handle error
                                Notification::make()
                                    ->title('Image Generation Failed')
                                    ->body($image_generate_response['error'] ?? 'Unknown error occurred during image generation.')
                                    ->danger()
                                    ->send();
                            }else{
                                Notification::make()
                                    ->title('Image Generated Successfully')
                                    ->body('The image has been generated and saved successfully.')
                                    ->success()
                                    ->send();
                            }
                        }
                    )
                    ->color('info'),

                Tables\Actions\Action::make('viewSummary')
                    ->visible(fn($record) => !empty($record->summarize_response))
                    ->modalHeading('Summary')
                    ->modalSubheading('Generated Summary')
                    ->modalContent(fn($record) => new HtmlString(nl2br(e($record->summarize_response))))
                    ->modalWidth('lg')
                    ->label('View Summary')
                    ->color('gray'),

                Tables\Actions\Action::make('viewDetails')
                    ->modalHeading('News Details')
                    ->modalContent(fn($record) => view('filament.news.details', ['record' => $record]))
                    ->modalWidth('4xl')
                    ->label('View Details')
                    ->color('secondary'),

                Tables\Actions\Action::make('publishWhatsapp')
                    ->visible(
                        fn($record) =>
                        !empty($record->local_image_path) &&
                        empty($record->published_at_whatsapp)
                    )
                    ->action(
                        fn($record) =>
                        app(\App\Services\PostService::class)->publishWhatsapp($record)
                    )
                    ->color('success'),

                Tables\Actions\Action::make('publishFacebook')
                    ->visible(
                        fn($record) =>
                        !empty($record->local_image_path) &&
                        empty($record->published_at_facebook)
                    )
                    ->action(
                        fn($record) =>
                        app(\App\Services\PostService::class)->publishFacebook($record)
                    )
                    ->color('primary'),

                Tables\Actions\Action::make('publishLinkedin')
                    ->visible(
                        fn($record) =>
                        !empty($record->local_image_path) &&
                        empty($record->published_at_linkedin)
                    )
                    ->action(
                        fn($record) =>
                        app(\App\Services\PostService::class)->publishLinkedin($record)
                    ),
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
            'index' => Pages\ListNewsMediastackItems::route('/'),
            'create' => Pages\CreateNewsMediastackItem::route('/create'),
            'edit' => Pages\EditNewsMediastackItem::route('/{record}/edit'),
        ];
    }
}
