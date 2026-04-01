<?php

namespace App\Services;

class LayananService
{
    public static function getLayanan(): array
    {
        return [
            [
                'icon' => 'fas fa-tools',
                'color' => 'blue',
                'nama' => 'Jasa Sewa Alat MKG',
                'url' => '/layanan/sewa-alat',
                'deskripsi' => 'Sewa alat di Stasiun Geofisika Sleman memungkinkan pengguna atau pelanggan untuk memanfaatkan berbagai peralatan canggih yang tersedia sesuai kebutuhan mereka. Proses peminjaman alat dilakukan dengan mudah dan efisien, sesuai dengan tarif yang telah ditetapkan oleh pihak stasiun.',
                'cta' => 'Sewa Alat',
            ],
            [
                'icon' => 'fas fa-graduation-cap',
                'color' => 'purple',
                'nama' => 'Magang',
                'url' => '/layanan/pelayanan-jasa#magang',
                'deskripsi' => 'Program magang dan penelitian bagi mahasiswa dari berbagai bidang ilmu untuk mendapatkan pengalaman praktis di bidang meteorologi dan geofisika secara komprehensif langsung di stasiun Geofisika Sleman.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'icon' => 'fas fa-file-invoice-dollar',
                'color' => 'amber',
                'nama' => 'Klaim Asuransi',
                'url' => '/layanan/pelayanan-jasa#asuransi',
                'deskripsi' => 'Layanan informasi resmi terkait kejadian gempa bumi, tsunami, dan cuaca ekstrem (petir) untuk keperluan klaim asuransi bencana alam.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'icon' => 'fas fa-database',
                'color' => 'indigo',
                'nama' => 'Layanan Data',
                'url' => '/layanan/pelayanan-jasa#data',
                'deskripsi' => 'Permintaan data hasil observasi geofisika historis maupun real-time untuk keperluan penelitian, proyek pembangunan, maupun referensi instansi di bidang terkait.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'icon' => 'fas fa-compass',
                'color' => 'red',
                'nama' => 'Layanan Survey',
                'url' => '/layanan/pelayanan-jasa#survey',
                'deskripsi' => 'Jasa survey geofisika di lapangan untuk mengetahui struktur lapisan tanah bawah permukaan, potensi air tanah, dan kerawanan bencana di suatu lokasi yang akan dituju.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'icon' => 'fas fa-comments',
                'color' => 'yellow',
                'nama' => 'Jasa Konsultasi',
                'url' => '/layanan/pelayanan-jasa#konsultasi',
                'deskripsi' => 'Konsultasi teknis dengan ahli geofisika terkait mitigasi bencana gempa bumi dan tsunami, perencanaan bangunan tahan gempa, dan kondisi geologis setempat.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'id' => 'permohonan-kunjungan',
                'icon' => 'fas fa-users',
                'color' => 'orange',
                'nama' => 'Permohonan Kunjungan',
                'url' => '/layanan/permohonan-kunjungan',
                'deskripsi' => 'Stasiun Geofisika Sleman menerima kunjungan edukatif dari berbagai tingkat pendidikan, mulai dari Taman Kanak-Kanak (TK) hingga perguruan tinggi. Selain itu, Stasiun Geofisika Sleman juga menyediakan layanan BGTS (BMKG Goes to School).',
                'cta' => 'Ajukan Kunjungan',
            ],
        ];
    }
}
