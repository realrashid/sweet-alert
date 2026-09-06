<?php

use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Enums\InputType;

describe('InputBuilder', function () {

    it('can be created', function () {
        $input = app(InputBuilder::class);
        expect($input)->toBeInstanceOf(InputBuilder::class);
    });

    it('sets title fluently', function () {
        $input = app(InputBuilder::class);
        $input->title('What is your name?');

        expect($input->getConfig()['title'])->toBe('What is your name?');
    });

    it('sets input type via enum', function () {
        $input = app(InputBuilder::class);
        $input->inputType(InputType::Email);

        expect($input->getConfig()['input'])->toBe('email');
    });

    it('sets input type via string', function () {
        $input = app(InputBuilder::class);
        $input->inputType('password');

        expect($input->getConfig()['input'])->toBe('password');
    });

    it('sets input placeholder', function () {
        $input = app(InputBuilder::class);
        $input->inputPlaceholder('Enter your email...');

        expect($input->getConfig()['inputPlaceholder'])->toBe('Enter your email...');
    });

    it('sets default input value', function () {
        $input = app(InputBuilder::class);
        $input->inputValue('john@example.com');

        expect($input->getConfig()['inputValue'])->toBe('john@example.com');
    });

    it('sets select options', function () {
        $input = app(InputBuilder::class);
        $options = ['US' => 'United States', 'UK' => 'United Kingdom'];
        $input->inputOptions($options);

        expect($input->getConfig()['inputOptions'])->toBe($options);
    });

    it('sets input attributes', function () {
        $input = app(InputBuilder::class);
        $input->inputAttributes(['maxlength' => 50, 'required' => true]);

        expect($input->getConfig()['inputAttributes']['maxlength'])->toBe(50);
    });

    it('sets input validation message', function () {
        $input = app(InputBuilder::class);
        $input->inputValidator('Please enter a valid email');

        expect($input->getConfig()['inputValidatorMessage'])->toBe('Please enter a valid email');
    });

    it('sets input label', function () {
        $input = app(InputBuilder::class);
        $input->inputLabel('Email Address');

        expect($input->getConfig()['inputLabel'])->toBe('Email Address');
    });

    it('sets pre-confirm route', function () {
        $input = app(InputBuilder::class);
        $input->preConfirmRoute('/api/validate-email');

        expect($input->getConfig()['preConfirmRoute'])->toBe('/api/validate-email');
    });

    it('sets input CSS class', function () {
        $input = app(InputBuilder::class);
        $input->inputClass('custom-input');

        expect($input->getConfig()['customClass']['input'])->toBe('custom-input');
    });

    it('enables auto-focus', function () {
        $input = app(InputBuilder::class);
        $input->inputAutoFocus();

        expect($input->getConfig()['inputAutoFocus'])->toBeTrue();
    });

    it('enables auto-trim', function () {
        $input = app(InputBuilder::class);
        $input->inputAutoTrim();

        expect($input->getConfig()['inputAutoTrim'])->toBeTrue();
    });

    it('shows confirm and cancel buttons by default', function () {
        $input = app(InputBuilder::class);
        expect($input->getConfig()['showConfirmButton'])->toBeTrue();
        expect($input->getConfig()['showCancelButton'])->toBeTrue();
    });

    it('supports all InputType enum values', function () {
        foreach (InputType::cases() as $type) {
            $input = app(InputBuilder::class);
            $input->inputType($type);
            expect($input->getConfig()['input'])->toBe($type->value);
        }
    });

    it('supports method chaining', function () {
        $input = app(InputBuilder::class);
        $result = $input
            ->title('Email?')
            ->inputType(InputType::Email)
            ->inputPlaceholder('user@example.com')
            ->confirmButton('Verify');

        expect($result)->toBeInstanceOf(InputBuilder::class);
        expect($input->getConfig()['title'])->toBe('Email?');
        expect($input->getConfig()['input'])->toBe('email');
        expect($input->getConfig()['inputPlaceholder'])->toBe('user@example.com');
    });

    it('serializes to array and JSON', function () {
        $input = app(InputBuilder::class);
        $input->title('Test')->inputType(InputType::Text);

        $array = $input->toArray();
        expect($array['title'])->toBe('Test');
        expect($array['input'])->toBe('text');

        $json = $input->toJson();
        $decoded = json_decode($json, true);
        expect($decoded['input'])->toBe('text');
    });
});
