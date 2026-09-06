---
name: sweet-alert-development
description: Build SweetAlert2 flows in Laravel with realrashid/sweet-alert v8 — flashing alerts and toasts, input dialogs that post their value, and confirmation dialogs for destructive or irreversible actions.
---

# Sweet Alert Development

## When to use this skill

Reach for this when a Laravel application needs a modal, a toast, an input
prompt, or a confirmation before a destructive action, and the project already
depends on `realrashid/sweet-alert`.

It covers v8. The v8 API is a fluent builder and differs from v7 in a few
places that fail quietly, so check the version in `composer.json` before
writing code against it. On a v7 codebase, run the migration command rather
than editing call sites by hand:

```bash
php artisan alert:upgrade --dry-run
```

## The mental model

An alert is built in PHP, flashed to the session, and rendered by the
`@sweetAlert` directive on the next response. That means the normal shape is
"flash, then redirect" — exactly like `with('status', ...)`.

```php
use RealRashid\SweetAlert\Facades\Alert;

public function update(Request $request, Post $post)
{
    $post->update($request->validated());

    Alert::success('Saved', 'Your changes have been stored.');

    return back();
}
```

The five icon shortcuts (`success`, `error`, `warning`, `info`, `question`)
display on their own when given a title. A chain you compose yourself —
starting from `Alert::title()`, `Alert::make()` or `Alert::input()` — must end
with `flash()`.

## Common tasks

### Toast instead of a modal

```php
Alert::toast('Profile updated', 'success')->flash();
```

### Ask before a destructive action

Put the attribute on the link. No controller change and no JavaScript:

```blade
<a href="{{ route('posts.destroy', $post) }}" data-confirm-delete>Delete</a>
```

For anything that is not a delete, name the method:

```blade
<a href="{{ route('posts.publish', $post) }}"
   data-confirm data-confirm-method="PUT">Publish</a>
```

Or guard the whole form:

```blade
<form method="POST" action="{{ route('orders.refund', $order) }}" data-confirm
      data-confirm-title="Refund this order?">
    @csrf
    <button type="submit">Refund</button>
</form>
```

Per-element copy: `data-confirm-title`, `data-confirm-text`,
`data-confirm-icon`, `data-confirm-button`, `data-confirm-cancel`. Defaults
live in the `confirm` and `confirm_delete` blocks of `config/sweetalert.php`.

### Collect a value from the user

```php
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('What should we call you?', InputType::Text)
    ->inputPlaceholder('Rashid Ali')
    ->submitTo(route('profile.name'), 'POST', 'name')
    ->flash();
```

```php
Route::post('/profile/name', function (Request $request) {
    $request->user()->update(['name' => $request->input('name')]);

    Alert::success('Name updated');

    return back();
})->name('profile.name');
```

Without `submitTo()` the dialog closes and the value is discarded. For
server-side validation while the dialog is still open, use
`preConfirmRoute($url)`, which expects JSON of the shape
`{"valid": false, "message": "..."}`.

### Render a Blade view inside the alert

```php
Alert::title('Invoice')
    ->view('invoices.summary', ['invoice' => $invoice])
    ->success()
    ->flash();
```

### Livewire

Session-flashed alerts do not fire on a Livewire update, so dispatch a browser
event instead:

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

`sweetToast()` and `sweetInput()` are there too, with `dispatchToast()` and
`dispatchInput()`.

## Setup, if the package is not installed yet

```bash
composer require realrashid/sweet-alert
php artisan alert:install
```

Then put the directive at the end of the layout body:

```blade
<body>
    ...
    @sweetAlert
</body>
```

Individual publish tags are `sweetalert-config`, `sweetalert-views` and
`sweetalert-asset`, or use `php artisan alert:publish --config`.

## Pitfalls

- **`html()` takes one argument.** v7's was `html($title, $code, $icon)`. PHP
  ignores surplus arguments, so a v7 call still runs and renders nothing at
  all. Set the title with `title()`.
- **`view()` takes the view name first.** v7 put the title first, so an
  un-migrated call tries to render a view named after the title.
- **`Alert::input()` and `Alert::make()` never flash themselves.** End the
  chain with `flash()`.
- **Do not set `background()`, `width()` or `padding()` casually.**
  SweetAlert2 applies each as an inline style on the popup, which overrides
  every theme stylesheet, so a stray `background()` makes themes do nothing.
- **`@sweetAlert` belongs at the end of `<body>`**, not in `<head>`.
- **The container binding is `app('alert')`.** `app('sweet-alert')` was v7.
- Do not hand-write `Swal.fire(...)` for confirmations. Use `data-confirm` —
  it already handles the CSRF token, the method spoofing and the form.
