<?php

// use App\Exports\ChatExport;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKlaimAsuransiController;
use App\Http\Controllers\AdminPermohonanMagangController;
use App\Http\Controllers\AdminPermohonanKunjunganController;
use App\Http\Controllers\AdminSewaAlatController;
use App\Http\Controllers\AdminJasaKonsultasiController;
use App\Http\Controllers\AdminSurveyController;
use App\Http\Controllers\AdminLayananDataController;
use App\Http\Controllers\AsuransiController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LayananDataController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\PermohonanKunjunganController;
use App\Http\Controllers\JasaKonsultasiController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SewaAlatController;
use App\Http\Middleware\Admin;
use App\Models\SewaAlat;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
// use App\Http\Controllers\DialogflowWebhookController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\DashboardPelayananController;
use App\Http\Controllers\AdminRatingController;
use App\Http\Controllers\AdminDownloadAreaController;
use App\Http\Controllers\FileController;

// Route Model Binding
Route::model('sewa_alat', SewaAlat::class);

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

Route::get('/profil', function () {
    return view('pages.profil');
})->name('profil');

// Redirect old tentang-kami route to profil
Route::redirect('/tentang-kami', '/profil', 301);

Route::get('/kontak', function () {
    return view('pages.kontak');
})->name('kontak');

Route::get('/kuisioner', function () {
    return view('pages.kuisioner');
})->name('kuisioner');

Route::get('/alur-pelayanan', function () {
    return view('pages.alur-pelayanan');
})->name('alur-pelayanan');

Route::get('/standar-pelayanan-jasa', function () {
    return view('pages.standar-pelayanan-jasa');
})->name('standar-pelayanan-jasa');

Route::get('/pembayaran-pnbp', function () {
    return view('pages.pembayaran-pnbp');
})->name('pembayaran-pnbp');

Route::get('/regulasi-pelayanan', function () {
    return view('pages.regulasi-pelayanan');
})->name('regulasi-pelayanan');

Route::get('/tarif-layanan', function () {
    return view('pages.tarif-layanan');
})->name('tarif-layanan');

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->role !== 'admin') {
        return redirect()->route('dashboard-pelayanan');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/api/chatbot/test-api', function () {
    return response()->json(['status' => 'Chatbot API via Web Routes is working']);
});

Route::post('/api/chatbot/chat', [ChatbotController::class, 'chat'])->name('chatbot.chat');
Route::post('/api/chatbot/rate', [ChatbotController::class, 'rate'])->name('chatbot.rate');

Route::get('/dashboard-pelayanan', [DashboardPelayananController::class, 'index'])
    ->middleware(['auth', 'verified', 'auth.notadmin'])->name('dashboard-pelayanan');

Route::get('/dashboard-pelayanan/load-more', [DashboardPelayananController::class, 'loadMore'])
    ->middleware(['auth', 'verified', 'auth.notadmin'])->name('dashboard-pelayanan.load-more');

Route::middleware(['auth', 'session.timeout'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::middleware('throttle:6,1')->patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::middleware('throttle:3,1')->delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/rating', [App\Http\Controllers\RatingController::class, 'create'])->name('rating.create');
    Route::post('/rating', [App\Http\Controllers\RatingController::class, 'storeGeneral'])->name('rating.store');
    Route::post('/api/rating', [App\Http\Controllers\RatingController::class, 'store'])->name('api.rating.store');
});

Route::middleware(['auth', 'verified', 'session.timeout'])->group(function () {

    /* ---------------------------------------------------------------------
     | PROTECTED ROUTES
     | ---------------------------------------------------------------------
     */
    Route::prefix('layanan')->group(function () {
        Route::get('/', [DashboardPelayananController::class, 'index'])->name('layanan');

        // Simple direct file download routes: /permohonan/{serviceType}/{fileName}/view
        Route::get('/permohonan/sewa-alat/{fileName}/view', [SewaAlatController::class, 'downloadFileSimple'])
            ->name('permohonan.sewa-alat.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/jasa-konsultasi/{fileName}/view', [JasaKonsultasiController::class, 'downloadFileSimple'])
            ->name('permohonan.jasa-konsultasi.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/survey/{fileName}/view', [SurveyController::class, 'downloadFileSimple'])
            ->name('permohonan.survey.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/layanan-data/{fileName}/view', [LayananDataController::class, 'downloadFileSimple'])
            ->name('permohonan.layanan-data.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/pelayanan-jasa/{fileName}/view', [MagangController::class, 'downloadFileSimple'])
            ->name('permohonan.pelayanan-jasa.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/permohonan-kunjungan/{fileName}/view', [PermohonanKunjunganController::class, 'downloadFileSimple'])
            ->name('permohonan.permohonan-kunjungan.download')
            ->where('fileName', '.+');

        Route::get('/permohonan/permohonan-asuransi/{fileName}/view', [AsuransiController::class, 'downloadFileSimple'])
            ->name('permohonan.permohonan-asuransi.download')
            ->where('fileName', '.+');

        Route::name('sewa-alat.')
            ->prefix('sewa-alat')
            ->controller(SewaAlatController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index'); // index halaman sewa alat
                Route::get('/permohonan', 'create')->name('create'); // menampilkan form permohonan sewa alat
                Route::post('/permohonan/tambah', 'store')->name('store'); // submit permohonan sewa alat
                Route::delete('/permohonan/{sewa_alat}/hapus', 'destroy')->name('destroy')->where('sewa_alat', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // hapus data permohonan by id
                Route::get('/permohonan/{sewa_alat}/download', 'download')->name('download-permohonan')->where('sewa_alat', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}'); // download permohonan
                Route::get('/permohonan/{sewa_alat}/file/{fileName}/view', 'downloadFile')->name('download-file')->where('sewa_alat', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}')->where('fileName', '.+'); // download any file (ktp, surat, etc)
            });

        Route::name('jasa-konsultasi.')
            ->prefix('jasa-konsultasi')
            ->controller(JasaKonsultasiController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/permohonan', 'create')->name('create');
                Route::post('/permohonan/tambah', 'store')->name('store');
                Route::delete('/permohonan/{jasa_konsultasi}/hapus', 'destroy')->name('destroy');
                Route::get('/permohonan/{jasa_konsultasi}/download', 'download')->name('download');
            });

        Route::name('survey.')
            ->prefix('survey')
            ->controller(SurveyController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/permohonan', 'create')->name('create');
                Route::post('/permohonan/tambah', 'store')->name('store');
                Route::delete('/permohonan/{survey}/hapus', 'destroy')->name('destroy');
                Route::get('/permohonan/{survey}/download', 'download')->name('download');
            });

        Route::name('layanan-data.')
            ->prefix('layanan-data')
            ->controller(LayananDataController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/permohonan', 'create')->name('create');
                Route::post('/permohonan/tambah', 'store')->name('store');
                Route::delete('/permohonan/{layanan_data}/hapus', 'destroy')->name('destroy');
                Route::get('/permohonan/{layanan_data}/download', 'download')->name('download');
            });

        Route::post('pelayanan-jasa', [MagangController::class, 'store'])->name('pelayanan-jasa.store');
        Route::get('pelayanan-jasa', [MagangController::class, 'index'])->name('pelayanan-jasa.index');
        Route::get('pelayanan-jasa/{permohonan_magang}', [MagangController::class, 'show'])->name('pelayanan-jasa.show');
        Route::delete('pelayanan-jasa/{permohonan_magang}', [MagangController::class, 'destroy'])->name('pelayanan-jasa.destroy');
        Route::get('permohonan-magang/{permohonan_magang}', [MagangController::class, 'download'])
            ->name('permohonan-magang.download');
        Route::get('pelayanan-jasa/{id}/file/{fileName}/view', [MagangController::class, 'downloadFile'])
            ->where('fileName', '.+')->name('pelayanan-jasa.download-file');

        Route::get('permohonan-kunjungan', function () {
            return redirect()->route('permohonan-kunjungan.create');
        });
        Route::get('permohonan-kunjungan/create', [PermohonanKunjunganController::class, 'create'])->name('permohonan-kunjungan.create');
        Route::post('permohonan-kunjungan', [PermohonanKunjunganController::class, 'store'])->name('permohonan-kunjungan.store');
        Route::delete('permohonan-kunjungan/{kunjungan}', [PermohonanKunjunganController::class, 'destroy'])->name('permohonan-kunjungan.destroy')->where('kunjungan', '[0-9]+');
        Route::get('permohonan-kunjungan/{id}/file/{fileName}/view', [PermohonanKunjunganController::class, 'downloadFile'])->where('fileName', '.+')->name('permohonan-kunjungan.download-file');
        Route::resource('permohonan-asuransi', AsuransiController::class);
        Route::get('klaim-asuransi/{klaim_asuransi}', [AsuransiController::class, 'download'])
            ->name('klaim-asuransi.download');
        Route::get('permohonan-asuransi/{id}/file/{fileName}/view', [AsuransiController::class, 'downloadFile'])->where('fileName', '.+')->name('permohonan-asuransi.download-file');
    });


    Route::middleware(['auth', 'auth.admin', 'session.timeout'])->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', 'dashboard');
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Download Area Routes
        Route::get('download-area', [AdminDownloadAreaController::class, 'index'])->name('download-area.index');
        Route::get('download-area/preview', [AdminDownloadAreaController::class, 'preview'])->name('download-area.preview');
        Route::post('download-area/download', [AdminDownloadAreaController::class, 'download'])->name('download-area.download');

        Route::resource('sewa-alat', AdminSewaAlatController::class);
        Route::get('sewa-alat/{id}/file/{fileName}/view', [AdminSewaAlatController::class, 'downloadFile'])->name('sewa-alat.download-file');
        Route::get('sewa-alat/{sewa_alat}/download', [AdminSewaAlatController::class, 'download'])->name('sewa-alat.download');
        // Route::resource('history-megabot', DialogflowWebhookController::class);
        Route::resource('pelayanan-jasa', AdminPermohonanMagangController::class);
        Route::get('pelayanan-jasa/{id}/download', [AdminPermohonanMagangController::class, 'download'])->name('pelayanan-jasa.download');
        Route::get('pelayanan-jasa/{id}/file/{fileName}/view', [AdminPermohonanMagangController::class, 'downloadFile'])->name('pelayanan-jasa.download-file');
        Route::resource('permohonan-kunjungan', AdminPermohonanKunjunganController::class);
        Route::get('permohonan-kunjungan/{id}/file/{fileName}/view', [AdminPermohonanKunjunganController::class, 'downloadFile'])->name('permohonan-kunjungan.download-file');
        Route::resource('survey', AdminSurveyController::class);
        Route::get('survey/{id}/file/{fileName}/view', [AdminSurveyController::class, 'downloadFile'])->name('survey.download-file');
        Route::resource('layanan-data', AdminLayananDataController::class);
        Route::get('layanan-data/{id}/file/{fileName}/view', [AdminLayananDataController::class, 'downloadFile'])->name('layanan-data.download-file');
        Route::resource('jasa-konsultasi', AdminJasaKonsultasiController::class);
        Route::get('jasa-konsultasi/{id}/file/{fileName}/view', [AdminJasaKonsultasiController::class, 'downloadFile'])->name('jasa-konsultasi.download-file');
        Route::resource('klaim-asuransi', AdminKlaimAsuransiController::class);
        Route::get('klaim-asuransi/{id}/file/{fileName}/view', [AdminKlaimAsuransiController::class, 'downloadFile'])->name('klaim-asuransi.download-file');
        // Route::get('/download-excel', function () {
        //     return Excel::download(new ChatExport, 'data.xlsx');
        // });
        Route::get('/api/chart-data', [AdminController::class, 'getChartData']);

        // Rating Routes
        Route::get('ratings', [AdminRatingController::class, 'index'])->name('ratings.index');
        Route::get('api/ratings-chart', [AdminRatingController::class, 'getRatingChartData'])->name('ratings.chart-data');

        // File Management Routes
        Route::delete('file/{filename}', [FileController::class, 'delete'])->name('file.delete');
        Route::post('file/delete-by-path', [FileController::class, 'deleteByPath'])->name('file.delete-by-path');

        // Folder Management Routes
        Route::get('files/folder/{serviceType}', [FileController::class, 'listByFolder'])->name('files.list-by-folder');
        Route::get('files/stats/{serviceType}', [FileController::class, 'folderStats'])->name('files.folder-stats');
        Route::delete('files/folder/{serviceType}', [FileController::class, 'deleteFolderContents'])->name('files.delete-folder-contents');
    });
});

// Health check endpoint for Koyeb deployment
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now(),
        'service' => 'BMKG SoftPro',
    ]);
});

// Route::post('/webhook', [DialogflowWebhookController::class, 'handleWebhook']);

// Graceful GET logout redirect (prevents 405 when user types /logout in URL bar)
Route::get('/logout', function () {
    return redirect()->route('login');
});

Route::get('/dbg-routes', function () {
    return response()->json([
        'app_url' => config('app.url'),
        'app_env' => config('app.env'),
        'current_url' => url()->current(),
        'routes' => collect(Route::getRoutes())->map(function ($route) {
            return [
                'methods' => $route->methods(),
                'uri' => $route->uri(),
                'name' => $route->getName(),
            ];
        })
    ]);
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Route not found in Laravel',
        'requested_uri' => request()->getRequestUri(),
        'requested_path' => request()->path(),
        'requested_method' => request()->method(),
        'app_url' => config('app.url'),
    ], 404);
});

require __DIR__ . '/auth.php';
