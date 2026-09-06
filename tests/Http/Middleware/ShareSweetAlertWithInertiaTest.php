<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Inertia\Inertia;
use RealRashid\SweetAlert\Http\Middleware\ShareSweetAlertWithInertia;

/*
 * The previous version of these tests spied on Inertia::flash() and asserted it
 * had been called. They passed while the middleware did not work at all: it
 * flashed after the response had already resolved its props, so the alert never
 * reached the page, and it shared the session envelope rather than the
 * configuration inside it.
 *
 * These assert the thing that matters instead — what a page actually receives.
 */
beforeEach(function () {
    Inertia::flushShared();
});

/**
 * Run the middleware, then resolve whatever it shared, the way Inertia does
 * when it builds the props for a response.
 */
function sharedAlert(Store $session): mixed
{
    $request = Request::create('/test', 'GET');
    $request->setLaravelSession($session);

    (new ShareSweetAlertWithInertia)->handle($request, fn () => new Response);

    $shared = Inertia::getShared('sweetalert');

    return is_callable($shared) ? $shared() : $shared;
}

describe('ShareSweetAlertWithInertia', function () {
    it('passes the request through', function () {
        $request = Request::create('/test', 'GET');
        $request->setLaravelSession(createFakeSession());

        $response = (new ShareSweetAlertWithInertia)->handle($request, fn () => new Response('downstream', 200));

        expect($response->getContent())->toBe('downstream')
            ->and($response->getStatusCode())->toBe(200);
    });

    /*
     * The session holds {"config": {...}, "type": "config"}. Sharing that whole
     * envelope gave SweetAlert2 an object it does not understand, so the alert
     * rendered with no title, no icon and no buttons.
     */
    it('shares the configuration, not the session envelope', function () {
        $session = createFakeSession([
            'alert.config' => json_encode([
                'config' => ['title' => 'Saved!', 'icon' => 'success'],
                'type' => 'config',
            ]),
        ]);

        expect(sharedAlert($session))->toBe(['title' => 'Saved!', 'icon' => 'success']);
    });

    it('shares nothing when no alert is pending', function () {
        expect(sharedAlert(createFakeSession()))->toBeNull();
    });

    it('shares nothing when the session holds something unusable', function () {
        expect(sharedAlert(createFakeSession(['alert.config' => 'not json'])))->toBeNull();
    });

    it('accepts an already-decoded array', function () {
        $session = createFakeSession(['alert.config' => ['title' => 'Hello', 'icon' => 'info']]);

        expect(sharedAlert($session))->toBe(['title' => 'Hello', 'icon' => 'info']);
    });

    /*
     * Resolved lazily: the controller flashes the alert while the request is
     * being handled, which is after this middleware runs. Reading the session
     * up front would find nothing, and reading it after the response has been
     * built is too late — the props are already resolved.
     */
    it('reads the session when the prop is resolved, not when it is shared', function () {
        $session = createFakeSession();

        $request = Request::create('/test', 'GET');
        $request->setLaravelSession($session);

        (new ShareSweetAlertWithInertia)->handle($request, function () use ($session) {
            // Stands in for a controller flashing an alert mid-request.
            $session->put('alert.config', json_encode([
                'config' => ['title' => 'Flashed during the request'],
                'type' => 'config',
            ]));

            return new Response;
        });

        $shared = Inertia::getShared('sweetalert');

        expect($shared('sweetalert'))->toBe(['title' => 'Flashed during the request']);
    });

    it('takes the alert out of the session so it cannot show twice', function () {
        $session = createFakeSession([
            'alert.config' => json_encode(['config' => ['title' => 'Once'], 'type' => 'config']),
        ]);

        sharedAlert($session);

        expect($session->has('alert.config'))->toBeFalse();
    });

    it('survives a request with no session at all', function () {
        $request = Request::create('/test', 'GET');

        (new ShareSweetAlertWithInertia)->handle($request, fn () => new Response);

        $shared = Inertia::getShared('sweetalert');

        expect($shared('sweetalert'))->toBeNull();
    });
})->skip(! class_exists(Inertia::class), 'Inertia is not installed');

// ─── Helper ─────────────────────────────────────────────────────────────────

/**
 * Create a minimal fake session for middleware testing.
 *
 * @param  array<string, mixed>  $data  Initial session data.
 */
function createFakeSession(array $data = []): Store
{
    $session = new Store('test', new ArraySessionHandler(10));

    foreach ($data as $key => $value) {
        $session->put($key, $value);
    }

    return $session;
}
