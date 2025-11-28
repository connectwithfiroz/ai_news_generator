<?php

namespace App\Services;

use Gemini\Laravel\Facades\Gemini;

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

    public function summarizeAndSaveInshort($news)
    {
        $title = $news->response['title'] ?? '';
        $description = $news->response['description'] ?? '';

        if (!$title && !$description) {
            return;
        }

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

        // Call Gemini
        $response = Gemini::generativeModel('gemini-2.5-flash')
            ->generateContent($prompt);

        // Extract text output from Gemini (JSON string)
        $output = $response->text();
        //log $output for debugging
        \Log::info('Gemini rewrite output: ' . $output);

        $data = json_decode($output, true);

        $news->rewritten_title = $data['title'] ?? null;
        $news->rewritten_description = $data['description'] ?? null;
        $news->summarize_response = $output;
        $news->save();
    }


}
