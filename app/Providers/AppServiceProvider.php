<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\Dashboard\TaskStats;
use App\View\Components\Dashboard\UpcomingDeadline;

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
        Blade::component('dashboard.task-stats', TaskStats::class);
        Blade::component('dashboard.upcoming-deadline', UpcomingDeadline::class);
    }
}
