<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AdminPemetaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pemetaan',
            'permohonan' => Pemetaan::all(),
        ];
        return view('pages.admin.pemetaan.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.pemetaan.create', $data);
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
            $file_name = 'pemetaan_user:' . Auth::id() . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/pemetaan', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            Pemetaan::create($validated);
            return redirect()->route('admin.pemetaan.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pemetaan.create')->with('error', 'Permohonan gagal dibuat');
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
        $permohonan = Pemetaan::where('id', $id)->first();

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        return view('pages.admin.pemetaan.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pemetaan $pemetaan)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
        ]);

        if ($request->hasFile('surat_permohonan')) {
            if ($pemetaan->surat_permohonan) {
                Storage::delete($pemetaan->surat_permohonan);
            }
            
            $file = $request->file('surat_permohonan');
            $file_name = 'pemetaan_user:' . $pemetaan->user_id . '_date:' . Carbon::now()->format('Y-m-d-H-i-s') . '.' . $file->getClientOriginalExtension();
            $path_permohonan = $file->storeAs('permohonan/pemetaan', $file_name);
            $validated['surat_permohonan'] = $path_permohonan;
        }

        try {
            $pemetaan->update($validated);
            return redirect()->route('admin.pemetaan.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pemetaan.index')->with('error', 'Permohonan gagal diupdate');
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
