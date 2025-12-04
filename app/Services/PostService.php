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
        $pageId = config('services.facebook.page_id');
        $pageToken = config('services.facebook.page_token');

        $summarize_title = $record->rewritten_title
            ?? ($record->summarize_response_json['title'] ?? '');

        $summarize_description = $record->rewritten_description
            ?? ($record->summarize_response_json['description'] ?? '');

        $source = $record->response['url']
            ? "\nSource URL - " . $record->response['url']
            : '';

        $caption = $summarize_title . "\n\n" . $summarize_description . $source;

        // FULL local path
        $localPath = storage_path('app/public/' . $record->local_image_path);

        // Check file exists
        if (!file_exists($localPath)) {
            $error = "File not found: " . $localPath;
            \Log::error("Facebook Error - {$error}");
            return ['ok' => false, 'error' => $error];
        }

        try {
            // Read the file contents (avoids "resource (closed)" issues)
            $fileContents = file_get_contents($localPath);
            if ($fileContents === false) {
                $error = "Failed to read file: " . $localPath;
                \Log::error("Facebook Error - {$error}");
                return ['ok' => false, 'error' => $error];
            }

            $response = Http::asMultipart()
                ->retry(3, 2000)        // number of retries and wait ms
                ->connectTimeout(30)    // optional: connection timeout
                ->timeout(120)          // increase overall timeout for uploads
                ->attach(
                    'source',
                    $fileContents,
                    basename($localPath)
                )
                ->post("https://graph.facebook.com/v24.0/{$pageId}/photos", [
                    'access_token' => $pageToken,
                    'caption' => $caption,
                ]);

            // Log full HTTP body for debugging if not successful (careful with tokens in logs)
            if (!$response->successful()) {
                \Log::error('Facebook upload failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
                return ['ok' => false, 'error' => 'Facebook API error', 'details' => $response->body()];
            }

            $data = $response->json();

            // Ensure expected key exists before using
            if (!isset($data['post_id']) && !isset($data['id'])) {
                \Log::warning('Facebook response missing post id', ['response' => $data]);
            }

            $record->update([
                'published_at_facebook' => now(),
                // Some endpoints return 'post_id' or 'id' — handle both
                'published_url_facebook' => isset($data['post_id'])
                    ? "https://www.facebook.com/{$data['post_id']}"
                    : (isset($data['id']) ? "https://www.facebook.com/{$data['id']}" : null),
            ]);

            return ['ok' => true, 'data' => $data];

        } catch (\Exception $e) {
            \Log::error("Facebook Error - {$e->getMessage()}", [
                'exception' => $e,
                'file' => $localPath,
            ]);
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
