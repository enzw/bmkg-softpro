<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Models\JasaKonsultasi;
use App\Models\Kunjungan;
use App\Models\LayananData;
use App\Models\Magang;
use App\Models\SewaAlat;
use App\Models\Survey;
use App\Enums\Status;
use App\Enums\SewaStatus;
use App\Services\LayananService;
use Illuminate\Support\Facades\Auth;

class DashboardPelayananController extends Controller
{
    private const ITEMS_PER_PAGE = 5;

    public function index()
    {
        $search = request()->input('search');
        $layanan = LayananService::getLayanan();
        
        // Get all permohonan for original count
        $allPermohonan = $this->getPermohonanList();
        $totalPermohonan = count($allPermohonan);

        // Get filtered permohonan
        $permohonan = $search ? $this->getPermohonanList($search) : $allPermohonan;
        $resultCount = count($permohonan);

        // Ambil hanya 5 data pertama untuk view pertama kali
        $displayedPermohonan = array_slice($permohonan, 0, self::ITEMS_PER_PAGE);

        $pendingRating = $this->getPendingRating();

        return view('pages.dashboard-pelayanan', [
            'layanan' => $layanan,
            'permohonan' => $displayedPermohonan,
            'totalPermohonan' => $totalPermohonan,
            'resultCount' => $resultCount,
            'pendingRating' => $pendingRating,
            'search' => $search,
        ]);
    }

    private function getPendingRating()
    {
        $userId = Auth::id();
        $statusCompletedValues = [Status::SELESAI->value, Status::DITOLAK->value];
        $sewaStatusCompletedValues = [SewaStatus::DIKEMBALIKAN->value, SewaStatus::DITOLAK->value];

        // Check each service for unrated completed items

        $sewaAlat = SewaAlat::where('user_id', $userId)
            ->whereIn('status', $sewaStatusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($sewaAlat)
            return ['id' => $sewaAlat->id, 'type' => SewaAlat::class, 'jenis' => 'Jasa Sewa Alat MKG'];

        $magang = Magang::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($magang)
            return ['id' => $magang->id, 'type' => Magang::class, 'jenis' => 'Magang'];

        $asuransi = Asuransi::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($asuransi)
            return ['id' => $asuransi->id, 'type' => Asuransi::class, 'jenis' => 'Klaim Asuransi'];

        $kunjungan = Kunjungan::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($kunjungan)
            return ['id' => $kunjungan->id, 'type' => Kunjungan::class, 'jenis' => 'Permohonan Kunjungan Teknis'];

        $jasaKonsultasi = JasaKonsultasi::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($jasaKonsultasi)
            return ['id' => $jasaKonsultasi->id, 'type' => JasaKonsultasi::class, 'jenis' => 'Jasa Konsultasi'];

        $survey = Survey::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($survey)
            return ['id' => $survey->id, 'type' => Survey::class, 'jenis' => 'Layanan Survey'];

        $layananData = LayananData::where('user_id', $userId)
            ->whereIn('status', $statusCompletedValues)
            ->doesntHave('rating')
            ->first();
        if ($layananData)
            return ['id' => $layananData->id, 'type' => LayananData::class, 'jenis' => 'Layanan Data'];

        return null;
    }

    /**
     * API endpoint untuk load more permohonan
     */
    public function loadMore()
    {
        $offset = request()->input('offset', 0);
        $search = request()->input('search');
        $permohonan = $this->getPermohonanList($search);

        // Ambil data berdasarkan offset
        $morePermohonan = array_slice($permohonan, $offset, self::ITEMS_PER_PAGE);
        $hasMore = count($permohonan) > ($offset + self::ITEMS_PER_PAGE);

        return response()->json([
            'permohonan' => $morePermohonan,
            'hasMore' => $hasMore,
            'count' => count($morePermohonan),
            'total' => count($permohonan), // results count after search
        ]);
    }

    /**
     * Get all user's permohonan
     */
    private function getPermohonanList($search = null)
    {
        $userId = Auth::id();
        $permohonan = [];

        // Dari SewaAlat (Jasa Sewa Alat)
        $sewaAlat = SewaAlat::with('alat')->where('user_id', $userId)->get();
        foreach ($sewaAlat as $item) {
            // Calculate rental duration and total price
            $mulai = \Carbon\Carbon::parse($item->sewa_mulai);
            $akhir = \Carbon\Carbon::parse($item->sewa_berakhir);
            $durasi = $mulai->diffInDays($akhir);
            $lama_sewa = $durasi == 0 ? 1 : $durasi;
            $harga_per_unit = $item->alat?->harga ?? 0;
            $total_harga = $harga_per_unit * $lama_sewa * ($item->banyak_unit ?? 1);
            
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'sewa_alat',
                'delete_url' => "/layanan/sewa-alat/permohonan/{$item->id}/hapus",
                'jenis'      => 'Jasa Sewa Alat MKG',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama'               => $item->nama ?? '-',
                    'No WhatsApp'        => $item->no_whatsapp ?? '-',
                    'Alat'               => $item->alat?->nama ?? '-',
                    'Harga Per Unit/Hari' => 'Rp' . number_format($harga_per_unit, 0, ',', '.'),
                    'Jumlah Unit'        => ($item->banyak_unit ?? 0) . ' unit',
                    'Durasi Sewa'        => $lama_sewa . ' hari',
                    'Mulai Sewa'         => $item->sewa_mulai ? \Carbon\Carbon::parse($item->sewa_mulai)->format('d/m/Y') : '-',
                    'Akhir Sewa'         => $item->sewa_berakhir ? \Carbon\Carbon::parse($item->sewa_berakhir)->format('d/m/Y') : '-',
                    'Total Harga'        => 'Rp' . number_format($total_harga, 0, ',', '.'),
                ],
            ];
        }

        // Dari Magang
        $magang = Magang::where('user_id', $userId)->get();
        foreach ($magang as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'magang',
                'delete_url' => "/layanan/pelayanan-jasa/{$item->id}",
                'jenis'      => 'Magang',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama'           => $item->nama_lengkap ?? '-',
                    'No WhatsApp'    => $item->no_whatsapp ?? '-',
                    'Universitas'    => $item->universitas ?? '-',
                    'Fakultas'       => $item->fakultas ?? '-',
                    'Program Studi'  => $item->prodi ?? '-',
                    'Mulai Magang'   => $item->tanggal_mulai ? \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') : '-',
                    'Selesai Magang' => $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-',
                ],
            ];
        }

        // Dari Asuransi
        $asuransi = Asuransi::where('user_id', $userId)->get();
        foreach ($asuransi as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'asuransi',
                'delete_url' => "/layanan/pelayanan-jasa/{$item->id}",
                'jenis'      => 'Klaim Asuransi',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Perusahaan'  => $item->perusahaan ?? '-',
                    'No WhatsApp' => $item->no_whatsapp ?? '-',
                    'Lokasi'      => $item->lokasi ?? '-',
                    'Tanggal'     => $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-',
                ],
            ];
        }

        // Dari Kunjungan (Permohonan Kunjungan Teknis)
        $kunjungan = Kunjungan::where('user_id', $userId)->get();
        foreach ($kunjungan as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'kunjungan',
                'delete_url' => "/layanan/permohonan-kunjungan/{$item->id}",
                'jenis'      => 'Permohonan Kunjungan Teknis',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama Lengkap'     => $item->nama_lengkap ?? '-',
                    'Instansi'         => $item->nama_instansi ?? '-',
                    'No WhatsApp'      => $item->no_whatsapp ?? '-',
                    'Jenis Kunjungan'  => $item->jenis_kunjungan ?? '-',
                    'Jumlah Rombongan' => ($item->jumlah_rombongan ?? 0) . ' orang',
                    'Rencana'          => \Str::limit($item->rencana_kunjungan ?? '-', 80),
                ],
            ];
        }

        // Dari JasaKonsultasi
        $jasaKonsultasi = JasaKonsultasi::where('user_id', $userId)->get();
        foreach ($jasaKonsultasi as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'jasa_konsultasi',
                'delete_url' => "/layanan/pelayanan-jasa/{$item->id}",
                'jenis'      => 'Jasa Konsultasi',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama'        => $item->nama_lengkap ?? '-',
                    'No WhatsApp' => $item->no_whatsapp ?? '-',
                    'Email'       => $item->email ?? '-',
                    'Keterangan'  => \Str::limit($item->keterangan ?? '-', 100),
                ],
            ];
        }

        // Dari Survey
        $survey = Survey::where('user_id', $userId)->get();
        foreach ($survey as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'survey',
                'delete_url' => "/layanan/pelayanan-jasa/{$item->id}",
                'jenis'      => 'Layanan Survey',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama'        => $item->nama_lengkap ?? '-',
                    'No WhatsApp' => $item->no_whatsapp ?? '-',
                    'Email'       => $item->email ?? '-',
                    'Keterangan'  => \Str::limit($item->keterangan ?? '-', 100),
                ],
            ];
        }

        // Dari LayananData
        $layananData = LayananData::where('user_id', $userId)->get();
        foreach ($layananData as $item) {
            $permohonan[] = [
                'id'         => $item->id,
                'model_type' => 'layanan_data',
                'delete_url' => "/layanan/pelayanan-jasa/{$item->id}",
                'jenis'      => 'Layanan Data',
                'status'     => $item->status?->label() ?? 'Menunggu',
                'tanggal'    => $item->created_at,
                'detail'     => [
                    'Nama'        => $item->nama_lengkap ?? '-',
                    'No WhatsApp' => $item->no_whatsapp ?? '-',
                    'Email'       => $item->email ?? '-',
                    'Keterangan'  => \Str::limit($item->keterangan ?? '-', 100),
                ],
            ];
        }

        // Filter by search if provided
        if ($search) {
            $search = strtolower($search);
            $permohonan = array_filter($permohonan, function ($item) use ($search) {
                // Check in jenis
                if (str_contains(strtolower($item['jenis']), $search)) return true;
                
                // Check in status
                if (str_contains(strtolower($item['status']), $search)) return true;
                
                // Check in detail values
                foreach ($item['detail'] as $value) {
                    if (str_contains(strtolower((string)$value), $search)) return true;
                }
                
                return false;
            });
        }

        // Sort by tanggal terbaru
        usort($permohonan, function ($a, $b) {
            return $b['tanggal']->timestamp <=> $a['tanggal']->timestamp;
        });

        return $permohonan;
    }

    /**
     * Translate status to Indonesian
     */
    private function translateStatus($status)
    {
        // Handle enum objects - extract the value
        if (is_object($status)) {
            // For backed enums, access the ->value property directly
            $status = $status->value ?? (string)$status;
        }
        
        // Ensure $status is a string
        $status = (string)$status;
        $statusLower = strtolower($status);
        
        return match ($statusLower) {
            'pending' => 'Menunggu',
            'approved', 'diterima', 'disetujui' => 'Disetujui',
            'rejected', 'ditolak' => 'Ditolak',
            'completed', 'selesai' => 'Selesai',
            'diproses' => 'Diproses',
            'alat siap diambil' => 'Alat Siap Diambil',
            'alat dibawa' => 'Alat Dibawa',
            'dikembalikan' => 'Dikembalikan',
            'dikirim' => 'Dikirim',
            'belum lunas' => 'Belum Lunas',
            'siap diambil' => 'Siap Diambil',
            'dibawa' => 'Dibawa',
            default => ucfirst($status)
        };
    }
}
