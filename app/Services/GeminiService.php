<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    public function analyzeImage(string $imagePath, string $prompt, int $maxRetries = 3): array
    {
        $apiKey = env('GEMINI_API_KEY');

        if (!$apiKey) {
            return ['valid' => false, 'count' => 0, 'reason' => 'API key belum diset'];
        }

        $imageData = base64_encode(file_get_contents($imagePath));
        $mime = mime_content_type($imagePath) ?: 'image/jpeg';

        $attempt = 0;

        while ($attempt < $maxRetries) {
            $attempt++;

            try {
                $response = Http::timeout(60)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent?key={$apiKey}",
                    [
                        'contents' => [[
                            'parts' => [
                                ['text' => $prompt],
                                ['inline_data' => [
                                    'mime_type' => $mime,
                                    'data' => $imageData,
                                ]],
                            ],
                        ]],
                        'generationConfig' => [
                            'response_mime_type' => 'application/json',
                        ],
                    ]
                );

                // Kalau 503, tunggu & coba lagi
                if ($response->status() === 503) {
                    Log::warning("Gemini 503, retry ke-{$attempt}");

                    if ($attempt < $maxRetries) {
                        sleep(2);
                        continue;
                    }

                    return ['valid' => false, 'count' => 0, 'reason' => 'Server AI sedang sibuk. Coba lagi.'];
                }

                if (!$response->successful()) {
                    Log::error('Gemini error', ['body' => $response->body()]);
                    return ['valid' => false, 'count' => 0, 'reason' => 'Gagal hubungi AI'];
                }

                $text = $response->json('candidates.0.content.parts.0.text');
                $parsed = json_decode($text, true);

                if (!is_array($parsed)) {
                    return ['valid' => false, 'count' => 0, 'reason' => 'Respons AI tidak valid'];
                }

                return [
                    'valid'  => (bool) ($parsed['valid'] ?? false),
                    'count'  => (int) ($parsed['count'] ?? 0),
                    'reason' => (string) ($parsed['reason'] ?? ''),
                ];
            } catch (\Throwable $e) {
                Log::error('Gemini exception', ['message' => $e->getMessage()]);

                if ($attempt < $maxRetries) {
                    sleep(2);
                    continue;
                }

                return ['valid' => false, 'count' => 0, 'reason' => 'AI timeout / error'];
            }
        }

        return ['valid' => false, 'count' => 0, 'reason' => 'AI gagal setelah beberapa percobaan'];
    }
}
