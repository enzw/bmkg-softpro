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
            'surat_permohonan' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $file_name = 'surat-permohonan_kunjungan_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/kunjungan', $file_name, 'local');
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            Kunjungan::create($validated);
            return redirect()->route('admin.permohonan-kunjungan.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
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
            'surat_permohonan' => 'nullable|mimes:pdf,jpg,jpeg,png|max:2048',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            // Delete old file if exists
            if ($permohonan_kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($permohonan_kunjungan->surat_permohonan);
            }

            $file = $request->file('surat_permohonan');
            $file_name = 'surat-permohonan_kunjungan_user:' . $permohonan_kunjungan->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/kunjungan', $file_name, 'local');
            $validated['surat_permohonan'] = $path_permohonan;
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
