<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ImageService
{

    public function generate($news)
    {
        $image = $news['response']['image'];
        $title = $news['response']['title'];
        // your local image generation logic
        // $news->local_image_path = $url;
        // $url = app(\App\Services\ImageOverlayService::class)->overlayText($image, $title);
        //FOR TESTING
        $news->local_image_path = $news->original_image_url;
        $news->save();
    }
}
