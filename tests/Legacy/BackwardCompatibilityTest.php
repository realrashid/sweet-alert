<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;

describe('Backward Compatibility', function () {

    it('alert() helper creates an alert builder', function () {
        $alert = alert('Title', 'Message', 'success');
        expect($alert)->toBeInstanceOf(AlertBuilder::class);
    });

    it('alert() helper returns builder when no args', function () {
        $alert = alert();
        expect($alert)->toBeInstanceOf(AlertBuilder::class);
    });

    it('toast() helper creates a toast builder', function () {
        $toast = toast('Message', 'success');
        expect($toast)->toBeInstanceOf(ToastBuilder::class);
    });

    it('confirmDelete() helper works', function () {
        $alert = confirmDelete('Delete this?', 'Are you sure?');
        expect($alert)->toBeInstanceOf(AlertBuilder::class);
    });

    it('old alert()->success() pattern works', function () {
        $alert = app('alert');
        $result = $alert->success('Success Title', 'Success text');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        $config = $alert->getConfig();
        expect($config['icon'])->toBe('success');
    });

    it('old alert()->error() pattern works', function () {
        $alert = app('alert');
        $result = $alert->error('Error Title', 'Error text');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($alert->getConfig()['icon'])->toBe('error');
    });

    it('old alert()->warning() pattern works', function () {
        $alert = app('alert');
        $alert->warning('Warning Title', 'Warning text');

        expect($alert->getConfig()['icon'])->toBe('warning');
    });

    it('old alert()->info() pattern works', function () {
        $alert = app('alert');
        $alert->info('Info Title', 'Info text');

        expect($alert->getConfig()['icon'])->toBe('info');
    });

    it('old alert()->question() pattern works', function () {
        $alert = app('alert');
        $alert->question('Question Title', 'Question text');

        expect($alert->getConfig()['icon'])->toBe('question');
    });

    it('old toast() with position works', function () {
        $toast = toast('Message', 'success', 'bottom-end');
        expect($toast->getConfig()['position'])->toBe('bottom-end');
    });
});
