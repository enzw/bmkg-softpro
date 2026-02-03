<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use App\Services\TelegramService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PemetaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = Pemetaan::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Permohonan Layanan Pemetaan',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.pemetaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan Layanan Pemetaan',
        ];

        return view('pages.layanan.pemetaan.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'email' => 'required|email',
            'keterangan' => 'nullable|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/pemetaan';
                if (!Storage::disk('local')->exists($directory)) {
                    Storage::disk('local')->makeDirectory($directory, 0755, true);
                }
                
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                } else {
                    return back()->with('error', 'Gagal upload file');
                }
            } catch (Exception $fileError) {
                return back()->with('error', 'Gagal upload file: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu';

        try {
            $pemetaan = Pemetaan::create($validated);
            
            // Send Telegram notification
            try {
                $telegramService = new TelegramService();
                
                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'no_whatsapp' => $validated['no_whatsapp'],
                    'area_pemetaan' => $validated['keterangan'] ?? '-',
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'created_at' => $pemetaan->created_at->format('d-m-Y H:i'),
                ];
                
                $telegramService->sendPermohonanNotification('pemetaan', $telegramData);
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }
            
            return back()->with('success', 'Permohonan layanan pemetaan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan pemetaan gagal dibuat: ' . $error->getMessage());
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
     * Show the form for editing the resource.
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
    public function destroy(Pemetaan $pemetaan)
    {
        try {
            if ($pemetaan->surat_permohonan) {
                Storage::disk('local')->delete($pemetaan->surat_permohonan);
            }
            $pemetaan->delete();
            return back()->with('success', 'Permohonan layanan pemetaan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan pemetaan gagal dihapus: ' . $error->getMessage());
        }
    }

    public function download(Pemetaan $pemetaan)
    {
        // This method can be used for downloading documents if needed in the future
        return back()->with('error', 'Download tidak tersedia untuk saat ini');
    }
}
