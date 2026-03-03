<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\LayananData;
use App\Services\TelegramService;
use App\Traits\HandlesFileDownload;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LayananDataController extends Controller
{
    use HandlesFileDownload;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = LayananData::where('user_id', Auth::id())->get();
        $data = [
            'title' => 'Permohonan Layanan Data',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.layanan-data.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan Layanan Data',
        ];

        return view('pages.layanan.layanan-data.create', $data);
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
                $directory = 'permohonan/layanan-data';

                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters in filename
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
                $directory = 'permohonan/layanan-data';

                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters in filename
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
            $layananData = LayananData::create($validated);

            // Send Telegram notification with document
            try {
                $telegramService = new TelegramService();

                $telegramData = [
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                    'no_whatsapp' => $validated['no_whatsapp'],
                    'jenis_data' => $validated['keterangan'] ?? '-',
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $layananData->created_at->format('d-m-Y H:i'),
                ];

                // Send notification (documents are on S3)
                $telegramService->sendPermohonanNotification('layanan_data', $telegramData);
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }

            return back()->with('success', 'Permohonan layanan data berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan data gagal dibuat: ' . $error->getMessage());
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
    public function destroy(LayananData $layanan_data)
    {
        try {
            // Delete associated files from Cloudflare S3
            if ($layanan_data->surat_permohonan) {
                Storage::disk('s3')->delete($layanan_data->surat_permohonan);
            }
            if ($layanan_data->ktp) {
                Storage::disk('s3')->delete($layanan_data->ktp);
            }

            $layanan_data->delete();
            return back()->with('success', 'Permohonan layanan data berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan layanan data gagal dihapus: ' . $error->getMessage());
        }
    }

    public function download(LayananData $layanan_data)
    {
        // Authorize - user or admin
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'superuser' && $layanan_data->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$layanan_data->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        return $this->redirectToTemporaryUrl($layanan_data->surat_permohonan, 60);
    }

    /**
     * Simplified direct file download from permohonan/layanan-data folder
     * Route: /permohonan/layanan-data/{fileName}
     */
    public function downloadFileSimple($fileName)
    {
        try {
            // Find the record matching the filename
            $layanan_data = LayananData::where(function ($query) use ($fileName) {
                $query->where('surat_permohonan', 'LIKE', '%' . $fileName)
                    ->orWhere('ktp', 'LIKE', '%' . $fileName);
            })
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$layanan_data) {
                \Log::warning("No database record found matching filename [{$fileName}] for LayananData");
                abort(404, 'File tidak ditemukan di database.');
            }

            // Authorize
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'superuser' && $layanan_data->user_id !== $user->id) {
                \Log::warning('Unauthorized file access attempt', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                ]);
                abort(403, 'Anda tidak memiliki akses ke file ini.');
            }

            // Use the actual path stored in database
            $filePath = str_contains($layanan_data->surat_permohonan ?? '', $fileName)
                ? $layanan_data->surat_permohonan
                : $layanan_data->ktp;

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }
            \Log::error('Error downloading layanan data file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}
