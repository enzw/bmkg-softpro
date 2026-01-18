<?php

use App\Exports\ChatExport;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKlaimAsuransiController;
use App\Http\Controllers\AdminPermohonanMagangController;
use App\Http\Controllers\AdminPetaSebaranController;
use App\Http\Controllers\AdminSewaAlatController;
use App\Http\Controllers\AsuransiController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SewaAlatController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DialogflowWebhookController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardPelayananController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/api/berita', [BeritaController::class, 'scrapper']);

Route::get('/berita', function () {
    return view('pages.berita');
});

Route::get('/berita', function () {
    return view('pages.berita');
})->name('berita');

Route::get('/', [LayananController::class, 'index']);

// Route::get('/', function () {
//     return view('pages.landing');
// });

Route::get('/tentang-kami', function () {
    return view('pages.tentang-kami');
})->name('tentang-kami');

Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');

Route::get('/kuisioner', function () {
    return view('pages.kuisioner');
})->name('kuisioner');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard-pelayanan', [DashboardPelayananController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard-pelayanan');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* ---------------------------------------------------------------------
     | PROTECTED ROUTES
     | ---------------------------------------------------------------------
     */
    Route::prefix('layanan')->group(function () {
        Route::get('/', [LayananController::class, 'index'])->name('layanan');

        Route::name('sewa-alat.')
            ->prefix('sewa-alat')
            ->controller(SewaAlatController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index'); // index halaman sewa alat
                Route::get('/permohonan', 'create')->name('create'); // menampilkan form permohonan sewa alat
                Route::post('/permohonan/tambah', 'store')->name('store'); // submit permohonan sewa alat
                Route::get('/permohonan/{sewa_alat}/ubah', 'edit')->name('edit'); // menampilkan form ubaha data permohonan by id
                Route::put('/permohonan/{sewa_alat}/ubah', 'update')->name('update'); // ubaha data permohonan by id
                Route::delete('/permohonan/{sewa_alat}/hapus', 'destroy')->name('destroy'); // hapus data permohonan by id
                Route::get('/permohonan/{sewa_alat}/download', 'download')->name('download-permohonan'); // download permohonan
            });

        Route::resource('pelayanan-jasa', MagangController::class);
        Route::get('permohonan-magang/{permohonan_magang}', [MagangController::class, 'download'])
            ->name('permohonan-magang.download');

        Route::resource('permohonan-kunjungan', AsuransiController::class);
        Route::get('klaim-asuransi/{klaim_asuransi}', [AsuransiController::class, 'download'])
            ->name('klaim-asuransi.download');

        Route::get('/peta-sebaran', function () {
            return view('pages.layanan.peta-sebaran');
        })->name('peta-sebaran');
    });

    Route::middleware(Admin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', 'dashboard');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('sewa-alat', AdminSewaAlatController::class);
        Route::get('sewa-alat/{sewa_alat}/download', [AdminSewaAlatController::class, 'download'])->name('sewa-alat.download');
        Route::resource('history-megabot', DialogflowWebhookController::class);
        Route::resource('pelayanan-jasa', AdminPermohonanMagangController::class);
        Route::resource('permohonan-kunjungan', AdminKlaimAsuransiController::class);
        Route::resource('peta-sebaran', AdminPetaSebaranController::class);
        Route::get('/download-excel', function () {
            return Excel::download(new ChatExport, 'data.xlsx');
        });
        Route::get('/api/chart-data', [AdminController::class, 'getChartData']);
    });
});


Route::post('/webhook', [DialogflowWebhookController::class, 'handleWebhook']);

require __DIR__ . '/auth.php';
