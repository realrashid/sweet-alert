{{--
  Laravel Boost guideline for realrashid/sweet-alert (v8).

  This file is compiled by Blade, so anything in an example that Blade would
  act on has to be escaped: {{ }} as @{{ }} and directives as @@sweetAlert,
  @@csrf and so on. Otherwise the compiler evaluates them and the guideline
  ships with a rendered CSRF field in it instead of the code to copy.
--}}

# Sweet Alert (realrashid/sweet-alert)

SweetAlert2 for Laravel, driven from PHP. Alerts are built with a fluent
builder, flashed to the session, and rendered by a Blade directive after the
redirect — the same round trip a normal Laravel form makes.

Version 8 is a complete rewrite. If you are looking at code that uses the v7
API, run `php artisan alert:upgrade --dry-run` rather than editing it by hand.

## Setup

```bash
composer require realrashid/sweet-alert
php artisan alert:install
```

Then add the directive to the layout, immediately before the closing body tag:

```blade
<body>
    ...
    @@sweetAlert
</body>
```

Publish tags, if you need them individually: `sweetalert-config`,
`sweetalert-views`, `sweetalert-asset`. Prefer the dedicated command:

```bash
php artisan alert:publish --config
```

## Showing an alert

The five icon shortcuts display immediately when given a title. This is the
common case and needs no `flash()`:

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::success('Saved', 'Your changes have been stored.');
Alert::error('Something broke', 'We could not reach the payment gateway.');
Alert::warning('Careful', 'This affects 42 records.');
Alert::info('Heads up', 'Exports now run in the background.');
Alert::question('Still there?', 'Your session expires in five minutes.');

return back();
```

Anything chained onto those keeps working — the session is kept in step:

```php
Alert::success('Auto-closing', 'Gone in three seconds.')
    ->timer(3000)
    ->timerProgressBar();
```

## Composing an alert explicitly

When you build one up rather than using a shortcut, finish with `flash()`:

```php
Alert::title('Order Summary')
    ->html('<p>Order <b>#12345</b> has shipped.</p>')
    ->success()
    ->flash();
```

`Alert::make()` gives you a builder independent of the shared instance. It
never flashes on its own.

## Toasts

```php
Alert::toast('Profile updated', 'success')->flash();

Alert::toast('Saved!')->success()->position('bottom-end')->flash();
```

## Inputs

An input alert never flashes itself. Always end the chain with `flash()`:

```php
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('What is your email?', InputType::Email)
    ->inputPlaceholder('you@example.com')
    ->submitTo(route('profile.email'), 'POST', 'email')
    ->flash();
```

`submitTo()` is how the typed value reaches the server. Without it the dialog
closes and the value is gone.

## Confirming an action

Put the attribute on the link or form. Nothing needs to be flashed first, and
no JavaScript is written by hand:

```blade
<a href="@{{ route('posts.destroy', $post) }}" data-confirm-delete>Delete</a>

<a href="@{{ route('posts.publish', $post) }}"
   data-confirm data-confirm-method="PUT">Publish</a>

<form method="POST" action="@{{ route('orders.refund', $order) }}" data-confirm>
    @@csrf
    <button type="submit">Refund</button>
</form>
```

Override the copy per element with `data-confirm-title`, `data-confirm-text`,
`data-confirm-icon`, `data-confirm-button` and `data-confirm-cancel`. On
confirm the package builds a form carrying the CSRF token and the right method
and submits it.

`Alert::confirmDelete($title, $text)` still works and customises the dialog for
that one request.

## Enums

`AlertType`, `InputType` and `Position` are accepted anywhere their string
equivalent is:

```php
use RealRashid\SweetAlert\Enums\Position;

Alert::toast('Saved')->success()->position(Position::BottomEnd)->flash();
```

## Livewire and Inertia

Livewire components use the trait, which dispatches a browser event instead of
relying on a session that a Livewire update will not re-read:

```php
use RealRashid\SweetAlert\Concerns\SweetAlertTrait;

class SavePost extends Component
{
    use SweetAlertTrait;

    public function save(): void
    {
        $this->dispatchAlert($this->sweetAlert()->success('Saved'));
    }
}
```

For Inertia, add `RealRashid\SweetAlert\Http\Middleware\ShareSweetAlertWithInertia`
to the web middleware group.

## Things that are commonly got wrong

- `html()` takes **one** argument in v8. In v7 it was `html($title, $code, $icon)`.
  PHP ignores the surplus arguments, so a v7 call does not error — it silently
  renders nothing. Use `title()` for the title.
- `view()` takes the **view name first**: `view($view, $data, $mergeData)`.
  In v7 the title came first.
- `Alert::input(...)` and `Alert::make()` do not flash. End with `flash()`.
- Do not set `background()`, `width()` or `padding()` unless you mean to
  override the theme — SweetAlert2 turns each into an inline style that no
  theme stylesheet can beat.
- `@@sweetAlert` goes at the end of the body, not in the head.
- The container binding is `app('alert')`. `app('sweet-alert')` was v7.
