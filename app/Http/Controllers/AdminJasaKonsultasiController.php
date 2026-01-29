<?php

namespace App\Http\Controllers;

use App\Models\JasaKonsultasi;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminJasaKonsultasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Jasa Konsultasi',
            'permohonan' => JasaKonsultasi::all(),
        ];
        return view('pages.admin.jasa-konsultasi.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.jasa-konsultasi.create', $data);
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
        ]);

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('surat_permohonan')) {
            $file = $request->file('surat_permohonan');
            $file_name = 'jasa-konsultasi_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/jasa-konsultasi', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            JasaKonsultasi::create($validated);
            return redirect()->route('admin.jasa-konsultasi.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.jasa-konsultasi.create')->with('error', 'Permohonan gagal dibuat');
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
        $permohonan = JasaKonsultasi::where('id', $id)->first();

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.jasa-konsultasi.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, JasaKonsultasi $jasa_konsultasi)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($jasa_konsultasi->surat_permohonan) {
                Storage::delete($jasa_konsultasi->surat_permohonan);
            }
            
            $file = $request->file('surat_permohonan');
            $file_name = 'jasa-konsultasi_user:' . $jasa_konsultasi->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/jasa-konsultasi', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            $jasa_konsultasi->update($validated);
            return redirect()->route('admin.jasa-konsultasi.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.jasa-konsultasi.index')->with('error', 'Permohonan gagal diupdate');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
