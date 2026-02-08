<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use App\Models\Kunjungan;
use App\Services\TelegramService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        
        // Fetch dari semua tabel layanan
        $magang = Magang::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Magang';
            $item->table_name = 'magang';
            return $item;
        });
        
        $asuransi = Asuransi::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Klaim Asuransi';
            $item->table_name = 'asuransi';
            return $item;
        });
        
        $layanan_data = LayananData::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Data';
            $item->table_name = 'layanan_data';
            return $item;
        });
        
        $survey = Survey::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Survey';
            $item->table_name = 'survey';
            return $item;
        });
        
        $jasa_konsultasi = JasaKonsultasi::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Konsultasi';
            $item->table_name = 'jasa_konsultasi';
            return $item;
        });
        
        // Gabungkan semua permohonan (tanpa kunjungan - punya halaman sendiri)
        $permohonan = collect()
            ->merge($magang)
            ->merge($asuransi)
            ->merge($layanan_data)
            ->merge($survey)
            ->merge($jasa_konsultasi)
            ->sortByDesc('created_at');
        
        $data = [
            'title' => 'Permohonan Pelayanan Jasa',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.permohonan-magang', $data);
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
        $jenis_layanan = $request->input('jenis_layanan');
        
        // Determine model based on jenis_layanan
        $model = match($jenis_layanan) {
            'Magang' => Magang::class,
            'Layanan Kunjungan Teknis' => Kunjungan::class,
            'Layanan Klaim Asuransi' => Asuransi::class,
            'Layanan Data' => LayananData::class,
            'Layanan Survey' => Survey::class,
            'Layanan Konsultasi' => JasaKonsultasi::class,
            default => null
        };
        
        if (!$model) {
            return back()->with('error', 'Jenis layanan tidak valid');
        }
        
        // Validate based on service type
        $validated = $this->validateByServiceType($request, $jenis_layanan);
        
        if ($validated instanceof \Illuminate\Http\RedirectResponse) {
            return $validated;
        }
        
        // Handle file upload BEFORE saving to database
        if ($request->hasFile('surat_permohonan')) {
            try {
                // Determine directory name based on service type
                $directoryMap = [
                    'Magang' => 'permohonan/magang',
                    'Layanan Kunjungan Teknis' => 'permohonan/kunjungan-teknis',
                    'Layanan Klaim Asuransi' => 'permohonan/layanan-asuransi',
                    'Layanan Data' => 'permohonan/layanan-data',
                    'Layanan Survey' => 'permohonan/layanan-survey',
                    'Layanan Konsultasi' => 'permohonan/layanan-konsultasi',
                ];
                
                $directory = $directoryMap[$jenis_layanan] ?? 'permohonan/lainnya';
                
                // Store file in private local disk (NOT public for security)
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['surat_permohonan'] = $path;
                }
            } catch (Exception $fileError) {
                report($fileError->getMessage());
                return back()->with('error', 'Gagal upload file: ' . $fileError->getMessage())->withInput();
            }
        }

        // Handle kartu_identitas upload untuk Kunjungan
        if ($request->hasFile('kartu_identitas') && in_array($jenis_layanan, ['Layanan Kunjungan Teknis'])) {
            try {
                $directoryMap = [
                    'Layanan Kunjungan Teknis' => 'permohonan/kunjungan-teknis',
                ];
                
                $directory = $directoryMap[$jenis_layanan] ?? 'permohonan/lainnya';
                
                $file = $request->file('kartu_identitas');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['kartu_identitas'] = $path;
                }
            } catch (Exception $fileError) {
                report($fileError->getMessage());
                return back()->with('error', 'Gagal upload kartu identitas: ' . $fileError->getMessage())->withInput();
            }
        }

        // Handle kartu_mahasiswa upload untuk Magang
        if ($request->hasFile('kartu_mahasiswa') && in_array($jenis_layanan, ['Magang'])) {
            try {
                $directoryMap = [
                    'Magang' => 'permohonan/magang',
                ];
                
                $directory = $directoryMap[$jenis_layanan] ?? 'permohonan/lainnya';
                
                $file = $request->file('kartu_mahasiswa');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path) {
                    $validated['kartu_mahasiswa'] = $path;
                }
            } catch (Exception $fileError) {
                report($fileError->getMessage());
                return back()->with('error', 'Gagal upload kartu mahasiswa: ' . $fileError->getMessage())->withInput();
            }
        }


        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu';
        
        // Auto-fill dari user profile jika tidak diisi
        $user = Auth::user();
        
        // Auto-fill untuk semua service yang punya field nama_lengkap
        if (in_array($jenis_layanan, ['Magang', 'Layanan Data', 'Layanan Survey', 'Layanan Konsultasi'])) {
            if (empty($validated['nama_lengkap'])) {
                $validated['nama_lengkap'] = $user->name;
            }
            if (empty($validated['no_whatsapp'])) {
                $validated['no_whatsapp'] = $user->telp;
            }
            if (empty($validated['email'])) {
                $validated['email'] = $user->email;
            }
        }
        
        // Auto-fill untuk Kunjungan dan Asuransi
        if (in_array($jenis_layanan, ['Layanan Kunjungan Teknis', 'Layanan Klaim Asuransi'])) {
            if (empty($validated['no_whatsapp'])) {
                $validated['no_whatsapp'] = $user->telp;
            }
        }
        
        try {
            $permohonan = $model::create($validated);
            
            // Send Telegram notification with document
            try {
                $telegramService = new TelegramService();
                $user = Auth::user();
                
                // Map jenis_layanan to telegram type
                $telegramTypeMap = [
                    'Magang' => 'magang',
                    'Layanan Kunjungan Teknis' => 'kunjungan',
                    'Layanan Klaim Asuransi' => 'asuransi',
                    'Layanan Data' => 'layanan_data',
                    'Layanan Survey' => 'survey',
                    'Layanan Konsultasi' => 'jasa_konsultasi',
                ];
                
                $telegramType = $telegramTypeMap[$jenis_layanan] ?? null;
                
                if ($telegramType) {
                    $telegramData = $validated;
                    $telegramData['created_at'] = $permohonan->created_at->format('d-m-Y H:i');
                    
                    // Get the full paths to documents if they exist
                    $suratPermohonanPath = null;
                    if (!empty($validated['surat_permohonan'])) {
                        $suratPermohonanPath = Storage::disk('local')->path($validated['surat_permohonan']);
                    }
                    
                    // Send notification with documents
                    if ($suratPermohonanPath && file_exists($suratPermohonanPath)) {
                        $telegramService->sendPermohonanWithDocument($telegramType, $telegramData, $suratPermohonanPath, null);
                    } else {
                        $telegramService->sendPermohonanNotification($telegramType, $telegramData);
                    }
                }
            } catch (Exception $telegramError) {
                \Log::warning('Telegram notification failed: ' . $telegramError->getMessage());
                // Continue even if telegram fails
            }
            
            return back()->with('success', "Permohonan $jenis_layanan berhasil dibuat");
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', "Permohonan $jenis_layanan gagal dibuat: " . $error->getMessage());
        }
    }
    
    private function validateByServiceType(Request $request, string $jenis_layanan)
    {
        $rules = [
            'jenis_layanan' => 'required|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
        
        match($jenis_layanan) {
            'Magang' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'universitas' => 'required|string',
                'fakultas' => 'required|string',
                'prodi' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'kartu_mahasiswa' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]),
            'Layanan Kunjungan Teknis' => $rules = array_merge($rules, [
                'nama_user' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'lokasi' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kartu_identitas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]),
            'Layanan Klaim Asuransi' => $rules = array_merge($rules, [
                'nama_user' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'lokasi' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'perusahaan' => 'required|string',
                'kejadian' => 'required|string',
            ]),
            'Layanan Data' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Survey' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Konsultasi' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            default => null
        };
        
        $validated = $request->validate($rules);
        
        return $validated;
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
        // Find permohonan in all possible tables
        $permohonan = Magang::find($id) 
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
            ?? Survey::find($id)
            ?? JasaKonsultasi::find($id);

        if (!$permohonan) {
            return redirect()->route('pelayanan-jasa.index')->with('error', 'Permohonan tidak ditemukan');
        }

        // Pastikan user hanya bisa edit miliknya sendiri
        $this->authorize('update', $permohonan);

        // Pass ke view dengan jenis_layanan type
        $jenis_layanan = match(get_class($permohonan)) {
            Magang::class => 'Magang',
            Asuransi::class => 'Layanan Klaim Asuransi',
            LayananData::class => 'Layanan Data',
            Survey::class => 'Layanan Survey',
            JasaKonsultasi::class => 'Layanan Konsultasi',
            default => null
        };

        return view('pages.layanan.permohonan-magang-edit', compact('permohonan', 'jenis_layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find dalam semua tabel yang mungkin
        $permohonan = Magang::find($id) 
            ?? Asuransi::find($id)
            ?? LayananData::find($id)
            ?? Survey::find($id)
            ?? JasaKonsultasi::find($id);

        if (!$permohonan) {
            return back()->with('error', 'Permohonan tidak ditemukan');
        }

        // Pastikan user hanya bisa edit miliknya sendiri
        $this->authorize('update', $permohonan);

        // Tentukan jenis_layanan berdasarkan class
        $jenis_layanan = match(get_class($permohonan)) {
            Magang::class => 'Magang',
            Asuransi::class => 'Layanan Klaim Asuransi',
            LayananData::class => 'Layanan Data',
            Survey::class => 'Layanan Survey',
            JasaKonsultasi::class => 'Layanan Konsultasi',
            default => null
        };

        if (!$jenis_layanan) {
            return back()->with('error', 'Jenis layanan tidak valid');
        }

        // Validate based on service type (with optional files)
        $validated = $this->validateByServiceTypeForUpdate($request, $jenis_layanan);

        if ($validated instanceof \Illuminate\Http\RedirectResponse) {
            return $validated;
        }

        // Handle surat_permohonan upload (optional)
        if ($request->hasFile('surat_permohonan')) {
            try {
                $directoryMap = [
                    'Magang' => 'permohonan/magang',
                    'Layanan Kunjungan Teknis' => 'permohonan/kunjungan-teknis',
                    'Layanan Klaim Asuransi' => 'permohonan/layanan-asuransi',
                    'Layanan Data' => 'permohonan/layanan-data',
                    'Layanan Survey' => 'permohonan/layanan-survey',
                    'Layanan Konsultasi' => 'permohonan/layanan-konsultasi',
                ];
                
                $directory = $directoryMap[$jenis_layanan] ?? 'permohonan/lainnya';
                
                // Delete old file
                if ($permohonan->surat_permohonan) {
                    Storage::disk('local')->delete($permohonan->surat_permohonan);
                }
                
                // Store new file
                $file = $request->file('surat_permohonan');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path && file_exists(Storage::disk('local')->path($path))) {
                    $validated['surat_permohonan'] = $path;
                }
            } catch (Exception $fileError) {
                \Log::warning('File upload error: ' . $fileError->getMessage());
                // Continue without file
            }
        }

        // Handle kartu_identitas upload untuk Kunjungan (optional)
        if ($request->hasFile('kartu_identitas') && in_array($jenis_layanan, ['Layanan Kunjungan Teknis'])) {
            try {
                $directory = 'permohonan/kunjungan-teknis';
                
                // Delete old file
                if ($permohonan->kartu_identitas ?? null) {
                    Storage::disk('local')->delete($permohonan->kartu_identitas);
                }
                
                // Store new file
                $file = $request->file('kartu_identitas');
                $fileName = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs($directory, $fileName, 'local');
                
                if ($path && file_exists(Storage::disk('local')->path($path))) {
                    $validated['kartu_identitas'] = $path;
                }
            } catch (Exception $fileError) {
                \Log::warning('File upload error: ' . $fileError->getMessage());
                // Continue without file
            }
        }

        try {
            $permohonan->update($validated);
            return back()->with('success', "Permohonan $jenis_layanan berhasil diperbarui");
        } catch (Exception $error) {
            \Log::error('Update error: ' . $error->getMessage());
            return back()->with('error', "Permohonan $jenis_layanan gagal diperbarui: " . $error->getMessage())->withInput();
        }
    }

    private function validateByServiceTypeForUpdate(Request $request, string $jenis_layanan)
    {
        $rules = [
            'jenis_layanan' => 'required|string',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ];
        
        match($jenis_layanan) {
            'Magang' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'universitas' => 'required|string',
                'fakultas' => 'required|string',
                'prodi' => 'required|string',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date',
                'kartu_mahasiswa' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            ]),
            'Layanan Kunjungan Teknis' => $rules = array_merge($rules, [
                'nama_user' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'lokasi' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kartu_identitas' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]),
            'Layanan Klaim Asuransi' => $rules = array_merge($rules, [
                'nama_user' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'tanggal' => 'nullable|date',
                'lokasi' => 'nullable|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'perusahaan' => 'required|string',
                'kejadian' => 'required|string',
            ]),
            'Layanan Data' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Survey' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Konsultasi' => $rules = array_merge($rules, [
                'nama_lengkap' => 'nullable|string',
                'no_whatsapp' => 'nullable|string',
                'email' => 'nullable|email',
                'keterangan' => 'nullable|string',
            ]),
            default => null
        };
        
        $validated = $request->validate($rules);
        
        return $validated;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Try to delete from each service table
            $tables = [
                Asuransi::class,
                LayananData::class,
                Survey::class,
                JasaKonsultasi::class,
                Magang::class,
            ];

            $deleted = false;
            foreach ($tables as $model) {
                $record = $model::where('id', $id)->first();
                if ($record) {
                    // Check permission: only uploader or admin can delete
                    $user = Auth::user();
                    $isAdmin = $user && ($user->role === 'admin' || $user->role === 'superuser');
                    $isOwner = $record->user_id === $user?->id;
                    
                    if (!($isOwner || $isAdmin)) {
                        if (request()->wantsJson()) {
                            return response()->json(['message' => 'Anda tidak memiliki akses untuk menghapus permohonan ini'], 403);
                        }
                        return back()->with('error', 'Anda tidak memiliki akses untuk menghapus permohonan ini');
                    }
                    
                    // Delete files if exist
                    if ($record->surat_permohonan && Storage::disk('local')->exists($record->surat_permohonan)) {
                        Storage::disk('local')->delete($record->surat_permohonan);
                    }
                    if ($record->kartu_mahasiswa && Storage::disk('local')->exists($record->kartu_mahasiswa)) {
                        Storage::disk('local')->delete($record->kartu_mahasiswa);
                    }
                    if ($record->kartu_identitas && Storage::disk('local')->exists($record->kartu_identitas)) {
                        Storage::disk('local')->delete($record->kartu_identitas);
                    }
                    
                    $record->delete();
                    $deleted = true;
                    break;
                }
            }

            if ($deleted) {
                if (request()->wantsJson()) {
                    return response()->json(['message' => 'Permohonan pelayanan jasa berhasil dihapus']);
                }
                return back()->with('success', 'Permohonan pelayanan jasa berhasil dihapus');
            }

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan tidak ditemukan'], 404);
            }
            return back()->with('error', 'Permohonan tidak ditemukan');
        } catch (Exception $error) {
            report($error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan pelayanan jasa gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan pelayanan jasa gagal dihapus: ' . $error->getMessage());
        }
    }

    /**
     * Download surat permohonan dengan permission check (hanya uploader & admin)
     */
    public function downloadFile($id, $fileName)
    {
        try {
            // Cari record di semua service tables
            $tables = [
                Magang::class,
                Asuransi::class,
                LayananData::class,
                Pemetaan::class,
                Survey::class,
                JasaKonsultasi::class,
            ];
            
            $record = null;
            $filePath = null;
            
            foreach ($tables as $model) {
                $found = $model::where('id', $id)->first();
                if ($found) {
                    // Cek di surat_permohonan atau kartu_identitas
                    if ($found->surat_permohonan && basename($found->surat_permohonan) === $fileName) {
                        $record = $found;
                        $filePath = $found->surat_permohonan;
                        break;
                    }
                    if (isset($found->kartu_identitas) && $found->kartu_identitas && basename($found->kartu_identitas) === $fileName) {
                        $record = $found;
                        $filePath = $found->kartu_identitas;
                        break;
                    }
                }
            }
            
            if (!$record || !$filePath) {
                abort(404, 'File tidak ditemukan');
            }
            
            // Cek permission: hanya uploader atau admin yang bisa download
            $user = Auth::user();
            $isAdmin = $user && ($user->role === 'admin' || $user->role === 'superuser');
            $isOwner = $record->user_id === $user?->id;
            
            if (!($isOwner || $isAdmin)) {
                abort(403, 'Anda tidak memiliki akses ke file ini');
            }
            
            // Verify file path untuk cegah directory traversal attack
            if (strpos($fileName, '..') !== false) {
                abort(403, 'File tidak valid');
            }
            
            // Check jika file exist
            if (!Storage::disk('local')->exists($filePath)) {
                abort(404, 'File tidak ditemukan di storage');
            }
            
            // Download file
            return Storage::disk('local')->download($filePath, $fileName);
        } catch (\Illuminate\Auth\Access\AuthorizationException|\Symfony\Component\HttpKernel\Exception\HttpException $error) {
            throw $error;
        } catch (Exception $error) {
            report($error->getMessage());
            abort(500, 'Gagal download file');
        }
    }

    public function download(Magang $pelayanan_jasa)
    {
        // This method can be used for downloading documents if needed in the future
        return back()->with('error', 'Download tidak tersedia untuk saat ini');
    }
}
