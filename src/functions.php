<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;

if (! function_exists('alert')) {
    /**
     * Return the AlertBuilder instance or create an alert with the given parameters.
     *
     * This helper function provides a convenient way to access the alert
     * builder without using the facade. When called with a non-empty title,
     * it sets the config and flashes immediately (backward compatibility).
     * When called with no arguments, it returns the builder for chaining.
     *
     * @param  string  $title  The alert title
     * @param  string  $message  The alert text/description
     * @param  string  $type  The alert icon type
     */
    function alert(string $title = '', string $message = '', string $type = ''): AlertBuilder
    {
        $alert = app('alert');

        if ($title !== '') {
            return $alert->alert($title, $message, $type)->flash();
        }

        return $alert;
    }
}

if (! function_exists('toast')) {
    /**
     * Return a ToastBuilder instance with the given configuration.
     *
     * This helper function provides a convenient way to create toast
     * notifications without using the facade.
     *
     * @param  string  $title  The toast message/title
     * @param  string|null  $type  The toast icon type
     * @param  string  $position  The toast position (deprecated, use ->position())
     */
    function toast(string $title = '', ?string $type = null, string $position = 'top-end'): ToastBuilder
    {
        $alert = app('alert');
        $toast = $alert->toast($title, $type);

        if ($position !== 'top-end') {
            $toast->position($position);
        }

        return $toast;
    }
}

if (! function_exists('confirmDelete')) {
    /**
     * Return an alert instance configured for confirm delete.
     *
     * This helper function provides backward compatibility for the
     * confirmDelete() helper from the previous package version.
     *
     * @param  string  $title  The confirm dialog title
     * @param  string|null  $text  The confirm dialog description
     */
    function confirmDelete(string $title = '', ?string $text = null): AlertBuilder
    {
        $alert = app('alert');

        if ($title !== '') {
            return $alert->confirmDelete($title, $text);
        }

        return $alert;
    }
}
