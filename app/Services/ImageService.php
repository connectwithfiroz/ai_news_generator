<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ImageService
{

    public function generate($news)
    {
        // your local image generation logic
        $news->image_generated = 1;
        $news->save();
    }
}
