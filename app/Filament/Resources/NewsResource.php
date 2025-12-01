<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsResource\Pages;
use App\Filament\Resources\NewsResource\RelationManagers;
use App\Models\News;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Storage;
use Table\Actions\Action;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\ToggleColumn;
class NewsResource extends Resource
{
    protected static ?string $model = News::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            ->recordAction(null)
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                ToggleColumn::make('mark_as_post')
                    ->label('Post?')
                    ->onColor('success')
                    ->offColor('gray')
                    ->updateStateUsing(function ($record, $state) {
                        $record->mark_as_post = $state;
                        $record->save();
                    }),
                ToggleColumn::make('selected_for_post')
                    ->label('Select?')
                    ->onColor('primary')
                    ->offColor('gray')
                    ->updateStateUsing(function ($record, $state) {
                        $record->selected_for_post = $state;
                        $record->save();
                    }),

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->limit(50)
                    ->searchable()
                    ->action(
                        Tables\Actions\Action::make('viewDetails')
                            ->modalHeading('News Details')
                            ->modalContent(function ($record) {
                                return view('filament.news.details', compact('record'));
                            })
                            ->modalWidth('xl')
                            ->modalSubmitAction(action: false)
                            ->label('View Details')
                            ->color('secondary'),
                    )
                    ->extraAttributes([
                        'class' => 'text-primary-600 cursor-pointer hover:underline',
                    ]),
                Tables\Columns\ImageColumn::make('response.image')->label('Image')->size(40)->square(),
                Tables\Columns\BadgeColumn::make('process_status')->label('Status')->colors([
                    'warning' => 'pending',
                    'info' => 'summarized',
                    'success' => 'image_ready',
                    'primary' => 'published',
                ])->getStateUsing(function ($record) {
                    if (empty($record->summarize_response))
                        return 'pending';
                    if (!empty($record->summarize_response) && empty($record->local_image_path))
                        return 'summarized';
                    if (
                        $record->local_image_path && !$record->published_at_whatsapp && !$record->published_at_facebook && !$record->published_at_linkedin
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
                        app(\App\Services\AiServiceGemini::class)->summarizeAndSaveInshortHindi($record)
                    )
                    ->color('warning'),

                Tables\Actions\Action::make('generateImage')
                    ->label('Generate Image')
                    ->url(fn($record) => route('filament.admin.news.generate-image', $record->id) . '?flag=1')

                    ->openUrlInNewTab()   // So async process doesn't block Filament
                    ->visible(
                        fn($record) =>
                        !empty($record->summarize_response) &&
                        empty($record->local_image_path)
                    )
                    ->color('info'),

                Tables\Actions\Action::make('viewGeneratedImage')
                    ->visible(fn($record) => !empty($record->local_image_path))
                    ->modalHeading('View Generated Image')
                    ->modalContent(fn($record) => new HtmlString('<img src="' . Storage::url($record->local_image_path) . '" alt="Generated Image" style="max-width:100%;">'))
                    ->modalWidth('md')
                    ->label('View Image')
                    ->color('gray')
                    ->extraModalFooterActions([
                        Tables\Actions\Action::make('download')
                            ->label('Download')
                            ->color('primary')
                            ->action(function ($record) {
                                return response()->download(
                                    Storage::disk('public')->path($record->local_image_path)
                                );
                            }),
                    ]),

                Tables\Actions\Action::make('viewSummary')
                    ->visible(fn($record) => !empty($record->summarize_response))
                    ->modalHeading('Summary')
                    ->modalContent(function ($record) {
                        return view('filament.news.modals.summary', compact('record'));
                    })
                    ->modalSubmitAction(false)
                    ->modalWidth('md')
                    ->label('View Summary')
                    ->color('gray'),



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
            ])
            ->filters([
                SelectFilter::make('process_status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'summarized' => 'Summarized',
                        'image_ready' => 'Image Ready',
                        'selected' => 'Selected for Post',
                        'mark_as_post' => 'Mark as Post',

                        // NEW publish options
                        'published_whatsapp' => 'Published on WhatsApp',
                        'published_facebook' => 'Published on Facebook',
                        'published_linkedin' => 'Published on LinkedIn',

                        'published_any' => 'Published (Any Platform)',
                    ])
                    ->query(function ($query, $data) {
                        $v = $data['value'] ?? null;

                        switch ($v) {

                            case 'pending':
                                return $query->whereNull('summarize_response');

                            case 'summarized':
                                return $query->whereNotNull('summarize_response')
                                    ->whereNull('local_image_path');

                            case 'image_ready':
                                return $query->whereNotNull('local_image_path')
                                    ->whereNull('published_at_whatsapp')
                                    ->whereNull('published_at_facebook')
                                    ->whereNull('published_at_linkedin');

                            case 'selected':
                                return $query->where('selected_for_post', true);

                            case 'mark_as_post':
                                return $query->where('mark_as_post', true);

                            case 'published_whatsapp':
                                return $query->whereNotNull('published_at_whatsapp');

                            case 'published_facebook':
                                return $query->whereNotNull('published_at_facebook');

                            case 'published_linkedin':
                                return $query->whereNotNull('published_at_linkedin');

                            case 'published_any':
                                return $query->where(function ($q) {
                                    $q->whereNotNull('published_at_whatsapp')
                                        ->orWhereNotNull('published_at_facebook')
                                        ->orWhereNotNull('published_at_linkedin');
                                });

                            default:
                                return $query;
                        }
                    }),
            ])
        ;
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
            'index' => Pages\ListNews::route('/'),
            'create' => Pages\CreateNews::route('/create'),
            'edit' => Pages\EditNews::route('/{record}/edit'),
        ];
    }
}
