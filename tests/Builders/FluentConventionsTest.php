<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;
use RealRashid\SweetAlert\Facades\Alert;

/*
 * Conditionable and Macroable are the two traits every other package in this
 * family exposes on its builder, so an alert chain reads the same way as a
 * cart, a QR code or a payment.
 */

describe('conditionable', function () {
    it('applies a when() branch', function () {
        $config = createAlertBuilder()
            ->success('Saved')
            ->when(true, fn (AlertBuilder $alert) => $alert->autoClose(3000))
            ->getConfig();

        expect($config['timer'])->toBe(3000);
    });

    it('skips a when() branch when the condition is false', function () {
        $default = createAlertBuilder()->success('Saved')->getConfig()['timer'];

        $config = createAlertBuilder()
            ->success('Saved')
            ->when(false, fn (AlertBuilder $alert) => $alert->autoClose(3000))
            ->getConfig();

        // The timer keeps its configured default rather than the branch value.
        expect($config['timer'])->toBe($default)->not->toBe(3000);
    });

    it('applies an unless() branch', function () {
        $config = createAlertBuilder()
            ->success('Saved')
            ->unless(false, fn (AlertBuilder $alert) => $alert->position('top-end'))
            ->getConfig();

        expect($config['position'])->toBe('top-end');
    });

    it('keeps the chain fluent either way', function () {
        expect(createAlertBuilder()->when(true, fn ($a) => $a->title('Yes')))->toBeFluent()
            ->and(createAlertBuilder()->when(false, fn ($a) => $a->title('No')))->toBeFluent();
    });

    it('reads naturally for a real decision', function () {
        // The reason this trait matters: the alternative is an if statement
        // wrapped around a chain, which reads far worse.
        $isAdmin = true;

        $config = Alert::make()
            ->success('Record saved')
            ->when($isAdmin, fn (AlertBuilder $alert) => $alert->text('Audit log updated.'))
            ->getConfig();

        expect($config['text'])->toBe('Audit log updated.');
    });

    it('works on a toast', function () {
        $config = ToastBuilder::make()
            ->title('Saved')
            ->when(true, fn (ToastBuilder $toast) => $toast->position('bottom-end'))
            ->getConfig();

        expect($config['position'])->toBe('bottom-end');
    });

    it('works on an input alert', function () {
        $config = InputBuilder::make()
            ->title('Your name?')
            ->when(true, fn (InputBuilder $input) => $input->autoClose(9000))
            ->getConfig();

        expect($config['timer'])->toBe(9000);
    });
});

describe('macroable', function () {
    afterEach(function () {
        AlertBuilder::flushMacros();
        ToastBuilder::flushMacros();
    });

    it('registers a macro on the alert builder', function () {
        AlertBuilder::macro('branded', function () {
            return $this->position('top-end')->autoClose(4000);
        });

        $config = createAlertBuilder()->success('Saved')->branded()->getConfig();

        expect($config['position'])->toBe('top-end')
            ->and($config['timer'])->toBe(4000);
    });

    it('lets a macro sit anywhere in the chain', function () {
        AlertBuilder::macro('quiet', fn () => $this->autoClose(1500));

        $config = createAlertBuilder()->quiet()->success('Saved')->getConfig();

        expect($config['timer'])->toBe(1500)
            ->and($config['icon'])->toBe('success');
    });

    it('registers a macro on the toast builder', function () {
        ToastBuilder::macro('corner', fn () => $this->position('bottom-start'));

        expect(ToastBuilder::make()->corner()->getConfig()['position'])->toBe('bottom-start');
    });

    it('throws for a method that is neither a macro nor real', function () {
        expect(fn () => createAlertBuilder()->definitelyNotAMethod())
            ->toThrow(BadMethodCallException::class);
    });
});
