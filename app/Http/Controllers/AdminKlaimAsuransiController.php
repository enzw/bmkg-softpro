<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;
use App\Traits\HandlesFileDownload;

class AdminKlaimAsuransiController extends Controller
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
            'title' => 'Permohonan Kunjungan',
            'permohonan' => Asuransi::with('user')->get(),
        ];
        return view('pages.admin.klaim-asuransi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.klaim-asuransi.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_user' => 'required|string',
            'no_whatsapp' => 'required|string',
            'perusahaan' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $file_name = 'klaim-asuransi_user:' . $request->user()->id . '_date:' . Carbon::now() . '.' . $file->getClientOriginalExtension();
                $path_permohonan = $file->storeAs('permohonan/asuransi', $file_name, 's3');
                $validated['surat_permohonan'] = $path_permohonan;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload surat permohonan: ' . $error->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $file_name = 'ktp_klaim-asuransi_user:' . $request->user()->id . '_date:' . Carbon::now() . '.' . $file->getClientOriginalExtension();
                $path_ktp = $file->storeAs('permohonan/asuransi', $file_name, 's3');
                $validated['ktp'] = $path_ktp;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload KTP: ' . $error->getMessage());
            }
        }

        try {
            Asuransi::create($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Klaim asuransi berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Klaim asuransi gagal dibuat');
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
    public function edit($id)
    {
        $klaim_asuransi = Asuransi::with('user')->find($id);

        if (!$klaim_asuransi) {
            return redirect()->route('admin.klaim-asuransi.index')->with('error', 'Klaim asuransi tidak ditemukan');
        }

        $data = [
            'title' => 'Update Klaim Asuransi',
            'permohonan' => $klaim_asuransi,
        ];

        return view('pages.admin.klaim-asuransi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asuransi $klaim_asuransi)
    {
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
            'status' => 'required|string',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($klaim_asuransi->surat_permohonan) {
                Storage::disk('s3')->delete($klaim_asuransi->surat_permohonan);
            }

            $file = $request->file('surat_permohonan');
            $file_name = 'klaim-asuransi_user:' . $klaim_asuransi->user_id . '_date:' . Carbon::now() . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/asuransi', $file_name, 's3');
            $validated['surat_permohonan'] = $path_permohonan;
        }

        if ($request->hasFile('ktp')) {
            if ($klaim_asuransi->ktp) {
                Storage::disk('s3')->delete($klaim_asuransi->ktp);
            }

            $file = $request->file('ktp');
            $file_name = 'ktp_klaim-asuransi_user:' . $klaim_asuransi->user_id . '_date:' . Carbon::now() . '.' . $file->getClientOriginalExtension();
            $path_ktp = $file->storeAs('permohonan/asuransi', $file_name, 's3');
            $validated['ktp'] = $path_ktp;
        }

        try {
            $klaim_asuransi->update($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Klaim asuransi berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Klaim asuransi gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asuransi $asuransi)
    {
        try {
            $this->authorize('delete', $asuransi);

            // Delete associated files from Cloudflare S3
            if ($asuransi->surat_permohonan) {
                Storage::disk('s3')->delete($asuransi->surat_permohonan);
            }
            if ($asuransi->ktp) {
                Storage::disk('s3')->delete($asuransi->ktp);
            }

            $asuransi->delete();

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
            \Log::error('Admin Klaim Asuransi Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dihapus: ' . $error->getMessage());
        }
    }

    public function downloadFile($id, $fileName)
    {
        $asuransi = Asuransi::findOrFail($id);

        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $asuransi->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        $filePath = null;

        if ($asuransi->surat_permohonan && str_contains($asuransi->surat_permohonan, $fileName)) {
            $filePath = $asuransi->surat_permohonan;
        } elseif ($asuransi->ktp && str_contains($asuransi->ktp, $fileName)) {
            $filePath = $asuransi->ktp;
        }

        if (!$filePath || !Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }
}
