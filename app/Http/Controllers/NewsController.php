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
    private function circularTemplate($template_files)
    {

        // total count
        $total = count($template_files);

        // get last index from cookie (default -1)
        $lastIndex = request()->cookie('social_template_index', -1);

        // calculate next index
        $nextIndex = ($lastIndex + 1) % $total;

        // pick template
        $selectedTemplate = $template_files[$nextIndex];

        // set cookie for next request (valid for 30 days)
        cookie()->queue('social_template_index', $nextIndex, 60 * 24 * 30);

        return $selectedTemplate;
    }
    public function generateImageWithBrowsershot(Request $request, $news_id)
    {
        $flag = $request->get('flag', null);
        $template = $request->get('template', null);
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
        $template_files = ['news.social_card1', 'news.social_card2', 'news.social_card3', 'news.social_card4'];
        if(!isset($template)){
            //pick reandom any
            $social_template = $template_files[0];
            $social_template = $template_files[1];
            // $social_template = $template_files[2];
            // $social_template = $template_files[array_rand($template_files)];
            // $social_template = $this->circularTemplate($template_files); //selecte 1, 2, 3.. if last start from 1
        }else{
            $social_template = $template_files[$template];
        }
        $html = view($social_template, data:
            compact('image_url', 'title', 'description', 'category', 'source', 'flag'))->render();
        return $html;
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
                    $url = "https://inshorts.com/api/hi/search/trending_topics/technology";
                    break;

                case 'hatke':
                    $url = "https://inshorts.com/api/hi/search/trending_topics/hatke";
                    break;
                case 'top_stories':
                    // $url = "https://inshorts.com/api/hi/news?category=top_stories&max_limit=80&include_card_data=true";
                    $url = "https://inshorts.com/api/hi/news?category=top_stories&include_card_data=true";
                    break;

                default:
                    // Default: top stories
                    $url = "https://inshorts.com/api/hi/news?category=top_stories&include_card_data=true";
                    break;
            }


            // 1. Fetch API response
            $response = Http::withHeaders([
                'Accept' => 'application/json'
            ])->timeout(120)  // increase timeout (in seconds)
                ->get($url);

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
                    continue;
                }

                //mapped fields
                // Map NewsAPI fields to Mediastack-style fields
                $source_url = $news['source_url'] ?? null;
                $clean_source_url = strtok($source_url, '?');
                $mapped = [
                    "unique_id" => $news['hash_id'] ?? null,
                    "author" => $news['author_name'] ?? null,
                    "title" => $news['title'] ?? null,
                    "description" => $news['content'] ?? null,
                    "url" => $clean_source_url,
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
