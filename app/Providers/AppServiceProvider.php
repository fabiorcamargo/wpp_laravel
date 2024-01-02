<?php

namespace App\Providers;

use App\Models\WppBatch;
use App\Models\WppConnect;
use App\Observers\WppBatchObserver;
use App\Observers\WppObserver;
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
        WppConnect::observe(WppObserver::class);
        WppBatch::observe(WppBatchObserver::class);
    }
}
