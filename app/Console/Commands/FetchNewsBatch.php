<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\NewsItem;
use Carbon\Carbon;


class FetchNewsBatch extends Command
{
    protected $signature = 'news:fetch';
    protected $description = 'Fetch 10 news items from mediastack and store if no pending batch exists';

    public function handle()
    {
        // if any un-published or un-processed items exist, skip fetch
        $pendingExists = NewsItem::where('is_published', false)->whereNull('summarize_response')->exists()
            || NewsItem::where('is_published', false)->whereNotNull('summarize_response')->whereNull('processed_at')->exists();

        if ($pendingExists) {
            $this->info('Pending batch exists — skipping new fetch.');
            return 0;
        }

        $key = config('services.mediastack.key') ?: env('MEDIASTACK_KEY');
        $url = 'http://api.mediastack.com/v1/news';
        $response = Http::retry(2, 100)->get($url, [
            'access_key' => $key,
            'countries' => 'in',
            'languages' => 'hi,en',
            'limit' => 20,
            'sort' => 'published_desc'
        ]);

        if (!$response->ok()) {
            $this->error('Mediastack request failed.');
            //log error details
            \Log::error('Mediastack API error', ['status' => $response->status(), 'body' => $response->body()]);
            return 1;
        }
        //write response into a file for debugging
        \Storage::disk('local')->put('mediastack_response.json', $response->body());

        $data = $response->json()['data'] ?? [];

        $count = 0;
        //create batch_no as max existing +1
        $maxBatchNo = NewsItem::max('batch_no');
        $batch_no = $maxBatchNo + 1;
        foreach ($data as $item) {
            if ($count >= 10) break;
            // simple dedupe: skip if url already present
            if (NewsItem::where('response->url', $item['url'])->exists()) continue;

            NewsItem::create([
                'requested_at' => Carbon::now(),
                'response' => $item,
                'original_image_url' => $item['image'] ?? null,
                'batch_no' => $batch_no,

            ]);
            $count++;
        }

        $this->info("Inserted {$count} news items.");
        return 0;
    }
}
