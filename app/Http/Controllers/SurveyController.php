<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Survey;
use App\Services\TelegramService;
use App\Traits\HandlesFileDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SurveyController extends Controller
{
    use HandlesFileDownload;
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
                $directory = 'permohonan/survey';

                
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
                
                // Send notification (documents are on S3)
                $telegramService->sendPermohonanNotification('survey', $telegramData);
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
            // Delete associated files from Cloudflare S3
            if ($survey->surat_permohonan) {
                Storage::disk('s3')->delete($survey->surat_permohonan);
            }
            if ($survey->ktp) {
                Storage::disk('s3')->delete($survey->ktp);
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
        // Authorize - user can only download their own files
        if ($survey->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$survey->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        if (!Storage::disk('s3')->exists($survey->surat_permohonan)) {
            return back()->with('error', 'File tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($survey->surat_permohonan, 60);
    }

    /**
     * Simplified direct file download from permohonan/survey folder
     * Route: /permohonan/survey/{fileName}
     */
    public function downloadFileSimple($fileName)
    {
        try {
            // Construct full file path
            $filePath = 'permohonan/survey/' . $fileName;

            // Verify that the authenticated user has a record with this file
            $survey = Survey::where('user_id', Auth::id())
                ->where(function ($query) use ($filePath, $fileName) {
                    $query->where('surat_permohonan', $filePath)
                        ->orWhere('surat_permohonan', 'LIKE', '%' . $fileName)
                        ->orWhere('ktp', $filePath)
                        ->orWhere('ktp', 'LIKE', '%' . $fileName);
                })
                ->first();

            if (!$survey) {
                // Check if it's an admin trying to access
                $user = Auth::user();
                if (!$user || ($user->role !== 'admin' && $user->role !== 'superuser')) {
                    \Log::warning('Unauthorized file access attempt', [
                        'user_id' => Auth::id(),
                        'fileName' => $fileName,
                        'filePath' => $filePath
                    ]);
                    abort(403, 'Anda tidak memiliki akses ke file ini.');
                }
                
                // Admin is accessing, find the file across all users
                $survey = Survey::where(function ($query) use ($filePath, $fileName) {
                    $query->where('surat_permohonan', $filePath)
                        ->orWhere('surat_permohonan', 'LIKE', '%' . $fileName)
                        ->orWhere('ktp', $filePath)
                        ->orWhere('ktp', 'LIKE', '%' . $fileName);
                })->first();
                
                if (!$survey) {
                    \Log::warning('Survey file not found in database', [
                        'user_id' => Auth::id(),
                        'fileName' => $fileName,
                        'filePath' => $filePath
                    ]);
                    abort(404, 'File tidak ditemukan.');
                }
            }

            // Check if file exists in storage
            if (!Storage::disk('s3')->exists($filePath)) {
                \Log::warning('Survey file not found in S3 storage', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                    'filePath' => $filePath,
                    'stored_path' => $survey->surat_permohonan ?? $survey->ktp
                ]);
                abort(404, 'File tidak ditemukan di sistem penyimpanan.');
            }

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if (method_exists($e, 'getStatusCode') && in_array($e->getStatusCode(), [403, 404])) {
                throw $e;
            }
            \Log::error('Error downloading survey file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName,
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}
