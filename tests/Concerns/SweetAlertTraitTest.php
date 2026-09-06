<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;
use RealRashid\SweetAlert\Concerns\SweetAlertTrait;

/**
 * Fake Livewire-like component to test the SweetAlertTrait in isolation.
 * Provides a stub dispatch() method that records what was dispatched
 * instead of hitting the real Livewire runtime.
 */
class FakeLivewireComponent
{
    use SweetAlertTrait;

    /** @var array<int, array{event: string, params: array<string, mixed>}> */
    public array $dispatched = [];

    public function dispatch(string $event, mixed ...$params): void
    {
        $this->dispatched[] = ['event' => $event, 'params' => $params];
    }
}

describe('SweetAlertTrait', function () {

    it('sweetAlert() resolves a fresh AlertBuilder from the container', function () {
        $component = new FakeLivewireComponent;

        expect($component->sweetAlert())->toBeInstanceOf(AlertBuilder::class);
    });

    it('sweetToast() resolves a fresh ToastBuilder from the container', function () {
        $component = new FakeLivewireComponent;

        expect($component->sweetToast())->toBeInstanceOf(ToastBuilder::class);
    });

    it('sweetInput() resolves a fresh InputBuilder from the container', function () {
        $component = new FakeLivewireComponent;

        expect($component->sweetInput())->toBeInstanceOf(InputBuilder::class);
    });

    it('dispatchAlert() fires the sweetalert browser event', function () {
        $component = new FakeLivewireComponent;
        $builder = AlertBuilder::make()->title('Live Alert')->success();

        $component->dispatchAlert($builder);

        expect($component->dispatched)->toHaveCount(1);
        expect($component->dispatched[0]['event'])->toBe('sweetalert');
    });

    it('dispatchAlert() passes the builder config in the event payload', function () {
        $component = new FakeLivewireComponent;
        $builder = AlertBuilder::make()->title('Payload Test')->error();

        $component->dispatchAlert($builder);

        $params = $component->dispatched[0]['params'];
        expect($params)->toHaveKey('config');
        expect($params['config']['title'])->toBe('Payload Test');
        expect($params['config']['icon'])->toBe('error');
    });

    it('dispatchToast() fires the sweetalert browser event with toast config', function () {
        $component = new FakeLivewireComponent;
        $toast = app(ToastBuilder::class)->title('Toast message!')->success();

        $component->dispatchToast($toast);

        expect($component->dispatched)->toHaveCount(1);
        expect($component->dispatched[0]['event'])->toBe('sweetalert');

        $params = $component->dispatched[0]['params'];
        expect($params)->toHaveKey('config');
        expect($params['config']['toast'])->toBeTrue();
        expect($params['config']['title'])->toBe('Toast message!');
    });

    it('dispatchInput() fires the sweetalert browser event with input config', function () {
        $component = new FakeLivewireComponent;
        $input = app(InputBuilder::class)->title('Enter a value');

        $component->dispatchInput($input);

        expect($component->dispatched)->toHaveCount(1);
        expect($component->dispatched[0]['event'])->toBe('sweetalert');

        $params = $component->dispatched[0]['params'];
        expect($params)->toHaveKey('config');
        expect($params['config']['title'])->toBe('Enter a value');
    });

    it('multiple dispatches accumulate independently', function () {
        $component = new FakeLivewireComponent;

        $component->dispatchAlert(AlertBuilder::make()->title('First')->success());
        $component->dispatchAlert(AlertBuilder::make()->title('Second')->error());

        expect($component->dispatched)->toHaveCount(2);
        expect($component->dispatched[0]['params']['config']['title'])->toBe('First');
        expect($component->dispatched[1]['params']['config']['title'])->toBe('Second');
    });
});

/*
 * A Livewire alert travels as a browser event. If the builder also flashed
 * itself to the session the same alert would appear a second time on the next
 * full page load — found while writing the v8 Boost guidelines.
 */
describe('the trait does not leak into the session', function () {
    it('does not flash when an icon shortcut is used', function () {
        session()->flush();
        $component = new FakeLivewireComponent;

        $component->sweetAlert()->success('Saved', 'All good');

        expect(session()->has('alert'))->toBeFalse();
    });

    it('does not flash a toast', function () {
        session()->flush();
        $component = new FakeLivewireComponent;

        $component->sweetToast()->title('Saved')->success();

        expect(session()->has('alert'))->toBeFalse();
    });

    it('still dispatches the alert as an event', function () {
        session()->flush();
        $component = new FakeLivewireComponent;

        $component->dispatchAlert($component->sweetAlert()->success('Saved'));

        expect($component->dispatched)->toHaveCount(1)
            ->and($component->dispatched[0]['event'])->toBe('sweetalert')
            ->and(session()->has('alert'))->toBeFalse();
    });

    it('still lets you flash on purpose', function () {
        session()->flush();
        $component = new FakeLivewireComponent;

        $component->sweetAlert()->success('Saved')->flash();

        expect(session()->has('alert'))->toBeTrue();
    });
});
