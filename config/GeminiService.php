<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');

        $this->model = config(
            'services.gemini.model',
            'gemini-2.5-flash'
        );

        $this->baseUrl = rtrim(
            config(
                'services.gemini.base_url',
                'https://generativelanguage.googleapis.com/v1beta'
            ),
            '/'
        );
    }

    /**
     * =========================
     * CHAT TEXT
     * =========================
     */
    public function chat(
        string $message,
        array $history = [],
        string $systemPrompt = ''
    ): string {
        $payload = [
            'contents' => array_merge(
                $history,
                [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'text' => $message,
                            ],
                        ],
                    ],
                ]
            ),
        ];

        if ($systemPrompt !== '') {
            $payload['systemInstruction'] = [
                'parts' => [
                    [
                        'text' => $systemPrompt,
                    ],
                ],
            ];
        }

        $data = $this->request($payload, 'chat');

        return $this->extractText($data);
    }

    /**
     * =========================
     * ANALYZE IMAGE
     * =========================
     */
    public function analyzeImage(
        string $imagePath,
        string $prompt = ''
    ): string {
        if (!file_exists($imagePath)) {
            throw new RuntimeException(
                'File gambar tidak ditemukan.'
            );
        }

        $mimeType = mime_content_type($imagePath);

        $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/heic',
            'image/heif',
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new RuntimeException(
                'Format gambar tidak didukung: ' . $mimeType
            );
        }

        $imageData = base64_encode(
            file_get_contents($imagePath)
        );

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
                        [
                            'text' => $prompt,
                        ],
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

        $data = $this->request(
            $payload,
            'analyzeImage'
        );

        return $this->extractText($data);
    }

    /**
     * =========================
     * REQUEST GEMINI
     * =========================
     */
    protected function request(
        array $payload,
        string $context = 'request'
    ): array {
        if (empty($this->apiKey)) {
            throw new RuntimeException(
                'GEMINI_API_KEY belum diisi di file .env'
            );
        }

        $url = "{$this->baseUrl}/models/{$this->model}:generateContent";

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

                            // Jangan retry 429.
                            // Kalau quota habis, retry tidak akan membantu.
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

            /*
             * =========================
             * 429
             * =========================
             */
            if ($response->status() === 429) {
                Log::warning(
                    "Gemini 429 ({$context})",
                    [
                        'status' => $response->status(),
                        'model' => $this->model,
                        'body' => $response->body(),
                    ]
                );

                $message = $response->json(
                    'error.message'
                );

                throw new RuntimeException(
                    $message
                    ?: 'Quota Gemini habis atau rate limit tercapai.'
                );
            }

            /*
             * =========================
             * ERROR LAIN
             * =========================
             */
            if ($response->failed()) {
                Log::error(
                    "Gemini API error ({$context})",
                    [
                        'status' => $response->status(),
                        'model' => $this->model,
                        'body' => $response->body(),
                    ]
                );

                $message = $response->json(
                    'error.message'
                );

                throw new RuntimeException(
                    $message
                    ?: 'Gemini API error: ' .
                    $response->status()
                );
            }

            return $response->json() ?? [];

        } catch (RuntimeException $e) {
            throw $e;

        } catch (\Throwable $e) {
            Log::error(
                "GeminiService exception ({$context})",
                [
                    'message' => $e->getMessage(),
                    'model' => $this->model,
                ]
            );

            // Jangan sembunyikan error asli
            throw new RuntimeException(
                $e->getMessage()
            );
        }
    }

    /**
     * =========================
     * AMBIL TEXT DARI RESPONSE
     * =========================
     */
    protected function extractText(array $data): string
    {
        $text = $data['candidates'][0]['content']['parts'][0]['text']
            ?? null;

        if (!$text) {
            Log::warning(
                'Gemini response tidak memiliki text',
                [
                    'response' => $data,
                ]
            );

            throw new RuntimeException(
                'Gemini tidak memberikan jawaban.'
            );
        }

        return trim($text);
    }
}