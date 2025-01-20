<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;  // ここでLogをインポート

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
        DB::listen(function ($query) {
            Log::info("SQL: " . $query->sql);
            Log::info("Bindings: " . json_encode($query->bindings));
            Log::info("Time: " . $query->time . "ms");
        });
    }
    
}
