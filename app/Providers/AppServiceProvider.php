<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email')
                ->line('Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda.')
                ->action('Verifikasi Email', $url)
                ->line('Jika Anda tidak membuat akun, abaikan email ini.');
        });
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
    }
}
