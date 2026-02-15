<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\SewaAlat;
use Carbon\Carbon;
use App\Traits\HandlesFileDownload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminSewaAlatController extends Controller
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
            'title' => 'Sewa Alat',
            'permohonan' => SewaAlat::with(['user', 'alat'])->orderBy('created_at', 'desc')->get(),
        ];

        return view('pages.admin.sewa-alat.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $alats = Alat::all();

        $data = [
            'title' => 'Buat Permohonan',
            'alats' => $alats,
        ];

        return view('pages.admin.sewa-alat.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'alat_id' => 'required',
            'banyak_unit' => 'required|numeric',
            'sewa_mulai' => 'required|date',
            'sewa_berakhir' => 'required|date|after_or_equal:sewa_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        // Handle file upload if present
        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/sewa-alat';

                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                }
            } catch (Exception $fileError) {
                return back()->with('error', 'Gagal upload surat permohonan: ' . $fileError->getMessage());
            }
        }

        // Handle KTP file upload if present
        if ($request->hasFile('ktp')) {
            try {
                $directory = 'permohonan/sewa-alat';

                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                if ($path) {
                    $validated['ktp'] = $path;
                }
            } catch (Exception $fileError) {
                return back()->with('error', 'Gagal upload KTP: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();

        try {
            SewaAlat::create($validated);
            return redirect()->route('admin.sewa-alat.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            \Log::error('Admin Sewa Alat Error: ' . $error->getMessage());
            return redirect()->route('admin.sewa-alat.create')->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
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
    public function edit(SewaAlat $sewa_alat)
    {
        $alats = Alat::all();
        $permohonan = $sewa_alat;

        $data = [
            'title' => 'Update Permohonan',
            'alats' => $alats,
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.sewa-alat.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SewaAlat $sewa_alat)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'alat_id' => 'required',
            'banyak_unit' => 'required|numeric',
            'status' => 'required',
            'sewa_mulai' => 'required|date',
            'sewa_berakhir' => 'required|date|after_or_equal:sewa_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        try {
            // Cek apakah user upload file baru
            if ($request->hasFile('surat_permohonan')) {
                try {
                    // Hapus file lama kalau ada
                    if ($sewa_alat->surat_permohonan && Storage::disk('s3')->exists($sewa_alat->surat_permohonan)) {
                        Storage::disk('s3')->delete($sewa_alat->surat_permohonan);
                    }

                    // Simpan file baru
                    $directory = 'permohonan/sewa-alat';

                    $file = $request->file('surat_permohonan');
                    $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($directory, $fileName, 's3');

                    if ($path) {
                        $validated['surat_permohonan'] = $path;
                        \Log::info('File uploaded successfully: ' . $path);
                    } else {
                        \Log::error('File upload returned false');
                    }
                } catch (Exception $fileError) {
                    \Log::error('File upload error: ' . $fileError->getMessage());
                    return redirect()->route('admin.sewa-alat.edit', $sewa_alat->id)->with('error', 'Gagal upload file: ' . $fileError->getMessage());
                }
            }

            // Update data ke database
            $sewa_alat->update($validated);

            return redirect()->route('admin.sewa-alat.index')
                ->with('success', 'Permohonan berhasil diupdate');
        } catch (\Exception $error) {
            \Log::error('Update SewaAlat Error: ' . $error->getMessage());
            return redirect()->route('admin.sewa-alat.edit', $sewa_alat->id)
                ->with('error', 'Permohonan gagal diupdate: ' . $error->getMessage());
        }
    }

    public function destroy(SewaAlat $sewa_alat)
    {
        try {
            // Authorize the delete action
            $this->authorize('delete', $sewa_alat);

            // Delete files from Cloudflare S3
            if ($sewa_alat->surat_permohonan) {
                Storage::disk('s3')->delete($sewa_alat->surat_permohonan);
            }
            if ($sewa_alat->ktp) {
                Storage::disk('s3')->delete($sewa_alat->ktp);
            }

            // hapus data DB
            $sewa_alat->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dibatalkan']);
            }
            return back()->with('success', 'Permohonan berhasil dibatalkan');
        } catch (\Illuminate\Auth\Access\AuthorizationException $error) {
            \Log::warning('Sewa Alat Delete Unauthorized: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus permohonan ini'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus permohonan ini');
        } catch (\Exception $error) {
            \Log::error('Sewa Alat Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dibatalkan: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dibatalkan');
        }
    }

    public function download(SewaAlat $sewa_alat)
    {
        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $sewa_alat->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        if (!$sewa_alat->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        if (!Storage::disk('s3')->exists($sewa_alat->surat_permohonan)) {
            return back()->with('error', 'File permohonan tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($sewa_alat->surat_permohonan, 60);
    }

    /**
     * Download file
     */
    public function downloadFile($id, $fileName)
    {
        $sewaAlat = SewaAlat::findOrFail($id);

        // Authorization check - only admin or the owner can download
        if (Auth::user()->role !== 'admin' && Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'superuser' && Auth::id() !== $sewaAlat->user_id) {
            abort(403, 'Anda tidak memiliki akses ke file ini');
        }

        // Security: validate that the file belongs to this record
        // Check both surat_permohonan and ktp fields
        $filePath = null;

        if ($sewaAlat->surat_permohonan && str_contains($sewaAlat->surat_permohonan, $fileName)) {
            $filePath = $sewaAlat->surat_permohonan;
        } elseif ($sewaAlat->ktp && str_contains($sewaAlat->ktp, $fileName)) {
            $filePath = $sewaAlat->ktp;
        } else {
            abort(404, 'File tidak ditemukan.');
        }

        if (!Storage::disk('s3')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }
}
