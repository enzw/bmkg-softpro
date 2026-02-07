<?php

namespace App\Http\Controllers;

use App\Models\SewaAlat;
use App\Models\Kunjungan;
use App\Models\Magang;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class AdminDownloadAreaController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin') {
                return redirect('/dashboard-pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Show download area page
     */
    public function index()
    {
        $data = [
            'title' => 'Download Area',
            'services' => [
                'sewa-alat' => 'Jasa Sewa Alat MKG',
                'kunjungan' => 'Permohonan Kunjungan',
                'magang' => 'Magang',
                'asuransi' => 'Asuransi',
                'layanan-data' => 'Layanan Data Geofisika',
                'survey' => 'Layanan Survey',
                'jasa-konsultasi' => 'Jasa Konsultasi',
            ]
        ];
        return view('pages.admin.download-area.index', $data);
    }

    /**
     * Preview data before download
     */
    public function preview(Request $request)
    {
        $request->validate([
            'service' => 'required|in:sewa-alat,kunjungan,magang,asuransi,layanan-data,survey,jasa-konsultasi',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $service = $request->input('service');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->getServiceQuery($service);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $data = $query->orderBy('created_at', 'desc')->get();
        $serviceName = $this->getServiceName($service);

        return view('pages.admin.download-area.preview', [
            'data' => $data,
            'service' => $service,
            'serviceName' => $serviceName,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'count' => $data->count(),
        ]);
    }

    /**
     * Download data as Excel
     */
    public function download(Request $request)
    {
        $request->validate([
            'service' => 'required|in:sewa-alat,kunjungan,magang,asuransi,layanan-data,survey,jasa-konsultasi',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $service = $request->input('service');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $query = $this->getServiceQuery($service);

        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $data = $query->orderBy('created_at', 'desc')->get();
        $serviceName = $this->getServiceName($service);

        // Generate filename with date range
        $fileName = $this->generateFileName($serviceName, $startDate, $endDate);

        // Get export class based on service
        $exportClass = $this->getExportClass($service, $data, $serviceName);

        return Excel::download($exportClass, $fileName);
    }

    /**
     * Get query for specific service
     */
    private function getServiceQuery($service)
    {
        return match($service) {
            'sewa-alat' => SewaAlat::query(),
            'kunjungan' => Kunjungan::query(),
            'magang' => Magang::query(),
            'asuransi' => Asuransi::query(),
            'layanan-data' => LayananData::query(),
            'survey' => Survey::query(),
            'jasa-konsultasi' => JasaKonsultasi::query(),
            default => collect(),
        };
    }

    /**
     * Get service display name
     */
    private function getServiceName($service)
    {
        return match($service) {
            'sewa-alat' => 'Jasa Sewa Alat MKG',
            'kunjungan' => 'Permohonan Kunjungan',
            'magang' => 'Magang',
            'asuransi' => 'Asuransi',
            'layanan-data' => 'Layanan Data Geofisika',
            'survey' => 'Layanan Survey',
            'jasa-konsultasi' => 'Jasa Konsultasi',
            default => 'Unknown',
        };
    }

    /**
     * Get export class instance
     */
    private function getExportClass($service, $data, $serviceName)
    {
        return match($service) {
            'sewa-alat' => new \App\Exports\SewaAlatExport($data),
            'kunjungan' => new \App\Exports\KunjunganExport($data),
            'magang' => new \App\Exports\MagangExport($data),
            'asuransi' => new \App\Exports\AsuransiExport($data),
            'layanan-data' => new \App\Exports\LayananDataExport($data),
            'survey' => new \App\Exports\SurveyExport($data),
            'jasa-konsultasi' => new \App\Exports\JasaKonsultasiExport($data),
        };
    }

    /**
     * Generate Excel filename
     */
    private function generateFileName($serviceName, $startDate, $endDate)
    {
        $date = now()->format('Y-m-d_H-i-s');
        $fileName = str_replace(' ', '_', $serviceName) . '_' . $date;

        if ($startDate && $endDate) {
            if ($startDate === $endDate) {
                $fileName .= '_' . $startDate;
            } else {
                $fileName .= '_' . $startDate . '_to_' . $endDate;
            }
        }

        return $fileName . '.xlsx';
    }
}
