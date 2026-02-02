<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Models\JasaKonsultasi;
use App\Models\LayananData;
use App\Models\Magang;
use App\Models\Pemetaan;
use App\Models\PetaSebaran;
use App\Models\SewaAlat;
use App\Models\Survey;
use App\Services\LayananService;
use Illuminate\Support\Facades\Auth;

class DashboardPelayananController extends Controller
{
    private const ITEMS_PER_PAGE = 5;

    public function index()
    {
        $layanan = LayananService::getLayanan();
        $permohonan = $this->getPermohonanList();
        
        // Ambil hanya 5 data pertama untuk view pertama kali
        $displayedPermohonan = array_slice($permohonan, 0, self::ITEMS_PER_PAGE);
        $totalPermohonan = count($permohonan);

        return view('pages.dashboard-pelayanan', [
            'layanan' => $layanan,
            'permohonan' => $displayedPermohonan,
            'totalPermohonan' => $totalPermohonan
        ]);
    }

    /**
     * API endpoint untuk load more permohonan
     */
    public function loadMore()
    {
        $offset = request()->input('offset', 0);
        $permohonan = $this->getPermohonanList();
        
        // Ambil data berdasarkan offset
        $morePermohonan = array_slice($permohonan, $offset, self::ITEMS_PER_PAGE);
        $hasMore = count($permohonan) > ($offset + self::ITEMS_PER_PAGE);

        return response()->json([
            'permohonan' => $morePermohonan,
            'hasMore' => $hasMore,
            'count' => count($morePermohonan),
        ]);
    }

    /**
     * Get all user's permohonan
     */
    private function getPermohonanList()
    {
        $userId = Auth::id();
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
        
        // Dari Magang
        $magang = Magang::where('user_id', $userId)->get();
        foreach ($magang as $item) {
            $permohonan[] = [
                'jenis' => 'Magang',
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
        
        // Dari JasaKonsultasi
        $jasaKonsultasi = JasaKonsultasi::where('user_id', $userId)->get();
        foreach ($jasaKonsultasi as $item) {
            $permohonan[] = [
                'jenis' => 'Jasa Konsultasi',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari Pemetaan
        $pemetaan = Pemetaan::where('user_id', $userId)->get();
        foreach ($pemetaan as $item) {
            $permohonan[] = [
                'jenis' => 'Layanan Pemetaan',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari Survey
        $survey = Survey::where('user_id', $userId)->get();
        foreach ($survey as $item) {
            $permohonan[] = [
                'jenis' => 'Layanan Survey',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari LayananData
        $layananData = LayananData::where('user_id', $userId)->get();
        foreach ($layananData as $item) {
            $permohonan[] = [
                'jenis' => 'Layanan Data',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Dari PetaSebaran
        $petaSebaran = PetaSebaran::where('user_id', $userId)->get();
        foreach ($petaSebaran as $item) {
            $permohonan[] = [
                'jenis' => 'Peta Sebaran',
                'status' => $item->status ?? 'Pending',
                'tanggal' => $item->created_at,
            ];
        }
        
        // Sort by tanggal terbaru
        usort($permohonan, function ($a, $b) {
            return $b['tanggal']->timestamp <=> $a['tanggal']->timestamp;
        });

        return $permohonan;
    }
}
