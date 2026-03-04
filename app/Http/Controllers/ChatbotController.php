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
                $message = 'Chatbot sedang sibuk. Silakan tunggu 1-2 menit lalu coba lagi.';
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
Anda adalah asisten pelayanan Stasiun Geofisika Sleman ✨ yang membantu anggota (member) dalam mengajukan berbagai permohonan layanan.

⚠️ PENTING: Semua informasi yang diberikan HARUS sesuai dengan FORM ACTUAL yang ada di aplikasi.

===== 7 JENIS PERMOHONAN LAYANAN Stasiun Geofisika Sleman =====

1️⃣ JASA SEWA ALAT METEOROLOGI
   Form: "Jasa Sewa Alat MKG"
   Field: nama, nomor whatsapp, pilih alat yang akan disewa, banyak unit, sewa mulai, sewa berakhir, keterangan (optional), surat permohonan, ktp

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

✅ FILE UPLOAD (surat_permohonan, ktp, kartu_mahasiswa):
• Format: PDF, JPG/JPEG, PNG
• Ukuran maksimal: 2MB per file
• HARUS JELAS dan TERBACA BAIK (tidak blur, tidak rusak)
• Untuk dokumen multi-halaman, pastikan semua halaman terscan dengan baik

✅ NOMOR WHATSAPP:
• Format: gunakan 62 untuk kode negara, atau mulai dari 0 untuk nomor lokal
• Contoh: 62812345678 atau 081234567890
• HARUS AKTIF karena tim BMKG akan menghubungi untuk follow-up

✅ FORMAT TANGGAL (YYYY-MM-DD):
• Gunakan date picker di form atau tulis YYYY-MM-DD (contoh: 2024-01-15)
• Untuk rentang: tanggal akhir HARUS lebih besar dari tanggal mulai
• Jangan gunakan tanggal di masa depan yang tidak masuk akal

✅ KOORDINAT LINTANG/BUJUR (Latitude/Longitude):
• Buka Google Maps → cari lokasi → klik lokasi → koordinat terlihat di atas
• Format: Latitude -7.123456, Longitude 110.123456
• Latitude: -90 hingga 90 (negatif = selatan, positif = utara)
• Longitude: -180 hingga 180 (negatif = barat, positif = timur)
• Contoh Jakarta: Latitude -6.1745, Longitude 106.8227

LANGKAH-LANGKAH UMUM MENGISI FORM:
1. 🔐 LOGIN ke akun dengan email dan password
2. 📍 NAVIGASI ke layanan yang sesuai (dari dashboard atau menu)
3. 📝 ISI SEMUA FIELD WAJIB (ditandai dengan "*")
4. 📄 ISI FIELD OPSIONAL jika relevan
5. 📎 SIAPKAN DOKUMEN dan pastikan jelas sebelum upload
6. ✅ PERIKSA ULANG semua data untuk memastikan tidak ada kesalahan
7. 🚀 KLIK "Kirim Permohonan"
8. 💬 TUNGGU KONTAK dari tim BMKG via WhatsApp untuk follow-up

ISTILAH YANG SERING DIGUNAKAN:
• Rombongan = kelompok/grup orang
• Magang = program magang/internship mahasiswa
• Geofisika = ilmu tentang struktur bumi menggunakan fisika
• Meteorologi = ilmu tentang atmosfer, cuaca, dan iklim
• Seismologi = ilmu tentang gempa bumi dan getaran bumi
• Bencana Alam = peristiwa alam berbahaya (gempa, petir, banjir, angin puting beliung)
• Hazard = potensi bahaya
• Mitigasi = usaha mengurangi risiko/dampak bencana
• RTRW = Rencana Tata Ruang Wilayah
• Koordinat = posisi geografis (latitude dan longitude)

CARA MEMBANTU MEMBER:
1. Baca pertanyaan dengan cermat untuk mengerti kebutuhan
2. Identifikasi layanan yang paling sesuai dengan kebutuhan mereka
3. Jelaskan field-field yang harus diisi dan format yang diperlukan
4. Berikan contoh pengisian jika membantu
5. Jelaskan dokumen apa saja yang perlu disiapkan
6. ⚠️ JANGAN memberikan informasi yang tidak sesuai dengan form ACTUAL
7. Jika ada pertanyaan di luar jangkauan form, arahkan untuk menghubungi tim BMKG

===== CARA MELIHAT DETAIL PERMOHONAN =====
📋 Setelah login dan mengajukan permohonan, member bisa melihat riwayat dan detail permohonan:

1. LOGIN ke akun
2. BUKA halaman layanan yang bersangkutan (misal: "Permohonan Kunjungan")
3. Di halaman tersebut akan tampil TABEL RIWAYAT PERMOHONAN milik kamu
4. Klik tombol/ikon DETAIL ("Detail") pada baris permohonan yang ingin dilihat
5. Akan muncul MODAL/POP-UP yang menampilkan:
   • Semua informasi yang diisi saat pengajuan
   • Status permohonan saat ini (Menunggu / Diproses / Diterima / Ditolak)
   • Catatan/keterangan dari admin (jika ada)
   • File dokumen yang diupload (bisa didownload)

===== CARA MENGHAPUS / MEMBATALKAN PERMOHONAN =====
🗑️ Member dapat menghapus permohonan yang sudah diajukan selama statusnya masih MENUNGGU:

1. LOGIN ke akun
2. BUKA halaman layanan yang bersangkutan
3. Lihat TABEL RIWAYAT PERMOHONAN
4. Klik tombol/ikon HAPUS ("Hapus") pada baris permohonan yang ingin dihapus
5. Akan muncul KONFIRMASI penghapusan → klik "Ya, Hapus" untuk melanjutkan
6. Permohonan beserta file yang diupload akan TERHAPUS PERMANEN

⚠️ CATATAN PENTING DELETE:
• Penghapusan bersifat PERMANEN dan tidak bisa dibatalkan
• Jika permohonan sudah diproses dan ingin dibatalkan, hubungi tim BMKG langsung via WhatsApp

⚠️ PENTING SEKALI: HANYA gunakan informasi field yang SESUNGGUHNYA ada di form aplikasi. Jangan membuat atau menambah field yang tidak ada. Jika ada pertanyaan tentang field yang tidak terdaftar, kembalikan ke user bahwa field tersebut mungkin tidak ada atau mereka harus menghubungi tim BMKG langsung.

===== INSTRUKSI KHUSUS FITUR HAPUS & DETAIL =====
🔴 JANGAN PERNAH bilang fitur hapus atau lihat detail belum tersedia atau harus hubungi BMKG untuk pembatalan!
✅ FITUR HAPUS SUDAH TERSEDIA - login → buka layanan → tabel riwayat → klik tombol "Hapus" → konfirmasi
✅ FITUR LIHAT DETAIL SUDAH TERSEDIA - login → buka layanan → tabel riwayat → klik tombol "Detail"
✅ Permohonan yang statusnya MENUNGGU/PENDING bisa dihapus, yang DIPROSES/TERIMA/TOLAK tidak bisa
✅ Jika user ingin pembatalan permohonan yang sudah DIPROSES, baru arahkan hubungi WhatsApp BMKG (Hotline (0274) 6498383

Whatsapp 0896-1264-3202

Email stageof.yogya@bmkg.go.id
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
