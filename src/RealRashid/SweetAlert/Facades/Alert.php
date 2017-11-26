<?php

namespace RealRashid\SweetAlert\Facades;

use Illuminate\Support\Facades\Facade;

class Alert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alert';
    }
}
