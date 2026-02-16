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
use App\Traits\StatusMapper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    use StatusMapper;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin') {
                return redirect('/dashboard-pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        try {
            $sewa_alat = SewaAlat::all();
            $magang = Magang::all();
            $kunjungan = Kunjungan::all();
            $asuransi = Asuransi::all();
            $jasa_konsultasi = JasaKonsultasi::all();
            $survey = Survey::all();
            $layanan_data = LayananData::all();
        } catch (\Exception $e) {
            Log::error('Database query failed in AdminController::dashboard', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            // Return empty collections if database queries fail
            $sewa_alat = collect([]);
            $magang = collect([]);
            $kunjungan = collect([]);
            $asuransi = collect([]);
            $jasa_konsultasi = collect([]);
            $survey = collect([]);
            $layanan_data = collect([]);
        }

        // Helper function untuk count dengan mapping status untuk Sewa Alat
        $countSewaAlatByDisplayStatus = function ($collection, $displayStatus) {
            $dbStatuses = self::getSewaAlatDbStatusesForDisplay($displayStatus);
            return $collection->filter(function ($item) use ($dbStatuses) {
                // Handle both Enum and string for backward compatibility/safety
                $val = $item->status instanceof \BackedEnum ? $item->status->value : $item->status;
                return in_array($val, $dbStatuses);
            })->count();
        };

        // Hitung statistik permohonan berdasarkan status
        $statistik = [
            'total' => $sewa_alat->count() + $magang->count() + $kunjungan->count() + $asuransi->count() +
                $jasa_konsultasi->count() + $survey->count() +
                $layanan_data->count(),
            'sewa_alat' => [
                'total' => $sewa_alat->count(),
                'menunggu' => $countSewaAlatByDisplayStatus($sewa_alat, 'Menunggu'),
                'diproses' => $countSewaAlatByDisplayStatus($sewa_alat, 'Diproses'),
                'selesai' => $countSewaAlatByDisplayStatus($sewa_alat, 'Selesai'),
                'ditolak' => $sewa_alat->filter(fn($i) => ($i->status->value ?? $i->status) === SewaStatus::DITOLAK->value)->count(),
            ],
            'magang' => [
                'total' => $magang->count(),
                'menunggu' => $magang->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $magang->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $magang->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $magang->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
            'kunjungan' => [
                'total' => $kunjungan->count(),
                'menunggu' => $kunjungan->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $kunjungan->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $kunjungan->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $kunjungan->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
            'asuransi' => [
                'total' => $asuransi->count(),
                'menunggu' => $asuransi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $asuransi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $asuransi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $asuransi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
            'jasa_konsultasi' => [
                'total' => $jasa_konsultasi->count(),
                'menunggu' => $jasa_konsultasi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $jasa_konsultasi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $jasa_konsultasi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $jasa_konsultasi->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
            'survey' => [
                'total' => $survey->count(),
                'menunggu' => $survey->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $survey->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $survey->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $survey->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
            'layanan_data' => [
                'total' => $layanan_data->count(),
                'menunggu' => $layanan_data->filter(fn($i) => ($i->status->value ?? $i->status) === Status::MENUNGGU->value)->count(),
                'diproses' => $layanan_data->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DIPROSES->value)->count(),
                'selesai' => $layanan_data->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count(),
                'ditolak' => $layanan_data->filter(fn($i) => ($i->status->value ?? $i->status) === Status::DITOLAK->value)->count(),
            ],
        ];

        // Hitung total status (hanya dari service arrays, bukan total key)
        $serviceKeys = ['sewa_alat', 'magang', 'kunjungan', 'asuransi', 'jasa_konsultasi', 'survey', 'layanan_data'];
        $statistik['total_menunggu'] = array_sum(array_map(fn($key) => $statistik[$key]['menunggu'], $serviceKeys));
        $statistik['total_diproses'] = array_sum(array_map(fn($key) => $statistik[$key]['diproses'], $serviceKeys));
        $statistik['total_selesai'] = array_sum(array_map(fn($key) => $statistik[$key]['selesai'], $serviceKeys));
        $statistik['total_ditolak'] = array_sum(array_map(fn($key) => $statistik[$key]['ditolak'], $serviceKeys));

        // Hitung perubahan dari bulan lalu
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $previousMonth = $currentMonth === 1 ? 12 : $currentMonth - 1;
        $previousYear = $currentMonth === 1 ? $currentYear - 1 : $currentYear;

        // Total permohonan bulan lalu untuk hitung perubahan
        $totalLastMonth = 0;
        foreach ([
            'sewa_alat' => SewaAlat::class,
            'magang' => Magang::class,
            'kunjungan' => Kunjungan::class,
            'asuransi' => Asuransi::class,
            'jasa_konsultasi' => JasaKonsultasi::class,
            'survey' => Survey::class,
            'layanan_data' => LayananData::class
        ] as $key => $model) {
            $totalLastMonth += $model::whereRaw(
                "EXTRACT(MONTH FROM created_at) = ? AND EXTRACT(YEAR FROM created_at) = ?",
                [$previousMonth, $previousYear]
            )->count();
        }

        // Hitung perubahan total dengan persentase
        $statistik['total_change'] = max(0, $statistik['total'] - $totalLastMonth);
        $statistik['total_change_percent'] = $totalLastMonth > 0 ? round(($statistik['total_change'] / $totalLastMonth) * 100) : 0;

        // Hitung persentase penyelesaian bulan lalu
        $totalSelesaiLastMonth = 0;
        $totalLastMonthAll = 0;
        foreach ([
            'sewa_alat' => SewaAlat::class,
            'magang' => Magang::class,
            'kunjungan' => Kunjungan::class,
            'asuransi' => Asuransi::class,
            'jasa_konsultasi' => JasaKonsultasi::class,
            'survey' => Survey::class,
            'layanan_data' => LayananData::class
        ] as $key => $model) {
            $itemsLastMonth = $model::whereRaw(
                "EXTRACT(MONTH FROM created_at) = ? AND EXTRACT(YEAR FROM created_at) = ?",
                [$previousMonth, $previousYear]
            )->get();
            $totalLastMonthAll += $itemsLastMonth->count();

            if ($key === 'kunjungan') {
                $totalSelesaiLastMonth += $itemsLastMonth->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count();
            } elseif ($key === 'sewa_alat') {
                $dbStatuses = self::getSewaAlatDbStatusesForDisplay('Selesai');
                $totalSelesaiLastMonth += $itemsLastMonth->filter(function ($item) use ($dbStatuses) {
                    $val = $item->status instanceof \BackedEnum ? $item->status->value : $item->status;
                    return in_array($val, $dbStatuses);
                })->count();
            } else {
                $totalSelesaiLastMonth += $itemsLastMonth->filter(fn($i) => ($i->status->value ?? $i->status) === Status::SELESAI->value)->count();
            }
        }

        $percentageLastMonth = $totalLastMonthAll > 0 ? round(($totalSelesaiLastMonth / $totalLastMonthAll) * 100) : 0;
        $currentPercentage = $statistik['total'] > 0 ? round(($statistik['total_selesai'] / $statistik['total']) * 100) : 0;
        $statistik['completion_rate_change'] = max(0, $currentPercentage - $percentageLastMonth);

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
            'kunjungan' => $kunjungan,
            'asuransi' => $asuransi,
            'jasa_konsultasi' => $jasa_konsultasi,
            'survey' => $survey,
            'layanan_data' => $layanan_data,
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
