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
            Log::info("SQL: " . $query->sql); //$query->sql: 実行されたSQL文。
            Log::info("Bindings: " . json_encode($query->bindings)); //$query->bindings: プレースホルダーのバインド値。
            Log::info("Time: " . $query->time . "ms"); //$query->time: 実行時間。
        });
    }
}
