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
        $validated = $request->validate([
            'perusahaan' => 'required',
            'tanggal' => 'required|date',
            'jumlah_rombongan' => 'required',
            'nama_lengkap' => 'required',
            'nomor_whatsapp' => 'required',
            'kejadian'=> 'required',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            $asuransi = Asuransi::create($validated);
            
            // Send Telegram notification
            try {
                $telegramService = new TelegramService();
                $user = Auth::user();
                
                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $user->email,
                    'no_whatsapp' => $validated['nomor_whatsapp'],
                    'jenis_asuransi' => $validated['perusahaan'],
                    'keterangan' => $validated['kejadian'] ?? '-',
                    'created_at' => $asuransi->created_at->format('d-m-Y H:i'),
                ];
                
                $telegramService->sendPermohonanNotification('asuransi', $telegramData);
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
