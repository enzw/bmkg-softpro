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
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('permohonan-kunjungan', $filename, 'local');
            $validated['surat_permohonan'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        try {
            $kunjungan = Kunjungan::create($validated);

            // Send Telegram notification with document
            try {
                $documentPath = null;
                if (!empty($validated['surat_permohonan'])) {
                    $documentPath = storage_path('app/' . $validated['surat_permohonan']);
                }
                
                $this->telegramService->sendPermohonanWithDocument('kunjungan', $kunjungan->toArray(), $documentPath);
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
        return view('permohonan-kunjungan.edit', compact('kunjungan'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $this->authorize('update', $kunjungan);

        $validated = $request->validate([
            'jenis_kunjungan' => 'required|string|in:Survey,Inspeksi,Konsultasi,Pelatihan,Kunjungan Kerja Sama',
            'nama_instansi' => 'required|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'jumlah_rombongan' => 'required|integer|min:1|max:1000',
            'rencana_kunjungan' => 'required|string|min:20|max:2000',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($kunjungan->surat_permohonan);
            }
            $file = $request->file('surat_permohonan');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('permohonan-kunjungan', $filename, 'local');
            $validated['surat_permohonan'] = $path;
        }

        $kunjungan->update($validated);

        return redirect()->route('dashboard')
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
            
            // Delete the uploaded file if exists
            if ($kunjungan->surat_permohonan) {
                Storage::disk('local')->delete($kunjungan->surat_permohonan);
                Log::info('File deleted', ['file' => $kunjungan->surat_permohonan]);
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

        // Security: validate that the file belongs to this record
        if (!$kunjungan->surat_permohonan || !str_contains($kunjungan->surat_permohonan, $fileName)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Check if file exists in private storage
        if (!Storage::disk('local')->exists($kunjungan->surat_permohonan)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('local')->download($kunjungan->surat_permohonan, $fileName);
    }
}
