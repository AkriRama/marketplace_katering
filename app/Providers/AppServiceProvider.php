<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ArielMejiaDev\LarapexCharts\AreaChart;
use App\Charts\ReportChart;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(ReportChart::class, function ($app) {
            return new ReportChart(new AreaChart());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
