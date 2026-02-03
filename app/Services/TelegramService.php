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

    public function sendPermohonanNotification($type, $data)
    {
        $message = $this->formatPermohonanMessage($type, $data);
        return $this->sendMessage($message);
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
            case 'asuransi':
                $content = $this->formatAsuransi($data);
                break;
            case 'layanan_data':
                $content = $this->formatLayananData($data);
                break;
            case 'pemetaan':
                $content = $this->formatPemetaan($data);
                break;
            case 'peta_sebaran':
                $content = $this->formatPetaSebaran($data);
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
            "<b>Nama Member:</b> " . htmlspecialchars($data['user_name'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Nama Alat:</b> " . htmlspecialchars($data['alat_name'] ?? '-') . "\n" .
            "<b>Jumlah Unit:</b> " . ($data['banyak_unit'] ?? '-') . "\n" .
            "<b>Tanggal Mulai:</b> " . htmlspecialchars($data['sewa_mulai'] ?? '-') . "\n" .
            "<b>Tanggal Berakhir:</b> " . htmlspecialchars($data['sewa_berakhir'] ?? '-') . "\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatJasaKonsultasi($data)
    {
        return "💼 <b>JASA KONSULTASI</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatMagang($data)
    {
        return "🎓 <b>PERMOHONAN MAGANG</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Universitas:</b> " . htmlspecialchars($data['universitas'] ?? '-') . "\n" .
            "<b>Fakultas:</b> " . htmlspecialchars($data['fakultas'] ?? '-') . "\n" .
            "<b>Program Studi:</b> " . htmlspecialchars($data['prodi'] ?? '-') . "\n" .
            "<b>Tanggal Mulai:</b> " . htmlspecialchars($data['tanggal_mulai'] ?? '-') . "\n" .
            "<b>Tanggal Selesai:</b> " . htmlspecialchars($data['tanggal_selesai'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatAsuransi($data)
    {
        return "📋 <b>KLAIM ASURANSI</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Deskripsi Kejadian:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatLayananData($data)
    {
        return "💾 <b>LAYANAN DATA</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Jenis Data:</b> " . htmlspecialchars($data['jenis_data'] ?? '-') . "\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatPemetaan($data)
    {
        return "🗺️ <b>LAYANAN PEMETAAN</b>\n\n" .
            "<b>Perusahaan/Instansi:</b> " . htmlspecialchars($data['perusahaan'] ?? '-') . "\n" .
            "<b>Tanggal Kejadian:</b> " . htmlspecialchars($data['tanggal'] ?? '-') . "\n" .
            "<b>Lokasi Kejadian:</b> " . htmlspecialchars($data['lokasi'] ?? '-') . "\n" .
            "<b>Latitude:</b> " . htmlspecialchars($data['latitude'] ?? '-') . "\n" .
            "<b>Longitude:</b> " . htmlspecialchars($data['longitude'] ?? '-') . "\n\n" .
            "<b>Deskripsi Kejadian:</b> " . htmlspecialchars($data['kejadian'] ?? '-') . "\n\n" .
            "🕐 <b>Waktu Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatPetaSebaran($data)
    {
        return "🗺️ <b>LAYANAN PETA SEBARAN</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }

    private function formatSurvey($data)
    {
        return "📊 <b>LAYANAN SURVEY</b>\n\n" .
            "<b>Nama Lengkap:</b> " . htmlspecialchars($data['nama_lengkap'] ?? '-') . "\n" .
            "<b>Email:</b> " . htmlspecialchars($data['email'] ?? '-') . "\n" .
            "<b>No. WhatsApp:</b> " . htmlspecialchars($data['no_whatsapp'] ?? '-') . "\n\n" .
            "<b>Lokasi Survey:</b> " . htmlspecialchars($data['lokasi_survey'] ?? '-') . "\n" .
            "<b>Keterangan:</b> " . htmlspecialchars($data['keterangan'] ?? '-') . "\n\n" .
            "🕐 <b>Tanggal Permohonan:</b> " . htmlspecialchars($data['created_at'] ?? '-');
    }
}