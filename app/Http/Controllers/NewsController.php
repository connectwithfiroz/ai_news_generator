<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini\Laravel\Facades\Gemini;
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
        /*
        ✔ You want only query string parameters
        ✔ Safety, clarity, less confusion

        Use $request->get() when:

        ✔ You want to accept input from query OR form POST OR route parameters
        ✔ You don’t care where the value comes from
        */
        $flag = $request->get('flag', null);
        $news = News::find($news_id);
        $response = $news->response ?? [];
        $image_url = $news->original_image_url ?? $news->response['image'] ?? '';


        if (empty($image_url)) {
            throw new \Exception('No image URL found for generating image.');
        }
        // $description = $news->summarize_response ?? $response['description'] ?? '';
        $description = $response['description'] ?? '';
        $category = $response['category'] ?? '';
        $source = $response['source'] ?? '';
        $title = $response['title'] ?? '';

        $fileName = 'social_' . time() . '.png';
        $filePath = storage_path('app/public/news_images/' . $fileName);

        // Render blade
        // $html = view('news.social_card', [
        //     'image_url' => $image_url,
        //     'title' => $title,
        //     'description' => $description,
        //     'category' => $category,
        //     'source' => $source,
        //     'flag' => $flag,
        // ])->render();
        // return $html;
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
            return redirect()->route('filament.admin.resources.news-mediastack-items.index')->with('success', 'Image generated successfully.');
        }
    }
    // ---------- GENERATE IMAGE USING BROWSERSHOT <<<---------- //


    // FETCH INSHORT NEWS USING API >>>--------------//
    public function fetchAndStore()
    {
        try {
            // Request URL (your static or configurable endpoint)
            $url = "https://inshorts.com/api/hi/search/trending_topics/national?page=1&type=NEWS_CATEGORY";

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
                if (!$news) continue;

                // Extract essential fields
                $title   = $news['title'] ?? null;
                $content = $news['content'] ?? null;
                $img     = $news['image_url'] ?? null;

                // Prevent duplicate insert by hash_id
                $exists = News::where('original_image_url', $img)
                            ->where('response->news_obj->hash_id', $news['hash_id'])
                            ->exists();

                if ($exists) continue;

                // 3. Insert into DB
                News::create([
                    'requested_at'        => Carbon::now(),
                    'response'            => $news,               // store raw item
                    'summarize_response'  => null,
                    'local_image_path'    => null,
                    'original_image_url'  => $img,
                    'gemini_api_url'      => null,
                    'published_at_whatsapp' => null,
                    'published_at_facebook' => null,
                    'published_at_linkedin' => null,
                    'published_url_whatsapp' => null,
                    'published_url_facebook' => null,
                    'published_url_linkedin' => null,
                    'is_published'          => false,
                    'processed_at'          => null,
                    'batch_no'              => 1,
                ]);
            }

            return response()->json(['status' => true, 'message' => 'News Stored Successfully']);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    // FETCH INSHORT NEWS USING API <<<--------------//
}
