<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ChatbotController extends Controller
{
    private $googleAiKey;
    private $guzzleClient;

    public function __construct()
    {
        $this->googleAiKey = env('GOOGLE_AI_KEY');
        $this->guzzleClient = new Client([
            'timeout' => 30,
            'connect_timeout' => 10,
        ]);
    }

    /**
     * Send message to Google AI (Gemini) API
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        if (!$this->googleAiKey) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi API belum diatur. Hubungi administrator.',
            ], 500);
        }

        try {
            $prompt = $this->buildPrompt($validated['message']);
            
            $response = $this->guzzleClient->post(
                'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
                [
                    'query' => [
                        'key' => $this->googleAiKey,
                    ],
                    'json' => [
                        'contents' => [
                            [
                                'parts' => [
                                    [
                                        'text' => $prompt,
                                    ]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => 0.7,
                            'topK' => 40,
                            'topP' => 0.95,
                            'maxOutputTokens' => 1024,
                        ],
                        'safetySettings' => [
                            [
                                'category' => 'HARM_CATEGORY_HARASSMENT',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                            ],
                            [
                                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
                            ],
                        ]
                    ]
                ]
            );

            $data = json_decode($response->getBody()->getContents(), true);

            // Extract text from response
            if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                $text = $data['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $text = 'Maaf, saya tidak dapat memproses pertanyaan Anda saat ini.';
            }

            return response()->json([
                'success' => true,
                'message' => $text,
            ]);

        } catch (RequestException $e) {
            $statusCode = $e->getResponse()?->getStatusCode();
            
            if ($statusCode === 401) {
                $message = 'API Key tidak valid atau sudah expired.';
            } elseif ($statusCode === 429) {
                $message = 'Terlalu banyak permintaan. Silakan coba lagi dalam beberapa saat.';
            } elseif ($statusCode === 500) {
                $message = 'Server Google AI sedang mengalami gangguan. Silakan coba lagi nanti.';
            } else {
                $message = 'Terjadi kesalahan API: ' . ($e->getResponse()?->getReasonPhrase() ?? 'Unknown');
            }

            \Log::error('Chatbot API Error', [
                'status' => $statusCode,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Chatbot Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build system prompt for BMKG chatbot
     */
    private function buildPrompt(string $userMessage): string
    {
        $systemPrompt = <<<'PROMPT'
Anda adalah asisten chatbot BMKG (Badan Meteorologi, Klimatologi, dan Geofisika Indonesia) yang profesional, ramah, dan informatif.

Tanggung Jawab Anda:
- Memberikan informasi akurat tentang layanan BMKG
- Membantu pengguna dengan pertanyaan seputar:
  * Layanan geofisika dan meteorologi
  * Informasi cuaca dan perkiraan iklim
  * Peringatan dini (gempa, tsunami, cuaca ekstrem)
  * Pembayaran PNBP (Penerimaan Negara Bukan Pajak)
  * Konsultasi jasa teknis
  * Perizinan dan regulasi
  * Alat dan instrumen BMKG
- Memberikan jawaban dalam bahasa Indonesia yang baik dan benar
- Berbicara dengan profesional dan santun

Panduan:
- Jika pertanyaan diluar cakupan BMKG, arahkan ke departemen yang relevan
- Gunakan emoji secara tepat untuk membuat percakapan lebih menarik
- Batasi jawaban maksimal 300 kata
- Jika tidak tahu jawaban, katakan dengan jujur dan tawarkan untuk dihubungkan dengan tim

Pertanyaan Pengguna:
PROMPT;

        return $systemPrompt . "\n" . $userMessage;
    }
}
