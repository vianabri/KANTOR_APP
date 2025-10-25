<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Jabatan;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(
            [
                'pegawai.*',       // semua view dalam folder pegawai
                'riwayat.*',       // semua view riwayat
                'layouts.*',       // kalau partial ada di layout
                // tambahkan view lain jika perlu
            ],
            function ($view) {
                $view->with('jabatans', Jabatan::with('bagian')->get());
            }
        );
    }
}
