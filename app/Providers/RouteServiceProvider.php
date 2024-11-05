<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @param \Laravel\Lumen\Application $app
     * @return void
     */
    public function boot()
    {
        // You can add any additional boot logic here if needed
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Load the routes from routes/web.php and routes/api.php
        $router = $this->app->router;

        $router->group(['namespace' => 'App\Http\Controllers'], function () use ($router) {
            require base_path('routes/web.php');
            require base_path('routes/api.php');
        });
    }
}
