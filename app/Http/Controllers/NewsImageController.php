<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NewsImageController extends Controller
{
    public function generate()
    {
        // 1️⃣ Load your base image
        $imagePath = storage_path('app/public/test.png');
        if (!file_exists($imagePath)) {
            return "Base image not found!";
        }

        $img = Image::make($imagePath);

        // 2️⃣ Resize if too wide
        if ($img->width() > 1200) {
            $img->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        // 3️⃣ Add bottom space for text
        $textBoxHeight = 100;
        $img->resizeCanvas($img->width(), $img->height() + $textBoxHeight, 'center', false, '#000000');

        // 4️⃣ Add text using internal GD font
        $text = "Breaking News: AI is changing the world!";
        $lines = wordwrap($text, 50, "\n");

        $img->text($lines, $img->width() / 2, $img->height() - $textBoxHeight / 2, function ($font) {
            $font->file(null);       // null = use GD internal font
            $font->size(5);          // size 1-5
            $font->color('#ffffff'); // white text
            $font->align('center');
            $font->valign('middle');
        });

        // 5️⃣ Save to storage
        $filename = 'news_' . time() . '.png';
        $savePath = 'news/' . $filename;

        Storage::disk('public')->put($savePath, (string) $img->encode('png', 90));

        // 6️⃣ Return URL
        return Storage::url($savePath);
    }
}
