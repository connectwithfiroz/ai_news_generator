<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
use Log;
use Spatie\Browsershot\Browsershot;
use App\Models\News;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class NewsController extends Controller
{
    // ---------- SUMMARIZE IN HINDI USING GEMINI API >>>---------- //
    public function summarizeHindi(string $prompt): string
    {
        $response = Gemini::generativeModel('gemini-2.5-flash')
            ->generateContent($prompt);

        return trim($response->text());
    }
    public function summarizeAndSave($news)
    {
        $title = $news->response['title'] ?? '';
        $description = $news->response['description'] ?? '';
        if (!$title && !$description) {
            //update column is_valide = false
            // $news->is_valid = false;
            // $news->save();
            return;
        }
        $prompt = <<<PROMPT
            Write a simple Hindi summary in one short paragraph.
            Target audience: low-education rural readers.

            Title: "$title"
            Description: "$description"

            Keep it very simple, clear, and easy to understand.
            PROMPT;
        $summarize_response = $this->summarizeHindi($prompt);
        $news->summarize_response = $summarize_response;
        $news->save();
    }
    // ---------- SUMMARIZE IN HINDI USING GEMINI API <<<---------- //

    // ---------- GENERATE IMAGE USING BROWSERSHOT >>>---------- //
    public function generateImageWithBrowsershot(Request $request, $news_id)
    {
        $flag = $request->get('flag', null);
        $news = News::find($news_id);
        $response = $news->response ?? [];
        $image_url = $news->original_image_url ?? $news->response['image'] ?? '';


        if (empty($image_url)) {
            throw new \Exception('No image URL found for generating image.');
        }
        // $description = $news->summarize_response ?? $response['description'] ?? '';
        $category = $response['category'] ?? '';
        $source = $response['source'] ?? '';
        $title = $news->rewritten_title ?? $news->summarize_response_json['title'] ?? $news['title'] ?? '';
        $description = $news->rewritten_description ?? $news->summarize_response_json['description'] ?? '';
        //if title or description is empty then return with error to filament 
        if (empty($title) || empty($description)) {
            if (empty($title) && empty($description)) {
                $message = 'Title and Description are missing for generating image.';
            } elseif (empty($title)) {
                $message = 'Title is missing for generating image.';
            } else {
                $message = 'Description is missing for generating image.';
            }

            if (empty($flag)) {
                return response()->json([
                    'status' => false,
                    'message' => $message
                ], 422);
            } else if ($flag == 1) {
                return redirect()->route('filament.admin.resources.news.index')->with('error', $message);
            }
        }
        // dd($news->summarize_response_json['description']);

        $fileName = 'social_' . time() . '.png';
        $filePath = storage_path('app/public/news_images/' . $fileName);

        //USE COMPACT SYNTAX IF KEY AND VARIABLE NAME ARE SAME
        $html = view('news.social_card', data:
            compact('image_url', 'title', 'description', 'category', 'source', 'flag'))->render();
        // return $html;
        // Generate image
        Browsershot::html($html)
            // 🚨 CHANGE 1: Use a vertical, mobile-friendly window size (e.g., 900px wide x 1200px high - a 3:4 ratio)
            ->windowSize(900, 1200)
            // Keep deviceScaleFactor(2) for a high-resolution output (1800x2400 actual pixels)
            ->deviceScaleFactor(2)
            ->fullPage(false) // ❗ Use false when generating fixed-height cards
            ->save($filePath);

        //if flag is empty then return json else return to route
        if (empty($flag)) {
            return response()->json([
                'status' => true,
                'image_url' => asset('storage/news_images/' . $fileName)
            ]);
        } else if ($flag == 1) {
            $news->local_image_path = 'news_images/' . $fileName;
            $news->save();
            //redirect to filament resource
            return redirect()->route('filament.admin.resources.news.index')->with('success', 'Image generated successfully.');
        }
    }
    // ---------- GENERATE IMAGE USING BROWSERSHOT <<<---------- //


    // FETCH INSHORT NEWS USING API >>>--------------//
    public function fetchInshortNewsAndStore($params = [])
    {
        try {
            $category = $params['category'] ?? 'top_stories';

            switch ($category) {
                case 'technology':
                    $url = "https://inshorts.com/api/hi/search/trending_topics/technology?page=1&type=NEWS_CATEGORY";
                    break;

                case 'hatke':
                    $url = "https://inshorts.com/api/hi/search/trending_topics/hatke?page=2&type=NEWS_CATEGORY";
                    break;
                case 'top_stories':
                    $url = "https://inshorts.com/api/hi/news?category=top_stories&max_limit=50&include_card_data=true";
                    break;

                default:
                    // Default: top stories
                    $url = "https://inshorts.com/api/hi/news?category=top_stories&max_limit=50&include_card_data=true";
                    break;
            }


            // 1. Fetch API response
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->get($url);

            if (!$response->successful()) {
                return response()->json(['status' => false, 'message' => 'API failed'], 500);
            }

            $data = $response->json();

            // 2. Parse news_list
            if (!isset($data['data']['news_list'])) {
                return response()->json(['status' => false, 'message' => 'Invalid JSON'], 422);
            }

            foreach ($data['data']['news_list'] as $item) {

                $news = $item['news_obj'] ?? null;
                if (!$news)
                    continue;

                // Extract essential fields
                $title = $news['title'] ?? null;
                $content = $news['content'] ?? null;
                $img = $news['image_url'] ?? null;

                // Prevent duplicate insert by hash_id
                $exists = News::where('response->unique_id', $news['hash_id'])
                    ->exists();

                if ($exists) {
                    Log::info('exist');
                    continue;
                }

                //mapped fields
                // Map NewsAPI fields to Mediastack-style fields
                $mapped = [
                    "unique_id" => $news['hash_id'] ?? null,
                    "author" => $news['author_name'] ?? null,
                    "title" => $news['title'] ?? null,
                    "description" => $news['content'] ?? null,
                    "url" => $news['source_url'] ?? null,
                    "source" => $news['source_name'] ?? null,
                    "image" => $news['image_url'] ?? null,
                    "category" => $news['category_names'] ? implode(',', $news['category_names']) : null,
                    "language" => "en",
                    "country" => $news['country_code'] ?? null,
                    "published_at" => $news['publishedAt'] ?? null,
                ];

                // 3. Insert into DB
                News::create([
                    'requested_at' => Carbon::now(),
                    'response' => $mapped,               // store raw item
                    'summarize_response' => null,
                    'local_image_path' => null,
                    'original_image_url' => $img,
                    'title' => $title,
                    'source' => 'INSHORTS',
                    'batch_no' => 1,
                ]);
            }

            return response()->json(['status' => true, 'message' => 'News Stored Successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // FETCH INSHORT NEWS USING API <<<--------------//
}
