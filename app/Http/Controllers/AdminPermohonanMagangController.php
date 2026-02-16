<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Support\Facades\Auth;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Traits\HandlesFileDownload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Exception;

class AdminPermohonanMagangController extends Controller
{
    use HandlesFileDownload;
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
        $magangData = Magang::all()->map(function ($item) {
            $item->jenis_layanan = 'Magang';
            $item->nama_lengkap = $item->nama_lengkap ?? ($item->user->name ?? '-');
            $item->no_whatsapp = $item->no_whatsapp ?? ($item->user->telp ?? '-');
            $item->email = $item->email ?? ($item->user->email ?? null);
            $item->keterangan = $item->prodi ?? null;
            return $item;
        });

        $asuransiData = Asuransi::all()->map(function ($item) {
            $item->jenis_layanan = 'Layanan Klaim Asuransi';
            $item->nama_lengkap = $item->perusahaan ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = null;
            return $item;
        });

        $datumData = LayananData::all()->map(function ($item) {
            $item->jenis_layanan = 'Layanan Data';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });

        $surveyData = Survey::all()->map(function ($item) {
            $item->jenis_layanan = 'Layanan Survey';
            $item->nama_lengkap = $item->nama_lengkap ?? '-';
            $item->no_whatsapp = $item->no_whatsapp ?? '-';
            $item->email = $item->email ?? null;
            $item->keterangan = $item->keterangan ?? null;
            return $item;
        });

        $konsultasiData = JasaKonsultasi::all()->map(function ($item) {
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
                    'kartu_mahasiswa' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                ]);
                $validated['user_id'] = auth()->id();

                // Handle file uploads
                if ($request->hasFile('surat_permohonan')) {
                    try {
                        $file = $request->file('surat_permohonan');
                        $path = $file->store('permohonan/magang', 's3');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('kartu_mahasiswa')) {
                    try {
                        $file = $request->file('kartu_mahasiswa');
                        $path = $file->store('permohonan/magang', 's3');
                        $validated['kartu_mahasiswa'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Kartu Mahasiswa upload error: ' . $e->getMessage());
                    }
                }

                $permohonan = Magang::create($validated);

            } elseif ($jenis_layanan === 'Layanan Klaim Asuransi') {
                $validated = $request->validate([
                    'jenis_layanan' => 'required',
                    'nama_user' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'perusahaan' => 'required|string',
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
                        $path = $file->store('permohonan/asuransi', 's3');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/asuransi', 's3');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }

                $permohonan = Asuransi::create($validated);

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
                        $path = $file->store('permohonan/data', 's3');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/data', 's3');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }

                $permohonan = LayananData::create($validated);

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
                        $path = $file->store('permohonan/survey', 's3');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/survey', 's3');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }

                $permohonan = Survey::create($validated);

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
                        $path = $file->store('permohonan/konsultasi', 's3');
                        $validated['surat_permohonan'] = $path;
                    } catch (Exception $e) {
                        \Log::error('Surat Permohonan upload error: ' . $e->getMessage());
                    }
                }

                if ($request->hasFile('ktp')) {
                    try {
                        $file = $request->file('ktp');
                        $path = $file->store('permohonan/konsultasi', 's3');
                        $validated['ktp'] = $path;
                    } catch (Exception $e) {
                        \Log::error('KTP upload error: ' . $e->getMessage());
                    }
                }

                $permohonan = JasaKonsultasi::create($validated);

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
    public function edit(string $pelayanan_jasa)
    {
        // Search di semua 5 tabel
        $permohonan = Magang::find($pelayanan_jasa)
            ?? Asuransi::find($pelayanan_jasa)
            ?? LayananData::find($pelayanan_jasa)
            ?? Survey::find($pelayanan_jasa)
            ?? JasaKonsultasi::find($pelayanan_jasa);

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
                    'perusahaan' => 'required|string',
                    'tanggal' => 'required|date',
                    'lokasi' => 'required|string',
                    'latitude' => 'nullable|numeric',
                    'longitude' => 'nullable|numeric',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);

            } elseif ($permohonan instanceof LayananData) {
                $validated = $request->validate([
                    'nama_lengkap' => 'required|string',
                    'no_whatsapp' => 'required|string',
                    'email' => 'required|email',
                    'keterangan' => 'required|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);

            } elseif ($permohonan instanceof Survey) {
                $validated = $request->validate([
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);

            } elseif ($permohonan instanceof JasaKonsultasi) {
                $validated = $request->validate([
                    'nama_lengkap' => 'nullable|string',
                    'no_whatsapp' => 'nullable|string',
                    'email' => 'nullable|email',
                    'keterangan' => 'nullable|string',
                    'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
                    'status' => 'nullable',
                ]);
            }

            if ($request->hasFile('surat_permohonan')) {
                // Delete old file if exists
                if ($permohonan->surat_permohonan && Storage::disk('s3')->exists($permohonan->surat_permohonan)) {
                    Storage::disk('s3')->delete($permohonan->surat_permohonan);
                }

                $file = $request->file('surat_permohonan');
                $modelType = class_basename($permohonan);
                $folder = strtolower(str_replace('Jasa', '', $modelType));
                $path = $file->store('permohonan/' . $folder, 's3');
                $validated['surat_permohonan'] = $path;
            }

            if ($request->hasFile('ktp') && !($permohonan instanceof Magang)) {
                // Delete old file if exists
                if ($permohonan->ktp && Storage::disk('s3')->exists($permohonan->ktp)) {
                    Storage::disk('s3')->delete($permohonan->ktp);
                }

                $file = $request->file('ktp');
                $modelType = class_basename($permohonan);
                $folder = strtolower(str_replace('Jasa', '', $modelType));
                $path = $file->store('permohonan/' . $folder, 's3');
                $validated['ktp'] = $path;
            }

            if ($request->hasFile('kartu_mahasiswa') && $permohonan instanceof Magang) {
                // Delete old file if exists
                if ($permohonan->kartu_mahasiswa && Storage::disk('s3')->exists($permohonan->kartu_mahasiswa)) {
                    Storage::disk('s3')->delete($permohonan->kartu_mahasiswa);
                }

                $file = $request->file('kartu_mahasiswa');
                $modelType = class_basename($permohonan);
                $folder = strtolower(str_replace('Jasa', '', $modelType));
                $path = $file->store('permohonan/' . $folder, 's3');
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
     * Delete associated files from storage based on model type
     */
    private function deleteAssociatedFiles($permohonan)
    {
        if ($permohonan instanceof Magang) {
            // Magang has: surat_permohonan, surat_ijin_magang, kartu_mahasiswa
            if ($permohonan->surat_permohonan) {
                Storage::disk('s3')->delete($permohonan->surat_permohonan);
            }
            if ($permohonan->surat_ijin_magang) {
                Storage::disk('s3')->delete($permohonan->surat_ijin_magang);
            }
            if ($permohonan->kartu_mahasiswa) {
                Storage::disk('s3')->delete($permohonan->kartu_mahasiswa);
            }
        } else {
            // Asuransi, LayananData, Survey, JasaKonsultasi have: surat_permohonan, ktp
            if ($permohonan->surat_permohonan) {
                Storage::disk('s3')->delete($permohonan->surat_permohonan);
            }
            if ($permohonan->ktp) {
                Storage::disk('s3')->delete($permohonan->ktp);
            }
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

            // Authorize deletion based on model type
            $this->authorize('delete', $permohonan);

            // Delete associated files from Cloudflare S3 before deleting record
            $this->deleteAssociatedFiles($permohonan);

            $permohonan->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan berhasil dihapus');
        } catch (\Illuminate\Auth\Access\AuthorizationException $error) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk menghapus permohonan ini'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus permohonan ini')->setStatusCode(403);
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
    public function downloadFile($id, $fileName)
    {
        // Search di semua 5 tabel
        $permohonan = Magang::find($id)
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
            ?? Survey::find($id)
            ?? JasaKonsultasi::find($id);

        if (!$permohonan) {
            return back()->with('error', 'Permohonan tidak ditemukan');
        }

        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $permohonan->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        // Determine which file field to use based on the file name
        $filePath = null;

        if ($permohonan->surat_permohonan && str_contains($permohonan->surat_permohonan, $fileName)) {
            $filePath = $permohonan->surat_permohonan;
        } elseif (isset($permohonan->surat_ijin_magang) && $permohonan->surat_ijin_magang && str_contains($permohonan->surat_ijin_magang, $fileName)) {
            $filePath = $permohonan->surat_ijin_magang;
        } elseif (isset($permohonan->kartu_mahasiswa) && $permohonan->kartu_mahasiswa && str_contains($permohonan->kartu_mahasiswa, $fileName)) {
            $filePath = $permohonan->kartu_mahasiswa;
        } elseif (isset($permohonan->ktp) && $permohonan->ktp && str_contains($permohonan->ktp, $fileName)) {
            $filePath = $permohonan->ktp;
        }

        if (!$filePath) {
            return back()->with('error', 'File tidak ditemukan.');
        }

        if (!Storage::disk('s3')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }

    /**
     * Download surat permohonan dari magang/layanan jasa
     */
    public function download(Request $request, $id)
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

        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $permohonan->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        $documentType = $request->query('document', 'surat_permohonan');

        // Determine which file to download
        $filePath = null;
        if ($documentType === 'ktp') {
            $filePath = $permohonan->ktp ?? null;
        } else {
            $filePath = $permohonan->surat_permohonan ?? null;
        }

        if (!$filePath) {
            return back()->with('error', 'File tidak ditemukan');
        }

        if (!Storage::disk('s3')->exists($filePath)) {
            return back()->with('error', 'File tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }
}
