<?php

namespace Vehicle;

use Illuminate\Support\ServiceProvider;

class VehicleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Load Views and Routes
        $this->app['router']->prefix('api/v1')->group(function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        });
    }


}
