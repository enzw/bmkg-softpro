<?php

namespace App\Http\Controllers;

use App\Models\LayananData;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminLayananDataController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::check() && Auth::user()->role !== 'admin') {
                return redirect('/dashboard-pelayanan')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Layanan Data',
            'permohonan' => LayananData::all(),
        ];
        return view('pages.admin.layanan-data.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.layanan-data.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
            'surat_permohonan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ktp' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_permohonan')) {
            try {
                $file = $request->file('surat_permohonan');
                $file_name = 'layanan-data_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
                $path_permohonan = $file->storeAs('permohonan/layanan-data', $file_name);
                $validated['surat_permohonan'] = $path_permohonan;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload surat permohonan: ' . $error->getMessage());
            }
        }

        if ($request->hasFile('ktp')) {
            try {
                $file = $request->file('ktp');
                $file_name = 'ktp_layanan-data_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
                $path_ktp = $file->storeAs('permohonan/layanan-data', $file_name);
                $validated['ktp'] = $path_ktp;
            } catch (Exception $error) {
                return back()->with('error', 'Gagal upload KTP: ' . $error->getMessage());
            }
        }

        try {
            LayananData::create($validated);
            return redirect()->route('admin.layanan-data.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.layanan-data.create')->with('error', 'Permohonan gagal dibuat');
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
        $permohonan = LayananData::where('id', $id)->first();

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.layanan-data.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LayananData $layanan_data)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($layanan_data->surat_permohonan) {
                Storage::delete($layanan_data->surat_permohonan);
            }
            
            $file = $request->file('surat_permohonan');
            $file_name = 'layanan-data_user:' . $layanan_data->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/layanan-data', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            $layanan_data->update($validated);
            return redirect()->route('admin.layanan-data.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.layanan-data.index')->with('error', 'Permohonan gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $layanan_data = LayananData::findOrFail($id);
            
            // Delete associated file
            if ($layanan_data->surat_permohonan) {
                Storage::disk('local')->delete($layanan_data->surat_permohonan);
            }
            
            $layanan_data->delete();
            
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan berhasil dihapus']);
            }
            return back()->with('success', 'Permohonan berhasil dihapus');
        } catch (Exception $error) {
            \Log::error('Admin Layanan Data Destroy Error: ' . $error->getMessage());
            if (request()->wantsJson()) {
                return response()->json(['message' => 'Permohonan gagal dihapus: ' . $error->getMessage()], 500);
            }
            return back()->with('error', 'Permohonan gagal dihapus: ' . $error->getMessage());
        }
    }

    /**
     * Download file
     */
    public function downloadFile($id, $fileName)
    {
        $layananData = LayananData::findOrFail($id);

        // Security: validate that the file belongs to this record
        if (!$layananData->surat_permohonan || !str_contains($layananData->surat_permohonan, $fileName)) {
            abort(404, 'File tidak ditemukan.');
        }

        $filePath = storage_path('app/' . $layananData->surat_permohonan);

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $fileName);
    }
}
