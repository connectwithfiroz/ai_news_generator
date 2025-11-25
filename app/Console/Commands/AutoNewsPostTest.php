<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Facades\Image;

// use Image;
class AutoNewsPostTest extends Command
{
    protected $signature = 'news:post-test';
    protected $description = 'Generate news image and post to Facebook Page';

    public function handle()
    {
        $this->info("Generating news image...");

        // --- 1. News Text ---
        $newsText = "Breaking News: AI is transforming the world.";

        // --- 2. Read base image ---
        $path = storage_path('app/public/test.png');

        if (!file_exists($path)) {
            $this->error("Image missing: storage/app/public/test.png");
            return;
        }

        // v3 = Image::read()


        $base = Image::make(storage_path('app/public/test.png'));

        $canvas = Image::canvas($base->width(), $base->height() + 180, '#ffffff');

        $canvas->text("Breaking News: AI is booming!", $base->width() / 2, 60, function ($font) {
            $font->file(public_path('fonts/arial.ttf'));
            $font->size(32);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        $canvas->insert($base, 'top', 0, 180);

        $canvas->save(storage_path('app/public/generated_news.png'));


        $this->info("Final image saved at: storage/app/public/generated_news.png");

        // --- 7. Upload to Facebook ---
        $pageId = config('services.facebook.page_id');
        $pageToken = config('services.facebook.page_token');

        if (!$pageId || !$pageToken) {
            $this->error("Facebook page_id or page_token missing.");
            return;
        }

        $this->info("Posting to Facebook...");

        $response = Http::attach(
            'source',
            file_get_contents($finalPath),
            'generated_news.png'
        )->post("https://graph.facebook.com/$pageId/photos", [
                    'caption' => $newsText,
                    'access_token' => $pageToken,
                ]);

        if ($response->successful()) {
            $this->info("Successfully posted to Facebook page!");
        } else {
            $this->error("Facebook API Error:");
            $this->error($response->body());
        }
    }
}
