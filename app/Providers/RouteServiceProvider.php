<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->ip());
        });

        Route::middleware('web')
            ->group(__DIR__ . '/../../routes/web.php');

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/../../routes/api.php');

        Route::middleware('api')
            ->prefix('api')
            ->group(__DIR__ . '/../../routes/admin.php');
    }
}