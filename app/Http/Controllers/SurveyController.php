<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Survey;
use App\Services\TelegramService;
use App\Traits\HandlesFileDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

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
                // Ensure no special characters
                $fileName = str_replace([':', ' ', '(', ')'], '_', $fileName);
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
                // Ensure no special characters
                $fileName = str_replace([':', ' ', '(', ')'], '_', $fileName);
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
        // Authorize - user or admin
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'superuser' && $survey->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$survey->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
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
            // Find the record matching the filename
            $survey = Survey::where(function ($query) use ($fileName) {
                $query->where('surat_permohonan', 'LIKE', '%' . $fileName)
                    ->orWhere('ktp', 'LIKE', '%' . $fileName);
            })
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$survey) {
                \Log::warning("No database record found matching filename [{$fileName}] for Survey");
                abort(404, 'File tidak ditemukan di database.');
            }

            // Authorize
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'superuser' && $survey->user_id !== $user->id) {
                \Log::warning('Unauthorized file access attempt', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                ]);
                abort(403, 'Anda tidak memiliki akses ke file ini.');
            }

            // Use the actual path stored in database
            $filePath = str_contains($survey->surat_permohonan ?? '', $fileName)
                ? $survey->surat_permohonan
                : $survey->ktp;

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }
            \Log::error('Error downloading survey file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}
