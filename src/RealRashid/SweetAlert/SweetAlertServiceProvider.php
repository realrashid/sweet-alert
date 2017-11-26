<?php

namespace RealRashid\SweetAlert;

use Illuminate\Support\ServiceProvider;

class SweetAlertServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('alert', function ($app) {
            return $this->app->make('RealRashid\SweetAlert\Toaster');
        });
    }
}
