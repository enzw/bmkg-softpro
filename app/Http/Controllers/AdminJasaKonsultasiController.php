<?php

namespace App\Http\Controllers;

use App\Models\JasaKonsultasi;
use Illuminate\Support\Facades\Auth;
use App\Traits\HandlesFileDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;

class AdminJasaKonsultasiController extends Controller
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
        $data = [
            'title' => 'Jasa Konsultasi',
            'permohonan' => JasaKonsultasi::orderBy('created_at', 'desc')->get(),
        ];
        return view('pages.admin.jasa-konsultasi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.jasa-konsultasi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $file_name = 'jasa-konsultasi_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
                $path_permohonan = $file->storeAs('layanan-konsultasi', $file_name);
                $validated['surat_permohonan'] = $path_permohonan;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload surat permohonan: ' . $error->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $file_name = 'ktp_jasa-konsultasi_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
                $path_ktp = $file->storeAs('layanan-konsultasi', $file_name);
                $validated['ktp'] = $path_ktp;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload KTP: ' . $error->getMessage());
            }
        }

        try {
            JasaKonsultasi::create($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Permohonan gagal dibuat');
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
    public function edit(JasaKonsultasi $jasa_konsultasi)
    {
        $permohonan = $jasa_konsultasi;

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.jasa-konsultasi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JasaKonsultasi $jasa_konsultasi)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($jasa_konsultasi->surat_permohonan) {
                Storage::delete($jasa_konsultasi->surat_permohonan);
            }

            $file = $request->file('surat_permohonan');
            $file_name = 'jasa-konsultasi_user:' . $jasa_konsultasi->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('layanan-konsultasi', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        if ($request->hasFile('ktp')) {
            if ($jasa_konsultasi->ktp) {
                Storage::delete($jasa_konsultasi->ktp);
            }

            $file = $request->file('ktp');
            $file_name = 'ktp_jasa-konsultasi_user:' . $jasa_konsultasi->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_ktp = $file->storeAs('layanan-konsultasi', $file_name);
            $validated['ktp'] = $path_ktp;
        }

        try {
            $jasa_konsultasi->update($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Permohonan gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JasaKonsultasi $jasaKonsultasi)
    {
        try {
            $this->authorize('delete', $jasaKonsultasi);

            // Delete associated files from local storage
            if ($jasaKonsultasi->surat_permohonan) {
                Storage::delete($jasaKonsultasi->surat_permohonan);
            }
            if ($jasaKonsultasi->ktp) {
                Storage::delete($jasaKonsultasi->ktp);
            }

            $jasaKonsultasi->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan berhasil dihapus');
        } catch (\Illuminate\Auth\Access\AuthorizationException $error) {
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus permohonan ini'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus permohonan ini');
        } catch (Exception $error) {
            \Log::error('Admin Jasa Konsultasi Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dihapus: ' . $error->getMessage());
        }
    }

    /**
     * Download file
     */
    public function downloadFile($id, $fileName)
    {
        $jasaKonsultasi = JasaKonsultasi::findOrFail($id);

        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $jasaKonsultasi->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        $filePath = null;

        if ($jasaKonsultasi->surat_permohonan && str_contains($jasaKonsultasi->surat_permohonan, $fileName)) {
            $filePath = $jasaKonsultasi->surat_permohonan;
        } elseif ($jasaKonsultasi->ktp && str_contains($jasaKonsultasi->ktp, $fileName)) {
            $filePath = $jasaKonsultasi->ktp;
        }

        if (!$filePath || !Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }
}
