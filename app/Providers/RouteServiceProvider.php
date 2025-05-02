<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Cargar las rutas API
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        // Cargar las rutas web
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
