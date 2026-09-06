<?php

namespace RealRashid\SweetAlert\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * ToSweetAlert Middleware - Intercepts session flash data and prepares alerts.
 *
 * This middleware checks the session for alert configuration data
 * that was flashed by the AlertBuilder and prepares it for rendering
 * in the Blade view. It also handles automatic display of validation
 * errors as alert messages.
 */
class ToSweetAlert
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $messageTypes = ['info', 'success', 'warning', 'error', 'question'];

        foreach ($messageTypes as $type) {
            if ($request->session()->has($type)) {
                $alert = app('alert');

                $value = $request->session()->get($type);

                $alert->alert(
                    is_array($value) ? $value[0] : $value,
                    is_array($value) ? ($value[1] ?? '') : '',
                    $type
                )->flash();
            }
        }

        // Auto-display validation errors
        if ($request->session()->has('errors') && config('sweetalert.middleware.auto_display_errors', true)) {
            $error = $request->session()->get('errors');

            if (! is_string($error)) {
                $error = $this->getErrors($error->getMessages());
            }

            app('alert')->error('Validation Error', $error)->flash();
        }

        // Handle toast session keys (backward compatibility)
        $toastTypes = ['success', 'info', 'warning', 'question', 'error'];

        foreach ($toastTypes as $type) {
            $sessionKey = "toast_{$type}";

            if ($request->session()->has($sessionKey)) {
                app('alert')->toast($request->session()->get($sessionKey), $type)->middleware();
            }
        }

        return $next($request);
    }

    /**
     * Get validation errors as a formatted string.
     */
    /**
     * Flatten a validation error bag into a single message.
     *
     * Takes an array because that is what `getMessages()` returns. Typing it
     * as an object meant every failed validation raised a TypeError before the
     * alert was ever built, which is the most common path through this
     * middleware.
     *
     * @param  array<string, array<int, string>>|object  $errors
     */
    private function getErrors(array|object $errors): string
    {
        return collect($errors)->flatten()->implode("\n");
    }
}
