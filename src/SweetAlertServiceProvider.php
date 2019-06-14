<?php

namespace RealRashid\SweetAlert;

use Illuminate\Support\ServiceProvider;

class SweetAlertServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerHelpers();

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sweetalert');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/sweetalert')
        ], 'view');

        $this->publishes([
            __DIR__.'/config/sweetalert.php' => config_path('sweetalert.php')
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/js' => public_path('vendor/sweetalert')
        ], 'public');
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'RealRashid\SweetAlert\Storage\SessionStore',
            'RealRashid\SweetAlert\Storage\AlertSessionStore',
            'RealRashid\SweetAlert\ToSweetAlert'
        );
        $this->app->singleton('alert', function ($app) {
            return $this->app->make('RealRashid\SweetAlert\Toaster');
        });
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        // Load the helpers in src/functions.php
        if (file_exists($file = __DIR__ . '/functions.php')) {
            require $file;
        }
    }
}
