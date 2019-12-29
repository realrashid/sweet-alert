<?php

namespace RealRashid\SweetAlert;

use Illuminate\Support\ServiceProvider;

class SweetAlertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the package services.
     *
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function boot()
    {
        /*
         * Registering the helper methods to package
         */
        $this->registerHelpers();

        /*
        * Registering the Package Views
        */
        $this->registerViews();

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->registerPublishing();
        }
    }

    /**
     * Register helpers file
     *
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function registerHelpers()
    {
        // Load the helpers in src/functions.php
        if (file_exists($file = __DIR__ . '/functions.php')) {
            require $file;
        }
    }

    /**
     * Register the package's views.
     *
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    private function registerViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'sweetalert');
    }

    /**
    * Register the package's publishable resources.
    *
    * @return void
    * @author Rashid Ali <realrashid05@gmail.com>
    */
    private function registerPublishing()
    {
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/sweetalert')
        ], 'sweetalert-view');

        $this->publishes([
            __DIR__ . '/config/sweetalert.php' => config_path('sweetalert.php')
        ], 'sweetalert-config');

        $this->publishes([
            __DIR__ . '/../resources/js' => public_path('vendor/sweetalert')
        ], 'sweetalert-asset');
    }

    /**
     * Register the application services.
     *
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/config/sweetalert.php', 'sweetalert');

        // Binding required classes to app
        $this->app->bind(
            'RealRashid\SweetAlert\Storage\SessionStore',
            'RealRashid\SweetAlert\Storage\AlertSessionStore',
            'RealRashid\SweetAlert\ToSweetAlert'
        );

        // Register the main class to use with the facade
        $this->app->singleton('alert', function ($app) {
            return $this->app->make(Toaster::class);
        });

        if ($this->app->runningInConsole()) {
            // Registering package commands.
            $this->commands([
                Console\PublishCommand::class,
            ]);
        }
    }
}
