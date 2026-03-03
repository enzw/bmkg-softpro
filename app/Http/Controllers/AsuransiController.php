<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use App\Services\TelegramService;
use Carbon\Carbon;
use App\Traits\HandlesFileDownload;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class AsuransiController extends Controller
{
    use HandlesFileDownload;
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
            'nama_user' => 'required|string',
            'no_whatsapp' => 'required|string',
            'perusahaan' => 'required|string',
            'tanggal' => 'required|date',
            'lokasi' => 'required|string',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/asuransi';

                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters
                $fileName = str_replace([':', ' ', '(', ')'], '_', $fileName);
                $path = $file->storeAs($directory, $fileName, 's3');

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

                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters
                $fileName = str_replace([':', ' ', '(', ')'], '_', $fileName);
                $path = $file->storeAs($directory, $fileName, 's3');

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
                    'nama_lengkap' => $validated['nama_user'],
                    'email' => $user->email,
                    'no_whatsapp' => $validated['no_whatsapp'],
                    'jenis_asuransi' => $validated['perusahaan'],
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $asuransi->created_at->format('d-m-Y H:i'),
                ];

                // Send notification (documents are on S3)
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

            // Delete associated files from Cloudflare S3
            if ($permohonan_kunjungan->surat_permohonan) {
                Storage::disk('s3')->delete($permohonan_kunjungan->surat_permohonan);
            }
            if ($permohonan_kunjungan->ktp) {
                Storage::disk('s3')->delete($permohonan_kunjungan->ktp);
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
        // Authorize - user or admin
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'superuser' && $klaim_asuransi->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        $path = $klaim_asuransi->surat_permohonan ?? $klaim_asuransi->surat_permohonan_klaim;
        if (!$path) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        return $this->redirectToTemporaryUrl($path, 60);
    }

    public function downloadFile($id, $fileName)
    {
        try {
            $asuransi = Asuransi::findOrFail($id);

            // Authorization check - user or admin
            $user = Auth::user();
            $isAdmin = $user && ($user->role === 'admin' || $user->role === 'superuser');
            if ($asuransi->user_id !== $user?->id && !$isAdmin) {
                abort(403, 'Anda tidak memiliki akses ke file ini');
            }

            // Find matching path in database
            $filePath = null;
            if ($asuransi->surat_permohonan && str_contains($asuransi->surat_permohonan, $fileName)) {
                $filePath = $asuransi->surat_permohonan;
            } elseif ($asuransi->ktp && str_contains($asuransi->ktp, $fileName)) {
                $filePath = $asuransi->ktp;
            } elseif ($asuransi->surat_permohonan_klaim && str_contains($asuransi->surat_permohonan_klaim, $fileName)) {
                $filePath = $asuransi->surat_permohonan_klaim;
            }

            if (!$filePath) {
                \Log::warning("File matching [{$fileName}] not found in database for Asuransi ID [{$id}]");
                abort(404, 'File tidak ditemukan.');
            }

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }
            \Log::error('Error accessing asuransi file: ' . $e->getMessage());
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }

    /**
     * Simplified direct file download from permohonan/asuransi folder
     * Route: /permohonan/permohonan-asuransi/{fileName}
     */
    public function downloadFileSimple($fileName)
    {
        try {
            // Find the record matching the filename
            $asuransi = Asuransi::where(function ($query) use ($fileName) {
                $query->where('surat_permohonan', 'LIKE', '%' . $fileName)
                    ->orWhere('surat_permohonan_klaim', 'LIKE', '%' . $fileName)
                    ->orWhere('ktp', 'LIKE', '%' . $fileName);
            })
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$asuransi) {
                \Log::warning("No database record found matching filename [{$fileName}] for Asuransi");
                abort(404, 'File tidak ditemukan di database.');
            }

            // Authorize
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'superuser' && $asuransi->user_id !== $user->id) {
                \Log::warning('Unauthorized file access attempt', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                ]);
                abort(403, 'Anda tidak memiliki akses ke file ini.');
            }

            // Use the actual path stored in database
            $filePath = null;
            if (str_contains($asuransi->surat_permohonan ?? '', $fileName)) {
                $filePath = $asuransi->surat_permohonan;
            } elseif (str_contains($asuransi->surat_permohonan_klaim ?? '', $fileName)) {
                $filePath = $asuransi->surat_permohonan_klaim;
            } elseif (str_contains($asuransi->ktp ?? '', $fileName)) {
                $filePath = $asuransi->ktp;
            }

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }
            \Log::error('Error downloading asuransi file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}
