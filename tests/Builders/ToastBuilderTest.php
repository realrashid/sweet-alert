<?php

use RealRashid\SweetAlert\Builders\ToastBuilder;

describe('ToastBuilder', function () {

    it('can be created', function () {
        $toast = app(ToastBuilder::class);
        expect($toast)->toBeInstanceOf(ToastBuilder::class);
    });

    it('sets toast: true by default', function () {
        $toast = app(ToastBuilder::class);
        expect($toast->getConfig()['toast'])->toBeTrue();
    });

    it('hides confirm button by default', function () {
        $toast = app(ToastBuilder::class);
        expect($toast->getConfig()['showConfirmButton'])->toBeFalse();
    });

    it('shows close button by default', function () {
        $toast = app(ToastBuilder::class);
        expect($toast->getConfig()['showCloseButton'])->toBeTrue();
    });

    it('sets default toast position', function () {
        $toast = app(ToastBuilder::class);
        expect($toast->getConfig()['position'])->toBe('top-end');
    });

    it('sets title fluently', function () {
        $toast = app(ToastBuilder::class);
        $toast->title('Toast message');

        expect($toast->getConfig()['title'])->toBe('Toast message');
    });

    it('sets icon fluently', function () {
        $toast = app(ToastBuilder::class);
        $toast->icon('success');

        expect($toast->getConfig()['icon'])->toBe('success');
    });

    it('provides icon shorthand methods', function () {
        $toast = app(ToastBuilder::class);
        $toast->success();
        expect($toast->getConfig()['icon'])->toBe('success');

        $toast->error();
        expect($toast->getConfig()['icon'])->toBe('error');

        $toast->warning();
        expect($toast->getConfig()['icon'])->toBe('warning');

        $toast->info();
        expect($toast->getConfig()['icon'])->toBe('info');
    });

    it('sets position', function () {
        $toast = app(ToastBuilder::class);
        $toast->position('bottom-end');

        expect($toast->getConfig()['position'])->toBe('bottom-end');
    });

    it('sets auto close timer', function () {
        $toast = app(ToastBuilder::class);
        $toast->autoClose(3000);

        expect($toast->getConfig()['timer'])->toBe(3000);
    });

    it('enables timer progress bar', function () {
        $toast = app(ToastBuilder::class);
        $toast->timerProgressBar();

        expect($toast->getConfig()['timerProgressBar'])->toBeTrue();
    });

    it('supports method chaining', function () {
        $toast = app(ToastBuilder::class);
        $result = $toast
            ->title('Chained toast')
            ->success()
            ->position('bottom-end')
            ->autoClose(2000)
            ->timerProgressBar();

        expect($result)->toBeInstanceOf(ToastBuilder::class);
        expect($toast->getConfig()['title'])->toBe('Chained toast');
        expect($toast->getConfig()['icon'])->toBe('success');
        expect($toast->getConfig()['position'])->toBe('bottom-end');
    });

    it('serializes to array', function () {
        $toast = app(ToastBuilder::class);
        $toast->title('Test Toast')->success();

        $array = $toast->toArray();
        expect($array['toast'])->toBeTrue();
        expect($array['title'])->toBe('Test Toast');
        expect($array['icon'])->toBe('success');
    });

    it('serializes to JSON', function () {
        $toast = app(ToastBuilder::class);
        $toast->title('JSON Toast')->error();

        $json = $toast->toJson();
        $decoded = json_decode($json, true);

        expect($decoded['toast'])->toBeTrue();
        expect($decoded['icon'])->toBe('error');
    });

    it('can be reset', function () {
        $toast = app(ToastBuilder::class);
        $toast->title('Old');
        $toast->reset();

        expect($toast->getConfig()['title'])->toBe('');
        expect($toast->getConfig()['toast'])->toBeTrue();
    });
});
