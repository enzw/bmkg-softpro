# Telegram Notification for Permohonan (Service Request)

## Overview
Fitur ini memungkinkan sistem untuk mengirim notifikasi otomatis ke Telegram ketika member membuat permohonan layanan. Setiap jenis permohonan memiliki format pesan yang disesuaikan.

## Setup

### 1. Environment Configuration
Pastikan `.env` file sudah berisi konfigurasi Telegram:

```env
TELEGRAM_BOT_TOKEN=your_bot_token_here
TELEGRAM_CHAT_ID=your_chat_id_here
```

**Catatan:** 
- `TELEGRAM_BOT_TOKEN`: Token dari bot Telegram yang dibuat via BotFather
- `TELEGRAM_CHAT_ID`: Chat ID Telegram channel atau personal chat tempat notifikasi akan dikirim

### 2. Configuration File
File `config/services.php` sudah berisi konfigurasi:

```php
'telegram' => [
    'token' => env('TELEGRAM_BOT_TOKEN'),
    'chat_id' => env('TELEGRAM_CHAT_ID'),
],
```

## Fitur Permohonan yang Mendapat Notifikasi

### 1. **Sewa Alat** (Rental Equipment)
- **Controller:** `SewaAlatController@store`
- **Format Pesan:** 
  - Nama Member
  - Email
  - No. WhatsApp
  - Nama Alat
  - Jumlah Unit
  - Tanggal Mulai
  - Tanggal Berakhir
  - Keterangan

### 2. **Jasa Konsultasi** (Consultation Service)
- **Controller:** `JasaKonsultasiController@store`
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Keterangan

### 3. **Permohonan Magang** (Internship Request)
- **Controller:** `MagangController@store` (dengan `jenis_layanan='Magang'`)
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Universitas
  - Fakultas
  - Program Studi
  - Tanggal Mulai
  - Tanggal Selesai

### 4. **Klaim Asuransi** (Insurance Claim)
- **Controller:** `AsuransiController@store`
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Jenis Asuransi
  - Keterangan

### 5. **Layanan Data** (Data Service)
- **Controller:** `LayananDataController@store` atau `MagangController@store` (dengan `jenis_layanan='Layanan Data'`)
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Jenis Data
  - Keterangan

### 6. **Layanan Pemetaan** (Mapping Service)
- **Controller:** `PemetaanController@store` atau `MagangController@store` (dengan `jenis_layanan='Layanan Pemetaan'`)
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Area Pemetaan
  - Keterangan

### 7. **Peta Sebaran** (Distribution Map)
- **Controller:** `PetaSebaranController@store`
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Keterangan

### 8. **Layanan Survey** (Survey Service)
- **Controller:** `SurveyController@store` atau `MagangController@store` (dengan `jenis_layanan='Layanan Survey'`)
- **Format Pesan:**
  - Nama Lengkap
  - Email
  - No. WhatsApp
  - Lokasi Survey
  - Keterangan

## Arsitektur

### TelegramService (`app/Services/TelegramService.php`)

**Metode Utama:**
- `sendMessage($text)` - Mengirim pesan plain text ke Telegram
- `sendPermohonanNotification($type, $data)` - Mengirim notifikasi permohonan dengan format yang sesuai

**Tipe Permohonan yang Didukung:**
- `sewa_alat`
- `jasa_konsultasi`
- `magang`
- `asuransi`
- `layanan_data`
- `pemetaan`
- `peta_sebaran`
- `survey`

### Implementasi di Controller

Setiap controller melakukan:

```php
try {
    $permohonan = Model::create($validated);
    
    // Send Telegram notification
    try {
        $telegramService = new TelegramService();
        $telegramData = [
            // Data fields sesuai tipe permohonan
            'created_at' => $permohonan->created_at->format('d-m-Y H:i'),
        ];
        
        $telegramService->sendPermohonanNotification('type', $telegramData);
    } catch (Exception $telegramError) {
        \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
        // Proses tetap berlanjut meski notifikasi gagal
    }
    
    return back()->with('success', 'Permohonan berhasil dibuat');
} catch (Exception $error) {
    return back()->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
}
```

## Error Handling

Sistem dirancang untuk **fail gracefully**:
- Jika pengiriman notifikasi Telegram gagal, proses pembuatan permohonan tetap berhasil
- Error pengiriman dicatat di log dengan level `warning` untuk monitoring
- User tidak akan melihat error Telegram, hanya notifikasi sukses permohonan

## Format Pesan di Telegram

Setiap notifikasi memiliki:
- Header dengan emoji dan judul permohonan
- Informasi detail dengan field-field yang relevan
- Format HTML dengan parsing mode untuk styling
- Timestamp pembuatan permohonan

**Contoh Format:**
```
📋 NOTIFIKASI PERMOHONAN BARU
━━━━━━━━━━━━━━━━━━━━━━

🔧 SEWA ALAT

Nama Member: John Doe
Email: john@example.com
No. WhatsApp: +62812345678

Nama Alat: Seismometer
Jumlah Unit: 2
Tanggal Mulai: 2025-02-03
Tanggal Berakhir: 2025-02-10
Keterangan: Untuk riset gempa

🕐 Tanggal Permohonan: 03-02-2025 10:30
```

## Testing

Untuk menguji notifikasi tanpa membuat permohonan lengkap:

```php
// Di Tinker atau sesuai tempat testing
$telegramService = new \App\Services\TelegramService();
$testData = [
    'user_name' => 'Test User',
    'email' => 'test@example.com',
    'no_whatsapp' => '08123456789',
    'alat_name' => 'Test Alat',
    'banyak_unit' => 1,
    'sewa_mulai' => '2025-02-03',
    'sewa_berakhir' => '2025-02-10',
    'keterangan' => 'Test keterangan',
    'created_at' => now()->format('d-m-Y H:i'),
];

$telegramService->sendPermohonanNotification('sewa_alat', $testData);
```

## Logging

Semua aktivitas notifikasi dicatat di `storage/logs/laravel.log`:
- Sukses pengiriman: Tidak ada log (berjalan normal)
- Gagal pengiriman: Log level `warning` dengan pesan error

Untuk melihat logs:
```bash
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Notifikasi Tidak Terkirim

1. **Verifikasi Environment Variables:**
   ```bash
   php artisan tinker
   > config('services.telegram.token')
   > config('services.telegram.chat_id')
   ```

2. **Cek Koneksi Internet:** Pastikan server memiliki akses ke `api.telegram.org`

3. **Validasi Bot Token:**
   - Token harus benar dari BotFather
   - Bot harus member di channel/group tempat chat_id

4. **Cek Logs:**
   ```bash
   tail -n 50 storage/logs/laravel.log | grep -i telegram
   ```

### Bot Tidak Merespons

1. Verifikasi bot sudah di-start di BotFather
2. Pastikan chat_id benar (format: positif untuk private chat, negatif untuk channel)
3. Bot harus memiliki permission untuk mengirim pesan

## Controllers Yang Sudah Diupdate

- ✅ `SewaAlatController`
- ✅ `JasaKonsultasiController`
- ✅ `MagangController`
- ✅ `SurveyController`
- ✅ `PemetaanController`
- ✅ `LayananDataController`
- ✅ `PetaSebaranController`
- ✅ `AsuransiController`

## Future Enhancements

Fitur yang bisa ditambahkan:
- [ ] Notifikasi real-time dengan WebSocket
- [ ] Update status permohonan ke Telegram
- [ ] Attachment files ke Telegram (PDF, gambar)
- [ ] Multiple Telegram channels untuk berbagai tipe permohonan
- [ ] Scheduled reports permohonan ke Telegram
- [ ] Approval workflow via Telegram bot
