<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PostService
{
    public function publishFacebook($record)
    {
        $caption = $record->title . "\n\n" . $record->summary . "\n\n" . $record->url;
        $imagePathOrUrl = $record->local_image_path ?? $record->image_url;
        //log image path url
        \Log::info("Publishing to Facebook with image: " . $imagePathOrUrl);


        $pageId = config('services.facebook.page_id');
        $token = config('services.facebook.page_token');
        $image = $this->ensureUrl($imagePathOrUrl);

        $res = Http::post("https://graph.facebook.com/{$token}/photos", [
            'url' => $image,
            'caption' => $caption,
            'access_token' => $token
        ]);

        return $res->json() + ['ok' => $res->ok()];
    }

    public function publishWhatsApp(string $caption, $imagePathOrUrl)
    {
        $token = env('WA_TOKEN');
        $phoneId = env('WA_PHONE_ID'); // WhatsApp Cloud Phone ID
        $image = $this->ensureUrl($imagePathOrUrl);

        // Simple template: send image message to a single number (you must manage recipients)
        $res = Http::withToken($token)->post("https://graph.facebook.com/v17.0/{$phoneId}/messages", [
            'messaging_product' => 'whatsapp',
            'to' => env('WA_TARGET_NUMBER'), // e.g. 919xxxxxxxx
            'type' => 'image',
            'image' => ['link' => $image, 'caption' => $caption]
        ]);

        return $res->json() + ['ok' => $res->ok()];
    }

    public function publishLinkedin(string $caption, $imagePathOrUrl)
    {
        // simple placeholder: LinkedIn API needs OAuth & multi-step upload. keep minimal example.
        // Return failure if not configured.
        if (!env('LINKEDIN_TOKEN') || !env('LINKEDIN_URN')) {
            return ['ok' => false, 'error' => 'LinkedIn not configured'];
        }

        // Implement proper LinkedIn upload steps when ready.
        return ['ok' => false, 'error' => 'Not implemented'];
    }

    protected function ensureUrl($pathOrUrl)
    {
        if (filter_var($pathOrUrl, FILTER_VALIDATE_URL)) return $pathOrUrl;
        // assume storage path, convert to asset url
        return config('app.url') . Storage::url(str_replace('/storage/','',$pathOrUrl));
    }
}
