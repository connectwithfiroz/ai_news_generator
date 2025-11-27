<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\Browsershot\Browsershot;

class NewsImageController extends Controller
{
    public function generateImageWithBrowsershot(Request $request)
    {
        $image = $request->get('image');      // static URL or dynamic API
        $title = $request->get('title');
        $description = $request->get('description');
        $category = $request->get('category') ?? 'GENERAL';
        $source = $request->get('source') ?? 'FREEPRESSJOURNAL.IN';

        $fileName = 'social_' . time() . '.png';
        $filePath = storage_path('app/public/' . $fileName);

        // Render blade
        $html = view('news.social_card', [
            'image' => $image,
            'title' => $title,
            'description' => $description,
            'category' => $category,
            'source' => $source,
        ])->render();
        return $html;

        // The HTML content in 'news.social_card' MUST be designed for 900x1200 now.

        // Generate image
        Browsershot::html($html)
            // 🚨 CHANGE 1: Use a vertical, mobile-friendly window size (e.g., 900px wide x 1200px high - a 3:4 ratio)
            ->windowSize(900, 1200)
            // Keep deviceScaleFactor(2) for a high-resolution output (1800x2400 actual pixels)
            ->deviceScaleFactor(2)
            ->fullPage(false) // ❗ Use false when generating fixed-height cards
            ->save($filePath);

        return response()->json([
            'status' => true,
            'image_url' => asset('storage/' . $fileName)
        ]);
    }
    public function generateImageWithPrompt()
    {
        $ai = new \App\Services\AiServiceGemini();
        $url = $ai->generateImage("Benefits of eating cauliflower (गोभी) for health");
        echo $url;

    }

    public function generate()
    {
        // 1️⃣ Load your base image
        $imagePath = storage_path('app/public/test.jpg');
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

    public function generateHtmlImage()
    {
        $imageUrl = asset('storage/test.jpg'); // Base image
        $text = "Breaking News: AI is revolutionizing the world!";

        // Generate temporary HTML URL
        $html = view('news.news_card', compact('imageUrl', 'text'))->render();

        $filename = 'news_html_' . time() . '.png';
        $savePath = storage_path('app/public/news/' . $filename);

        Browsershot::html($html)
            ->windowSize(1200, 630)
            ->save($savePath);

        return Storage::url('news/' . $filename);
    }

}
