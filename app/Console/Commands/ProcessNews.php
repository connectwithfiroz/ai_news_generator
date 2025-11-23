<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsItem;
use App\Services\AiService;
use App\Services\ImageOverlayService;
use Carbon\Carbon;

class ProcessNews extends Command
{
    protected $signature = 'news:process {--limit=5}';
    protected $description = 'Summarize & overlay text on image for pending news items';

    public function handle(AiService $ai, ImageOverlayService $imgService)
    {
        $limit = (int) $this->option('limit');
        $items = NewsItem::whereNull('summarize_response')->limit($limit)->get();

        foreach ($items as $item) {
            $title = data_get($item->response, 'title', '');
            $description = data_get($item->response, 'description', '');
            $prompt = "Simple Hindi summary in 1 short paragraph for low-education audience. TITLE: {$title} DESCRIPTION: {$description}";

            $summary = $ai->summarizeHindi($prompt);
            $item->summarize_response = $summary;
            $item->save();

            // overlay title/short caption over original image (if exists)
            if ($item->original_image_url) {
                $local = $imgService->overlayText($item->original_image_url, $title); // returns storage path
                $item->local_image_path = $local;
            }
            $item->processed_at = Carbon::now();
            $item->save();

            $this->info("Processed item {$item->id}");
        }

        return 0;
    }
}
