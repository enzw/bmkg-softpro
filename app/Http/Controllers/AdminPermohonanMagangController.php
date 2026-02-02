<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Pemetaan;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class AdminPermohonanMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Collect dari semua 5 tabel layanan jasa
        $magangData = Magang::all()->map(function($item) {
            $item->jenis_layanan = 'Magang';
            $item->nama_lengkap = $item->nama_lengkap ?? ($item->user->name ?? '-');
            $item->no_whatsapp = $item->no_whatsapp ?? ($item->user->telp ?? '-');
            $item->email = $item->email ?? ($item->user->email ?? null);
            $item->keterangan = $item->prodi ?? null;
            return $item;
        });
        
        $asuransiData = Asuransi::all()->map(function($item) {
            $item->jenis_layanan = 'Layanan Klaim Asuransi';
            $item->nama_lengkap = $item->perusahaan ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = null;
            return $item;
        });
        
        $datumData = LayananData::all()->map(function($item) {
            $item->jenis_layanan = 'Layanan Data';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });
        
        $pemetaanData = Pemetaan::all()->map(function($item) {
            $item->jenis_layanan = 'Layanan Peta Sebaran';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });
        
        $surveyData = Survey::all()->map(function($item) {
            $item->jenis_layanan = 'Layanan Survey';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });
        
        $konsultasiData = JasaKonsultasi::all()->map(function($item) {
            $item->jenis_layanan = 'Layanan Konsultasi';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });
        
        // Merge semua data
        $permohonan = collect()
            ->merge($magangData)
            ->merge($asuransiData)
            ->merge($datumData)
            ->merge($pemetaanData)
            ->merge($surveyData)
            ->merge($konsultasiData)
            ->sortByDesc('created_at');
        
        $data = [
            'title' => 'Pelayanan Jasa',
            'permohonan' => $permohonan,
        ];
        return view('pages.admin.permohonan-magang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.permohonan-magang.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $jenis_layanan = $request->input('jenis_layanan');
        $validated = [];

        try {
            if ($jenis_layanan === 'Magang') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'universitas' => 'required|string',
                    'fakultas' => 'required|string',
                    'prodi' => 'required|string',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                ]);
                $validated['user_id'] = auth()->id();
                Magang::create($validated);
                
            } elseif ($jenis_layanan === 'Layanan Klaim Asuransi') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'perusahaan' => 'required|string',
                    'tanggal' => 'required|date',
                    'lokasi' => 'required|string',
                    'latitude' => 'required|string',
                    'longitude' => 'required|string',
                    'kejadian' => 'required|string',
                    'no_whatsapp' => 'required|string',
                ]);
                $validated['user_id'] = auth()->id();
                Asuransi::create($validated);
                
            } elseif ($jenis_layanan === 'Layanan Data') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                ]);
                $validated['user_id'] = auth()->id();
                LayananData::create($validated);
                
            } elseif ($jenis_layanan === 'Layanan Peta Sebaran') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                ]);
                $validated['user_id'] = auth()->id();
                Pemetaan::create($validated);
                
            } elseif ($jenis_layanan === 'Layanan Survey') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                ]);
                $validated['user_id'] = auth()->id();
                Survey::create($validated);
                
            } elseif ($jenis_layanan === 'Layanan Konsultasi') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                ]);
                $validated['user_id'] = auth()->id();
                JasaKonsultasi::create($validated);
                
            } else {
                return redirect()->route('admin.pelayanan-jasa.create')
                    ->with('error', 'Jenis layanan tidak valid');
            }

            return redirect()->route('admin.pelayanan-jasa.index')
                ->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.create')
                ->withInput()
                ->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Search di semua 5 tabel
        $permohonan = Magang::find($id) 
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
            ?? Pemetaan::find($id)
            ?? Survey::find($id)
            ?? JasaKonsultasi::find($id);

        if (!$permohonan) {
            abort(404, 'Permohonan tidak ditemukan');
        }

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.permohonan-magang.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Search di semua 6 tabel
        $permohonan = Magang::find($id) 
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
            ?? Pemetaan::find($id)
            ?? Survey::find($id)
            ?? JasaKonsultasi::find($id);

        if (!$permohonan) {
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Permohonan tidak ditemukan');
        }

        try {
            // Validate and update based on model type
            if ($permohonan instanceof Magang) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'universitas' => 'required|string',
                    'fakultas' => 'required|string',
                    'prodi' => 'required|string',
                    'tanggal_mulai' => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof Asuransi) {
                $validated = $request->validate([
                    'perusahaan' => 'required|string',
                    'tanggal' => 'required|date',
                    'lokasi' => 'required|string',
                    'latitude' => 'required|string',
                    'longitude' => 'required|string',
                    'kejadian' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof LayananData) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof Pemetaan) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof Survey) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof JasaKonsultasi) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'status' => 'nullable',
                ]);
            }

            $permohonan->update($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Permohonan gagal diupdate: ' . $error->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Search di semua 5 tabel
            $permohonan = Magang::find($id) 
                ?? Asuransi::find($id)
                ?? LayananData::find($id)
                ?? Pemetaan::find($id)
                ?? Survey::find($id)
                ?? JasaKonsultasi::find($id);

            if (!$permohonan) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
                }
                return back()->with('error', 'Permohonan tidak ditemukan');
            }

            $permohonan->delete();
            
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan berhasil dihapus');
        } catch (Exception $error) {
            \Log::error('Admin Permohonan Magang Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dihapus: ' . $error->getMessage());
        }
    }
}
