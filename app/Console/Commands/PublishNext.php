<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsItem;
use App\Services\PostService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class PublishNext extends Command
{
    protected $signature = 'news:publish {--platform=all}';
    protected $description = 'Publish next processed but not fully published news item';

    public function handle(PostService $post)
    {
        $item = NewsItem::whereNotNull('processed_at')->where(function($q){
            $q->whereNull('published_at_facebook')
              ->orWhereNull('published_at_whatsapp')
              ->orWhereNull('published_at_linkedin');
        })->orderBy('processed_at')->first();

        if (!$item) {
            $this->info('No processed item to publish.');
            return 0;
        }

        $caption = $item->summarize_response ?? data_get($item->response,'title','');

        // Facebook
        if (is_null($item->published_at_facebook)) {
            $res = $post->publishFacebook($caption, $item->local_image_path ?? $item->original_image_url);
            if ($res['ok'] ?? false) {
                $item->published_at_facebook = Carbon::now();
                $item->published_url_facebook = $res['url'] ?? null;
                $item->save();
                $this->info("Published to Facebook: {$item->id}");
            } else {
                Log::warning('FB publish failed', [$res]);
            }
        }

        // WhatsApp
        if (is_null($item->published_at_whatsapp)) {
            $res = $post->publishWhatsApp($caption, $item->local_image_path ?? $item->original_image_url);
            if ($res['ok'] ?? false) {
                $item->published_at_whatsapp = Carbon::now();
                $item->published_url_whatsapp = $res['url'] ?? null;
                $item->save();
                $this->info("Published to WhatsApp: {$item->id}");
            } else {
                Log::warning('WA publish failed', [$res]);
            }
        }

        // LinkedIn
        if (is_null($item->published_at_linkedin)) {
            $res = $post->publishLinkedin($caption, $item->local_image_path ?? $item->original_image_url);
            if ($res['ok'] ?? false) {
                $item->published_at_linkedin = Carbon::now();
                $item->published_url_linkedin = $res['url'] ?? null;
                $item->save();
                $this->info("Published to LinkedIn: {$item->id}");
            } else {
                Log::warning('LinkedIn publish failed', [$res]);
            }
        }

        // mark fully published
        if ($item->published_at_facebook && $item->published_at_whatsapp && $item->published_at_linkedin) {
            $item->is_published = true;
            $item->save();
        }

        return 0;
    }
}
