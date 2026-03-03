<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\SewaAlat;
use App\Services\TelegramService;
use App\Traits\HandlesFileDownload;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SewaAlatController extends Controller
{
    use HandlesFileDownload;

    public function index()
    {
        $alats = Alat::all();
        $permohonan = SewaAlat::where('user_id', Auth::id())->get();
        return view('pages.layanan.sewa-alat.index', ['permohonan' => $permohonan, 'alats' => $alats]);
    }

    public function create(Alat $alat)
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'alat_id' => 'required',
            'banyak_unit' => 'required|numeric|min:1',
            'sewa_mulai' => 'required|date',
            'sewa_berakhir' => 'required|date|after_or_equal:sewa_mulai',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'keterangan' => 'nullable',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            try {
                $directory = 'permohonan/sewa-alat';

                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                }
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                    \Log::info('File uploaded successfully: ' . $path);
                } else {
                    \Log::error('File upload returned false');
                }
            } catch (Exception $fileError) {
                \Log::error('File upload error: ' . $fileError->getMessage());
                return back()->with('error', 'Gagal upload file: ' . $fileError->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $directory = 'permohonan/sewa-alat';

                $file = $request->file('ktp');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 's3');
                if ($path) {
                    $validated['ktp'] = $path;
                }
                if ($path) {
                    $validated['ktp'] = $path;
                    \Log::info('KTP file uploaded successfully: ' . $path);
                } else {
                    \Log::error('KTP file upload returned false');
                }
            } catch (Exception $fileError) {
                \Log::error('KTP file upload error: ' . $fileError->getMessage());
                return back()->with('error', 'Gagal upload KTP file: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();

        $alat_id = $validated['alat_id'];
        $sewa_mulai = $validated['sewa_mulai'];
        $sewa_berakhir = $validated['sewa_berakhir'];

        // Check if there is any existing rental for the same barang and overlapping dates
        $ada_sewa = SewaAlat::where('alat_id', $alat_id)
            ->where(function ($query) use ($sewa_mulai, $sewa_berakhir) {
                $query->where(function ($q) use ($sewa_mulai, $sewa_berakhir) {
                    $q->where('sewa_mulai', '>=', $sewa_mulai)
                        ->where('sewa_mulai', '<', $sewa_berakhir);
                })
                    ->orWhere(function ($q) use ($sewa_mulai, $sewa_berakhir) {
                        $q->where('sewa_berakhir', '>', $sewa_mulai)
                            ->where('sewa_berakhir', '<=', $sewa_berakhir);
                    });
            })
            ->first();

        // Set default status for new rental request
        $validated['status'] = \App\Enums\SewaStatus::BELUM_LUNAS;

        try {
            $sewaAlat = SewaAlat::create($validated);

            // Send Telegram notification
            try {
                $telegramService = new TelegramService();
                $alat = Alat::find($validated['alat_id']);
                $user = Auth::user();

                $telegramData = [
                    'user_name' => $user->name,
                    'email' => $user->email,
                    'no_whatsapp' => $validated['no_whatsapp'] ?? '-',
                    'nama' => $validated['nama'] ?? '-',
                    'alat_name' => $alat->nama ?? '-',
                    'banyak_unit' => $validated['banyak_unit'],
                    'sewa_mulai' => $validated['sewa_mulai'],
                    'sewa_berakhir' => $validated['sewa_berakhir'],
                    'keterangan' => $validated['keterangan'] ?? '-',
                    'surat_permohonan' => $validated['surat_permohonan'] ?? null,
                    'ktp' => $validated['ktp'] ?? null,
                    'created_at' => $sewaAlat->created_at->format('d-m-Y H:i'),
                ];

                // Send notification (documents are on S3)
                $telegramService->sendPermohonanNotification('sewa_alat', $telegramData);
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }

            return back()->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            \Log::error('Sewa Alat Error: ' . $error->getMessage());
            return back()->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
        }
    }

    public function destroy(SewaAlat $sewa_alat)
    {
        try {
            // Authorize the delete action via policy
            $this->authorize('delete', $sewa_alat);

            // Delete associated files from Cloudflare S3
            if ($sewa_alat->surat_permohonan) {
                Storage::disk('s3')->delete($sewa_alat->surat_permohonan);
            }
            if ($sewa_alat->ktp) {
                Storage::disk('s3')->delete($sewa_alat->ktp);
            }

            $sewa_alat->delete();

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dibatalkan']);
            }
            return back()->with('success', 'Permohonan berhasil dibatalkan');
        } catch (Exception $error) {
            \Log::error('Sewa Alat Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dibatalkan: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dibatalkan: ' . $error->getMessage());
        }
    }

    public function download(SewaAlat $sewa_alat)
    {
        // Authorize - user can only download their own files
        if ($sewa_alat->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses ke file ini');
        }

        if (!$sewa_alat->surat_permohonan) {
            return back()->with('error', 'File permohonan tidak tersedia');
        }

        if (!Storage::disk('s3')->exists($sewa_alat->surat_permohonan)) {
            return back()->with('error', 'File permohonan tidak ditemukan di sistem');
        }

        return $this->redirectToTemporaryUrl($sewa_alat->surat_permohonan, 60);
    }

    public function downloadFile(SewaAlat $sewaAlat, $fileName)
    {
        try {
            // Authorize - user can only download their own files
            if ($sewaAlat->user_id !== Auth::id()) {
                abort(403, 'Anda tidak memiliki akses ke file ini');
            }

            // Check if file is surat_permohonan
            $filePath = null;
            if ($sewaAlat->surat_permohonan && basename($sewaAlat->surat_permohonan) === $fileName) {
                $filePath = $sewaAlat->surat_permohonan;
            }
            // Check if file is ktp
            elseif ($sewaAlat->ktp && basename($sewaAlat->ktp) === $fileName) {
                $filePath = $sewaAlat->ktp;
            }

            if (!$filePath) {
                abort(404, 'File tidak ditemukan.');
            }

            if (!Storage::disk('s3')->exists($filePath)) {
                abort(404, 'File tidak ditemukan di sistem.');
            }

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            \Log::error('Error download file: ' . $e->getMessage());
            abort(500, 'Error mengakses file.');
        }
    }

    /**
     * Simplified direct file download from permohonan/sewa-alat folder
     * Route: /permohonan/sewa-alat/{fileName}
     */
    public function downloadFileSimple($fileName)
    {
        try {
            // Construct full file path in permohonan/sewa-alat folder
            $filePath = 'permohonan/sewa-alat/' . $fileName;

            // Verify that the authenticated user has a record with this file
            $sewaAlat = SewaAlat::where('user_id', Auth::id())
                ->where(function ($query) use ($filePath, $fileName) {
                    $query->where('surat_permohonan', $filePath)
                        ->orWhere('surat_permohonan', 'LIKE', '%' . $fileName)
                        ->orWhere('ktp', $filePath)
                        ->orWhere('ktp', 'LIKE', '%' . $fileName);
                })
                ->first();

            if (!$sewaAlat) {
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
                $sewaAlat = SewaAlat::where(function ($query) use ($filePath, $fileName) {
                    $query->where('surat_permohonan', $filePath)
                        ->orWhere('surat_permohonan', 'LIKE', '%' . $fileName)
                        ->orWhere('ktp', $filePath)
                        ->orWhere('ktp', 'LIKE', '%' . $fileName);
                })->first();
                
                if (!$sewaAlat) {
                    \Log::warning('File not found in database', [
                        'user_id' => Auth::id(),
                        'fileName' => $fileName,
                        'filePath' => $filePath
                    ]);
                    abort(404, 'File tidak ditemukan.');
                }
            }

            // Check if file exists in S3
            if (!Storage::disk('s3')->exists($filePath)) {
                \Log::warning('File not found in S3 storage', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                    'filePath' => $filePath,
                    'stored_path' => $sewaAlat->surat_permohonan ?? $sewaAlat->ktp
                ]);
                abort(404, 'File tidak ditemukan di sistem penyimpanan.');
            }

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if (method_exists($e, 'getStatusCode') && in_array($e->getStatusCode(), [403, 404])) {
                throw $e;
            }
            \Log::error('Error downloading file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName,
                'trace' => $e->getTraceAsString()
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}

