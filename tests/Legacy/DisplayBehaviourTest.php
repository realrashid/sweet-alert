<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Facades\Alert;

/*
 * These cover two bugs that a unit test alone would not have found, because
 * both only appear when the package is used the way a real application uses
 * it — a whole request, with more than one alert in it.
 */

/**
 * The config that actually reached the session.
 *
 * confirmDelete writes to `alert.delete` rather than `alert.config`, because
 * the Blade view binds it to a click handler instead of showing it on load.
 */
function flashedConfig(?string $key = null, string $bag = 'config'): mixed
{
    $json = session("alert.{$bag}");

    if (! $json) {
        return null;
    }

    $config = json_decode($json, true)['config'] ?? [];

    return $key === null ? $config : ($config[$key] ?? null);
}

describe('legacy calls display immediately', function () {
    /*
     * Every released version called flash() inside alert(), so a single line
     * in a controller showed something. Losing that is a silent break:
     * no exception, no deprecation, alerts simply stop appearing.
     */
    it('flashes on a bare icon shortcut', function (string $method) {
        session()->flush();

        Alert::{$method}('A title', 'Some text');

        expect(flashedConfig('title'))->toBe('A title');
    })->with(['success', 'error', 'warning', 'info', 'question']);

    it('flashes on alert()', function () {
        session()->flush();

        Alert::alert('Legacy', 'Still works', 'success');

        expect(flashedConfig('title'))->toBe('Legacy')
            ->and(flashedConfig('icon'))->toBe('success');
    });

    it('flashes on toast()', function () {
        session()->flush();

        Alert::toast('Profile updated', 'success');

        expect(flashedConfig('toast'))->toBeTrue()
            ->and(flashedConfig('title'))->toBe('Profile updated');
    });

    it('flashes on confirmDelete()', function () {
        session()->flush();

        Alert::confirmDelete('Delete order?', 'Cannot be undone');

        // Bound to a delete link rather than shown on load, so it has its own bag.
        expect(flashedConfig('title', 'delete'))->toBe('Delete order?')
            ->and(flashedConfig('confirmButtonText', 'delete'))->toBe('Yes, delete it!');
    });

    it('keeps the session in step when the chain continues', function () {
        // The released package flashed on every mutator, so this applied the
        // timer. Chains written against the old API must keep working.
        session()->flush();

        Alert::success('Saved')->autoClose(3000);

        expect(flashedConfig('timer'))->toBe(3000);
    });
});

describe('explicit chains wait for flash()', function () {
    it('does not touch the session until told to', function () {
        session()->flush();

        AlertBuilder::make()->success('Composed')->autoClose(9000);

        expect(session('alert.config'))->toBeNull();
    });

    it('flashes the finished config', function () {
        session()->flush();

        AlertBuilder::make()->success('Composed')->autoClose(9000)->flash();

        expect(flashedConfig('title'))->toBe('Composed')
            ->and(flashedConfig('timer'))->toBe(9000);
    });
});

describe('two alerts in one request', function () {
    /*
     * The builder is a singleton, and make() used to resolve it from the
     * container rather than building a new one. A confirmDelete followed by a
     * success left "Yes, delete it!" on the success alert — which only shows
     * up when a single request produces two alerts.
     */
    it('does not carry confirmDelete buttons onto the next alert', function () {
        session()->flush();

        Alert::confirmDelete('Delete order?', 'Cannot be undone');
        Alert::success('Saved');

        expect(flashedConfig('title'))->toBe('Saved')
            ->and(flashedConfig('confirmButtonText'))->not->toBe('Yes, delete it!')
            ->and(flashedConfig('showCancelButton'))->not->toBeTrue();
    });

    it('does not carry a timer onto the next alert', function () {
        session()->flush();

        Alert::success('First')->autoClose(1234);
        Alert::error('Second');

        expect(flashedConfig('title'))->toBe('Second')
            ->and(flashedConfig('timer'))->not->toBe(1234);
    });

    it('gives make() a genuinely new builder each time', function () {
        expect(AlertBuilder::make())->not->toBe(AlertBuilder::make());
    });

    it('leaves the singleton alone when make() is used', function () {
        $singleton = app('alert');
        $singleton->title('On the singleton');

        AlertBuilder::make()->success('Separate')->flash();

        expect($singleton->getConfig()['title'])->toBe('On the singleton');
    });
});

describe('methods restored from the released API', function () {
    it('image() takes the released six-argument signature', function () {
        $config = AlertBuilder::make()
            ->image('Nice shot', 'Uploaded just now', '/photo.png', 320, 180)
            ->getConfig();

        expect($config['imageUrl'])->toBe('/photo.png')
            ->and($config['imageWidth'])->toBe(320)
            ->and($config['imageHeight'])->toBe(180)
            // The icon animation looks wrong on a photograph.
            ->and($config['animation'])->toBeFalse();
    });

    it('image() defaults the alt text to the title', function () {
        $config = AlertBuilder::make()->image('Nice shot', 'Text', '/photo.png')->getConfig();

        expect($config['imageAlt'])->toBe('Nice shot');
    });

    it('toToast() converts an alert', function () {
        $config = AlertBuilder::make()->success('Saved')->toToast('bottom-end')->getConfig();

        expect($config['toast'])->toBeTrue()
            ->and($config['position'])->toBe('bottom-end')
            ->and($config['showConfirmButton'])->toBeFalse()
            // A toast sizes itself; a modal width makes it span the screen.
            ->and($config)->not->toHaveKey('width')
            ->and($config)->not->toHaveKey('padding');
    });
});

/*
 * A legacy call flashes the moment it is made, so anything chained onto it has
 * to rewrite the session or it never reaches the page. The Concerns already
 * did this through reflash(); the core setters did not, so the chained value
 * was silently dropped and the alert rendered without it.
 */
describe('chaining after a legacy call keeps the session in step', function () {
    it('keeps html() set on an already-flashed alert', function () {
        session()->flush();

        Alert::success('Release notes')->html('<b>v8</b> is out.');

        expect(flashedConfig('html'))->toBe('<b>v8</b> is out.')
            ->and(flashedConfig())->not->toHaveKey('text');
    });

    it('keeps a chained toast icon', function () {
        session()->flush();

        Alert::toast('Profile updated')->success();

        expect(flashedConfig('icon'))->toBe('success')
            ->and(flashedConfig('toast'))->toBeTrue();
    });

    it('keeps a chained theme', function () {
        session()->flush();

        Alert::info('Dark')->theme('dark');

        expect(flashedConfig('theme'))->toBe('dark');
    });

    it('keeps a retitled alert', function () {
        session()->flush();

        Alert::success('First')->title('Second')->text('Body');

        expect(flashedConfig('title'))->toBe('Second')
            ->and(flashedConfig('text'))->toBe('Body');
    });

    it('does not touch the session before an explicit chain calls flash()', function () {
        session()->flush();

        AlertBuilder::make()->title('Composed')->icon('success');

        expect(flashedConfig())->toBeNull();
    });
});

/*
 * SweetAlert2 turns width, padding and background into inline styles on the
 * popup, and an inline style beats every stylesheet. Shipping a background
 * default meant no theme — global or per-alert — could ever change the popup
 * colour: every alert stayed white.
 */
describe('themable defaults', function () {
    it('sends no width, padding or background unless asked', function () {
        session()->flush();

        Alert::success('Themable');

        expect(flashedConfig())->not->toHaveKey('background')
            ->and(flashedConfig())->not->toHaveKey('width')
            ->and(flashedConfig())->not->toHaveKey('padding');
    });

    it('still sends them when the developer sets them', function () {
        session()->flush();

        Alert::success('Explicit')->background('#111')->width('40rem')->padding('2rem');

        expect(flashedConfig('background'))->toBe('#111')
            ->and(flashedConfig('width'))->toBe('40rem')
            ->and(flashedConfig('padding'))->toBe('2rem');
    });

    it('honours a configured background', function () {
        session()->flush();
        config()->set('sweetalert.background', '#0f172a');

        Alert::success('Configured');

        expect(flashedConfig('background'))->toBe('#0f172a');
    });
});
