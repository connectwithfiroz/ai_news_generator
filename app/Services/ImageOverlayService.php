<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageOverlayService
{
    public function overlayText(string $imageUrl, string $text): ?string
{
    try {
        // STEP 1: Download image safely
        $response = Http::retry(3, 200)
            ->withHeaders([
                'User-Agent' => 'Mozilla/5.0'
            ])
            ->withOptions(['allow_redirects' => true])
            ->connectTimeout(10)
            ->timeout(30)
            ->get($imageUrl);

        if (!$response->ok()) {
            throw new \Exception("HTTP error: " . $response->status());
        }

        $mime = $response->header('Content-Type');

        if (!str_starts_with($mime, 'image/')) {
            throw new \Exception("Not an image. MIME = $mime");
        }

        $contents = $response->body();

        // STEP 2: Load directly from memory
        $manager = new ImageManager(new Driver());
        $img = $manager->read($contents);

        // STEP 3: Resize if needed
        if ($img->width() > 1200) {
            $img = $img->scale(width: 1200);
        }

        // STEP 4: Add caption area
        $captionHeight = 120;
        $img = $img->resizeCanvas(
            $img->width(),
            $img->height() + $captionHeight,
            'center',
            'top'
        );

        // STEP 5: Draw background
        $img->drawRectangle(
            0,
            $img->height() - $captionHeight,
            $img->width(),
            $img->height(),
            color: 'rgba(0,0,0,0.55)',
            filled: true
        );

        // STEP 6: Wrap text
        $wrapped = wordwrap($text, 55, "\n");

        // STEP 7: Draw text
        $img->text(
            $wrapped,
            $img->width() / 2,
            $img->height() - ($captionHeight / 2),
            function ($font) {
                $font->color('white');
                $font->align('center');
                $font->valign('middle');
                $font->size(28);
            }
        );

        // STEP 8: Save
        $filename = 'news_' . time() . '_' . Str::random(6) . '.jpg';
        $path = 'news/' . $filename;

        Storage::disk('public')->put($path, $img->toJpeg(85));

        return Storage::url($path);

    } catch (\Throwable $e) {
        \Log::error('Image overlay failed: '.$e->getMessage());
        return null;
    }
}

}
