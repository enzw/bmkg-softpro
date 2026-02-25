<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && config('clockwork.enable')) {
            $this->app->register(\Clockwork\Support\Laravel\ClockworkServiceProvider::class);
        }

        if (!config('clockwork.enable', false))
            return;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Force HTTPS for all URL generation in production
        if ($this->app->environment('production') && str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        Blade::directive('rupiah', function ($harga) {
            $float = floatval($harga);
            return 'Rp' . number_format($float, 0, ',', '.');
        });

        if (!config('clockwork.enable', false))
            return;

        // Map legacy polymorphic class names to new ones
        Relation::morphMap([
            'Pelayanan\Asuransi' => \App\Models\Asuransi::class,
            'Pelayanan\JasaKonsultasi' => \App\Models\JasaKonsultasi::class,
            'Pelayanan\Kunjungan' => \App\Models\Kunjungan::class,
            'Pelayanan\LayananData' => \App\Models\LayananData::class,
            'Pelayanan\Magang' => \App\Models\Magang::class,
            'Pelayanan\SewaAlat' => \App\Models\SewaAlat::class,
            'Pelayanan\Survey' => \App\Models\Survey::class,
        ]);
    }
}
