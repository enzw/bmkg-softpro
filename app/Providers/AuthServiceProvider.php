<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Kunjungan;
use App\Models\SewaAlat;
use App\Models\Survey;
use App\Models\LayananData;
use App\Models\JasaKonsultasi;
use App\Models\Magang;
use App\Models\Asuransi;
use App\Policies\KunjunganPolicy;
use App\Policies\SewaAlatPolicy;
use App\Policies\SurveyPolicy;
use App\Policies\LayananDataPolicy;
use App\Policies\JasaKonsultasiPolicy;
use App\Policies\MagangPolicy;
use App\Policies\AsuransiPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Kunjungan::class => KunjunganPolicy::class,
        SewaAlat::class => SewaAlatPolicy::class,
        Survey::class => SurveyPolicy::class,
        LayananData::class => LayananDataPolicy::class,
        JasaKonsultasi::class => JasaKonsultasiPolicy::class,
        Magang::class => MagangPolicy::class,
        Asuransi::class => AsuransiPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
