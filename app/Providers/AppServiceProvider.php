<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Master\Period;

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
        view()->composer(
            'template.period-navbar', 
            function ($view) {
                
                $periodStates = Period::orderBy('year', 'asc')->get();
                
                $view->with('periodStates', $periodStates );
            }
        );
    }
}
