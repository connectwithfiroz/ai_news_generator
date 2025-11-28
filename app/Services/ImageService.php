<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Spatie\Browsershot\Browsershot;
use function Termwind\render;
class ImageService
{
    public function generateImageWithBrowsershotSeperate($news)
    {

        

        // Render blade
        $html = view('news.social_card', [
            'image' => $image,
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'source' => $source,
        ])->render();
        // Generate image
        Browsershot::html($html)
            // 🚨 CHANGE 1: Use a vertical, mobile-friendly window size (e.g., 900px wide x 1200px high - a 3:4 ratio)
            ->windowSize(900, 1200)
            // Keep deviceScaleFactor(2) for a high-resolution output (1800x2400 actual pixels)
            ->deviceScaleFactor(2)
            ->fullPage(false) // ❗ Use false when generating fixed-height cards
            ->save($filePath);

        //if flag is empty then return json else return to route
        if (empty($flag)) {

            return response()->json([
                'status' => true,
                'image_url' => asset('storage/' . $fileName)
            ]);
        } else if ($flag == 1) {
            //redirect to filament resource
            return redirect()->route('filament.resources.news-mediastack-items.index')->with('success', 'Image generated successfully.');
        }
    }

    public function generate($news)
    {
        try {
            // Always use object access — NOT array access
            $response = $news->response ?? [];
            $image_url = $news->original_image_url ?? $news->response['image'] ?? '';
            $description = $news->summarize_response ?? $response['description'] ?? '';
            if (empty($image_url)) {
                throw new \Exception('No image URL found for generating image.');
            }
            // dd('ok');


            $imagesResponse = $this->generateImageWithBrowsershot([
                'image' => $image_url,
                'title' => $response['title'] ?? '',
                'description' => $description,
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
            return render($html);

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
