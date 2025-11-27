<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
class ImageService
{

    public function generate($news)
    {
        try {
            // Always use object access — NOT array access
            $response = $news->response ?? [];

            $imagesResponse = $this->generateImageWithBrowsershot([
                'image' => $response['image'] ?? '',
                'title' => $response['title'] ?? '',
                'description' => $response['content'] ?? $news->summarize_response,
                'category' => $response['category'] ?? '',
                'source' => $response['source'] ?? '',
            ]);

            if ($imagesResponse['status'] === true) {

                // Save image path into model
                $news->local_image_path = 'news_images/' . $imagesResponse['file_name'];
                dd($news->local_image_path);
                $news->save();

                return [
                    'status' => true,
                    'image_url' => $imagesResponse['image_url'],
                ];
            }

            // Return failure to Filament
            return [
                'status' => false,
                'error' => $imagesResponse['error'] ?? 'Image generation failed.',
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    public function generateImageWithBrowsershot(array $data)
    {
        try {
            $fileName = 'social_' . time() . '.png';
            $filePath = storage_path('app/public/news_images/' . $fileName);

            $html = view('news.social_card', $data)->render();

            Browsershot::html($html)
                ->windowSize(900, 1200)
                ->deviceScaleFactor(2)
                ->fullPage(false)
                ->save($filePath);

            return [
                'status' => true,
                'file_name' => $fileName,
                'image_url' => asset('storage/news_images/' . $fileName),
            ];

        } catch (\Throwable $e) {

            return [
                'status' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

}
