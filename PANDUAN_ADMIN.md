# 👨‍💼 MANUAL PANDUAN ADMIN BMKG SOFTPRO

**Versi 1.0** | **Panduan Khusus Administrator dan Staff**

---

## 📋 DAFTAR ISI

1. [Pengantar untuk Admin](#pengantar-untuk-admin)
2. [Login dan Akses Admin](#login-dan-akses-admin)
3. [Dashboard Admin](#dashboard-admin)
4. [Pengelolaan Permohonan per Layanan](#pengelolaan-permohonan-per-layanan)
5. [Manajemen File](#manajemen-file)
6. [Analisis Rating dan Feedback](#analisis-rating-dan-feedback)
7. [Laporan dan Export Data](#laporan-dan-export-data)
8. [Troubleshooting Admin](#troubleshooting-admin)

---

## 👨‍💼 PENGANTAR UNTUK ADMIN

### Fungsi Admin

Admin BMKG SOFTPRO bertanggung jawab untuk:

✅ **Menerima dan memproses permohonan** dari pengguna  
✅ **Update status permohonan** secara real-time  
✅ **Verifikasi dokumen** yang diupload pengguna  
✅ **Berkomunikasi dengan pengguna** tentang progress permohonan  
✅ **Mengelola file dan dokumen**  
✅ **Monitoring rating dan kepuasan pengguna**  
✅ **Membuat laporan dan analisis data**  
✅ **Maintenance sistem dan data cleanup**  

### Ruang Lingkup Admin

Admin memiliki akses penuh ke:
- Dashboard statistik dan overview
- Semua permohonan dari semua pengguna
- File management untuk semua layanan
- Rating dan feedback analytics
- Download-area untuk export data
- Settings dan konfigurasi (tergantung permission level)

---

## 🔐 LOGIN DAN AKSES ADMIN

### Cara Login sebagai Admin

1. **Buka Platform BMKG SOFTPRO**
   - Kunjungi website di browser

2. **Klik Menu "Login"**
   - Di halaman utama

3. **Masukkan Email Admin**
   - Email yang terdaftar sebagai admin/staff

4. **Masukkan Password**
   - Password admin

5. **Klik "Masuk"**

6. **Redirect ke Dashboard Admin**
   - Anda akan diarahkan ke `/admin/dashboard` jika login berhasil
   - Jika tidak, mungkin akun belum memiliki role admin
   - Hubungi superuser untuk mengubah role

### Perbedaan Akun User vs Admin

| Aspek | User Biasa | Admin |
|-------|-----------|-------|
| **Bisa lihat permohonan sendiri** | ✅ | ✅ |
| **Bisa lihat permohonan semua user** | ❌ | ✅ |
| **Bisa update status permohonan** | ❌ | ✅ |
| **Bisa akses admin dashboard** | ❌ | ✅ |
| **Bisa manage file semua user** | ❌ | ✅ |
| **Bisa lihat rating analytics** | ❌ | ✅ |
| **Bisa export data** | ❌ | ✅ |

### Menu Admin

Setelah login, di sidebar admin Anda akan melihat menu:

```
├── Dashboard
├── Sewa Alat
├── Magang
├── Kunjungan
├── Asuransi
├── Jasa Konsultasi
├── Survey
├── Layanan Data
├── Ratings & Analytics
├── File Management
└── Settings (jika superuser)
```

---

## 📊 DASHBOARD ADMIN

Dashboard Admin menampilkan overview semua layanan dan permohonan.

### Elemen Dashboard

#### **1. Statistik Ringkas**

```
┌─────────────────────────────────────────────┐
│ STATISTIK PERMOHONAN (bulan ini)            │
├─────────────────────────────────────────────┤
│ Total Permohonan: 245                       │
│ Menunggu: 45                                │
│ Diproses: 120                               │
│ Selesai: 75                                 │
│ Ditolak: 5                                  │
└─────────────────────────────────────────────┘
```

#### **2. Statistik per Layanan**

Tabel yang menampilkan:
- Jumlah permohonan per layanan
- Status breakdown (Menunggu, Diproses, Selesai, Ditolak)
- Persentase permohonan selesai
- Waktu rata-rata penyelesaian

Contoh:
```
Layanan          | Total | Menunggu | Diproses | Selesai | Ditolak | Rata-rata hari
Sewa Alat        | 45    | 5        | 15       | 24      | 1       | 4.2
Magang           | 32    | 2        | 8        | 20      | 2       | 5.1
Kunjungan        | 58    | 8        | 20       | 28      | 2       | 4.8
Asuransi         | 35    | 12       | 15       | 8       | 0       | 6.5
Konsultasi       | 28    | 8        | 10       | 9       | 1       | 3.2
Survey           | 33    | 5        | 15       | 13      | 0       | 2.1
Layanan Data     | 14    | 5        | 5        | 4       | 0       | 5.9
───────────────────────────────────────────────────────────────────────
TOTAL            | 245   | 45       | 88       | 106     | 6       | 4.6
```

#### **3. Permohonan Terbaru**

Menampilkan 10-20 permohonan paling terbaru dengan kolom:
- Nomor ID
- Nama Pengguna
- Jenis Layanan
- Tanggal Pengajuan
- Status Saat Ini
- Aksi (View, Update, Delete)

#### **4. Aktivitas Hari Ini**

Log aktivitas real-time:
- Permohonan baru masuk
- Status yang diupdate
- File yang diupload
- Rating yang diberikan

#### **5. Grafik dan Chart**

- 📈 Grafik permohonan per layanan
- 📊 Pie chart status permohonan
- 📉 Trend permohonan per bulan
- ⭐ Rating trend

### Cara Menggunakan Dashboard

1. **Monitoring Real-time**
   - Refresh halaman untuk update terbaru
   - Atau set auto-refresh ke 30 detik

2. **Identify Bottleneck**
   - Lihat layanan mana dengan status "Menunggu" paling banyak
   - Prioritaskan untuk penanganan

3. **Cek Kecepatan Penyelesaian**
   - Lihat "Rata-rata hari" dari setiap layanan
   - Target < 5 hari untuk customer satisfaction

4. **Quick Action**
   - Klik permohonan untuk view detail
   - Update status langsung dari dashboard

---

## 📝 PENGELOLAAN PERMOHONAN PER LAYANAN

Setiap layanan memiliki sistem pengelolaan yang sama dengan variasi status sesuai tipe layanan.

### Workflow Umum Pengelolaan Permohonan

```
┌──────────────┐
│  Pengajuan   │ ← User mengajukan permohonan
│  Permohonan  │
└──────┬───────┘
       │
       ↓
┌──────────────┐
│  Menunggu    │ ← Admin review dokumen
│              │   Status: Menunggu (atau Ditinjau)
└──────┬───────┘
       │ ✅ Dokumen OK      │ ❌ Dokumen Tidak OK
       │                    │
       ↓                    ↓
┌──────────────┐      ┌──────────────┐
│  Diproses    │      │   Ditolak    │ ← Reject, hubungi user
│              │      │              │
└──────┬───────┘      └──────────────┘
       │
       ↓
┌──────────────┐
│  Selesai     │ ← Permohonan selesai diproses
│  (atau Done) │   User bisa download hasil
└──────────────┘
```

### Login dan Lihat Daftar Permohonan

**Contoh: Mengelola Permohonan Sewa Alat**

1. **Login Admin**

2. **Klik "Sewa Alat"** di menu sidebar
   - Atau `/admin/sewa-alat`

3. **Daftar Semua Permohonan Terbuka**
   - Menampilkan permohonan dari semua user
   - Urutkan berdasarkan tanggal, status, atau nama

4. **Cari Permohonan Spesifik**
   - Gunakan search box untuk cari nama/nomor ID
   - Filter berdasarkan status, tanggal, atau user

### Lihat Detail Permohonan

1. **Di Daftar Permohonan, Klik Baris Permohonan**
   - Atau klik tombol "View" / "Lihat Detail"

2. **Halaman Detail Terbuka**

Informasi yang ditampilkan:

```
╔══════════════════════════════════════╗
║ DETAIL PERMOHONAN SEWA ALAT         ║
╠══════════════════════════════════════╣
║ ID Permohonan: SWA-202603-0001      ║
║ Status: Menunggu                     ║
║ Tanggal Pengajuan: 29-03-2026        ║
║                                      ║
║ DATA PEMOHON                         ║
║ Nama: Ahmad Santoso                  ║
║ Email: ahmad@email.com               ║
║ No. WhatsApp: 081234567890           ║
║ Alamat: Jl. Sudirman No. 123         ║
║                                      ║
║ DATA SEWA                            ║
║ Jenis Alat: Barometer Presisi        ║
║ Jumlah Unit: 2 buah                  ║
║ Tanggal Mulai: 01-04-2026            ║
║ Tanggal Berakhir: 15-04-2026         ║
║ Perkiraan Biaya: Rp 2.000.000        ║
║                                      ║
║ DOKUMEN YANG DIUPLOAD                ║
║ ✅ KTP_Ahmad_Santoso.pdf (850KB)     ║
║ ✅ Surat_Permohonan.pdf (234KB)      ║
║                                      ║
║ CATATAN/NOTES                        ║
║ Admin: -                             ║
║                                      ║
║ AKSI                                 ║
║ [Update Status] [Edit] [Delete]      ║
╚══════════════════════════════════════╝
```

### Update Status Permohonan

1. **Di Halaman Detail, Lihat Dropdown "Status"**

2. **Pilih Status Baru**
   - Untuk Sewa Alat:
     - Menunggu → Belum Lunas
     - Belum Lunas → Siap Diambil
     - Siap Diambil → Dibawa
     - Dibawa → Dikembalikan
     - Atau direktly ke Ditolak

3. **Tambahkan Catatan (Optional)**
   - Ketik catatan untuk pemohon
   - Contoh: "Alat siap pickup jam 10:00 WIB"
   - Catatan ini akan terlihat di dashboard pengguna

4. **Klik "Simpan" atau "Update Status"**

5. **Sistem akan:**
   - Memperbarui database
   - Mengirim notifikasi ke user
   - Menampilkan timestamp update
   - Mencatat siapa yang update (audit log)

### Hubungi Pengguna Terkait Permohonan

#### Opsi 1: Via WhatsApp

1. **Di halaman detail, lihat nomor WhatsApp**
2. **Klik nomor WhatsApp**
   - Browser akan membuka WhatsApp Web atau app
3. **Tulis pesan**
   - Contoh: "Assalamualaikum, permohonan sewa alat Anda sudah diproses. Alat siap pickup hari Kamis pkl 10:00 WIB di kantor pusat BMKG."
4. **Kirim pesan**

#### Opsi 2: Via Email

1. **Klik email di detail permohonan**
2. **Atau copy email dan gunakan email client Anda**
3. **Tulis email dengan template:**

```
Subject: Update Permohonan Sewa Alat - ID: SWA-202603-0001

Yth. Bapak Ahmad Santoso,

Kami sengat berterima kasih telah menggunakan layanan BMKG SOFTPRO.

Kami ingin memberitahukan bahwa permohonan sewa alat Anda telah kami proses 
dan alat sudah siap untuk diambil.

Detail Alat:
- Barometer Presisi (2 unit)
- Dapat diambil di: Kantor Pusat BMKG, Jl. Angkasa No. 1
- Waktu: Senin-Jumat, 09:00-16:00 WIB
- Estimasi berat: 15 kg

Silakan hubungi kami jika ada pertanyaan.

Salam,
Tim BMKG SOFTPRO
```

### Contoh: Pengelolaan Permohonan Magang (Detail)

1. **Buka Menu Magang**
   - Lihat list calon magang

2. **Klik Detail Calon**
   - Lihat data lengkap calon (universitas, jurusan, CV, dll)

3. **Verifikasi Dokumen**
   - KTP: Valid? ✅
   - CV: Lengkap? ✅
   - Surat Rekomendasi: Ada? ✅

4. **Review CV dan Kualifikasi**
   - Apakah sesuai dengan divisi yang ada?

5. **Tentukan Penempatan**
   - Pilih divisi/departemen (dari dropdown)
   - Tuliskan mentor yang akan membimbing

6. **Update Status**
   - Dari "Menunggu" → "Ditinjau"
   - Lalu "Diproses" (proses penugasan)
   - Akhirnya "Selesai" (sudah ditempatkan)

7. **Hubungi Calon Magang**
   - Kirim detail penempatan via WhatsApp/Email
   - Kirim tanggal mulai dan reporting point

8. **Download Surat Penugasan (jika ada fitur)**
   - Print dan kirim via email

### Status berbeda per Layanan

#### Sewa Alat:
```
Menunggu → Belum Lunas → Siap Diambil → Dibawa → Dikembalikan
                                                      ↓
                                                  Ditolak
```

#### Magang, Kunjungan, Asuransi, Konsultasi, Survey, Layanan Data:
```
Menunggu → Diproses → Selesai
     ↓                  ↓
  Ditinjau         Ditolak
```

### Edit Permohonan

Jika pengguna salah input data:

1. **Klik tombol "Edit" di halaman detail**

2. **Halaman edit terbuka**
   - Field yang editable bisa diubah

3. **Update data yang salah**

4. **Klik "Simpan"**
   - Data akan terupdate

⚠️ **Catatan:** 
- Tidak semua field editable setelah submitted
- Dokumen biasanya tidak bisa diedit, hanya bisa diupload ulang

### Hapus Permohonan

⚠️ **Gunakan dengan hati-hati!**

1. **Klik tombol "Delete" atau "Hapus"** di halaman detail

2. **Konfirmasi Penghapusan**
   - Dialog akan muncul: "Yakin ingin menghapus?"
   - Klik "Ya, Hapus" untuk lanjutkan

3. **Permohonan Dihapus**
   - Permohonan akan hilang dari database
   - File yang terkait juga terhapus
   - Aksi ini tidak bisa dibatalkan!

📌 **Best Practice:** 
- Hanya hapus permohonan jika terduplikasi atau salah masuk
- Jangan hapus permohonan yang sebenarnya sudah diproses
- Dokumentasikan alasan penghapusan untuk audit

---

## 📁 MANAJEMEN FILE

### Akses File Management

1. **Klik "File Management"** di menu admin
   - Atau "Download-Area"

2. **Halaman File Management**
   - Menampilkan semua folder file per layanan

### Struktur File

File disimpan per layanan:

```
storage/app/
├── sewa-alat/
│   ├── SWA-001/
│   │   ├── KTP.pdf
│   │   ├── Surat_Permohonan.pdf
│   │   └── Dokumen_Pendukung.pdf
│   ├── SWA-002/
│   └── ...
├── magang/
│   ├── MAG-001/
│   │   ├── KTP.pdf
│   │   ├── CV.pdf
│   │   └── Surat_Rekomendasi.pdf
│   └── ...
├── kunjungan/
├── asuransi/
├── konsultasi/
├── survey/
└── layanan-data/
```

### Operasi File

#### **Lihat File**

1. **Klik folder untuk expand**
   - Lihat semua file dalam folder
   - Tampilkan nama file, ukuran, dan tanggal upload

2. **Preview File**
   - Klik nama file untuk preview (khusus PDF)
   - Atau klik icon "eye" untuk preview

#### **Download File**

1. **Single File:**
   - Klik icon download di sebelah file
   - File akan diunduh ke komputer Anda

2. **Bulk Download:**
   - Checkbox untuk memilih multiple files
   - Klik "Download Selected"
   - Sistem akan membuat ZIP dan download

3. **Download Seluruh Folder:**
   - Klik "Download Folder"
   - Semua file dalam folder di-ZIP dan download

#### **Delete File**

1. **Klik icon "Trash" atau "Delete"** di sebelah file

2. **Konfirmasi**
   - Dialog: "Yakin hapus file ini?"

3. **File Dihapus**
   - File akan dihapus dari storage
   - Tidak bisa dibatalkan

#### **Delete Folder**

1. **Klik "Delete Folder"** di halaman folder

2. **Konfirmasi**
   - Dialog: "Yakin hapus seluruh folder?"
   - Semua file dalam folder akan dihapus

3. **Folder Dihapus**

⚠️ **Hati-hati:** Ini operasi destructive dan tidak bisa dibatalkan!

### Pembersihan File Rutin

Untuk menjaga storage tetap efisien:

1. **Monthly Cleanup**
   - Lihat folder permohonan yang sudah "Selesai" > 3 bulan
   - Delete file-nya untuk hemat storage

2. **Archive Old Files**
   - Sebelum delete, backup file ke external drive/storage
   - Atau download untuk arsip offline

3. **Monitor Storage Usage**
   - Lihat total size semua folder
   - Target: < 10GB untuk performance optimal

---

## 📊 ANALISIS RATING DAN FEEDBACK

### Akses Rating Analytics

1. **Klik "Ratings" atau "Analytics"** di menu admin

2. **Halaman Rating Analytics Terbuka**
   - Menampilkan data rating dari semua layanan

### Data yang Ditampilkan

#### **Statistik Rating**

```
┌─────────────────────────────────────┐
│ STATISTIK RATING (semua layanan)    │
├─────────────────────────────────────┤
│ Total Rating: 342                   │
│ Rating Rata-rata: 4.2 / 5.0         │
│ Rating Tertinggi: 5.0 (Sewa Alat)   │
│ Rating Terendah: 3.5 (Asuransi)     │
│ Trend: ↑ Naik 0.3 poin (bulan ini)  │
└─────────────────────────────────────┘
```

#### **Statistik Per Layanan**

Tabel menampilkan:
- Layanan
- Total Rating Diterima
- Rata-rata Rating
- % Rating 5 Bintang
- % Rating 4 Bintang
- % Rating < 3 Bintang
- Trend

Contoh:
```
Layanan        | Total | Rata-rata | ⭐⭐⭐⭐⭐ | ⭐⭐⭐⭐ | <3 ⭐ | Trend
Sewa Alat      | 45    | 4.4       | 71%      | 24%     | 5%   | ↑
Magang         | 32    | 4.0       | 47%      | 41%     | 12%  | →
Kunjungan      | 58    | 4.3       | 66%      | 28%     | 6%   | ↑
Asuransi       | 35    | 3.5       | 20%      | 51%     | 29%  | ↓
Konsultasi     | 28    | 4.5       | 75%      | 21%     | 4%   | ↑
Survey         | 33    | 4.1       | 55%      | 36%     | 9%   | →
Layanan Data   | 14    | 4.2       | 57%      | 36%     | 7%   | ↑
─────────────────────────────────────────────────────────────────
TOTAL          | 245   | 4.2       | 56%      | 34%     | 10%  | ↑
```

#### **Grafik Visualisasi**

1. **Pie Chart Rating Distribution**
   - Persentase 1⭐, 2⭐, 3⭐, 4⭐, 5⭐

2. **Bar Chart Rating per Layanan**
   - Perbandingan rating setiap layanan

3. **Line Chart Trend Rating**
   - Tren rating per bulan/minggu
   - Lihat apakah meningkat atau menurun

4. **Feedback Word Cloud**
   - Kata-kata yang sering muncul di feedback

### Feedback Pelanggan

#### **Lihat Feedback Detail**

1. **Scroll ke bagian "Feedback Pelanggan"**

2. **Daftar feedback terbaru ditampilkan**
   - Nama pengguna
   - Rating bintang
   - Teks feedback
   - Tanggal feedback
   - Aksi (Reply, Delete)

#### **Contoh Feedback:**

```
⭐⭐⭐⭐⭐ "Sangat memuaskan! Prosesnya cepat dan staff sangat membantu"
    Oleh: Budi Hartono | Layanan: Sewa Alat | 28-03-2026

⭐⭐⭐⭐ "Bagus, tapi dokumentasi prosesnya bisa lebih jelas"
    Oleh: Siti Nurhaliza | Layanan: Kunjungan | 27-03-2026

⭐⭐⭐ "Cukup, tapi lama prosesnya. Harap dipercepat"
    Oleh: Ahmad Wijaya | Layanan: Asuransi | 26-03-2026

⭐⭐ "Tidak puas. Staff tidak responsif"
    Oleh: Rina Gunawan | Layanan: Konsultasi | 25-03-2026
```

#### **Reply ke Feedback**

1. **Klik tombol "Reply" atau "Balas"**

2. **Tulis balasan**
   - Contoh: "Terima kasih atas feedback positifnya! Kami terus berusaha memberikan layanan terbaik."
   - Untuk feedback negatif: "Kami sangat menyesal dengan pengalaman Anda. Bisa kami bantu?"

3. **Klik "Kirim Reply"**

4. **Balasan akan dikirim ke pengguna** via email/dashboard

### Action Items dari Rating

#### **Jika Rating Tinggi (4-5 bintang):**
✅ Pertahankan kualitas layanan  
✅ Share feedback positif ke team  
✅ Highlight sebagai best practice  

#### **Jika Rating Rendah (1-2 bintang):**
🔴 Review masalah yang dilaporkan  
🔴 Hubungi pengguna untuk clarification  
🔴 Buat action plan untuk improvement  
🔴 Track improvement di feedback berikutnya  

---

## 📈 LAPORAN DAN EXPORT DATA

### Tipe-tipe Laporan

#### **1. Laporan Harian**

1. **Pilih Tanggal**
   - Tentukan tanggal laporan yang ingin diambil

2. **Generate Laporan**
   - Klik "Generate Report" atau "Buat Laporan"

3. **Laporan berisi:**
   - Total permohonan masuk hari ini
   - Status breakdown hari ini
   - Permohonan yang di-complete hari ini
   - Rating yang diterima hari ini
   - Aktivitas key lainnya

4. **Download Laporan**
   - Format PDF atau Excel

#### **2. Laporan Bulanan**

Menampilkan:
- Statistik keseluruhan bulan
- Trend permohonan
- Performance rating
- Service time analysis
- Feedback summary
- Rekomendasi improvement

#### **3. Laporan Per Layanan**

1. **Pilih Layanan** dari dropdown
   - Sewa Alat, Magang, Kunjungan, dll

2. **Tentukan Periode**
   - Tanggal mulai dan berakhir

3. **Generate Laporan**
   - Berisi detail statistik layanan tersebut
   - Daftar semua permohonan dalam periode
   - Rating detail
   - Issues dan followup needed

### Export Data

#### **Export ke Excel**

1. **Pilih data yang ingin diexport**
   - Semua permohonan
   - Permohonan per layanan
   - Permohonan dalam date range tertentu

2. **Klik "Export to Excel"**

3. **File Excel akan didownload**
   - Berisi data dalam format tabel yang rapih
   - Bisa di-sort dan di-filter

4. **Gunakan untuk:**
   - Import ke sistem lain
   - Analisis menggunakan pivot table
   - Presentasi kepada leadership

#### **Export ke CSV**

1. **Pilih "Export to CSV"**

2. **Format CSV diunduh**
   - Lighter weight format
   - Bisa dibuka di berbagai aplikasi

#### **Export Multiple Periode**

1. **Di halaman Admin → Download-Area**

2. **Tentukan kriteria filter:**
   - Date range
   - Service type
   - Status
   - User

3. **Klik "Bulk Export"**

4. **Sistem membuat ZIP file berisi:**
   - Multiple Excel/CSV files
   - Organized by service type

---

## 🔧 TROUBLESHOOTING ADMIN

### Masalah Umum dan Solusi

#### **❌ Status Permohonan Tidak Terupdate**

**Penyebab & Solusi:**
1. Data belum di-sync
   - Refresh halaman (F5)
   - Tunggu 30 detik dan cek lagi

2. Browser cache
   - Clear browser cache (Ctrl+Shift+Del)
   - Login lagi

3. Connection error
   - Cek koneksi internet
   - Cek server status

4. Permission issue
   - Pastikan admin account memiliki role yang tepat
   - Hubungi superuser jika perlu escalate permission

#### **❌ File Upload Gagal**

**Penyebab & Solusi:**
1. File terlalu besar (> 5MB)
   - Kompres file terlebih dahulu
   - Gunakan online image compressor

2. Format file tidak didukung
   - Pastikan file adalah PDF, JPG, atau PNG
   - Convert jika perlu

3. Connection timeout
   - Upload ulang
   - Gunakan internet yang lebih stabil

4. Server storage penuh
   - Hubungi IT untuk cek storage usage
   - Mungkin perlu cleanup old files

#### **❌ Tidak Bisa Akses Admin Dashboard**

**Penyebab & Solusi:**
1. Belum login
   - Login dengan account admin

2. Account tidak punya role admin
   - Hubungi superuser untuk assign role admin
   - Atau cek apakah account yang digunakan benar

3. Session expired
   - Logout dan login lagi

4. IP address restricted (jika ada)
   - Cek whitelist IP
   - Hubungi IT untuk add IP

#### **❌ WhatsApp Link Tidak Bekerja**

**Penyebab & Solusi:**
1. WhatsApp Web tidak terbuka
   - Pastikan WhatsApp Web sudah di-scan QR code
   - Buka https://web.whatsapp.com di browser

2. Nomor format tidak benar
   - Pastikan nomor dengan format internasional: +62xxx
   - Atau pastikan nomor sudah ada di kontak

3. Desktop app issue
   - Install WhatsApp Desktop app
   - Link akan berfungsi via app

#### **❌ Email Notifikasi Tidak Terkirim**

**Penyebab & Solusi:**
1. Email service down
   - Cek status email server
   - Hubungi IT

2. Email masuk ke SPAM
   - Instruksikan user untuk check SPAM folder
   - Mark email sebagai "Not Spam"

3. Email tidak valid
   - Verifikasi email di database
   - Mungkin perlu user update akun mereka

4. Delay pengiriman
   - Email dapat delay hingga 15 menit
   - Tunggu dan cek lagi

#### **❌ Rating/Feedback Tidak Terlihat**

**Penyebab & Solusi:**
1. Refresh halaman
   - Halaman rating cache
   - Clear cache dan refresh

2. Filter settings
   - Cek apakah ada filter aktif yang menyembunyikan rating
   - Reset filter

3. Feedback pending moderation
   - Mungkin ada approval system
   - Check "Pending Feedback" section

4. Data belum disync
   - Tunggu beberapa saat
   - Atau kontrol database langsung

### Database Issue

#### **❌ Data Inkonsisten**

**Contoh:** Permohonan ada di dashboard tapi tidak di detail view

**Solusi:**
1. Database consistency check
   - Hubungi IT/developer
   - Mungkin perlu run database repair command

2. Cache clear
   - Clear application cache
   - Restart application

3. Manual sync
   - Export data
   - Verify integrity
   - Re-import jika perlu

---

## 📋 CHECKLIST ADMIN HARIAN

- ✅ Check dashboard pukul 09:00 WIB (awal shift)
- ✅ Review permohonan dengan status "Menunggu" (prioritas)
- ✅ Verifikasi dokumen yang diupload
- ✅ Update status setiap permohonan yang sudah diproses
- ✅ Hubungi pengguna via WhatsApp untuk update progress
- ✅ Check email untuk inquiry dari pengguna
- ✅ Review rating baru dan reply feedback jika ada
- ✅ Monitor file storage usage
- ✅ Generate daily report (end of day)
- ✅ Escalate issue yang kompleks ke supervisor

---

## 📞 ESKALASI DAN SUPPORT

Jika menghadapi masalah yang tidak bisa diselesaikan:

1. **Hubungi IT Support**
   - Email: it-support@bmkg.go.id
   - Ext: 1234

2. **Hubungi Supervisor Admin**
   - Untuk decision atau permission issue

3. **Hubungi Developer**
   - Untuk bug atau technical issue
   - Email: dev-team@bmkg.go.id

---

**Dokumentasi Selesai**

Last Updated: 31 Maret 2026
