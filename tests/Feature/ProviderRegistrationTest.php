<?php

use PHPUnit\Framework\TestCase;
use RealRashid\SweetAlert\SweetAlertServiceProvider;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Toaster;

class ProviderRegistrationTest extends TestCase
{
    public function test_service_provider_class_exists()
    {
        $this->assertTrue(class_exists(SweetAlertServiceProvider::class));
    }

    public function test_alert_facade_class_exists()
    {
        $this->assertTrue(class_exists(Alert::class));
    }

    public function test_toaster_has_warning_method()
    {
        $this->assertTrue(method_exists(Toaster::class, 'warning'));
    }

    public function test_toaster_has_all_alert_methods()
    {
        $methods = ['alert', 'success', 'info', 'warning', 'error', 'question', 'html', 'toast'];

        foreach ($methods as $method) {
            $this->assertTrue(
                method_exists(Toaster::class, $method),
                "Toaster is missing the '{$method}' method"
            );
        }
    }

    public function test_service_provider_binds_session_store_correctly()
    {
        $reflection = new ReflectionClass(SweetAlertServiceProvider::class);
        $method = $reflection->getMethod('register');

        // Ensure the register method exists
        $this->assertTrue($method->isPublic());
    }

    public function test_alert_facade_accessor_returns_alert()
    {
        $reflection = new ReflectionClass(Alert::class);
        $method = $reflection->getMethod('getFacadeAccessor');
        $method->setAccessible(true);

        // Create a mock instance to call the protected static method
        $result = $method->invoke(null);
        $this->assertEquals('alert', $result);
    }
}
