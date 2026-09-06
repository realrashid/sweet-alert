<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Use Orchestra Testbench so the SweetAlertServiceProvider is booted for
| every test. This ensures `app()` resolves all container bindings
| (SessionStoreInterface, AlertFlasher, AlertBuilder, etc.) correctly.
|
*/

uses(TestCase::class)->in('.');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can chain
| to make your assertions more expressive.
|
*/

expect()->extend('toBeFluent', function () {
    return $this->toBeInstanceOf(AlertBuilder::class);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you reduce the number of lines in your test files.
|
*/

function createAlertBuilder(): AlertBuilder
{
    return AlertBuilder::make();
}
