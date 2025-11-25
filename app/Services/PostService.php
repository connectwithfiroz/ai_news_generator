<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Str;
class PostService
{
    private function ensureUrl($path)
    {
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path; // already a URL
        }

        // convert "news/abc.jpg" → full public URL
        return asset('storage/' . ltrim($path, '/'));
    }
    public function publishFacebook($record)
    {
        $caption = $record->response['title'] . " - " .
            $record->summarize_response . " - " .
            $record->response['url'];

        $imageUrl = $record->local_image_path
            ? asset('storage/' . $record->local_image_path)
            : $record->response['image'];

        $pageId = config('services.facebook.page_id');
    $pageToken = config('services.facebook.page_token');

    $response = Http::retry(3, 800)
        ->timeout(30)
        ->withoutVerifying()   // XAMPP fix only
        ->post("https://graph.facebook.com/{$pageId}/photos", [
            'url' => $imageUrl,
            'caption' => $caption,
            'access_token' => $pageToken
        ]);

        return $response->json() + ['ok' => $response->ok()];
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


}
