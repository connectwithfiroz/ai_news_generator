<?php

namespace App\Services;

use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Exception;

class ImageOverlayService
{
    public function overlayText(string $imageUrl, string $text): ?string
    {
        try {
            $contents = Http::get($imageUrl)->body();
            $ext = 'jpg';
            $filename = 'news_' . time() . '_' . Str::random(6) . '.' . $ext;
            $tmp = sys_get_temp_dir() . '/' . $filename;
            file_put_contents($tmp, $contents);

            Image::configure(['driver' => 'gd']);
            $img = Image::make($tmp);

            // resize width if too large
            if ($img->width() > 1200) $img->resize(1200, null, function ($c) { $c->aspectRatio(); });

            // add bottom caption box
            $height = $img->height();
            $img->resizeCanvas($img->width(), $height + 120, 'center', false, 'rgba(0,0,0,0.0)');

            // place semi-transparent rectangle
            $img->rectangle(0, $height, $img->width(), $height + 120, function ($draw) {
                $draw->background('rgba(0,0,0,0.55)');
            });

            // write text (wrap)
            $wrapped = wordwrap($text, 55, "\n");
            $img->text($wrapped, $img->width()/2, $height + 60, function($font){
                $font->file(__DIR__ . '/../../resources/fonts/Roboto-Regular.ttf');
                $font->size(28);
                $font->color('#ffffff');
                $font->align('center');
                $font->valign('middle');
                $font->angle(0);
            });

            // save to storage/public/news/
            $path = 'news/' . $filename;
            Storage::disk('public')->put($path, (string) $img->encode('jpg', 85));
            @unlink($tmp);
            return Storage::url($path);
        } catch (Exception $e) {
            \Log::error('Image overlay failed: '.$e->getMessage());
            return null;
        }
    }
}
