<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected array $fallbackModels;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->model = config(
            'services.gemini.model',
            'gemini-3.6-flash'
        );

        $this->fallbackModels = config(
            'services.gemini.fallback',
            ['gemini-2.0-flash']
        );

        $this->baseUrl = rtrim(
            config(
                'services.gemini.base_url',
                'https://generativelanguage.googleapis.com/v1beta'
            ),
            '/'
        );
    }

    /* ==========================================================
     * CHAT (TEXT + GAMBAR)
     * ========================================================== */
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

    /* ==========================================================
     * ANALYZE IMAGE (khusus sampah)
     * ========================================================== */
    public function analyzeImage(
        string $imagePath,
        string $prompt = ''
    ): string {
        if (!file_exists($imagePath)) {
            throw new RuntimeException('File gambar tidak ditemukan.');
        }

        $mimeType = mime_content_type($imagePath);
        $allowed = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/heic',
            'image/heif',
        ];

        if (!in_array($mimeType, $allowed)) {
            throw new RuntimeException(
                'Format gambar tidak didukung: ' . $mimeType
            );
        }

        $imageData = base64_encode(file_get_contents($imagePath));

        if ($prompt === '') {
            $prompt = <<<PROMPT
Analisis gambar sampah ini.

Tentukan:
1. Jenis sampah
2. Nama benda
3. Materialnya
4. Apakah termasuk sampah yang dapat didaur ulang

Jawab singkat dalam bahasa Indonesia.

Format:
Jenis: ...
Benda: ...
Material: ...
Daur ulang: Ya/Tidak
PROMPT;
        }

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data' => $imageData,
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $data = $this->requestWithFallback($payload, 'analyzeImage');

        return $this->extractText($data);
    }

    /* ==========================================================
     * REQUEST + AUTO FALLBACK MODEL
     * ========================================================== */
    protected function requestWithFallback(array $payload, string $context): array
    {
        $models = array_unique(array_merge(
            [$this->model],
            $this->fallbackModels
        ));

        $lastError = null;

        foreach ($models as $model) {
            try {
                return $this->request($payload, $model, $context);
            } catch (RuntimeException $e) {
                $lastError = $e;
                $msg = $e->getMessage();

                // Kalau errornya karena model tidak ada → coba model berikutnya
                $isModelError =
                    stripos($msg, 'not found') !== false ||
                    stripos($msg, 'not supported') !== false ||
                    stripos($msg, 'is not found for API version') !== false;

                if ($isModelError) {
                    Log::warning("Gemini model '{$model}' gagal, coba fallback...", [
                        'error' => $msg,
                    ]);
                    continue;
                }

                // Error lain (quota, API key, dsb) → langsung lempar
                throw $e;
            }
        }

        throw $lastError
            ?? new RuntimeException('Semua model Gemini gagal diakses.');
    }

    /* ==========================================================
     * REQUEST INTI (satu model)
     * ========================================================== */
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
            $response = Http::timeout(60)
                ->acceptJson()
                ->withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->retry(
                    3,
                    2000,
                    function ($exception) {
                        if ($exception instanceof RequestException) {
                            $status = $exception->response?->status();
                            return in_array($status, [
                                408,
                                500,
                                502,
                                503,
                                504,
                            ]);
                        }
                        return false;
                    }
                )
                ->post($url, $payload);

            // 429 quota
            if ($response->status() === 429) {
                Log::warning("Gemini 429 ({$context})", [
                    'model' => $model,
                    'body' => $response->body(),
                ]);

                $message = $response->json('error.message');

                throw new RuntimeException(
                    $message
                    ?: 'Quota Gemini habis atau rate limit tercapai.'
                );
            }

            // Error lain
            if ($response->failed()) {
                Log::error("Gemini API error ({$context})", [
                    'status' => $response->status(),
                    'model' => $model,
                    'body' => $response->body(),
                ]);

                $message = $response->json('error.message');

                throw new RuntimeException(
                    $message
                    ?: 'Gemini API error: ' . $response->status()
                );
            }

            return $response->json() ?? [];

        } catch (RuntimeException $e) {
            throw $e;

        } catch (\Throwable $e) {
            Log::error("GeminiService exception ({$context})", [
                'message' => $e->getMessage(),
                'model' => $model,
            ]);

            throw new RuntimeException($e->getMessage());
        }
    }

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