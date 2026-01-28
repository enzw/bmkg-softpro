<?php

namespace App\Http\Controllers;

use App\Models\Magang;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Storage;

class AdminPermohonanMagangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Pelayanan Jasa',
            'permohonan' => Magang::all(),
        ];
        return view('pages.admin.permohonan-magang.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Permohonan',
        ];

        return view('pages.admin.permohonan-magang.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required',
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
        ]);

        try {
            Magang::create($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Permohonan berhasil dibuat');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.create')->with('error', 'Permohonan gagal dibuat: ' . $error->getMessage());
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
        $permohonan = Magang::where('id', $id)->first();

        $data = [
            'title' => 'Update Permohonan',
            'permohonan' => $permohonan,
        ];

        // return dd($data);
        return view('pages.admin.permohonan-magang.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Magang $permohonan_magang)
    {
        $validated = $request->validate([
            'jenis_layanan' => 'required',
            'nama_lengkap' => 'required',
            'no_whatsapp' => 'required',
            'email' => 'required|email',
            'keterangan' => 'nullable',
            'status' => 'nullable',
        ]);

        try {
            $permohonan_magang->update($validated);
            return redirect()->route('admin.pelayanan-jasa.index')->with('success', 'Permohonan berhasil diupdate');
        } catch (Exception $error) {
            report($error->getMessage());
            return redirect()->route('admin.pelayanan-jasa.index')->with('error', 'Permohonan gagal diupdate: ' . $error->getMessage());
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
