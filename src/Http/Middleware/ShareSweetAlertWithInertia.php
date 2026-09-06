<?php

namespace RealRashid\SweetAlert\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * ShareSweetAlertWithInertia — shares a pending alert with an Inertia page.
 *
 * An Inertia response has no Blade view to render the alert into, so the
 * configuration travels as a prop and the client fires it.
 *
 * The share is registered before the request is handled but resolved lazily:
 * Inertia calls the closure while it builds the props, which is after the
 * controller has flashed the alert. Doing this the other way round — handling
 * the request and then flashing — is too late, because the props have already
 * been resolved by then and the alert never reaches the page.
 *
 * Register it after Inertia's own middleware:
 *
 *   ->withMiddleware(function (Middleware $middleware) {
 *       $middleware->web(append: [
 *           \RealRashid\SweetAlert\Http\Middleware\ShareSweetAlertWithInertia::class,
 *       ]);
 *   })
 *
 * On the client:
 *   - Vue 3:  const { sweetalert } = usePage().props
 *   - React:  const { sweetalert } = usePage().props
 *
 * Or let the bundled useSweetAlert() composable/hook do it for you.
 */
class ShareSweetAlertWithInertia
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): mixed
    {
        Inertia::share('sweetalert', fn () => $this->pullAlert($request, 'alert.config'));

        return $next($request);
    }

    /**
     * Take the alert out of the session and hand back the configuration only.
     *
     * The session stores an envelope — {"config": {...}, "type": "config"} —
     * and it is the inner configuration that SweetAlert2 understands. Sharing
     * the envelope produced an alert with no title, no icon and no buttons.
     */
    protected function pullAlert(Request $request, string $key): ?array
    {
        if (! $request->hasSession() || ! $request->session()->has($key)) {
            return null;
        }

        $raw = $request->session()->pull($key);

        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;

        if (! is_array($decoded)) {
            return null;
        }

        return $decoded['config'] ?? $decoded;
    }
}
