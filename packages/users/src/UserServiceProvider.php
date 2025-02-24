<?php

namespace User;

use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
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
        // $this->app['router']->prefix('api/v1')->middleware('auth:api')->group(function () {
        //     $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
        // });

        $this->app['router']
            ->prefix('api/v1')
            ->name('api.v1.') // Unique name prefix
            ->middleware('auth:api')
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/routes/api.php');
            });
    }


}
