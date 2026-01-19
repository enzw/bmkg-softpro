<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardPelayananController extends Controller
{
    public function index()
    {
        $layanan = [
            [
                'id' => 'sewa-alat',
                'images' => '/images/alat.png',
                'nama' => 'Sewa Alat',
                'url' => '/layanan/sewa-alat',
                'deskripsi' => 'Sewa alat di Stasiun Geofisika Sleman memungkinkan pengguna atau pelanggan untuk memanfaatkan berbagai peralatan canggih yang tersedia sesuai kebutuhan mereka. Proses peminjaman alat dilakukan dengan mudah dan efisien, sesuai dengan tarif yang telah ditetapkan oleh pihak stasiun.',
                'cta' => 'Lihat Layanan',
            ],
            [
                'id' => 'pelayanan-jasa',
                'images' => '/images/layanan.jpg',
                'nama' => 'Pelayanan Jasa',
                'url' => '/layanan/pelayanan-jasa',
                'deskripsi' => 'Stasiun Geofisika juga menyediakan layanan informasi terkait gempa bumi, tsunami, dan listrik udara. Informasi ini dapat dimanfaatkan untuk berbagai keperluan, termasuk klaim asuransi. Semua tarif layanan telah disesuaikan dengan peraturan perundang-undangan yang berlaku, memastikan transparansi dan kepatuhan terhadap hukum.',
                'cta' => 'Ajukan Permohonan',
            ],
            [
                'id' => 'permohonan-kunjungan',
                'images' => '/images/kunjungan.jpg',
                'nama' => 'Permohonan Kunjungan',
                'url' => '/layanan/permohonan-kunjungan',
                'deskripsi' => 'Stasiun Geofisika Sleman menerima kunjungan edukatif dari berbagai tingkat pendidikan, mulai dari Taman Kanak-Kanak (TK) hingga perguruan tinggi. Selain itu, Stasiun Geofisika Sleman juga menyediakan layanan BGTS (BMKG Goes to School).',
                'cta' => 'Ajukan Kunjungan',
            ],
        ];

        return view('pages.dashboard-pelayanan', compact('layanan'));
    }
}
