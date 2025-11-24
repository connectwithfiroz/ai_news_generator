<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ImageService
{

    public function generate($news)
    {
        $image = $news['response']['image'];
        $title = $news['response']['title'];
        $url = app(\App\Services\ImageOverlayService::class)->overlayText($image, $title);
        //log url
        \Log::info("LOCAL URL IS- ".$url);
        // your local image generation logic
        $news->local_image_path = $url;
        // $news->image_generated = 1;
        $news->save();
    }
}
