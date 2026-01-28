<?php

namespace App\Http\Controllers;

use App\Models\Asuransi;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsuransiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permohonan = Asuransi::all();
        $data = [
            'title' => 'Permohonan Kunjungan',
            'permohonan' => $permohonan,
        ];

        return view('pages.layanan.klaim-asuransi', $data);
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
            'tanggal' => 'required|date',
            'lokasi' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'kejadian'=> 'required',
        ]);

        $validated['user_id'] = Auth::id();

        try {
            Asuransi::create($validated);
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
    public function destroy(Asuransi $permohonan_kunjungan)
    {
        try {
            // Storage::delete($permohonan_asuransi->surat_permohonan_klaim);
            $permohonan_kunjungan->delete();
            return back()->with('success', 'Permohonan kunjungan berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan kunjungan gagal dihapus');
        }
    }

    public function download(Asuransi $klaim_asuransi)
    {
        Storage::download($klaim_asuransi->surat_permohonan_klaim);
    }
}
