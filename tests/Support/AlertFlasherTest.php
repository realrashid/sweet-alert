<?php

use Illuminate\Contracts\Session\Session;
use RealRashid\SweetAlert\Storage\AlertSessionStore;
use RealRashid\SweetAlert\Support\AlertConfig;
use RealRashid\SweetAlert\Support\AlertFlasher;

describe('AlertFlasher', function () {

    it('can flash an alert config to session', function () {
        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('flash')->once()->with('alert.config', Mockery::type('string'));

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        $config = new AlertConfig(['title' => 'Test', 'icon' => 'success']);
        $flasher->flash($config);
    });

    it('can flash a config array directly', function () {
        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('flash')->once()->with('alert.config', Mockery::type('string'));

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        $flasher->flashConfig(['title' => 'Direct', 'icon' => 'info']);
    });

    it('checks if alert exists in session', function () {
        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('has')->with('alert.config')->andReturn(true);
        $mockSession->shouldReceive('has')->with('alert.delete')->andReturn(false);

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        expect($flasher->hasAlert())->toBeTrue();
    });

    it('gets alert from session', function () {
        $config = new AlertConfig(['title' => 'Stored'], 'config');
        $json = $config->toJson();

        $mockSession = Mockery::mock(Session::class);
        // AlertSessionStore::get() forwards the $default arg, so the call is get('alert.config', null)
        $mockSession->shouldReceive('get')->with('alert.config', null)->andReturn($json);

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        $result = $flasher->getAlert();
        expect($result)->not->toBeNull();
        expect($result->get('title'))->toBe('Stored');
    });

    it('returns null when no alert in session', function () {
        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('get')->with('alert.config', null)->andReturn(null);

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        expect($flasher->getAlert())->toBeNull();
    });

    it('clears all alert data from session', function () {
        $mockSession = Mockery::mock(Session::class);
        $mockSession->shouldReceive('forget')->with('alert.config')->once();
        $mockSession->shouldReceive('forget')->with('alert.delete')->once();

        $sessionStore = new AlertSessionStore($mockSession);
        $flasher = new AlertFlasher($sessionStore);

        $flasher->clear();
    });
});
