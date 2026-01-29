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
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {
        $sewa_alat = SewaAlat::all();
        $magang = Magang::all();
        $asuransi = Asuransi::all();
        $jasa_konsultasi = JasaKonsultasi::all();
        $pemetaan = Pemetaan::all();
        $survey = Survey::all();
        $layanan_data = LayananData::all();
        $peta_sebaran = PetaSebaran::all();
        
        // Hitung statistik permohonan berdasarkan status
        $statistik = [
            'total' => $sewa_alat->count() + $magang->count() + $asuransi->count() + 
                       $jasa_konsultasi->count() + $pemetaan->count() + $survey->count() + 
                       $layanan_data->count() + $peta_sebaran->count(),
            'sewa_alat' => [
                'total' => $sewa_alat->count(),
                'menunggu' => $sewa_alat->where('status', 'Menunggu')->count(),
                'diproses' => $sewa_alat->where('status', 'Diproses')->count(),
                'selesai' => $sewa_alat->where('status', 'Selesai')->count(),
                'ditolak' => $sewa_alat->where('status', 'Ditolak')->count(),
            ],
            'magang' => [
                'total' => $magang->count(),
                'menunggu' => $magang->where('status', 'Menunggu')->count(),
                'diproses' => $magang->where('status', 'Diproses')->count(),
                'selesai' => $magang->where('status', 'Selesai')->count(),
                'ditolak' => $magang->where('status', 'Ditolak')->count(),
            ],
            'asuransi' => [
                'total' => $asuransi->count(),
                'menunggu' => $asuransi->where('status', 'Menunggu')->count(),
                'diproses' => $asuransi->where('status', 'Diproses')->count(),
                'selesai' => $asuransi->where('status', 'Selesai')->count(),
                'ditolak' => $asuransi->where('status', 'Ditolak')->count(),
            ],
            'jasa_konsultasi' => [
                'total' => $jasa_konsultasi->count(),
                'menunggu' => $jasa_konsultasi->where('status', 'Menunggu')->count(),
                'diproses' => $jasa_konsultasi->where('status', 'Diproses')->count(),
                'selesai' => $jasa_konsultasi->where('status', 'Selesai')->count(),
                'ditolak' => $jasa_konsultasi->where('status', 'Ditolak')->count(),
            ],
            'pemetaan' => [
                'total' => $pemetaan->count(),
                'menunggu' => $pemetaan->where('status', 'Menunggu')->count(),
                'diproses' => $pemetaan->where('status', 'Diproses')->count(),
                'selesai' => $pemetaan->where('status', 'Selesai')->count(),
                'ditolak' => $pemetaan->where('status', 'Ditolak')->count(),
            ],
            'survey' => [
                'total' => $survey->count(),
                'menunggu' => $survey->where('status', 'Menunggu')->count(),
                'diproses' => $survey->where('status', 'Diproses')->count(),
                'selesai' => $survey->where('status', 'Selesai')->count(),
                'ditolak' => $survey->where('status', 'Ditolak')->count(),
            ],
            'layanan_data' => [
                'total' => $layanan_data->count(),
                'menunggu' => $layanan_data->where('status', 'Menunggu')->count(),
                'diproses' => $layanan_data->where('status', 'Diproses')->count(),
                'selesai' => $layanan_data->where('status', 'Selesai')->count(),
                'ditolak' => $layanan_data->where('status', 'Ditolak')->count(),
            ],
            'peta_sebaran' => [
                'total' => $peta_sebaran->count(),
                'menunggu' => $peta_sebaran->where('status', 'Menunggu')->count(),
                'diproses' => $peta_sebaran->where('status', 'Diproses')->count(),
                'selesai' => $peta_sebaran->where('status', 'Selesai')->count(),
                'ditolak' => $peta_sebaran->where('status', 'Ditolak')->count(),
            ],
        ];
        
        // Hitung total status (hanya dari service arrays, bukan total key)
        $serviceKeys = ['sewa_alat', 'magang', 'asuransi', 'jasa_konsultasi', 'pemetaan', 'survey', 'layanan_data', 'peta_sebaran'];
        $statistik['total_menunggu'] = array_sum(array_map(fn($key) => $statistik[$key]['menunggu'], $serviceKeys));
        $statistik['total_diproses'] = array_sum(array_map(fn($key) => $statistik[$key]['diproses'], $serviceKeys));
        $statistik['total_selesai'] = array_sum(array_map(fn($key) => $statistik[$key]['selesai'], $serviceKeys));
        $statistik['total_ditolak'] = array_sum(array_map(fn($key) => $statistik[$key]['ditolak'], $serviceKeys));
        
        // $permohonan = collect([...$sewa_alat, ...$magang, ...$asuransi]);
        $rating = DB::select('select round(cast((sum(total)/count(question)::float) as numeric),1) as percentage ,sum(total) as total, count(question) as user
        from (select question , right(question,1) ::int as total
        from chatlogs c 
        where intent = ?)data', array('Bintang'));

        $bintang = DB::select(' select  right(question,1) ::int as value, count(question) ::int as total
        from chatlogs c 
        where intent = ?
        group by value order by value DESC', array('Bintang'));

        $data = [
            'title' => 'Dashboard',
            'sewa_alat' => $sewa_alat,
            'magang' => $magang,
            'asuransi' => $asuransi,
            'jasa_konsultasi' => $jasa_konsultasi,
            'pemetaan' => $pemetaan,
            'survey' => $survey,
            'layanan_data' => $layanan_data,
            'peta_sebaran' => $peta_sebaran,
            'statistik' => $statistik,
            // 'permohonan' => $permohonan,
            'bintang' => $rating,
            'rating' => $bintang,
        ];

        return view('pages.admin.dashboard', $data);
    }

    public function getChartData(Request $request)
    {
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Get query parameters, default to the current month and year if not provided
        $month = $request->query('month', $currentMonth);
        $year = $request->query('year', $currentYear);

        // Build the query
        $query = DB::table('chatlogs as c')
            ->selectRaw("
            CASE 
                WHEN intent = '02 Alat' THEN 'Sewa Alat' 
                WHEN intent = '01 Layanan' THEN 'Pelayanan Jasa'
                WHEN intent = '05 Kunjungan' THEN 'Permohonan Kunjungan'
            END as label,
            COUNT(intent) as value,
            EXTRACT(MONTH FROM created_at) as month,
            EXTRACT(YEAR FROM created_at) as year
        ")
            ->whereIn('intent', ['02 Alat', '01 Layanan', '05 Kunjungan'])
            ->whereRaw('EXTRACT(MONTH FROM created_at) = ?', [$month])
            ->whereRaw('EXTRACT(YEAR FROM created_at) = ?', [$year])
            ->groupBy('intent', 'month', 'year');

        // Execute the query
        $results = $query->get();

        // Calculate max value
        $maxValue = $results->max('value');

        // Return response
        return response()->json([
            'data' => $results,
            'maxValue' => $maxValue,
            'currentMonth' => $month,
            'currentYear' => $year
        ]);
    }
}
