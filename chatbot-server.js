import { GoogleGenerativeAI } from '@google/generative-ai';
import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';

dotenv.config();

const app = express();
const PORT = process.env.CHATBOT_SERVER_PORT || 3001;
const API_KEY = process.env.GOOGLE_AI_KEY;

if (!API_KEY) {
    console.error('❌ GOOGLE_AI_KEY is not set in environment variables');
    process.exit(1);
}

const genAI = new GoogleGenerativeAI(API_KEY);
const model = genAI.getGenerativeModel({ model: 'gemini-2.5-flash' });

// Middleware
app.use(cors());
app.use(express.json());

// System prompt untuk BMKG Chatbot
const SYSTEM_PROMPT = `Anda adalah asisten chatbot BMKG (Badan Meteorologi, Klimatologi, dan Geofisika Indonesia) yang profesional, ramah, dan informatif.

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
- Jika tidak tahu jawaban, katakan dengan jujur dan tawarkan untuk dihubungkan dengan tim`;

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
});
