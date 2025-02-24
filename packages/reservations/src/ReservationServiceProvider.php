<?php

namespace Reservation;

use Illuminate\Support\ServiceProvider;

class ReservationServiceProvider extends ServiceProvider
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
