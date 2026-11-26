<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use RealRashid\SweetAlert\Http\Middleware\ToSweetAlert;

/*
 * The middleware is what turns an ordinary `redirect()->with('success', ...)`
 * into an alert, so most applications hit it on every form submission. None of
 * these paths had a test.
 */

/** Run the middleware over a seeded session and return what it flashed. */
function throughMiddleware(callable $seed, string $bag = 'config'): array
{
    session()->flush();
    $seed();

    $request = Request::create('/', 'GET');
    $request->setLaravelSession(session()->driver());

    app(ToSweetAlert::class)->handle($request, fn () => new Response('ok'));

    $json = session("alert.{$bag}");

    return $json ? (json_decode($json, true)['config'] ?? []) : [];
}

describe('flash message conversion', function () {
    it('converts each Laravel flash key', function (string $key) {
        $config = throughMiddleware(fn () => session()->flash($key, "A {$key} message"));

        expect($config['icon'])->toBe($key)
            ->and($config['title'])->toBe("A {$key} message");
    })->with(['success', 'error', 'warning', 'info', 'question']);

    it('reads an array payload as title and text', function () {
        $config = throughMiddleware(fn () => session()->flash('success', ['Saved', 'All good']));

        expect($config['title'])->toBe('Saved')
            ->and($config['text'])->toBe('All good');
    });

    it('leaves unrelated flash keys alone', function () {
        expect(throughMiddleware(fn () => session()->flash('status', 'Profile updated')))->toBe([]);
    });

    it('converts a toast_ key into a toast', function () {
        $config = throughMiddleware(fn () => session()->flash('toast_success', 'Saved via toast'));

        expect($config['toast'])->toBeTrue()
            ->and($config['title'])->toBe('Saved via toast');
    });
});

describe('validation errors', function () {
    it('displays MessageBag errors in a persistent escaped HTML list', function () {
        $config = throughMiddleware(fn () => session()->flash('errors', new MessageBag([
            'email' => ['Email is <required>'],
            'name' => ['Name is too short'],
        ])));

        expect($config['icon'])->toBe('error')
            ->and($config['title'])->toBe('Please fix the following')
            ->and($config['html'])->toContain('<li>Email is &lt;required&gt;</li>')
            ->and($config['html'])->toContain('<li>Name is too short</li>')
            ->and($config['allowEscapeKey'])->toBeFalse()
            ->and($config['allowOutsideClick'])->toBeFalse()
            ->and($config)->not->toHaveKey('timer');
    });

    it('handles the ViewErrorBag Laravel actually flashes', function () {
        // Laravel puts a ViewErrorBag in the session, not a bare MessageBag.
        $bag = new ViewErrorBag;
        $bag->put('default', new MessageBag(['email' => ['Email is required']]));

        $config = throughMiddleware(fn () => session()->flash('errors', $bag));

        expect($config['html'])->toContain('<li>Email is required</li>');
    });

    it('does not display an empty error bag', function () {
        expect(throughMiddleware(fn () => session()->flash('errors', new ViewErrorBag)))->toBe([]);
    });

    it('passes a plain string through untouched', function () {
        $config = throughMiddleware(fn () => session()->flash('errors', 'Something broke'));

        expect($config['text'])->toBe('Something broke');
    });

    it('can be turned off', function () {
        config(['sweetalert.middleware.auto_display_errors' => false]);

        $config = throughMiddleware(fn () => session()->flash('errors', new MessageBag(['a' => ['b']])));

        expect($config)->toBe([]);
    });
});

describe('several messages at once', function () {
    it('leaves nothing of the first on the second', function () {
        // Each conversion goes through the legacy entry point, which resets,
        // so an earlier message cannot leak into a later one.
        $config = throughMiddleware(function () {
            session()->flash('success', 'First');
            session()->flash('error', 'Second');
        });

        expect($config['icon'])->toBe('error')
            ->and($config['title'])->toBe('Second');
    });
});

/*
 * Verified in a browser: redirect()->with('success', ...) produces a success
 * modal, and a genuine failed validate() produces a persistent error alert
 * with the validation messages. These pin the behaviour down.
 */
describe('plain Laravel flash keys', function () {
    it('turns a with() flash into an alert', function (string $type) {
        session()->flush();
        session()->put($type, 'A plain flash message');

        $request = Request::create('/', 'GET');
        $request->setLaravelSession(session()->driver());

        (new ToSweetAlert)
            ->handle($request, fn () => new Response);

        $flashed = json_decode(session('alert')['config'] ?? '{}', true)['config'] ?? [];

        expect($flashed['title'] ?? null)->toBe('A plain flash message')
            ->and($flashed['icon'] ?? null)->toBe($type);
    })->with(['success', 'error', 'warning', 'info', 'question']);

    it('turns a validation error bag into a persistent HTML alert', function () {
        session()->flush();
        session()->put('errors', new ViewErrorBag);
        session()->get('errors')->put('default', new MessageBag([
            'email' => ['The email field is required.'],
            'name' => ['The name field is required.'],
        ]));

        $request = Request::create('/', 'GET');
        $request->setLaravelSession(session()->driver());

        (new ToSweetAlert)
            ->handle($request, fn () => new Response);

        $flashed = json_decode(session('alert')['config'] ?? '{}', true)['config'] ?? [];

        expect($flashed['title'] ?? null)->toBe('Please fix the following')
            ->and($flashed['html'] ?? null)->toContain('<li>The email field is required.</li>')
            ->and($flashed['html'] ?? null)->toContain('<li>The name field is required.</li>')
            ->and($flashed['allowEscapeKey'] ?? null)->toBeFalse()
            ->and($flashed['allowOutsideClick'] ?? null)->toBeFalse();
    });

    it('leaves validation errors alone when the option is off', function () {
        session()->flush();
        config()->set('sweetalert.middleware.auto_display_errors', false);
        session()->put('errors', new ViewErrorBag);

        $request = Request::create('/', 'GET');
        $request->setLaravelSession(session()->driver());

        (new ToSweetAlert)
            ->handle($request, fn () => new Response);

        expect(session()->has('alert'))->toBeFalse();
    });
});
