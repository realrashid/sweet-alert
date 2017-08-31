<?php

if (!function_exists('alert')) {
    /**
     * Return app instance of Alert.
     * 
     * @return RashidAli05\SweetAlert\Toaster
     */
    function alert() {
        return app('alert');
    }
}
