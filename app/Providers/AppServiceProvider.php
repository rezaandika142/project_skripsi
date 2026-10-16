<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Otomatisasi inisialisasi basis data SQLite saat berjalan di lingkungan serverless seperti Vercel
        if (config('database.default') === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if ($dbPath && !file_exists($dbPath)) {
                @touch($dbPath);
            }
            if ($dbPath && file_exists($dbPath)) {
                try {
                    if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
                        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                        \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
                    }
                } catch (\Throwable $e) {
                    // Penanganan toleransi error pada lingkungan stateless
                }
            }
        }
    }
}
