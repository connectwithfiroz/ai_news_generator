<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\NewsItem;
use App\Services\AiServiceGemini;
use App\Services\ImageOverlayService;
use Carbon\Carbon;

class ProcessNews extends Command
{
    protected $signature = 'news:process {--limit=5}';
    protected $description = 'Summarize & overlay text on image for pending news items';

    public function handle(AiServiceGemini $ai, ImageOverlayService $imgService)
    {
        $limit = (int) $this->option('limit');
        $items = NewsItem::whereNull('summarize_response')
            ->limit($limit)
            ->get();

        foreach ($items as $item) {

            $title = data_get($item->response, 'title', '');
            $description = data_get($item->response, 'description', '');

            // Skip useless entries
            if (!$title && !$description) {
                continue;
            }

            // Prompt (no unwanted indentation inside heredoc)
            $prompt = <<<PROMPT
Write a simple Hindi summary in one short paragraph.
Target audience: low-education rural readers.

Title: "$title"
Description: "$description"

Keep it very simple, clear, and easy to understand.
PROMPT;

            // AI summary with fallback
            try {
                $summary = $ai->summarizeHindi($prompt);
            } catch (\Exception $e) {
                \Log::error('AI summarization error', [
                    'item_id' => $item->id,
                    'error' => $e->getMessage()
                ]);
                continue;
            }

            $item->summarize_response = $summary;

            // If image exists, generate local overlay image  
            if ($item->original_image_url) {
                try {
                    $local = $imgService->overlayText($item->original_image_url, $title);
                    $item->local_image_path = $local;
                } catch (\Exception $e) {
                    \Log::error('Image overlay error', [
                        'item_id' => $item->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            $item->processed_at = now();
            $item->save();

            $this->info("Processed item {$item->id}");
        }

        return self::SUCCESS;
    }

}
