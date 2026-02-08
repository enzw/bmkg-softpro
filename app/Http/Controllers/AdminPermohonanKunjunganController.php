<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class AdminPermohonanKunjunganController extends Controller
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
        $data = [
            'title' => 'Permohonan Kunjungan Teknis',
            'permohonan' => Kunjungan::all(),
        ];
        return view('pages.admin.permohonan-kunjungan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Permohonan Kunjungan Teknis',
        ];

        return view('pages.admin.permohonan-kunjungan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kunjungan' => 'required|in:goes to BMKG,goes to school',
            'nama_instansi' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'jumlah_rombongan' => 'required|integer|min:1|max:1000',
            'rencana_kunjungan' => 'required|string|min:20|max:2000',
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Remove file objects from validated to avoid storing temp paths
        unset($validated['surat_permohonan'], $validated['ktp']);
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $directory = 'permohonan/kunjungan';
                \Log::info('Starting file upload: ' . $file->getClientOriginalName());
                \Log::info('Storage disk root: ' . Storage::disk('local')->path(''));
                
                // Create directory with full path
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Use simple filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing file as: ' . $filename . ' in ' . $directory);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                \Log::info('storeAs result: ' . var_export($result, true));
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['surat_permohonan'] = $result;
                    \Log::info('Admin Surat Permohonan uploaded successfully: ' . $result . ' at ' . $storedPath);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
                }
            } catch (Exception $error) {
                \Log::error('Surat Permohonan upload error: ' . $error->getMessage());
                \Log::error('Stack trace: ' . $error->getTraceAsString());
                return back()->withInput()->with('error', 'Error: ' . $error->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $directory = 'permohonan/kunjungan';
                \Log::info('Starting file upload: ' . $file->getClientOriginalName());
                
                // Create directory with full path
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Use simple filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing file as: ' . $filename . ' in ' . $directory);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                \Log::info('storeAs result: ' . var_export($result, true));
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['ktp'] = $result;
                    \Log::info('Admin KTP uploaded successfully: ' . $result . ' at ' . $storedPath);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
                }
            } catch (Exception $error) {
                \Log::error('KTP upload error: ' . $error->getMessage());
                \Log::error('Stack trace: ' . $error->getTraceAsString());
                return back()->withInput()->with('error', 'Error: ' . $error->getMessage());
            }
        }

        try {
            \Log::info('Creating Kunjungan with validated data', ['surat_permohonan' => $validated['surat_permohonan'] ?? 'not set', 'ktp' => $validated['ktp'] ?? 'not set']);
            Kunjungan::create($validated);
            return redirect()->route('admin.permohonan-kunjungan.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            \Log::error('Create error: ' . $error->getMessage());
            report($error->getMessage());
            return redirect()->route('admin.permohonan-kunjungan.create')->with('error', 'Permohonan gagal dibuat');
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
     * Show the form for editing a resource.
     */
    public function edit(Kunjungan $permohonan_kunjungan)
    {
        $data = [
            'title' => 'Edit Permohonan Kunjungan Teknis',
            'permohonan' => $permohonan_kunjungan,
        ];

        return view('pages.admin.permohonan-kunjungan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kunjungan $permohonan_kunjungan)
    {
        $validated = $request->validate([
            'jenis_kunjungan' => 'required|in:goes to BMKG,goes to school',
            'nama_instansi' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'jumlah_rombongan' => 'required|integer|min:1|max:1000',
            'rencana_kunjungan' => 'required|string|min:20|max:2000',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        // Remove file objects from validated to avoid storing temp paths
        unset($validated['surat_permohonan'], $validated['ktp']);

        if ($request->hasFile('surat_permohonan')) {
            // Delete old file if exists
            if ($permohonan_kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($permohonan_kunjungan->surat_permohonan);
            }

            $file = $request->file('surat_permohonan');
            $file_name = 'surat-permohonan_kunjungan_user:' . $permohonan_kunjungan->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/kunjungan', $file_name, 'local');
            $validated['surat_permohonan'] = $path_permohonan;
            \Log::info('Admin Surat Permohonan updated: ' . $path_permohonan);
        }

        if ($request->hasFile('ktp')) {
            // Delete old file if exists
            if ($permohonan_kunjungan->ktp) {
                Storage::disk('local')->delete($permohonan_kunjungan->ktp);
            }

            $file = $request->file('ktp');
            $file_name = 'ktp_kunjungan_user:' . $permohonan_kunjungan->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_ktp = $file->storeAs('permohonan/kunjungan', $file_name, 'local');
            $validated['ktp'] = $path_ktp;
            \Log::info('Admin KTP updated: ' . $path_ktp);
        }

        try {
            $permohonan_kunjungan->update($validated);
            return redirect()->route('admin.permohonan-kunjungan.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.permohonan-kunjungan.edit', $permohonan_kunjungan)->with('error', 'Permohonan gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kunjungan $permohonan_kunjungan)
    {
        try {
            // Delete file if exists
            if ($permohonan_kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($permohonan_kunjungan->surat_permohonan);
            }

            $permohonan_kunjungan->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dihapus'], 500);
            }
            return back()->with('error', 'Permohonan gagal dihapus');
        }
    }

    /**
     * Download file
     */
    public function downloadFile($id, $fileName)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        // Security: validate that the file belongs to this record
        if (!$kunjungan->surat_permohonan || !str_contains($kunjungan->surat_permohonan, $fileName)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if file exists in private storage
        if (!Storage::disk('local')->exists($kunjungan->surat_permohonan)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($kunjungan->surat_permohonan, $fileName);
    }
}
