<?php

namespace App\Http\Controllers;

use App\Models\SewaAlat;
use App\Models\Magang;
use App\Models\Asuransi;
use App\Services\LayananService;
use Illuminate\Support\Facades\Auth;

class DashboardPelayananController extends Controller
{
    public function index()
    {
        $layanan = LayananService::getLayanan();
        $userId = Auth::id();
        
        // Ambil semua permohonan dari user yang login
        $permohonan = [];
        
        // Dari SewaAlat (Jasa Sewa Alat)
        $sewaAlat = SewaAlat::where('user_id', $userId)->get();
        foreach ($sewaAlat as $item) {
            $permohonan[] = [
                'jenis' => 'Jasa Sewa Alat MKG',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari Magang (Pelayanan Informasi Geofisika)
        $pelayananJasa = Magang::where('user_id', $userId)->get();
        foreach ($pelayananJasa as $item) {
            $permohonan[] = [
                'jenis' => 'Pelayanan Informasi Geofisika',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari Asuransi (Permohonan Kunjungan)
        $kunjungan = Asuransi::where('user_id', $userId)->get();
        foreach ($kunjungan as $item) {
            $permohonan[] = [
                'jenis' => 'Permohonan Kunjungan',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Sort by tanggal terbaru
        usort($permohonan, function ($a, $b) {
            return $b['tanggal']->timestamp <=> $a['tanggal']->timestamp;
        });

        return view('pages.dashboard-pelayanan', compact('layanan', 'permohonan'));
    }
}
