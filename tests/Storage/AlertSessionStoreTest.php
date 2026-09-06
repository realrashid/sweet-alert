<?php

use Illuminate\Contracts\Session\Session;
use RealRashid\SweetAlert\Contracts\SessionStoreInterface;
use RealRashid\SweetAlert\Storage\AlertSessionStore;

describe('AlertSessionStore', function () {

    it('implements SessionStoreInterface', function () {
        $session = Mockery::mock(Session::class);
        $store = new AlertSessionStore($session);

        expect($store)->toBeInstanceOf(SessionStoreInterface::class);
    });

    it('can be instantiated with Session contract — not concrete Store (BUG-1 DI fix)', function () {
        // Verifies the constructor accepts Illuminate\Contracts\Session\Session,
        // which is what session.store resolves to via the service container.
        $session = Mockery::mock(Session::class);

        expect(fn () => new AlertSessionStore($session))->not->toThrow(Throwable::class);
    });

    it('delegates flash() to the underlying session', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('flash')->once()->with('alert.config', 'json-payload');

        $store = new AlertSessionStore($session);
        $store->flash('alert.config', 'json-payload');
    });

    it('delegates put() to the underlying session', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('put')->once()->with('key', 'value');

        $store = new AlertSessionStore($session);
        $store->put('key', 'value');
    });

    it('delegates get() to the underlying session and returns its value', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('get')->with('alert.config', null)->andReturn('stored-value');

        $store = new AlertSessionStore($session);

        expect($store->get('alert.config'))->toBe('stored-value');
    });

    it('returns the provided default when the key is missing', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('get')->with('missing', 'fallback')->andReturn('fallback');

        $store = new AlertSessionStore($session);

        expect($store->get('missing', 'fallback'))->toBe('fallback');
    });

    it('delegates has() to the underlying session', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('has')->with('alert.config')->andReturn(true);
        $session->shouldReceive('has')->with('alert.queue')->andReturn(false);

        $store = new AlertSessionStore($session);

        expect($store->has('alert.config'))->toBeTrue();
        expect($store->has('alert.queue'))->toBeFalse();
    });

    it('delegates forget() to the underlying session', function () {
        $session = Mockery::mock(Session::class);
        $session->shouldReceive('forget')->once()->with('alert.config');

        $store = new AlertSessionStore($session);
        $store->forget('alert.config');
    });
});
