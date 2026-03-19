<?php

use PHPUnit\Framework\TestCase;
use RealRashid\SweetAlert\SweetAlertServiceProvider;

class ProviderRegistrationTest extends TestCase
{
    public function test_service_provider_class_exists()
    {
        $this->assertTrue(class_exists(SweetAlertServiceProvider::class));
    }
}
