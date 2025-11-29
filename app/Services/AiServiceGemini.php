<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;
use App\Services\TokenService;
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
        $string = strip_tags($string);

        // Remove code fences like ```json or ```
        $string = preg_replace('/```(json)?/i', '', $string);
        $string = preg_replace('/```/', '', $string);

        // Trim whitespace
        return trim($string);
    }

    public function summarizeAndSaveInshortHindi($news)
    {
        $test_data = '```json
{
  "title": "Chief Minister Nitish Kumar has disbursed ₹10,000 individually to the accounts of 10 lakh women across Bihar.",
  "description": "On Friday, Bihar\'s Chief Minister Nitish Kumar oversaw the transfer of ₹10,000 each into the bank accounts of 10 lakh female beneficiaries. This financial aid was provided as part of the \'Mukhyamantri Mahila Rojgar Yojana\'. The Chief Minister later posted on X, explaining that the core objective of the scheme is to offer monetary support to one woman from every family throughout the state, enabling them to initiate an employment venture of their own preference."
}
```';
        $output = $this->convertToJsonSafeString($test_data);
        $output = json_encode($output);
        dd($output);
        return;
       
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
        // Call Gemini
        $response = Gemini::generativeModel('gemini-2.5-flash')
            ->generateContent($prompt);
        // Extract text output from Gemini (JSON string)
        $output = $response->text();
        // ------------ CLEAN AND PARSE JSON OUTPUT ------------ //
        $output = $this->convertToJsonSafeString($output);
        $data = json_decode($output, true);
        //------------ SAVE TO NEWS MODEL ------------ //

        $news->rewritten_title = $data['title'] ?? null;
        $news->rewritten_description = $data['description'] ?? null;
        $news->summarize_response = $output;
        // $news->summarize_response_json = $output;
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
