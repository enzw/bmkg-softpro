<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Services\TelegramService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = Asuransi::all();
        $data = [
            'title' => 'Permohonan Kunjungan',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.klaim-asuransi', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        \Log::info('Form submission:', $request->all());
        
        $validated = $request->validate([
            'perusahaan' => 'required|string',
            'tanggal' => 'required|date',
            'jumlah_rombongan' => 'required|string',
            'nama_lengkap' => 'required|string',
            'nomor_whatsapp' => 'required|string',
            'kejadian' => 'required|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/asuransi';
                if (!Storage::disk('local')->exists($directory)) {
                    Storage::disk('local')->makeDirectory($directory, 0755, true);
                }
                
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                } else {
                    \Log::error('Surat Permohonan upload returned false');
                }
            } catch (Exception $fileError) {
                \Log::error('Surat Permohonan upload error: ' . $fileError->getMessage());
                return back()->with('error', 'Gagal upload surat permohonan: ' . $fileError->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $directory = 'permohonan/asuransi';
                if (!Storage::disk('local')->exists($directory)) {
                    Storage::disk('local')->makeDirectory($directory, 0755, true);
                }
                
                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['ktp'] = $path;
                } else {
                    \Log::error('KTP upload returned false');
                }
            } catch (Exception $fileError) {
                \Log::error('KTP upload error: ' . $fileError->getMessage());
                return back()->with('error', 'Gagal upload KTP: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();

        try {
            $asuransi = Asuransi::create($validated);
            
            // Send Telegram notification with documents
            try {
                $telegramService = new TelegramService();
                $user = Auth::user();
                
                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $user->email,
                    'no_whatsapp' => $validated['nomor_whatsapp'],
                    'jenis_asuransi' => $validated['perusahaan'],
                    'keterangan' => $validated['kejadian'] ?? '-',
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $asuransi->created_at->format('d-m-Y H:i'),
                ];
                
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
                // Continue even if telegram fails
            }
            
            return back()->with('success', 'Permohonan kunjungan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan kunjungan gagal dibuat');
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asuransi $permohonan_kunjungan)
    {
        try {
            // Check permission: only uploader or admin can delete
            $user = Auth::user();
            $isAdmin = $user && ($user->role === 'admin' || $user->role === 'superuser');
            $isOwner = $permohonan_kunjungan->user_id === $user?->id;
            
            if (!($isOwner || $isAdmin)) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus permohonan ini'], 403);
                }
                return back()->with('error', 'Anda tidak memiliki akses untuk menghapus permohonan ini');
            }
            
            // Delete associated file
            if ($permohonan_kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($permohonan_kunjungan->surat_permohonan);
            }
            
            $permohonan_kunjungan->delete();
            
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan kunjungan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan kunjungan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan kunjungan gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan kunjungan gagal dihapus');
        }
    }

    public function download(Asuransi $klaim_asuransi)
    {
        Storage::download($klaim_asuransi->surat_permohonan_klaim);
    }
}
