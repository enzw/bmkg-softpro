import { GoogleGenerativeAI } from '@google/generative-ai';
import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';

// Get current directory for ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Load environment variables from .env file
dotenv.config({ path: path.join(__dirname, '.env') });
dotenv.config({ path: path.join(__dirname, '.env.local') });

const app = express();
const PORT = process.env.CHATBOT_SERVER_PORT || 3001;
const API_KEY = process.env.GOOGLE_AI_KEY;

// Warning if API_KEY not set, but don't exit
if (!API_KEY) {
    console.warn('⚠️  WARNING: GOOGLE_AI_KEY is not set in environment variables');
    console.warn('   Chatbot will not function, but server will continue running');
    console.warn('   Please set GOOGLE_AI_KEY in environment or .env file');
}

const genAI = API_KEY ? new GoogleGenerativeAI(API_KEY) : null;
const model = genAI ? genAI.getGenerativeModel({ model: 'gemini-2.5-flash' }) : null;

// Middleware
app.use(cors());
app.use(express.json());

// System prompt untuk BMKG Chatbot
const SYSTEM_PROMPT = `Anda adalah asisten pelayanan Stasiun Geofisika Yogyakarta ✨ yang membantu anggota (member) dalam mengajukan berbagai permohonan layanan.

⚠️ PENTING: Semua informasi yang diberikan HARUS sesuai dengan FORM ACTUAL yang ada di aplikasi.

===== 7 JENIS PERMOHONAN LAYANAN Stasiun Geofisika Yogyakarta =====

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
1. 🔐 LOGIN ke akun member dengan email dan password
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

⚠️ PENTING SEKALI: HANYA gunakan informasi field yang SESUNGGUHNYA ada di form aplikasi. Jangan membuat atau menambah field yang tidak ada. Jika ada pertanyaan tentang field yang tidak terdaftar, kembalikan ke user bahwa field tersebut mungkin tidak ada atau mereka harus menghubungi tim BMKG langsung.`;

// Chat endpoint
app.post('/chat', async (req, res) => {
    try {
        const { message } = req.body;

        if (!message || !message.trim()) {
            return res.status(400).json({
                success: false,
                message: 'Pesan tidak boleh kosong'
            });
        }

        // Check if API_KEY is configured
        if (!API_KEY || !model) {
            console.warn('⚠️  Chat request received but GOOGLE_AI_KEY not configured');
            return res.status(503).json({
                success: false,
                message: '❌ Chatbot service not available. GOOGLE_AI_KEY is not configured. Please contact administrator.',
                statusCode: 503
            });
        }

        const chat = model.startChat({
            history: [],
            generationConfig: {
                maxOutputTokens: 1024,
                temperature: 0.7,
                topP: 0.95,
                topK: 40,
            },
        });

        const prompt = `${SYSTEM_PROMPT}\n\nPertanyaan pengguna: ${message}`;

        const result = await chat.sendMessage(prompt);
        const responseText = result.response.text();

        res.json({
            success: true,
            message: responseText
        });

    } catch (error) {
        console.error('❌ Chatbot API Error:', error);
        console.error('Error Details:', {
            message: error.message,
            status: error.status,
            statusCode: error.statusCode
        });

        let errorMessage = 'Terjadi kesalahan saat memproses pertanyaan Anda.';

        if (error.message.includes('401') || error.message.includes('Unauthorized')) {
            errorMessage = 'API Key tidak valid atau sudah expired.';
        } else if (error.message.includes('429') || error.message.includes('Too Many')) {
            errorMessage = 'Terlalu banyak permintaan. Silakan coba lagi dalam beberapa saat.';
        } else if (error.message.includes('500') || error.message.includes('Internal')) {
            errorMessage = 'Server Google AI sedang mengalami gangguan. Silakan coba lagi nanti.';
        }

        res.status(500).json({
            success: false,
            message: errorMessage
        });
    }
});

// Health check
app.get('/health', (req, res) => {
    res.json({ status: 'OK', timestamp: new Date().toISOString() });
});

// Start server
app.listen(PORT, () => {
    console.log(`✅ Chatbot server running on port ${PORT}`);
    console.log(`   Chat endpoint: POST http://localhost:${PORT}/chat`);
    console.log(`   Health check: GET http://localhost:${PORT}/health`);
    
    if (API_KEY && model) {
        console.log(`   Status: ✅ GOOGLE_AI_KEY configured - Chatbot READY`);
    } else {
        console.warn(`   Status: ⚠️  GOOGLE_AI_KEY NOT configured - Chat will return error 503`);
        console.warn(`   Please set GOOGLE_AI_KEY environment variable to enable chatbot`);
    }
});
