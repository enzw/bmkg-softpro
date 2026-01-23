<?php

namespace App\Http\Controllers;

use App\Models\Magang;
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
        $permohonan = Magang::where('user_id', Auth::id())->get();
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
        $validated = $request->validate([
            'jenis_layanan' => 'required|string',
            'nama_lengkap' => 'required|string|max:255',
            'no_whatsapp' => 'required|string|max:20',
            'email' => 'required|email',
            'keterangan' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'Menunggu';

        try {
            Magang::create($validated);
            return back()->with('success', 'Permohonan pelayanan jasa berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan pelayanan jasa gagal dibuat: ' . $error->getMessage());
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
    public function destroy(Magang $pelayanan_jasa)
    {
        try {
            $pelayanan_jasa->delete();
            return back()->with('success', 'Permohonan pelayanan jasa berhasil dihapus');
        } catch (Exception $error) {
            report($error->getMessage());
            return back()->with('error', 'Permohonan pelayanan jasa gagal dihapus: ' . $error->getMessage());
        }
    }

    public function download(Magang $pelayanan_jasa)
    {
        // This method can be used for downloading documents if needed in the future
        return back()->with('error', 'Download tidak tersedia untuk saat ini');
    }
}
