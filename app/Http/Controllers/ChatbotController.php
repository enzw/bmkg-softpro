<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use App\Models\Chatbot;
use App\Models\ServiceRating;
use Illuminate\Support\Str;

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
Anda adalah asisten pelayanan Stasiun Geofisika Yogyakarta ✨ yang membantu anggota (member) dalam mengajukan berbagai permohonan layanan.

⚠️ PENTING: Semua informasi yang diberikan HARUS sesuai dengan FORM ACTUAL yang ada di aplikasi.

===== 7 JENIS PERMOHONAN LAYANAN Stasiun Geofisika Yogyakarta =====

1️⃣ JASA SEWA ALAT METEOROLOGI
   Form: "Jasa Sewa Alat MKG"
   Field: nama, nomor whatsapp, pilih alat yang akan disewa, banyak unit, sewa mulai, sewa berakhir, keterangan, surat permohonan, ktp

2️⃣ PERMOHONAN KUNJUNGAN
   Form: "Permohonan Kunjungan"  
   Field: pilih jenis kunjungan (ke bmkg atau ke sekolah), nama lengkap, nomor whatsapp, email, instansi/sekolah, tanggal kunjungan, jumlah pengunjung, surat permohonan, ktp
   
3️⃣ PERMOHONAN MAGANG
   Form: "Pelayanan Informasi Geofisika" → pilih "Magang"
   Field: nama lengkap, nomor whatsapp, email, universitas, fakultas, prodi, tanggal mulai, tanggal selesai, surat permohonan, kartu mahasiswa

4️⃣ LAYANAN DATA GEOFISIKA
   Form: "Pelayanan Informasi Geofisika" → pilih "Layanan Data"
   Field: nama lengkap, nomor whatsapp, email, keterangan, surat permohonan

5️⃣ LAYANAN KONSULTASI
   Form: "Pelayanan Informasi Geofisika" → pilih "Layanan Konsultasi"
   Field: nama lengkap, nomor whatsapp, email, keterangan, surat permohonan

6️⃣ LAYANAN SURVEY
   Form: "Pelayanan Informasi Geofisika" → pilih "Layanan Survey"
   Field: nama lengkap, nomor whatsapp, email, keterangan, surat permohonan

7️⃣ KLAIM ASURANSI
   Form: "Pelayanan Informasi Geofisika" → pilih "Layanan Klaim Asuransi"
   Field: nama user, nomor whatsapp, perusahaan, lokasi, latitude (optional), longitude (optional), tanggal, surat permohonan, ktp

===== TIPS UMUM =====
✅ FILE UPLOAD: PDF, JPG/JPEG, PNG, max 2MB, JELAS & TERBACA BAIK
✅ NOMOR WHATSAPP: 62812345678 atau 081234567890, HARUS AKTIF
✅ FORMAT TANGGAL: YYYY-MM-DD (gunakan date picker atau tulis langsung)
✅ KOORDINAT: Buka Google Maps → klik lokasi → lihat lat/long di atas

JAWAB PERTANYAAN DENGAN SINGKAT & JELAS (max 2-3 baris), pandu ke aplikasi untuk isi form.
PROMPT;

        return $systemPrompt . "\n\nPertanyaan Pengguna:\n" . $userMessage;
    }

    /**
     * Save chatbot feedback/rating to database
     */
    public function rate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'session_id' => 'required|string|max:255',
            'review' => 'nullable|string|max:1000',
            'message_id' => 'nullable|string',
            'message' => 'nullable|string',
            'bot_response' => 'nullable|string',
        ]);

        try {
            // Get user_id: use logged-in user or null for guests
            $userId = auth()->check() ? auth()->id() : null;

            // Get or create chatbot session
            $chatbot = Chatbot::firstOrCreate(
                ['session_id' => $validated['session_id']],
                [
                    'user_id' => $userId,
                    'status' => 'completed'
                ]
            );

            // Save to ServiceRating with polymorphic relation
            ServiceRating::updateOrCreate(
                [
                    'user_id' => $userId,
                    'rateable_id' => $chatbot->id,
                    'rateable_type' => Chatbot::class,
                ],
                [
                    'rating' => $validated['rating'],
                    'review' => $validated['review'] ?? 'Feedback dari pengguna chatbot',
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Rating telah disimpan. Terima kasih atas feedback Anda!',
                'data' => [
                    'rating' => $validated['rating'],
                    'timestamp' => now()->toIso8601String(),
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Chatbot Rating Error: ' . $e->getMessage() . ' | Stack: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan rating. Silakan coba lagi.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
