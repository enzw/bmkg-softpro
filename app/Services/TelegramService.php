<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected $token;
    protected $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.token');
        $this->chatId = config('services.telegram.chat_id');
    }

    public function sendMessage($text)
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendMessage";

        return Http::post($url, [
            'chat_id' => $this->chatId,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
    }

    public function sendDocument($filePath, $caption = null)
    {
        $url = "https://api.telegram.org/bot{$this->token}/sendDocument";

        $file = fopen($filePath, 'r');
        
        $response = Http::attach(
            'document',
            $file,
            basename($filePath)
        )->post($url, [
            'chat_id' => $this->chatId,
            'caption' => $caption,
            'parse_mode' => 'HTML'
        ]);

        fclose($file);

        return $response;
    }

    public function sendPermohonanNotification($type, $data)
    {
        $message = $this->formatPermohonanMessage($type, $data);
        return $this->sendMessage($message);
    }

    public function sendPermohonanWithDocument($type, $data, $suratPermohonanPath = null, $ktpPath = null)
    {
        // Kirim notifikasi teks terlebih dahulu
        $this->sendPermohonanNotification($type, $data);

        // Kirim surat permohonan jika ada
        if ($suratPermohonanPath && file_exists($suratPermohonanPath)) {
            $caption = "📎 <b>Dokumen Lampiran:</b>\n" .
                       "<b>Layanan:</b> " . $this->getServiceName($type) . "\n" .
                       "<b>File:</b> " . basename($suratPermohonanPath) . "\n" .
                       "<b>Tipe:</b> Surat Permohonan";
            
            $this->sendDocument($suratPermohonanPath, $caption);
        }

        // Kirim KTP jika ada
        if ($ktpPath && file_exists($ktpPath)) {
            $caption = "📎 <b>Dokumen Lampiran:</b>\n" .
                       "<b>Layanan:</b> " . $this->getServiceName($type) . "\n" .
                       "<b>File:</b> " . basename($ktpPath) . "\n" .
                       "<b>Tipe:</b> KTP/Identitas";
            
            $this->sendDocument($ktpPath, $caption);
        }
    }

    private function getServiceName($type)
    {
        $services = [
            'sewa_alat' => 'Sewa Alat',
            'jasa_konsultasi' => 'Jasa Konsultasi',
            'magang' => 'Permohonan Magang',
            'kunjungan' => 'Permohonan Kunjungan',
            'asuransi' => 'Klaim Asuransi',
            'layanan_data' => 'Layanan Data',
            'survey' => 'Layanan Survey'
        ];

        return $services[$type] ?? 'Permohonan Layanan';
    }

    private function formatWhatsAppLink($phoneNumber)
    {
        if (!$phoneNumber) {
            return '-';
        }

        // Remove all non-digit characters
        $cleaned = preg_replace('/\D/', '', $phoneNumber);

        // Convert 0 prefix to 62
        if (substr($cleaned, 0, 1) === '0') {
            $cleaned = '62' . substr($cleaned, 1);
        }

        // If still doesn't have 62 prefix, add it
        if (strpos($cleaned, '62') !== 0) {
            $cleaned = '62' . $cleaned;
        }

        // Create clickable WhatsApp link
        return '<a href="https://wa.me/' . $cleaned . '">' . htmlspecialchars($phoneNumber) . '</a>';
    }

    private function formatPermohonanMessage($type, $data)
    {
        $header = "📋 <b>NOTIFIKASI PERMOHONAN BARU</b>\n";
        $header .= "━━━━━━━━━━━━━━━━━━━━━━\n\n";

        $content = "";

        switch ($type) {
            case 'sewa_alat':
                $content = $this->formatSewaAlat($data);
                break;
            case 'jasa_konsultasi':
                $content = $this->formatJasaKonsultasi($data);
                break;
            case 'magang':
                $content = $this->formatMagang($data);
                break;
            case 'kunjungan':
                $content = $this->formatKunjungan($data);
                break;
            case 'asuransi':
                $content = $this->formatAsuransi($data);
                break;
            case 'layanan_data':
                $content = $this->formatLayananData($data);
                break;
            case 'survey':
                $content = $this->formatSurvey($data);
                break;
            default:
                $content = "Jenis permohonan tidak dikenali";
        }

        return $header . $content;
    }

    private function formatSewaAlat($data)
    {
        return "🔧 <b>SEWA ALAT</b>\n\n" .
            "<b>Nama:</b> " . htmlspecialchars($data['nama'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n\n" .
            "<b>Nama Alat:</b> " . htmlspecialchars($data['alat_name'] ?? '-') . "\n" .
            "<b>Jumlah Unit:</b> " . ($data['banyak_unit'] ?? '-') . "\n" .
            "<b>Tanggal Mulai:</b> " . htmlspecialchars($data['sewa_mulai'] ?? '-') . "\n" .
            "<b>Tanggal Berakhir:</b> " . htmlspecialchars($data['sewa_berakhir'] ?? '-') . "\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatJasaKonsultasi($data)
    {
        return "🎯 <b>JASA KONSULTASI</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n\n" .
            "<b>Topik dan Detail Konsultasi:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatMagang($data)
    {
        return "🎓 <b>PERMOHONAN MAGANG</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n\n" .
            "<b>Universitas:</b> " . htmlspecialchars($data['universitas'] ?? '-') . "\n" .
            "<b>Fakultas:</b> " . htmlspecialchars($data['fakultas'] ?? '-') . "\n" .
            "<b>Program Studi:</b> " . htmlspecialchars($data['prodi'] ?? '-') . "\n" .
            "<b>Tanggal Mulai:</b> " . htmlspecialchars($data['tanggal_mulai'] ?? '-') . "\n" .
            "<b>Tanggal Selesai:</b> " . htmlspecialchars($data['tanggal_selesai'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatKunjungan($data)
    {
        return "🏢 <b>PERMOHONAN KUNJUNGAN</b>\n\n" .
            "<b>Jenis Kunjungan:</b> " . htmlspecialchars($data['jenis_kunjungan'] ?? '-') . "\n" .
            "<b>Nama Instansi:</b> " . htmlspecialchars($data['nama_instansi'] ?? '-') . "\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n" .
            "<b>Jumlah Rombongan:</b> " . htmlspecialchars($data['jumlah_rombongan'] ?? '-') . "\n\n" .
            "<b>Rencana Kunjungan:</b>\n" . htmlspecialchars($data['rencana_kunjungan'] ?? '-') . "\n\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatAsuransi($data)
    {
        return "📋 <b>KLAIM ASURANSI</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_user'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n" .
            "<b>Nama Instansi:</b> " . htmlspecialchars($data['perusahaan'] ?? '-') . "\n" .
            "<b>Tanggal Kejadian:</b> " . htmlspecialchars($data['tanggal'] ?? '-') . "\n" .
            "<b>Lokasi Kejadian:</b> " . htmlspecialchars($data['lokasi'] ?? '-') . "\n" .
            "<b>Lintang (Latitude):</b> " . htmlspecialchars($data['latitude'] ?? '-') . "\n" .
            "<b>Bujur (Longitude):</b> " . htmlspecialchars($data['longitude'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatLayananData($data)
    {
        return "💾 <b>LAYANAN DATA GEOFISIKA</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n\n" .
            "<b>Deskripsi Data yang Dibutuhkan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatSurvey($data)
    {
        return "📋 <b>LAYANAN KONSULTASI TEKNIS GEOFISIKA</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . $this->formatWhatsAppLink($data['no_whatsapp'] ?? '') . "\n\n" .
            "<b>Topik dan Detail Konsultasi:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n" .
            "<b>Surat Permohonan:</b> " . (isset($data['surat_permohonan']) && $data['surat_permohonan'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n" .
            "<b>KTP/Identitas:</b> " . (isset($data['ktp']) && $data['ktp'] ? "✅ Tersedia" : "❌ Tidak ada") . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

}