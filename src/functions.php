<?php

if (!function_exists('alert')) {
    /**
     * Return app instance of Alert.
     *
     * @return RealRashid\SweetAlert\Toaster
     */
    function alert($title = null, $message = '', $type = null)
    {
        $alert = app('alert');

        if (!is_null($title)) {
            return $alert->alert($title, $message, $type);
        }

        return $alert;
    }
}

if (!function_exists('toast')) {
    /**
     * Return app instance of Toast.
     *
     * @return RealRashid\SweetAlert\Toaster
     */
    function toast($title = '', $type = null, $position = 'bottom-right')
    {
        $alert = app('alert');

        if (!is_null($title)) {
            return $alert->toast($title, $type, $position);
        }

        return $alert;
    }
}
