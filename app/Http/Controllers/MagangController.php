<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use App\Models\Asuransi;
use App\Models\LayananData;
use App\Models\Pemetaan;
use App\Models\PetaSebaran;
use App\Models\Survey;
use App\Models\JasaKonsultasi;
use Exception;
use Illuminate\Support\Facades\Auth;
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
        
        $pemetaan = Pemetaan::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Pemetaan';
            $item->table_name = 'pemetaan';
            return $item;
        });
        
        $peta_sebaran = PetaSebaran::where('user_id', $userId)->get()->map(function($item) {
            $item->jenis_layanan = 'Layanan Peta Sebaran';
            $item->table_name = 'peta_sebaran';
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
        
        // Gabungkan semua permohonan
        $permohonan = collect()
            ->merge($magang)
            ->merge($asuransi)
            ->merge($layanan_data)
            ->merge($pemetaan)
            ->merge($peta_sebaran)
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
            'Layanan Klaim Asuransi' => Asuransi::class,
            'Layanan Data' => LayananData::class,
            'Layanan Pemetaan' => Pemetaan::class,
            'Layanan Peta Sebaran' => PetaSebaran::class,
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
                    'Layanan Klaim Asuransi' => 'permohonan/layanan-asuransi',
                    'Layanan Data' => 'permohonan/layanan-data',
                    'Layanan Pemetaan' => 'permohonan/layanan-pemetaan',
                    'Layanan Peta Sebaran' => 'permohonan/peta-sebaran',
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
        
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu';
        
        // Auto-fill dari user profile jika tidak diisi
        $user = Auth::user();
        if ($jenis_layanan === 'Magang') {
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
        
        try {
            $model::create($validated);
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
            ]),
            'Layanan Klaim Asuransi' => $rules = array_merge($rules, [
                'perusahaan' => 'required|string',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kejadian' => 'required|string',
            ]),
            'Layanan Data' => $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string',
                'no_whatsapp' => 'required|string',
                'email' => 'required|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Pemetaan' => $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string',
                'no_whatsapp' => 'required|string',
                'email' => 'required|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Peta Sebaran' => $rules = array_merge($rules, [
                'perusahaan' => 'required|string',
                'tanggal' => 'required|date',
                'lokasi' => 'required|string',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'kejadian' => 'required|string',
            ]),
            'Layanan Survey' => $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string',
                'no_whatsapp' => 'required|string',
                'email' => 'required|email',
                'keterangan' => 'nullable|string',
            ]),
            'Layanan Konsultasi' => $rules = array_merge($rules, [
                'nama_lengkap' => 'required|string',
                'no_whatsapp' => 'required|string',
                'email' => 'required|email',
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
    public function destroy($id)
    {
        try {
            // Try to delete from each service table
            $tables = [
                Asuransi::class,
                LayananData::class,
                Pemetaan::class,
                PetaSebaran::class,
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
                    
                    // Delete file if exists
                    if ($record->surat_permohonan && Storage::disk('local')->exists($record->surat_permohonan)) {
                        Storage::disk('local')->delete($record->surat_permohonan);
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
                Asuransi::class,
                LayananData::class,
                Pemetaan::class,
                Survey::class,
                JasaKonsultasi::class,
            ];
            
            $record = null;
            foreach ($tables as $model) {
                $found = $model::where('id', $id)->first();
                if ($found && $found->surat_permohonan) {
                    $record = $found;
                    break;
                }
            }
            
            if (!$record) {
                abort(404, 'File tidak ditemukan');
            }
            
            // Cek permission: hanya uploader atau admin yang bisa download
            $user = Auth::user();
            $isAdmin = $user && ($user->role === 'admin' || $user->role === 'superuser'); // Adjust sesuai struktur role
            $isOwner = $record->user_id === $user?->id;
            
            if (!($isOwner || $isAdmin)) {
                abort(403, 'Anda tidak memiliki akses ke file ini');
            }
            
            // Verify file path untuk cegah directory traversal attack
            $filePath = $record->surat_permohonan;
            if (strpos($fileName, '..') !== false || basename($filePath) !== $fileName) {
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
