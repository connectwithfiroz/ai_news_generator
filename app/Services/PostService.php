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
    // use Illuminate\Support\Facades\Http;

    public function publishFacebook($record)
    {
        $summarize_title = $record->rewritten_title
            ?? ($record->summarize_response_json['title'] ?? '');

        $summarize_description = $record->rewritten_description
            ?? ($record->summarize_response_json['description'] ?? '');

        $source = $record->response['url']
            ? "\nSource URL - " . $record->response['url']
            : '';

        $caption = $summarize_title . "\n\n" . $summarize_description . $source;

        // Image
        $imageUrl = $record->local_image_path
            ? asset('storage/news_images/' . $record->local_image_path)
            : $record->response['image'];

        $pageId = config('services.facebook.page_id');
        $pageToken = config('services.facebook.page_token');

        \Log::info($caption);

        try {
            $response = Http::retry(3, 500)
                ->timeout(20)
                ->post("https://graph.facebook.com/v24.0/{$pageId}/photos", [
                    'access_token' => $pageToken,
                    'url' => $imageUrl,
                    'caption' => $caption,
                ]);

            \Log::info("facebook- ", json_encode($response));
            if ($response->successful()) {
                //log
                return ['ok' => true, 'data' => $response->json()];
            }

            return ['ok' => false, 'error' => $response->json()];

        } catch (\Exception $e) {
            return ['ok' => false, 'error' => $e->getMessage()];
        }
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
