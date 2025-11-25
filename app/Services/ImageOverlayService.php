<?php

namespace App\Services;

use Exception;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
class ImageOverlayService
{


    public function overlayText(string $imagePath, string $text): ?string
    {
        // For testing local image
        $imagePath = storage_path('app/public/test.png');
        $text = "Hello, Quiz";

        try {
            // 1️⃣ Check if image exists
            if (!file_exists($imagePath)) {
                Log::error("Local image not found: " . $imagePath);
                return null;
            }

            // 2️⃣ Load image with Intervention v2
            $img = Image::make($imagePath);

            // 3️⃣ Optional resize if too wide
            if ($img->width() > 1200) {
                $img->resize(1200, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            // 4️⃣ Add bottom black box
            $height = $img->height();
            $textBoxHeight = 140;

            $img->resizeCanvas($img->width(), $height + $textBoxHeight, 'center', false, '#000000');

            // Make the bottom box slightly transparent (v2 supports opacity via color hex + alpha)
            $img->rectangle(
                0,
                $height,
                $img->width(),
                $height + $textBoxHeight,
                function ($draw) {
                    $draw->background('rgba(0,0,0,0.55)');
                }
            );

            // 5️⃣ Add text
            $wrapped = wordwrap($text, 60, "\n");

            $img->text($wrapped, $img->width() / 2, $height + ($textBoxHeight / 2), function ($font) {
                // Make sure you have a valid font file
                // $font->file(public_path('fonts/arial.ttf'));
                $font->size(30);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
            });
            

            // 6️⃣ Save result to storage/app/public/news/
            $filename = 'news_' . time() . '_' . Str::random(5) . '.jpg';
            $savePath = 'news/' . $filename;

            Storage::disk('public')->put($savePath, (string) $img->encode('jpg', 90));

            return Storage::url($savePath);

        } catch (\Exception $e) {
            Log::error("Image overlay failed: " . $e->getMessage());
            return null;
        }
    }


    /* ------------------------------------------------------------
     * MIME DETECTION
     * ------------------------------------------------------------ */
    private function detectMime(string $binary): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_buffer($finfo, $binary);
        finfo_close($finfo);

        return $mime ?: null;
    }

    /* ------------------------------------------------------------
     * MIME → EXT
     * ------------------------------------------------------------ */
    private function mimeToExt(string $mime): ?string
    {
        return [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ][$mime] ?? null;
    }
}
