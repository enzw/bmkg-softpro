<?php

namespace App\Http\Controllers;

use App\Services\LayananService;

class DashboardPelayananController extends Controller
{
    public function index()
    {
        $layanan = LayananService::getLayanan();

        return view('pages.dashboard-pelayanan', compact('layanan'));
    }
}
