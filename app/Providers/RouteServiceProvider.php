<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        // Route::middleware('web')
        //      ->namespace($this->namespace)
        //      ->group(base_path('routes/web.php'));


         Route::group([
             'middleware' => 'web',
             'namespace' => $this->namespace,
         ], function ($router) {
             require base_path('routes/web.php');
             require base_path('routes/web/basic.php');
             require base_path('routes/web/bcertify.php');
             require base_path('routes/web/certify.php');
             require base_path('routes/web/config.php');
             require base_path('routes/web/besurv.php');
             require base_path('routes/web/esurv.php');
             require base_path('routes/web/tis.php');
             require base_path('routes/web/rsurv.php');
             require base_path('routes/web/ssurv.php');
             require base_path('routes/web/resurv.php');
             require base_path('routes/web/asurv.php');
             require base_path('routes/web/csurv.php');
             require base_path('routes/web/user.php');
             require base_path('routes/web/ws.php');
             require base_path('routes/web/section5.php');
             require base_path('routes/web/bsection5.php');
             require base_path('routes/web/cerreport.php');
             require base_path('routes/web/report.php');

             require base_path('routes/web/laws.php');
             require base_path('routes/web/accounting.php');

         });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
