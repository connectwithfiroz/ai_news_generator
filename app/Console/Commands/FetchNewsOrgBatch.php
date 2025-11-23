<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsItem;
use Carbon\Carbon;

class FetchNewsOrgBatch extends Command
{
    protected $signature = 'news_org:fetch';
    protected $description = 'Fetch 10 news items from NewsAPI.org and store if no pending batch exists';

    public function handle()
    {
        // If any unprocessed items exist → skip fetching
        $pendingExists =
            NewsItem::where('is_published', false)
                ->whereNull('summarize_response')
                ->exists()
            ||
            NewsItem::where('is_published', false)
                ->whereNotNull('summarize_response')
                ->whereNull('processed_at')
                ->exists();

        // if ($pendingExists) {
        //     $this->info('Pending batch exists — skipping new fetch.');
        //     return 0;
        // }

        // NewsAPI Key
        $apiKey = config('services.newsapi_org.key') ?: env('NEWS_API_ORG_KEY');

        // Build API request
        $url = 'https://newsapi.org/v2/top-headlines';

        $response = Http::retry(2, 200)->get($url, [
            'country' => 'in',       // India
            // 'language' => 'en',
            'pageSize' => 10,
            'category' => 'entertainment', // supported here
            'apiKey' => $apiKey,
        ]);
        //I need url of above request for debugging
        \Log::info('NewsAPI.org request', [
            'url' => $response->effectiveUri(),
        ]);

        // log response for debugging
        \Log::info('NewsAPI.org response', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        if (!$response->ok()) {
            $this->error('NewsAPI request failed.');
            \Log::error('NewsAPI error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return 1;
        }

        // Save raw response for debugging
        \Storage::disk('local')->put('newsapiorg_response.json', $response->body());

        $articles = $response->json()['articles'] ?? [];

        $count = 0;
        $maxBatchNo = NewsItem::max('batch_no');
        $batch_no = ($maxBatchNo ?? 0) + 1;

        foreach ($articles as $item) {

            if ($count >= 10)
                break;

            // Map NewsAPI fields to Mediastack-style fields
            $mapped = [
                "author" => $item['author'] ?? null,
                "title" => $item['title'] ?? null,
                "description" => $item['description'] ?? null,
                "url" => $item['url'] ?? null,
                "source" => $item['source']['name'] ?? null,
                "image" => $item['urlToImage'] ?? null,
                "category" => "general",
                "language" => "en",
                "country" => "in",
                "published_at" => $item['publishedAt'] ?? null,
            ];

            // Skip duplicates
            if (NewsItem::where('response->url', $mapped['url'])->exists()) {
                continue;
            }

            NewsItem::create([
                'requested_at' => Carbon::now(),
                'response' => $mapped,
                'original_image_url' => $mapped['image'] ?? null,
                'batch_no' => $batch_no,
            ]);

            $count++;
        }

        $this->info("Inserted {$count} news items.");
        return 0;
    }
}
