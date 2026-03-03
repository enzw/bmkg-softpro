<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Enums\Status;
use App\Traits\HandlesFileDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Storage;
use Exception;

class PermohonanKunjunganController extends Controller
{
    use HandlesFileDownload;
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
        $this->middleware('auth');
    }

    public function create()
    {
        $permohonan = Auth::user()->kunjungans()->orderBy('created_at', 'desc')->get();
        return view('pages.layanan.permohonan-kunjungan', compact('permohonan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_kunjungan' => 'required|string|in:goes to BMKG,goes to school',
            'nama_instansi' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'jumlah_rombongan' => 'required|integer|min:1|max:1000',
            'rencana_kunjungan' => 'required|string|max:2000',
            'surat_permohonan' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Remove file objects from validated to avoid storing temp paths
        unset($validated['surat_permohonan'], $validated['ktp']);

        // Handle file upload
        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $directory = 'permohonan/kunjungan';
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters in filename (like :)
                $filename = str_replace([':', ' ', '(', ')'], '_', $filename);
                $result = $file->storeAs($directory, $filename, 's3');
                if ($result) {
                    $validated['surat_permohonan'] = $result;
                }
            } catch (Exception $fileError) {
                \Log::error('Surat Permohonan upload error: ' . $fileError->getMessage());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $directory = 'permohonan/kunjungan';
                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                // Ensure no special characters in filename
                $filename = str_replace([':', ' ', '(', ')'], '_', $filename);
                $result = $file->storeAs($directory, $filename, 's3');
                if ($result) {
                    $validated['ktp'] = $result;
                }
            } catch (Exception $fileError) {
                \Log::error('KTP upload error: ' . $fileError->getMessage());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = Status::MENUNGGU->value;

        try {
            \Log::info('Creating Kunjungan with validated data', ['surat_permohonan' => $validated['surat_permohonan'] ?? 'not set', 'ktp' => $validated['ktp'] ?? 'not set']);
            $kunjungan = Kunjungan::create($validated);

            // Send Telegram notification
            try {
                $telegramData = $kunjungan->toArray();
                $this->telegramService->sendPermohonanNotification('kunjungan', $telegramData);
            } catch (\Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }

            return redirect()->route('permohonan-kunjungan.create')
                ->with('success', 'Permohonan kunjungan berhasil dikirim. Tim BMKG akan menghubungi Anda melalui WhatsApp.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat mengirim permohonan. Silakan coba lagi.');
        }
    }

    public function show(Kunjungan $kunjungan)
    {
        $this->authorize('view', $kunjungan);
        return view('permohonan-kunjungan.show', compact('kunjungan'));
    }

    public function edit(Kunjungan $kunjungan)
    {
        $this->authorize('update', $kunjungan);
        return view('pages.layanan.permohonan-kunjungan-edit', compact('kunjungan'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $this->authorize('update', $kunjungan);

        $validated = $request->validate([
            'jenis_kunjungan' => 'required|string|in:goes to BMKG,goes to school',
            'nama_instansi' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'jumlah_rombongan' => 'required|integer|min:1|max:1000',
            'rencana_kunjungan' => 'required|string|max:2000',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Remove file objects from validated to avoid storing temp paths
        unset($validated['surat_permohonan'], $validated['ktp']);

        // Handle surat_permohonan upload
        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $directory = 'permohonan/kunjungan';

                // Delete old file if exists
                if ($kunjungan->surat_permohonan) {
                    Storage::disk('s3')->delete($kunjungan->surat_permohonan);
                }

                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $result = $file->storeAs($directory, $filename, 's3');

                if ($result) {
                    $validated['surat_permohonan'] = $result;
                }
            } catch (\Exception $fileError) {
                \Log::error('Surat Permohonan upload error: ' . $fileError->getMessage());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        // Handle KTP upload
        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $directory = 'permohonan/kunjungan';

                // Delete old file if exists
                if ($kunjungan->ktp) {
                    Storage::disk('s3')->delete($kunjungan->ktp);
                }

                $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $result = $file->storeAs($directory, $filename, 's3');
                if ($result) {
                    $validated['ktp'] = $result;
                }
            } catch (\Exception $fileError) {
                \Log::error('KTP upload error: ' . $fileError->getMessage());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        $kunjungan->update($validated);

        return redirect()->route('permohonan-kunjungan.create')
            ->with('success', 'Permohonan kunjungan berhasil diperbarui.');
    }

    public function destroy(Kunjungan $kunjungan)
    {
        Log::info('Destroy method called', [
            'kunjungan_id' => $kunjungan->id ?? 'null',
            'kunjungan_user_id' => $kunjungan->user_id ?? 'null',
        ]);

        try {
            // Check authorization
            $this->authorize('delete', $kunjungan);

            Log::info('Authorization passed', [
                'kunjungan_id' => $kunjungan->id,
            ]);

            // Delete the uploaded files if they exist
            if ($kunjungan->surat_permohonan) {
                Storage::disk('s3')->delete($kunjungan->surat_permohonan);
                Log::info('Surat Permohonan deleted', ['file' => $kunjungan->surat_permohonan]);
            }
            if ($kunjungan->ktp) {
                Storage::disk('s3')->delete($kunjungan->ktp);
                Log::info('KTP deleted', ['file' => $kunjungan->ktp]);
            }

            // Delete the record
            $deleted = $kunjungan->delete();

            Log::info('Record deleted', [
                'kunjungan_id' => $kunjungan->id,
                'deleted' => $deleted,
            ]);

            // Return response based on request type
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permohonan kunjungan berhasil dihapus.'
                ], 200);
            }

            return redirect()->route('permohonan-kunjungan.create')
                ->with('success', 'Permohonan kunjungan berhasil dihapus.');
        } catch (\Illuminate\Auth\Access\AuthorizationException $authError) {
            Log::warning('Authorization failed for delete', [
                'kunjungan_id' => $kunjungan->id,
                'user_id' => Auth::id(),
                'kunjungan_user_id' => $kunjungan->user_id,
                'user_is_admin' => Auth::user()?->is_admin,
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki izin untuk menghapus permohonan ini.'
                ], 403);
            }

            return redirect()->back()
                ->with('error', 'Anda tidak memiliki izin untuk menghapus permohonan ini.');
        } catch (\Exception $e) {
            Log::error('Error deleting permohonan kunjungan', [
                'kunjungan_id' => $kunjungan->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus permohonan kunjungan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Gagal menghapus permohonan kunjungan.');
        }
    }

    public function downloadFile($id, $fileName)
    {
        $kunjungan = Kunjungan::findOrFail($id);

        // Authorize the user
        $user = Auth::user();
        if ($user->role !== 'admin' && $user->role !== 'superuser' && $kunjungan->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengakses file ini.');
        }

        // Determine which file is being requested
        $filePath = null;
        if ($kunjungan->surat_permohonan && str_contains($kunjungan->surat_permohonan, $fileName)) {
            $filePath = $kunjungan->surat_permohonan;
        } elseif ($kunjungan->ktp && str_contains($kunjungan->ktp, $fileName)) {
            $filePath = $kunjungan->ktp;
        }

        if (!$filePath) {
            \Log::warning("File matching [{$fileName}] not found in database record for Kunjungan [{$kunjungan->id}]");
            abort(404, 'File tidak ditemukan.');
        }

        return $this->redirectToTemporaryUrl($filePath, 60);
    }

    /**
     * Simplified direct file download from permohonan/kunjungan folder
     * Route: /permohonan/permohonan-kunjungan/{fileName}
     */
    public function downloadFileSimple($fileName)
    {
        try {
            // Find the record that contains this filename
            $kunjungan = Kunjungan::where(function ($query) use ($fileName) {
                $query->where('surat_permohonan', 'LIKE', '%' . $fileName)
                    ->orWhere('ktp', 'LIKE', '%' . $fileName);
            })
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$kunjungan) {
                \Log::warning("No database record found matching filename [{$fileName}] for Kunjungan");
                abort(404, 'File tidak ditemukan di database.');
            }

            // Authorize
            $user = Auth::user();
            if ($user->role !== 'admin' && $user->role !== 'superuser' && $kunjungan->user_id !== $user->id) {
                \Log::warning('Unauthorized file access attempt', [
                    'user_id' => Auth::id(),
                    'fileName' => $fileName,
                ]);
                abort(403, 'Anda tidak memiliki akses ke file ini.');
            }

            // Use the actual path stored in database
            $filePath = str_contains($kunjungan->surat_permohonan ?? '', $fileName)
                ? $kunjungan->surat_permohonan
                : $kunjungan->ktp;

            return $this->redirectToTemporaryUrl($filePath, 60);
        } catch (\Exception $e) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                throw $e;
            }
            Log::error('Error downloading kunjungan file: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'fileName' => $fileName
            ]);
            abort(500, 'Terjadi kesalahan saat mengakses file.');
        }
    }
}
