<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiService
{
    // === Dari punyamu (HEAD) ===
    protected ?string $apiKey;
    protected string $model;
    protected array $fallbackModels;
    protected string $baseUrl;

    // === Dari temenmu (de3b9d1) ===
    private const MODEL = 'gemini-flash-lite-latest';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->model = config(
            'services.gemini.model',
            'gemini-3.6-flash'
        );

        $this->fallbackModels = config(
            'services.gemini.fallback',
            ['gemini-3.7-flash', 'gemini-3.5-flash', 'gemini-flash-latest']
        );

        $this->baseUrl = rtrim(
            config(
                'services.gemini.base_url',
                'https://generativelanguage.googleapis.com/v1beta'
            ),
            '/'
        );
    }

    // === Dari punyamu ===
    public function chat(
        string $message,
        array $history = [],
        string $systemPrompt = '',
        ?UploadedFile $image = null
    ): string {
        $parts = [['text' => $message]];

        if ($image) {
            $mimeType = $image->getMimeType();
            $allowed = [
                'image/jpeg',
                'image/png',
                'image/webp',
                'image/heic',
                'image/heif',
            ];

            if (!in_array($mimeType, $allowed, true)) {
                throw new RuntimeException(
                    'Format gambar tidak didukung: ' . $mimeType
                );
            }

            $parts[] = [
                'inline_data' => [
                    'mime_type' => $mimeType,
                    'data' => base64_encode(
                        file_get_contents($image->getRealPath())
                    ),
                ],
            ];
        }

        $payload = [
            'contents' => array_merge(
                $history,
                [['role' => 'user', 'parts' => $parts]]
            ),
        ];

        if ($systemPrompt !== '') {
            $payload['systemInstruction'] = [
                'parts' => [['text' => $systemPrompt]],
            ];
        }

        $data = $this->requestWithFallback($payload, 'chat');

        return $this->extractText($data);
    }

    // === GABUNGAN: struktur punyamu + ide retry & pesan dari temenmu ===
    public function analyzeImage(
        string $imagePath,
        string $prompt = '',
        int $maxRetries = 3
    ): array {
        if (!file_exists($imagePath)) {
            return [
                'valid'  => false,
                'count'  => 0,
                'reason' => 'File gambar tidak ditemukan',
            ];
        }

        $mime = mime_content_type($imagePath) ?: 'image/jpeg';
        $imageData = base64_encode(file_get_contents($imagePath));

        if ($prompt === '') {
            $prompt = "Analisis gambar sampah ini.\n\n"
                . "Tentukan:\n"
                . "1. Jenis sampah\n"
                . "2. Nama benda\n"
                . "3. Materialnya\n"
                . "4. Apakah termasuk sampah yang dapat didaur ulang\n\n"
                . "Jawab singkat dalam bahasa Indonesia.\n\n"
                . "Format JSON:\n"
                . "{\"valid\": true, \"count\": 1, \"reason\": \"...\"}";
        }

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mime,
                                'data' => $imageData,
                            ],
                        ],
                    ],
                ],
            ],
            'generationConfig' => [
                'response_mime_type' => 'application/json',
            ],
        ];

        try {
            // requestWithFallback punyamu sudah handle retry (503, 429, timeout)
            $data = $this->requestWithFallback($payload, 'analyzeImage');
        } catch (\Throwable $e) {
            Log::error('analyzeImage gagal', [
                'message' => $e->getMessage(),
            ]);

            return [
                'valid'  => false,
                'count'  => 0,
                'reason' => 'Server AI sedang sibuk. Tunggu 1-2 menit, terus coba lagi ya.',
            ];
        }

        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        $parsed = json_decode($text, true);

        if (!is_array($parsed)) {
            return [
                'valid'  => false,
                'count'  => 0,
                'reason' => 'Respons AI tidak valid',
            ];
        }

        return [
            'valid'  => (bool) ($parsed['valid'] ?? false),
            'count'  => (int) ($parsed['count'] ?? 0),
            'reason' => (string) ($parsed['reason'] ?? ''),
        ];
    }

    // === Dari punyamu ===
    public function extractImageMarkers(string $reply): array
    {
        $images = [];

        if (preg_match_all('/\[GAMBAR:([a-z_]+):([a-zA-Z0-9_\-]+)\]/', $reply, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $m) {
                $images[] = [
                    'tipe' => $m[1],
                    'kode' => $m[2],
                ];
            }
        }

        $cleanText = preg_replace('/\[GAMBAR:[a-z_]+:[a-zA-Z0-9_\-]+\]/', '', $reply);
        $cleanText = preg_replace('/\s{2,}/', ' ', $cleanText);
        $cleanText = trim($cleanText);

        return [
            'text' => $cleanText,
            'images' => $images,
        ];
    }

    // === Dari punyamu ===
    protected function requestWithFallback(array $payload, string $context): array
    {
        $models = array_values(array_unique(array_merge(
            [$this->model],
            $this->fallbackModels
        )));

        $lastError = null;

        foreach ($models as $model) {
            try {
                return $this->request($payload, $model, $context);
            } catch (RuntimeException $e) {
                $lastError = $e;
                $msg = strtolower($e->getMessage());

                if ($this->isModelNotFound($msg)
                    || $this->isOverloaded($msg)
                    || $this->isQuotaExceeded($msg)
                    || str_contains($msg, 'timeout')
                    || str_contains($msg, 'curl error')
                    || str_contains($msg, 'connection')
                    || str_contains($msg, 'resolving')) {
                    Log::warning("Gemini {$model} gagal, coba model berikutnya", [
                        'context' => $context,
                    ]);
                    continue;
                }

                throw $e;
            }
        }

        throw $lastError
            ?? new RuntimeException('Semua model Gemini gagal diakses.');
    }

    // === Dari punyamu ===
    protected function isModelNotFound(string $msg): bool
    {
        return stripos($msg, 'not found') !== false
            || stripos($msg, 'not supported') !== false
            || stripos($msg, 'is not found for api version') !== false
            || stripos($msg, 'no longer available') !== false;
    }

    protected function isOverloaded(string $msg): bool
    {
        return str_contains($msg, '503')
            || str_contains($msg, 'high demand')
            || str_contains($msg, 'overloaded')
            || str_contains($msg, 'service unavailable')
            || str_contains($msg, 'the model is overloaded');
    }

    protected function isQuotaExceeded(string $msg): bool
    {
        return str_contains($msg, '429')
            || str_contains($msg, 'quota')
            || str_contains($msg, 'rate limit')
            || str_contains($msg, 'too many requests');
    }

    protected function request(
        array $payload,
        string $model,
        string $context = 'request'
    ): array {
        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'GEMINI_API_KEY belum diisi di file .env'
            );
        }

        $url = "{$this->baseUrl}/models/{$model}:generateContent";

        try {
            $response = Http::timeout(15)
                ->acceptJson()
                ->withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post($url, $payload);
        } catch (\Throwable $e) {
            Log::error("GeminiService exception ({$context})", [
                'model' => $model,
                'message' => $e->getMessage(),
            ]);

            $msg = strtolower($e->getMessage());

            if (str_contains($msg, 'curl error')
                || str_contains($msg, 'connection')
                || str_contains($msg, 'resolving')
                || str_contains($msg, 'recv failure')) {
                throw new RuntimeException('503 connection reset');
            }

            throw new RuntimeException($e->getMessage());
        }

        $status = $response->status();

        if ($status === 429) {
            Log::warning("Gemini 429 ({$context})", ['model' => $model]);
            throw new RuntimeException('429 quota exceeded');
        }

        if ($status === 503) {
            Log::warning("Gemini 503 ({$context})", ['model' => $model]);
            throw new RuntimeException('503 service unavailable: high demand');
        }

        if ($status === 404) {
            Log::warning("Gemini 404 ({$context})", ['model' => $model]);
            throw new RuntimeException('404 model not found');
        }

        if ($response->failed()) {
            $errorMessage = $response->json('error.message');

            Log::error("Gemini API error ({$context})", [
                'status' => $status,
                'model' => $model,
            ]);

            throw new RuntimeException(
                $errorMessage
                ?: ('Gemini API error: ' . $status)
            );
        }

        return $response->json() ?? [];
    }

    // === Dari punyamu ===
    protected function extractText(array $data): string
    {
        $text = $data['candidates'][0]['content']['parts'][0]['text']
            ?? null;

        if (!$text) {
            Log::warning('Gemini response tidak memiliki text', [
                'response' => $data,
            ]);

            throw new RuntimeException(
                'Gemini tidak memberikan jawaban.'
            );
        }

        return trim($text);
    }
}