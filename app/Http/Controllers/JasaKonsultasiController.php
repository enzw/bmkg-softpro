<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\JasaKonsultasi;
use App\Services\TelegramService;
use App\Traits\HandlesFileDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class JasaKonsultasiController extends Controller
{
    use HandlesFileDownload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = JasaKonsultasi::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Permohonan Jasa Konsultasi',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.jasa-konsultasi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan Jasa Konsultasi',
        ];

        return view('pages.layanan.jasa-konsultasi.create', $data);
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
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/jasa-konsultasi';
                
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                } else {
                    return back()->with('error', 'Gagal upload file');
                }
            } catch (Exception $fileError) {
                return back()->with('error', 'Gagal upload file: ' . $fileError->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $directory = 'permohonan/jasa-konsultasi';
                
                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                
                if ($path) {
                    $validated['ktp'] = $path;
                } else {
                    return back()->with('error', 'Gagal upload KTP file');
                }
            } catch (Exception $fileError) {
                return back()->with('error', 'Gagal upload KTP file: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = Status::MENUNGGU->value;

        try {
            $jasaKonsultasi = JasaKonsultasi::create($validated);
            
            // Send Telegram notification with document
            try {
                $telegramService = new TelegramService();
                
                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'no_whatsapp' => $validated['no_whatsapp'],
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $jasaKonsultasi->created_at->format('d-m-Y H:i'),
                ];
                
                // Send notification (documents are on S3)
                $telegramService->sendPermohonanNotification('jasa_konsultasi', $telegramData);
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }
            
            return back()->with('success', 'Permohonan jasa konsultasi berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan jasa konsultasi gagal dibuat: ' . $error->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(JasaKonsultasi $jasaKonsultasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(JasaKonsultasi $jasaKonsultasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JasaKonsultasi $jasaKonsultasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(JasaKonsultasi $jasa_konsultasi)
    {
        // Check if the jasa_konsultasi belongs to the current user
        if ($jasa_konsultasi->user_id !== Auth::id()) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus permohonan ini'], 403);
            }
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus permohonan ini');
        }

        try {
            // Delete associated files from Cloudflare S3
            if ($jasa_konsultasi->surat_permohonan) {
                Storage::disk('s3')->delete($jasa_konsultasi->surat_permohonan);
            }
            if ($jasa_konsultasi->ktp) {
                Storage::disk('s3')->delete($jasa_konsultasi->ktp);
            }
            
            $jasa_konsultasi->delete();
            
            // Return JSON if it's an AJAX request, otherwise redirect
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Permohonan jasa konsultasi berhasil dihapus'], 200);
            }
            return back()->with('success', 'Permohonan jasa konsultasi berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            $message = 'Permohonan jasa konsultasi gagal dihapus: ' . $error->getMessage();
            
            if (request()->expectsJson()) {
                return response()->json(['message' => $message], 500);
            }
            return back()->with('error', $message);
        }
    }

    public function download(JasaKonsultasi $jasa_konsultasi)
    {
        // Authorize - user can only download their own files
        if ($jasa_konsultasi->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$jasa_konsultasi->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        if (!Storage::disk('s3')->exists($jasa_konsultasi->surat_permohonan)) {
            return back()->with('error', 'File tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($jasa_konsultasi->surat_permohonan, 60);
    }
}
