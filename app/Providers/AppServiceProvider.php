<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

// Service provider utama aplikasi Laravel
// Tempat melakukan pendaftaran dan bootstrapping layanan aplikasi.
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * Gunakan method ini untuk mendaftarkan binding container,
     * singleton, atau service lainnya.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Gunakan method ini untuk melakukan inisialisasi saat aplikasi dijalankan.
     */
    public function boot(): void
    {
        //
    }
}
