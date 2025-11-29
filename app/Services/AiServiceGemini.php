<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use App\Services\TokenService;
use App\Models\News;
class AiServiceGemini
{
    private function getImageBase64($response): ?string
    {
        // Gemini response may contain multiple candidates
        $candidates = $response->getCandidates() ?? [];

        foreach ($candidates as $candidate) {
            // Each candidate may have parts (text/image)
            $parts = $candidate->getContent()->getParts() ?? [];

            foreach ($parts as $part) {
                // Check if part has inline image data
                if (method_exists($part, 'hasInlineData') && $part->hasInlineData()) {
                    $inline = $part->getInlineData();
                    if (method_exists($inline, 'getData')) {
                        return $inline->getData(); // base64 string
                    }
                }
            }
        }

        return null;
    }


    public function generateImage(string $topic): ?string
    {
        try {
            $prompt = "Create a visually appealing social media image for a news post about: {$topic}. 
                   Make it mobile-friendly (Instagram/Facebook) with clear readable text overlay.";

            $response = Gemini::generativeModel('gemini-2.5-flash-image')
                ->generateContent($prompt);

            // Extract base64 from response
            $imageBase64 = $this->getImageBase64($response);

            if (!$imageBase64) {
                return null;
            }

            $filename = 'news_' . time() . '_' . \Illuminate\Support\Str::random(5) . '.png';
            $path = storage_path('app/public/news/' . $filename);

            file_put_contents($path, base64_decode($imageBase64));

            return asset('storage/news/' . $filename);

        } catch (\Exception $e) {
            \Log::error("Gemini image generation failed: " . $e->getMessage());
            return null;
        }
    }


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
    private function convertToJsonSafeString($string): string
    {
        // Remove HTML tags
        // $string = strip_tags($string);

        // Remove code fences like ```json or ```
        $string = preg_replace('/```(json)?/i', '', $string);
        $string = preg_replace('/```/', '', $string);

        // Trim whitespace
        return trim($string);
    }

    public function summarizeAndSaveInshortHindi($news)
    {
//         $test = '```json
// {
//   "title": "प्रधानमंत्री नरेंद्र मोदी ने दुनिया की सबसे ऊंची 77 फीट की श्रीराम प्रतिमा का अनावरण किया",
//   "description": "शुक्रवार को प्रधानमंत्री नरेंद्र मोदी ने गोवा में स्थित ऐतिहासिक श्री संस्थान गोकर्ण जीवोत्तम मठ में भगवान श्रीराम की उस प्रतिमा का अनावरण किया, जो विश्व में सबसे ऊंची है। इस प्रतिमा की ऊंचाई 77 फीट है, और इसे प्रसिद्ध मूर्तिकार राम सुतार ने कांस्य धातु से निर्मित किया है। प्रधानमंत्री ने इस मठ के 550वें वार्षिक समारोह में भी हिस्सा लिया।"
// }
// ```';
//         $test_response = $this->convertToJsonSafeString($test);
//         dd($test_response);

        $title = $news->response['title'] ?? '';
        $description = $news->response['description'] ?? '';

        if (!$title && !$description) {
            return;
        }
        $title = trim($title);
        $description = trim($description);

        $prompt = <<<PROMPT
            Rewrite the given Hindi title and description into completely fresh, original sentences
            while keeping the same meaning. Do NOT summarize. Just rephrase to avoid copyright issues.

            Rules:
            - Use simple Hindi suitable for low-education rural readers.
            - Make sure the wording is different from the original.
            - Keep the information accurate.
            - Output ONLY valid JSON with two keys: "title" and "description".

            Input:
            Title: "$title"
            Description: "$description"
            PROMPT;

        //for now create a test prompt with less token usage
        // trim the title and description

        $prompt = <<<PROMPT
            Rewrite the following Hindi title and description into fresh, original sentences
            while keeping the same meaning. Do NOT summarize. Just rephrase.        
            Give the answer only in this JSON format: {"title": "...", "description": "..."}
            Title: "$title"
            Description: "$description"
        PROMPT;
        $prompt = <<<PROMPT
        नीचे दिए गए हिंदी शीर्षक और विवरण को बिना अर्थ बदले सरल और नए वाक्यों में दोबारा लिखें। केवल हिंदी में लिखें। सारांश न बनाएं। आउटपुट JSON में दें:
        {"title":"...", "description":"..."}

        शीर्षक: "$title"
        विवरण: "$description"
        PROMPT;

        // Call Gemini
        $response = Gemini::generativeModel('gemini-2.5-flash')
            ->generateContent($prompt);
        // Extract text output from Gemini (JSON string)
        $output = $response->text();
        $news->summarize_response = $output;
        $output = $this->convertToJsonSafeString($output);
        // ------------ CLEAN AND PARSE JSON OUTPUT ------------ //
        // $output = $this->convertToJsonSafeString($output);
        $news->summarize_response_json = $output;
        
        //------------ SAVE TO NEWS MODEL ------------ //

        $data = json_decode($output, true);
        $news->rewritten_title = $data['title'] ?? null;
        $news->rewritten_description = $data['description'] ?? null;
        $news->summarize_response_json = $output;
        $news->save();


        // Check usage array
        $usageMetadata = $response->usageMetadata;

        if ($usageMetadata) {
            // Access the specific token counts from the UsageMetadata object
            $promptTokenCount = $usageMetadata->promptTokenCount;
            $candidatesTokenCount = $usageMetadata->candidatesTokenCount;
            $totalTokens = $usageMetadata->totalTokenCount;

        } else {
            // Fallback if usage metadata is not available
            $promptTokenCount = 0;
            $candidatesTokenCount = 0;
            $totalTokens = 0;
        }
        $tokenService = new TokenService();
        $tokenService->deductTokens(
            source: "gemini",
            usedTokens: $totalTokens,
            meta: ['prompt' => $prompt]
        );

    }


}
