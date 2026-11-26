<?php

namespace RealRashid\SweetAlert\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

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

            if ($error instanceof ViewErrorBag || $error instanceof MessageBag) {
                if ($error->any()) {
                    app('alert')
                        ->error(__('Please fix the following'))
                        ->html($this->getErrors($error))
                        ->persistent()
                        ->flash();
                }
            }
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
     * Format validation errors as an HTML list.
     */
    private function getErrors(ViewErrorBag|MessageBag $errors): string
    {
        $items = collect($errors->all())
            ->map(fn ($message) => '<li>'.e($message).'</li>')
            ->implode('');

        return '<ul style="text-align:left;margin:0;padding-left:1.25rem;">'.$items.'</ul>';
    }
}
