<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\SewaAlat;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SewaAlatController extends Controller
{
    public function index()
    {
        $alats = Alat::all();
        $permohonan = SewaAlat::where('user_id', Auth::id())->get();
        return view('pages.layanan.sewa-alat.index', ['permohonan' => $permohonan, 'alats' => $alats]);
    }

    public function create(Alat $alat)
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alat_id' => 'required',
            'banyak_unit' => 'required|numeric|min:1',
            'sewa_mulai' => 'required|date',
            'sewa_berakhir' => 'required|date|after_or_equal:sewa_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

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
                return back()->with('error', 'Gagal upload file: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();

        $alat_id = $validated['alat_id'];
        $sewa_mulai = $validated['sewa_mulai'];
        $sewa_berakhir = $validated['sewa_berakhir'];

        // Check if there is any existing rental for the same barang and overlapping dates
        $ada_sewa = SewaAlat::where('alat_id', $alat_id)
            ->where(function ($query) use ($sewa_mulai, $sewa_berakhir) {
                $query->where(function ($q) use ($sewa_mulai, $sewa_berakhir) {
                    $q->where('sewa_mulai', '>=', $sewa_mulai)
                        ->where('sewa_mulai', '<', $sewa_berakhir);
                })
                    ->orWhere(function ($q) use ($sewa_mulai, $sewa_berakhir) {
                        $q->where('sewa_berakhir', '>', $sewa_mulai)
                            ->where('sewa_berakhir', '<=', $sewa_berakhir);
                    });
            })
            ->first();

        try {
            SewaAlat::create($validated);
            return back()->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            \Log::error('Sewa Alat Error: ' . $error->getMessage());
            return back()->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
        }
    }

    public function destroy(SewaAlat $sewa_alat)
    {
        try {
            if ($sewa_alat->surat_permohonan) {
                Storage::disk('local')->delete($sewa_alat->surat_permohonan);
            }
            $sewa_alat->delete();
            return back()->with('success', 'Permohonan berhasil dibatalkan');
        } catch (Exception $error) {
            \Log::error('Sewa Alat Destroy Error: ' . $error->getMessage());
            return back()->with('error', 'Permohonan gagal dibatalkan: ' . $error->getMessage());
        }
    }

    public function download(SewaAlat $sewa_alat)
    {
        // Authorize - user can only download their own files
        if ($sewa_alat->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$sewa_alat->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        \Log::info('Download attempt - File path: ' . $sewa_alat->surat_permohonan);
        
        if (!Storage::disk('local')->exists($sewa_alat->surat_permohonan)) {
            \Log::error('File not found at path: ' . $sewa_alat->surat_permohonan);
            return back()->with('error', 'File permohonan tidak ditemukan di sistem');
        }

        try {
            // Extract original extension from stored path
            $extension = pathinfo($sewa_alat->surat_permohonan, PATHINFO_EXTENSION);
            $downloadName = 'surat-permohonan.' . $extension;
            
            \Log::info('Downloading file: ' . $sewa_alat->surat_permohonan . ' as ' . $downloadName);
            return Storage::disk('local')->download($sewa_alat->surat_permohonan, $downloadName);
        } catch (Exception $error) {
            \Log::error('Download Error: ' . $error->getMessage() . ' | Trace: ' . $error->getTraceAsString());
            return back()->with('error', 'Gagal mengunduh file: ' . $error->getMessage());
        }
    }
}
