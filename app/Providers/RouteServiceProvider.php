<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';
    protected $namespace = 'App\Http\Controllers';
    protected $namespaceApi = 'App\Http\Controllers\API';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            // Endpoints da fila de impressao termica: o Agente Local faz polling
            // a cada 1,5s (~40 req/min + POSTs de concluir), acima do limite de
            // 60/min do grupo api. Sem limite para nao descartar cupons na fila.
            if ($request->is('api/print-queue*')) {
                return Limit::none();
            }

            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
            ->prefix('api')
            ->namespace($this->namespaceApi)
            ->group(base_path('routes/api.php'));

            Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
        });
    }
}
