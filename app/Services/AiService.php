<?php

namespace App\Services;

use OpenAI;

class AiService
{
    protected $client;
    private $openaiKey;
    public function __construct()
    {
        $openaiKey = config('services.openai_key.key');
        $this->client = OpenAI::client(apiKey: $openaiKey);
    }

    public function summarizeHindi(string $prompt): string
    {
        $res = $this->client->chat()->create([
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
            'max_tokens' => 200
        ]);

        return trim($res['choices'][0]['message']['content'] ?? '');
    }
}
