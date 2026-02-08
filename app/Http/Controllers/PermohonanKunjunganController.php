<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\TelegramService;
use Illuminate\Support\Facades\Storage;

class PermohonanKunjunganController extends Controller
{
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
            'rencana_kunjungan' => 'required|string|min:20|max:2000',
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
                \Log::info('Starting file upload: ' . $file->getClientOriginalName());
                \Log::info('Storage disk root: ' . Storage::disk('local')->path(''));
                
                // Create directory with full path
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Use simple filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing file as: ' . $filename . ' in ' . $directory);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                \Log::info('storeAs result: ' . var_export($result, true));
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['surat_permohonan'] = $result;
                    \Log::info('Surat Permohonan uploaded successfully: ' . $result . ' at ' . $storedPath);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
                }
            } catch (Exception $fileError) {
                \Log::error('Surat Permohonan upload error: ' . $fileError->getMessage());
                \Log::error('Stack trace: ' . $fileError->getTraceAsString());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $directory = 'permohonan/kunjungan';
                \Log::info('Starting file upload: ' . $file->getClientOriginalName());
                
                // Create directory with full path
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Use simple filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing file as: ' . $filename . ' in ' . $directory);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                \Log::info('storeAs result: ' . var_export($result, true));
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['ktp'] = $result;
                    \Log::info('KTP uploaded successfully: ' . $result . ' at ' . $storedPath);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
                }
            } catch (Exception $fileError) {
                \Log::error('KTP upload error: ' . $fileError->getMessage());
                \Log::error('Stack trace: ' . $fileError->getTraceAsString());
                return back()->withInput()->with('error', 'Error: ' . $fileError->getMessage());
            }
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        try {
            \Log::info('Creating Kunjungan with validated data', ['surat_permohonan' => $validated['surat_permohonan'] ?? 'not set', 'ktp' => $validated['ktp'] ?? 'not set']);
            $kunjungan = Kunjungan::create($validated);

            // Send Telegram notification with documents
            try {
                $suratPermohonanPath = null;
                $ktpPath = null;
                if (!empty($validated['surat_permohonan'])) {
                    $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                }
                if (!empty($validated['ktp'])) {
                    $ktpPath = Storage::disk('local')->path($validated['ktp']);
                }
                
                $telegramData = $kunjungan->toArray();
                $telegramData['ktp'] = $validated['ktp'] ?? null;
                
                if (($suratPermohonanPath && file_exists($suratPermohonanPath)) || ($ktpPath && file_exists($ktpPath))) {
                    $this->telegramService->sendPermohonanWithDocument('kunjungan', $telegramData, $suratPermohonanPath, $ktpPath);
                } else {
                    $this->telegramService->sendPermohonanNotification('kunjungan', $telegramData);
                }
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
            'rencana_kunjungan' => 'required|string|min:20|max:2000',
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
                
                // Create directory if it doesn't exist
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Delete old file if exists
                if ($kunjungan->surat_permohonan) {
                    Storage::disk('local')->delete($kunjungan->surat_permohonan);
                }
                
                // Create safe filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing surat_permohonan as: ' . $filename);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['surat_permohonan'] = $result;
                    \Log::info('Surat Permohonan updated successfully: ' . $result);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
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
                
                // Create directory if it doesn't exist
                $fullPath = Storage::disk('local')->path($directory);
                if (!is_dir($fullPath)) {
                    mkdir($fullPath, 0777, true);
                    \Log::info('Created directory: ' . $fullPath);
                }
                
                // Delete old file if exists
                if ($kunjungan->ktp) {
                    Storage::disk('local')->delete($kunjungan->ktp);
                }
                
                // Create safe filename
                $filename = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $file->getClientOriginalName());
                \Log::info('Storing KTP as: ' . $filename);
                
                // Store the file
                $result = $file->storeAs($directory, $filename, 'local');
                
                // Verify file exists
                $storedPath = Storage::disk('local')->path($result);
                if (file_exists($storedPath)) {
                    $validated['ktp'] = $result;
                    \Log::info('KTP updated successfully: ' . $result);
                } else {
                    \Log::error('File stored but not found at: ' . $storedPath);
                    return back()->withInput()->with('error', 'File tidak ditemukan setelah upload');
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
                Storage::disk('local')->delete($kunjungan->surat_permohonan);
                Log::info('Surat Permohonan deleted', ['file' => $kunjungan->surat_permohonan]);
            }
            if ($kunjungan->ktp) {
                Storage::disk('local')->delete($kunjungan->ktp);
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
        $this->authorize('view', $kunjungan);

        // Determine which file is being requested
        $fileField = null;
        if ($kunjungan->surat_permohonan && str_contains($kunjungan->surat_permohonan, $fileName)) {
            $fileField = 'surat_permohonan';
        } elseif ($kunjungan->ktp && str_contains($kunjungan->ktp, $fileName)) {
            $fileField = 'ktp';
        }

        if (!$fileField || !Storage::disk('local')->exists($kunjungan->$fileField)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($kunjungan->$fileField, $fileName);
    }
}
