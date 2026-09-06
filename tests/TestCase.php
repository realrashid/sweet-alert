<?php

namespace RealRashid\SweetAlert\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use RealRashid\SweetAlert\SweetAlertServiceProvider;

class TestCase extends BaseTestCase
{
    /**
     * Register the package service provider so `app()` resolves
     * all bindings (AlertBuilder, ToastBuilder, InputBuilder,
     * SessionStoreInterface, etc.) inside tests.
     */
    protected function getPackageProviders($app): array
    {
        return [SweetAlertServiceProvider::class];
    }
}
