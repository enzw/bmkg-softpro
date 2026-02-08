<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Support\Facades\Auth;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;

class AdminPermohonanMagangController extends Controller
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
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'kartu_mahasiswa' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();
                
                // Handle file uploads
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/magang');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/magang');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }
                
                if ($request->hasFile('kartu_mahasiswa')) {
                    try {
                        $file = $request->file('kartu_mahasiswa');
                        $path = $file->store('permohonan/magang');
                        $validated['kartu_mahasiswa'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Kartu Mahasiswa upload error: ' . $e->getMessage());
                    }
                }
                
                $permohonan = Magang::create($validated);
                
                // Send Telegram notification with documents
                try {
                    $telegramService = new TelegramService();
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    $ktpPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    if (!empty($validated['ktp'])) {
                        $ktpPath = Storage::disk('local')->path($validated['ktp']);
                    }
                    
                    // Send notification with documents
                    if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                        $telegramService->sendPermohonanWithDocument('magang', $telegramData, $suratPermohonanPath, $ktpPath);
                    } else {
                        $telegramService->sendPermohonanNotification('magang', $telegramData);
                    }
                } catch (Exception $telegramError) {
                    \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                }
                
            } elseif ($jenis_layanan === 'Layanan Klaim Asuransi') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_user' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'tanggal' => 'required|date',
                    'lokasi' => 'required|string',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();
                
                // Handle file uploads
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/asuransi');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/asuransi');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }
                
                $permohonan = Asuransi::create($validated);
                
                // Send Telegram notification with documents
                try {
                    $telegramService = new TelegramService();
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    $ktpPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    if (!empty($validated['ktp'])) {
                        $ktpPath = Storage::disk('local')->path($validated['ktp']);
                    }
                    
                    // Send notification with documents
                    if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                        $telegramService->sendPermohonanWithDocument('asuransi', $telegramData, $suratPermohonanPath, $ktpPath);
                    } else {
                        $telegramService->sendPermohonanNotification('asuransi', $telegramData);
                    }
                } catch (Exception $telegramError) {
                    \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                }
                
            } elseif ($jenis_layanan === 'Layanan Data') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();
                
                // Handle file upload
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/data');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/data');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }
                
                $permohonan = LayananData::create($validated);
                
                // Send Telegram notification with document
                try {
                    $telegramService = new TelegramService();
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    $ktpPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    if (!empty($validated['ktp'])) {
                        $ktpPath = Storage::disk('local')->path($validated['ktp']);
                    }
                    
                    // Send notification with documents
                    if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                        $telegramService->sendPermohonanWithDocument('layanan_data', $telegramData, $suratPermohonanPath, $ktpPath);
                    } else {
                        $telegramService->sendPermohonanNotification('layanan_data', $telegramData);
                    }
                } catch (Exception $telegramError) {
                    \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                }
                
            } elseif ($jenis_layanan === 'Layanan Survey') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();
                
                // Handle file upload
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/survey');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/survey');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }
                
                $permohonan = Survey::create($validated);
                
                // Send Telegram notification with document
                try {
                    $telegramService = new TelegramService();
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    $ktpPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    if (!empty($validated['ktp'])) {
                        $ktpPath = Storage::disk('local')->path($validated['ktp']);
                    }
                    
                    // Send notification with documents
                    if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                        $telegramService->sendPermohonanWithDocument('survey', $telegramData, $suratPermohonanPath, $ktpPath);
                    } else {
                        $telegramService->sendPermohonanNotification('survey', $telegramData);
                    }
                } catch (Exception $telegramError) {
                    \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                }
                
            } elseif ($jenis_layanan === 'Layanan Konsultasi') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();
                
                // Handle file upload
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/konsultasi');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/konsultasi');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }
                
                $permohonan = JasaKonsultasi::create($validated);
                
                // Send Telegram notification with document
                try {
                    $telegramService = new TelegramService();
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    $ktpPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    if (!empty($validated['ktp'])) {
                        $ktpPath = Storage::disk('local')->path($validated['ktp']);
                    }
                    
                    // Send notification with documents
                    if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                        $telegramService->sendPermohonanWithDocument('jasa_konsultasi', $telegramData, $suratPermohonanPath, $ktpPath);
                    } else {
                        $telegramService->sendPermohonanNotification('jasa_konsultasi', $telegramData);
                    }
                } catch (Exception $telegramError) {
                    \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                }
                
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
        // Search di semua 5 tabel
        $permohonan = Magang::find($id) 
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
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
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'kartu_mahasiswa' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof Asuransi) {
                $validated = $request->validate([
                    'nama_user' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'tanggal' => 'required|date',
                    'lokasi' => 'required|string',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof LayananData) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof Survey) {
                $validated = $request->validate([
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
                
            } elseif ($permohonan instanceof JasaKonsultasi) {
                $validated = $request->validate([
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
            }

            // Handle file uploads
            if ($request->hasFile('surat_permohonan')) {
                // Delete old file if exists
                if ($permohonan->surat_permohonan && Storage::disk('local')->exists($permohonan->surat_permohonan)) {
                    Storage::disk('local')->delete($permohonan->surat_permohonan);
                }
                
                $file = $request->file('surat_permohonan');
                $modelType = class_basename($permohonan);
                $folder = strtolower(str_replace('Jasa', '', $modelType));
                $path = $file->store('permohonan/' . $folder);
                $validated['surat_permohonan'] = $path;
            }

            if ($request->hasFile('kartu_mahasiswa') && $permohonan instanceof Magang) {
                // Delete old file if exists
                if ($permohonan->kartu_mahasiswa && Storage::disk('local')->exists($permohonan->kartu_mahasiswa)) {
                    Storage::disk('local')->delete($permohonan->kartu_mahasiswa);
                }
                
                $file = $request->file('kartu_mahasiswa');
                $modelType = class_basename($permohonan);
                $folder = strtolower(str_replace('Jasa', '', $modelType));
                $path = $file->store('permohonan/' . $folder);
                $validated['kartu_mahasiswa'] = $path;
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

    /**
     * Download surat permohonan dari magang/layanan jasa
     */
    public function download(Magang $pelayanan_jasa)
    {
        if (!$pelayanan_jasa->surat_permohonan) {
            return back()->with('error', 'File surat permohonan tidak ditemukan');
        }

        try {
            $path = $pelayanan_jasa->surat_permohonan;
            
            if (!Storage::exists($path)) {
                return back()->with('error', 'File tidak ditemukan di server');
            }

            $fileName = 'surat-permohonan-' . $pelayanan_jasa->id . '.' . pathinfo($path, PATHINFO_EXTENSION);
            
            return Storage::download($path, $fileName);
        } catch (Exception $error) {
            \Log::error('Download Permohonan Error: ' . $error->getMessage());
            return back()->with('error', 'Gagal mengunduh file: ' . $error->getMessage());
        }
    }
}
