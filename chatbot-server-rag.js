import { GoogleGenerativeAI } from '@google/generative-ai';
import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import path from 'path';
import { fileURLToPath } from 'url';
import axios from 'axios';

// Get current directory for ES modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Load environment variables from .env file
dotenv.config({ path: path.join(__dirname, '.env') });
dotenv.config({ path: path.join(__dirname, '.env.local') });

const app = express();
const PORT = process.env.CHATBOT_SERVER_PORT || 3001;
const API_KEY = process.env.GOOGLE_AI_KEY;
const RAG_SERVICE_URL = process.env.RAG_SERVICE_URL || 'http://localhost:5000';
const USE_RAG = process.env.USE_RAG !== 'false'; // Default to true

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
const SYSTEM_PROMPT = `Anda adalah asisten pelayanan Stasiun Geofisika Sleman ✨ yang membantu anggota (member) dalam mengajukan berbagai permohonan layanan.

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

===== MELIHAT DETAIL PERMOHONAN & MENGHAPUS =====
📋 MELIHAT DETAIL PERMOHONAN:
• Login ke aplikasi dengan akun Anda
• Pilih salah satu antara 3 jenis permohonan, lalu buka halaman permohonan itu
• Cari permohonan yang ingin dilihat di daftar
• Klik tombol "Lihat Detail" atau "View" untuk melihat status & informasi lengkap
• Jika ada file yang di-upload, bisa download langsung dari detail page

❌ HAPUS PERMOHONAN:
• Login ke aplikasi dengan akun Anda
• Pilih salah satu antara 3 jenis permohonan, lalu buka halaman permohonan itu
• Cari permohonan yang ingin dihapus
• Klik tombol "Hapus"
• Akan muncul konfirmasi - klik "Hapus" lagi untuk memastikan
⚠️ PERHATIAN: Permohonan yang SUDAH DIPROSES atau berstatus TERIMA/TOLAK tidak bisa dihapus lagi
⚠️ HANYA permohonan dengan status PENDING/DRAFT yang bisa dihapus

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

⚠️ PENTING SEKALI: HANYA gunakan informasi field yang SESUNGGUHNYA ada di form aplikasi.

===== INSTRUKSI KHUSUS FITUR HAPUS =====
🔴 JANGAN PERNAH bilang fitur hapus belum tersedia atau harus hubungi BMKG untuk pembatalan!
✅ FITUR HAPUS SUDAH TERSEDIA - tutorialkan cara menghapus sesuai instruksi di bagian "HAPUS PERMOHONAN" di atas
✅ Jika user bertanya cara hapus permohonan → jawab dengan tutorialnya, jangan redirect ke hubungi BMKG
✅ Jika ada doubt, SELALU ASUMSIKAN FITUR SUDAH ADA dan berlaku sesuai instruksi yang diberikan

===== INSTRUKSI KHUSUS LIHAT DETAIL =====
🔴 JANGAN PERNAH bilang tidak bisa lihat detail permohonan!
✅ FITUR LIHAT DETAIL SUDAH TERSEDIA - tutorialkan sesuai instruksi di bagian "MELIHAT DETAIL PERMOHONAN" di atas
✅ Selalu arahkan user untuk login → pilih layanan → lihat tabel riwayat → klik tombol detail`;


// ============================================================================
// RAG INTEGRATION
// ============================================================================

/**
 * Check if RAG service is available
 */
async function checkRagService() {
    try {
        const response = await axios.get(`${RAG_SERVICE_URL}/health`, {
            timeout: 5000
        });
        console.log('✅ RAG service is available');
        return true;
    } catch (error) {
        console.warn('⚠️  RAG service not available - using direct Gemini responses');
        return false;
    }
}

/**
 * Generate response using RAG service
 * Falls back to direct Gemini if RAG is unavailable
 */
async function generateRagResponse(message) {
    try {
        const response = await axios.post(
            `${RAG_SERVICE_URL}/api/rag/chat`,
            {
                query: message,
                system_prompt: SYSTEM_PROMPT,
                use_rag: true,
                top_k: 5,
                max_output_tokens: 1024
            },
            {
                timeout: 30000
            }
        );

        if (response.data.success) {
            return {
                response: response.data.response,
                sources: response.data.sources || [],
                use_rag: true,
                fallback: response.data.fallback || false
            };
        } else {
            console.warn('⚠️  RAG service returned error:', response.data.error);
            return null;
        }
    } catch (error) {
        console.warn('⚠️  RAG service error:', error.message);
        return null;
    }
}

/**
 * Generate direct response using Gemini (fallback)
 */
async function generateDirectResponse(message) {
    try {
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
        
        return {
            response: result.response.text(),
            sources: [],
            use_rag: false,
            fallback: false
        };
    } catch (error) {
        console.error('❌ Error generating direct response:', error);
        throw error;
    }
}

// ============================================================================
// ENDPOINTS
// ============================================================================

/**
 * Main chat endpoint (RAG-aware)
 * Uses RAG if available, falls back to direct Gemini
 */
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

        console.log(`📨 Chat request: ${message.substring(0, 100)}...`);
        
        let result = null;
        
        // Try RAG first if enabled
        if (USE_RAG) {
            console.log('🤖 Attempting RAG response...');
            result = await generateRagResponse(message);
        }
        
        // Fallback to direct Gemini if RAG failed or disabled
        if (!result) {
            console.log('📨 Falling back to direct Gemini response...');
            result = await generateDirectResponse(message);
        }

        console.log(`✅ Response generated (RAG: ${result.use_rag}, Fallback: ${result.fallback})`);

        res.json({
            success: true,
            message: result.response,
            use_rag: result.use_rag,
            sources: result.sources,
            fallback: result.fallback
        });

    } catch (error) {
        console.error('❌ Chatbot API Error:', error);

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

/**
 * Chat endpoint without RAG (direct Gemini only)
 * Useful for comparison or when RAG context is not needed
 */
app.post('/chat/direct', async (req, res) => {
    try {
        const { message } = req.body;

        if (!message || !message.trim()) {
            return res.status(400).json({
                success: false,
                message: 'Pesan tidak boleh kosong'
            });
        }

        if (!API_KEY || !model) {
            return res.status(503).json({
                success: false,
                message: '❌ Chatbot service not available'
            });
        }

        console.log(`📨 Direct chat request (no RAG): ${message.substring(0, 100)}...`);
        
        const result = await generateDirectResponse(message);

        res.json({
            success: true,
            message: result.response
        });

    } catch (error) {
        console.error('❌ Error:', error);
        res.status(500).json({
            success: false,
            message: 'Terjadi kesalahan saat memproses pertanyaan Anda.'
        });
    }
});

/**
 * RAG status endpoint
 * Shows whether RAG is enabled and available
 */
app.get('/rag/status', async (req, res) => {
    try {
        const ragAvailable = await checkRagService();
        
        res.json({
            success: true,
            rag_enabled: USE_RAG,
            rag_available: ragAvailable,
            rag_service_url: RAG_SERVICE_URL,
            message: USE_RAG && ragAvailable 
                ? '✅ RAG is enabled and available'
                : '⚠️  RAG is disabled or unavailable - using direct responses'
        });
    } catch (error) {
        res.status(500).json({
            success: false,
            error: error.message
        });
    }
});

/**
 * Health check endpoint
 */
app.get('/health', (req, res) => {
    res.json({ 
        status: 'OK', 
        timestamp: new Date().toISOString(),
        service: 'BMKG Chatbot with RAG'
    });
});

// ============================================================================
// SERVER STARTUP
// ============================================================================

app.listen(PORT, async () => {
    console.log('\n' + '='.repeat(60));
    console.log('🚀 BMKG Chatbot Server with RAG Support');
    console.log('='.repeat(60));
    console.log(`✅ Chatbot server running on port ${PORT}`);
    console.log(`   Chat endpoint: POST http://localhost:${PORT}/chat`);
    console.log(`   Direct endpoint: POST http://localhost:${PORT}/chat/direct`);
    console.log(`   RAG status: GET http://localhost:${PORT}/rag/status`);
    console.log(`   Health check: GET http://localhost:${PORT}/health`);
    console.log('');
    
    if (API_KEY && model) {
        console.log(`✅ GOOGLE_AI_KEY configured - Chatbot READY`);
    } else {
        console.warn(`⚠️  GOOGLE_AI_KEY NOT configured - Chat will return error 503`);
    }
    
    if (USE_RAG) {
        console.log('\n🤖 RAG Configuration:');
        console.log(`   RAG Service URL: ${RAG_SERVICE_URL}`);
        const ragAvailable = await checkRagService();
        if (ragAvailable) {
            console.log(`   ✅ RAG Service: AVAILABLE`);
        } else {
            console.log(`   ⚠️  RAG Service: UNAVAILABLE - will use direct responses`);
        }
    } else {
        console.log('\n⚠️  RAG is DISABLED - using direct Gemini responses only');
    }
    
    console.log('='.repeat(60) + '\n');
});
