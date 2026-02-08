<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Services\TelegramService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = Survey::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Permohonan Layanan Survey',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.survey.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan Layanan Survey',
        ];

        return view('pages.layanan.survey.create', $data);
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
                $directory = 'permohonan/survey';
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

        if ($request->hasFile('ktp')) {
            try {
                $directory = 'permohonan/survey';
                if (!Storage::disk('local')->exists($directory)) {
                    Storage::disk('local')->makeDirectory($directory, 0755, true);
                }
                
                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
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
        $validated['status'] = 'Menunggu';

        try {
            $survey = Survey::create($validated);
            
            // Send Telegram notification with document
            try {
                $telegramService = new TelegramService();
                
                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'no_whatsapp' => $validated['no_whatsapp'],
                    'lokasi_survey' => $validated['keterangan'] ?? '-',
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $survey->created_at->format('d-m-Y H:i'),
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
                    $telegramService->sendPermohonanWithDocument('survey', $telegramData, $suratPermohonanPath, $ktpPath);
                } else {
                    $telegramService->sendPermohonanNotification('survey', $telegramData);
                }
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }
            
            return back()->with('success', 'Permohonan layanan survey berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan survey gagal dibuat: ' . $error->getMessage());
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
    public function destroy(Survey $survey)
    {
        try {
            if ($survey->surat_permohonan) {
                Storage::disk('local')->delete($survey->surat_permohonan);
            }
            $survey->delete();
            return back()->with('success', 'Permohonan layanan survey berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan survey gagal dihapus: ' . $error->getMessage());
        }
    }

    public function download(Survey $survey)
    {
        // This method can be used for downloading documents if needed in the future
        return back()->with('error', 'Download tidak tersedia untuk saat ini');
    }
}
