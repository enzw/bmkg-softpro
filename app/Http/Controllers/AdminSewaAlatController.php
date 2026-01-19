<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\SewaAlat;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminSewaAlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = [
            'title' => 'Sewa Alat',
            'permohonan' => SewaAlat::all(),
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
            'alat_id' => 'required',
            'banyak_unit' => 'required|numeric',
            'sewa_mulai' => 'required|date',
            'sewa_berakhir' => 'required|date|after_or_equal:sewa_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        // Handle file upload if present
        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/sewa-alat';
                if (!Storage::disk('local')->exists($directory)) {
                    Storage::disk('local')->makeDirectory($directory, 0755, true);
                }
                
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                    \Log::info('File uploaded successfully: ' . $path);
                } else {
                    \Log::error('File upload returned false');
                }
            } catch (Exception $fileError) {
                \Log::error('File upload error: ' . $fileError->getMessage());
                return redirect()->route('admin.sewa-alat.create')->with('error', 'Gagal upload file: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();

        try {
            SewaAlat::create($validated);
            return redirect()->route('admin.sewa-alat.create')->with('success', 'Permohonan berhasil dibuat');
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
    public function edit(string $id)
    {
        $alats = Alat::all();
        $permohonan = SewaAlat::where('id', $id)->first();

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
                    if ($sewa_alat->surat_permohonan && Storage::disk('local')->exists($sewa_alat->surat_permohonan)) {
                        Storage::disk('local')->delete($sewa_alat->surat_permohonan);
                    }

                    // Simpan file baru
                    $directory = 'permohonan/sewa-alat';
                    if (!Storage::disk('local')->exists($directory)) {
                        Storage::disk('local')->makeDirectory($directory, 0755, true);
                    }
                    
                    $file = $request->file('surat_permohonan');
                    $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs($directory, $fileName, 'local');
                    
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
            // hanya hapus kalau field gak null dan file ada
            if ($sewa_alat->surat_permohonan && Storage::disk('local')->exists($sewa_alat->surat_permohonan)) {
                Storage::disk('local')->delete($sewa_alat->surat_permohonan);
            }

            // hapus data DB
            $sewa_alat->delete();

            return back()->with('success', 'Permohonan berhasil dibatalkan');
        } catch (\Exception $error) {
            \Log::error('Sewa Alat Destroy Error: ' . $error->getMessage());
            return back()->with('error', 'Permohonan gagal dibatalkan');
        }
    }

    public function download(SewaAlat $sewa_alat)
    {
        if (!$sewa_alat->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        if (!Storage::disk('local')->exists($sewa_alat->surat_permohonan)) {
            return back()->with('error', 'File permohonan tidak ditemukan di sistem');
        }

        try {
            // Extract original extension from stored path
            $extension = pathinfo($sewa_alat->surat_permohonan, PATHINFO_EXTENSION);
            $downloadName = 'surat-permohonan.' . $extension;
            
            return Storage::disk('local')->download($sewa_alat->surat_permohonan, $downloadName);
        } catch (Exception $error) {
            \Log::error('Download Error: ' . $error->getMessage());
            return back()->with('error', 'Gagal mengunduh file: ' . $error->getMessage());
        }
    }
}
