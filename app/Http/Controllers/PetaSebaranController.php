<?php

namespace App\Http\Controllers;

use App\Models\PetaSebaran;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PetaSebaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = PetaSebaran::all();
        $data = [
            'title' => 'Peta Sebaran',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.peta-sebaran', $data);
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
        $validated = $request->validate([
            'perusahaan' => 'required',
            'minta_data' => 'required',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            PetaSebaran::create($validated);
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
    public function destroy(PetaSebaran $klaim_asuransi)
    {
        try {
            // Storage::delete($permohonan_asuransi->surat_permohonan_klaim);
            $klaim_asuransi->delete();
            return back()->with('success', 'Permohonan kunjungan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan kunjungan gagal dihapus');
        }
    }

    public function download(PetaSebaran $klaim_asuransi)
    {
        Storage::download($klaim_asuransi->surat_permohonan_klaim);
    }
}
