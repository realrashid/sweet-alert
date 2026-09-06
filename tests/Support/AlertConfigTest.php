<?php

use RealRashid\SweetAlert\Support\AlertConfig;

describe('AlertConfig', function () {

    it('can be created with empty config', function () {
        $config = new AlertConfig;
        expect($config->toArray())->toBe([]);
    });

    it('can be created with config array', function () {
        $config = new AlertConfig(['title' => 'Test', 'icon' => 'success']);
        expect($config->toArray())->toBe(['title' => 'Test', 'icon' => 'success']);
    });

    it('can get individual config values', function () {
        $config = new AlertConfig(['title' => 'Test']);
        expect($config->get('title'))->toBe('Test');
    });

    it('returns default for missing keys', function () {
        $config = new AlertConfig([]);
        expect($config->get('missing', 'default'))->toBe('default');
    });

    it('checks if key exists', function () {
        $config = new AlertConfig(['title' => 'Test']);
        expect($config->has('title'))->toBeTrue();
        expect($config->has('missing'))->toBeFalse();
    });

    it('serializes to JSON', function () {
        $config = new AlertConfig(['title' => 'Test'], 'config');
        $json = $config->toJson();
        $decoded = json_decode($json, true);

        expect($decoded['config']['title'])->toBe('Test');
        expect($decoded['type'])->toBe('config');
    });

    it('deserializes from JSON', function () {
        $original = new AlertConfig(['title' => 'Test', 'icon' => 'success'], 'config');
        $json = $original->toJson();
        $restored = AlertConfig::fromJson($json);

        expect($restored->get('title'))->toBe('Test');
        expect($restored->get('icon'))->toBe('success');
        expect($restored->type())->toBe('config');
    });

    it('detects toast configuration', function () {
        $toastConfig = new AlertConfig(['toast' => true, 'title' => 'Toast!']);
        expect($toastConfig->isToast())->toBeTrue();

        $modalConfig = new AlertConfig(['title' => 'Modal']);
        expect($modalConfig->isToast())->toBeFalse();
    });

    it('detects input configuration', function () {
        $inputConfig = new AlertConfig(['input' => 'email', 'title' => 'Enter email']);
        expect($inputConfig->hasInput())->toBeTrue();

        $modalConfig = new AlertConfig(['title' => 'No input']);
        expect($modalConfig->hasInput())->toBeFalse();
    });

    it('detects pre-confirm route', function () {
        $config = new AlertConfig(['preConfirmRoute' => '/api/validate']);
        expect($config->hasPreConfirmRoute())->toBeTrue();
        expect($config->getPreConfirmRoute())->toBe('/api/validate');
    });

    it('returns Swal config JSON (without wrapper)', function () {
        $config = new AlertConfig(['title' => 'Test', 'icon' => 'success']);
        $swalJson = $config->toSwalConfigJson();
        $decoded = json_decode($swalJson, true);

        expect($decoded['title'])->toBe('Test');
        expect($decoded)->not->toHaveKey('config');
        expect($decoded)->not->toHaveKey('type');
    });

    it('removes pre-confirm route for JS output', function () {
        $config = new AlertConfig(['title' => 'Test', 'preConfirmRoute' => '/api/validate']);
        $clean = $config->withoutPreConfirmRoute();

        expect($clean->has('preConfirmRoute'))->toBeFalse();
        expect($clean->has('title'))->toBeTrue();
    });

    it('returns correct type', function () {
        $config = new AlertConfig([], 'delete');
        expect($config->type())->toBe('delete');
    });
});
