<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class GeminiQuestionGenerator
{
    public function generate(string $prompt): string
    {
        $response = Http::withHeaders([
            'x-goog-api-key' => config('services.gemini.api_key'),
        ])
            ->timeout(60)
            ->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.8-flash:generateContent',
                [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],

                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                    ],

                ]
            );

        if ($response->failed()) {
            throw new RuntimeException(
                'Gemini API request failed: ' . $response->body()
            );
        }

        $text = $response->json('candidates.0.content.parts.0.text');

        if (! is_string($text) || trim($text) === '') {
            throw new RuntimeException('Gemini returned an empty response.');
        }

        return $text;
    }
}