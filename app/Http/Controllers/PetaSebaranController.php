<?php

namespace App\Http\Controllers;

use App\Models\PetaSebaran;
use App\Services\TelegramService;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetaSebaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = PetaSebaran::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Peta Sebaran',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.peta-sebaran', $data);
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
        $validated = $request->validate([
            'perusahaan' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'kejadian' => 'required|string',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            $petaSebaran = PetaSebaran::create($validated);
            
            // Send Telegram notification
            try {
                $telegramService = new TelegramService();
                $user = Auth::user();
                
                $telegramData = [
                    'nama_lengkap' => $user->name,
                    'email' => $user->email,
                    'no_whatsapp' => $user->telp ?? '-',
                    'keterangan' => $validated['kejadian'] ?? '-',
                    'created_at' => $petaSebaran->created_at->format('d-m-Y H:i'),
                ];
                
                $telegramService->sendPermohonanNotification('peta_sebaran', $telegramData);
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
    public function destroy(PetaSebaran $klaim_asuransi)
    {
        try {
            // Storage::delete($permohonan_asuransi->surat_permohonan_klaim);
            $klaim_asuransi->delete();
            return back()->with('success', 'Permohonan kunjungan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan kunjungan gagal dihapus');
        }
    }

    public function download(PetaSebaran $klaim_asuransi)
    {
        Storage::download($klaim_asuransi->surat_permohonan_klaim);
    }
}
