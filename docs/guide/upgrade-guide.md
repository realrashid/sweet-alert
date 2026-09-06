# Upgrade Guide

## Upgrading from v7 to v8

### Let the package do it

Most of the migration is mechanical, so v8 ships a command that does it for you:

```bash
php artisan alert:upgrade --dry-run
```

It scans `app`, `routes`, `resources/views`, `config` and `database` — never
`vendor` — and prints exactly what it would change. Nothing is written until you
run it without `--dry-run`, and even then it asks first:

```bash
php artisan alert:upgrade
```

What it rewrites:

| Found | Rewritten to |
|---|---|
| `alert()->html($title, $code, $icon)` | `alert()->title($title)->html($code)->icon($icon)->flash()` |
| `alert()->view($title, $view, $data)` | `alert()->title($title)->view($view, $data)->flash()` |
| `app('sweet-alert')` | `app('alert')` |
| `Storage\SessionStore` | `Contracts\SessionStoreInterface` |
| `@include('sweetalert::alert')` | `@sweetAlert` |
| `background`/`width`/`padding` config defaults | `null`, so themes work |

It reads your code with PHP's own tokeniser rather than searching for text, so
a `->html()` call on some other object, or the string `app('sweet-alert')` inside
a comment or a message, is reported but never edited. Anything it cannot rewrite
safely — a `Toaster` type hint, a call to the removed `buildConfig()` — is listed
under **Needs a human** so you can deal with it yourself.

Scope it to one directory while you build confidence:

```bash
php artisan alert:upgrade --path=app/Http/Controllers --dry-run
```

Commit before you run it, review the diff afterwards, and run your test suite.



Version 8 is a complete rewrite of the Laravel SweetAlert package. While we've maintained backward compatibility for the most common usage patterns, some breaking changes were necessary to deliver the new fluent API architecture.

## Backward Compatible Changes

The following v7 patterns continue to work without modification:

### Global Helper Functions

```php
// v7 — still works in v8
alert('Title', 'Message', 'success');
toast('Message', 'success');
confirmDelete('Delete this?', 'Are you sure?');
```

### Middleware-Based Toasts

The `ToSweetAlert` middleware continues to intercept Laravel's built-in flash messages (`success`, `error`, `warning`, `info`, `question`) and turn them into alerts automatically, just like in v7. Use a `toast_` prefix — `toast_success`, `toast_error` — when you want a toast instead.

### Session Keys

All session keys (`alert.config`, `alert.delete`) remain the same, so any custom integrations that read from these keys will continue to work.

### Blade Include

The `@include('sweetalert::alert')` directive works exactly as before. No changes needed in your layout files — but you can now use the cleaner `@sweetAlert` directive instead.

### Toaster Class (BC Shim)

The legacy `Toaster` class methods are mapped to the new `AlertBuilder` through backward-compatibility shims. If you were using `Toaster` directly, those calls will still resolve correctly through the service container.

## What Still Works

Code written against v7 keeps working. In particular, a bare call still shows an
alert without `flash()`:

```php
// v7, and still v8
Alert::success('Saved!', 'Your changes have been saved.');
Alert::error('Something went wrong');
Alert::toast('Profile updated', 'success');
Alert::confirmDelete('Delete order?', 'This cannot be undone.');
```

So does carrying on from one:

```php
Alert::success('Saved!')->autoClose(3000);
```

You do not need to revisit existing controllers. The fluent API in v8 is
something you can adopt where it helps, not a migration you have to complete.

## Breaking Changes

### PHP and Laravel Requirements

| Requirement | v7 | v8 |
|---|---|---|
| PHP | 7.3+ | 8.3+ |
| Laravel | 7.0+ | 11.0+ |

If you're running an older PHP or Laravel version, you'll need to upgrade your application before installing v8.

### New `@sweetAlert` Blade Directive

Version 8 introduces a dedicated `@sweetAlert` Blade directive as the recommended way to include SweetAlert2 in your layout. The old `@include('sweetalert::alert')` approach still works, but `@sweetAlert` is cleaner and more Laravel-idiomatic:

```blade
{{-- v7 (still works) --}}
@include('sweetalert::alert')

{{-- v8 (recommended) --}}
@sweetAlert
```

### Removed Methods

| v7 Method | v8 Replacement | Notes |
|---|---|---|
| `Toaster::alert()` | `Alert::alert()` or `alert()` | BC shim available |
| `Toaster::success()` | `Alert::success()` | `flash()` is optional here |
| `Toaster::info()` | `Alert::info()` | `flash()` is optional here |
| `Toaster::warning()` | `Alert::warning()` | `flash()` is optional here |
| `Toaster::error()` | `Alert::error()` | `flash()` is optional here |
| `Toaster::question()` | `Alert::question()` | `flash()` is optional here |
| `Toaster::message()` | `Alert::title()->text()->flash()` | More explicit API |
| `Toaster::basic()` | `Alert::title()->flash()` | More explicit API |

### Changed Method Signatures

Two methods kept their name but changed shape. Both take the content first now,
because in v8 the title is set by `title()` (or by the icon shortcut) rather than
passed to every method:

| v7 | v8 |
|---|---|
| `alert()->html($title, $code, $icon)` | `Alert::title($title)->html($code)->icon($icon)` |
| `alert()->view($title, $view, $data, $mergeData, $icon)` | `Alert::title($title)->view($view, $data, $mergeData)` |

`html()` is the one to grep for: PHP ignores the extra arguments, so an
un-migrated v7 call will not error — it will quietly put your **title** in the
alert body. Search your codebase for `->html(` before deploying.

### Configuration File Changes

::: warning If you published the config in v7, change three keys
`width`, `padding` and `background` are now `null` by default. SweetAlert2 turns
each of them into an *inline style* on the popup, and an inline style beats every
stylesheet — so a `background` value means no theme can ever change the popup
colour, and every alert stays white no matter what `theme` you set.

A config file published under v7 still carries the old values, so after
upgrading either re-publish it or set these three to `null` by hand:

```php
'width' => env('SWEET_ALERT_WIDTH'),
'padding' => env('SWEET_ALERT_PADDING'),
'background' => env('SWEET_ALERT_BACKGROUND'),
```

Set them again — per alert with `width()`, `padding()`, `background()`, or
globally in the config — whenever you actually want to override the theme.
:::

The configuration file has been restructured. Key changes:

- `sweetalert.alert_auto_close` → `sweetalert.timer`
- `sweetalert.alert_close_button` → `sweetalert.show_close_button`
- `sweetalert.toast_position` → `sweetalert.toast.position`
- `sweetalert.toast_close_button` → `sweetalert.toast.show_close_button`
- New `sweetalert.confirm_delete` section for delete dialog defaults
- New `sweetalert.middleware` section with expanded options
- New `sweetalert.animation` section for Animate.css support
- New `sweetalert.theme` option for SweetAlert2 themes

After upgrading, re-publish the configuration file:

```bash
php artisan alert:publish --config --force
```

### Service Container Binding

The `sweet-alert` binding has been replaced with `alert`. If you were resolving the service container directly:

```php
// v7
app('sweet-alert');

// v8
app('alert');
```

The `Alert` facade is the recommended way to interact with the package:

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::title('Hello')->success()->flash();
```

## New Features in v8

### Fluent Builder API

The most significant change is the introduction of the fluent builder pattern. Instead of the old positional-argument API, you now chain methods:

```php
// v7
alert()->success('Done!', 'Your data has been saved.');

// v8 — fluent
Alert::title('Done!')->success()->text('Your data has been saved.')->flash();
```

### `@sweetAlert` Blade Directive

A clean, dedicated Blade directive replaces the `@include` approach:

```blade
{{-- Add to your layout --}}
@sweetAlert
```

### ToastBuilder

Toasts now have their own dedicated builder with toast-specific defaults:

```php
Alert::toast('Saved!', 'success')
    ->position('bottom-end')
    ->autoClose(3000)
    ->flash();
```

### InputBuilder

You can now create input dialogs with full SweetAlert2 input support:

```php
Alert::input('Enter your email', InputType::Email)
    ->inputPlaceholder('user@example.com')
    ->flash();
```

### Enums

All type-safe values are now backed PHP enums:

```php
use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Enums\Position;
```

### Deny Button Support

SweetAlert2's three-button dialog pattern is now fully supported:

```php
Alert::title('What do you want?')
    ->showDenyButton('Archive', '#6c757d')
    ->showCancelButton()
    ->flash();
```

### Pre-Confirm Route

Validate input on the server before the alert resolves:

```php
Alert::input('Enter code', InputType::Text)
    ->preConfirmRoute(route('validate-code'))
    ->flash();
```

### Artisan Commands

New commands for installing and publishing assets:

```bash
php artisan alert:install          # One-step setup
php artisan alert:publish          # Publish individual assets
```

## Migration Checklist

1. **Update Composer**: `composer require realrashid/sweet-alert:^8.0`
2. **Re-publish config**: `php artisan alert:publish --config --force`
3. **Update middleware reference** if you registered it manually
4. **Replace `sweet-alert` container binding** with `alert`
5. **Switch to `@sweetAlert` directive** in your layout (optional but recommended)
6. **Adopt fluent API** for new code (old patterns still work)
7. **Update custom Blade views** if you published them

---

## What's New in v8

### SweetAlert2 v11 Compatibility

The bundled JavaScript has been updated for SweetAlert2 v11. The CDN configuration has been expanded — see [npm / Asset Bundling](/guide/npm) for all options.

### Confirm vs Confirm Delete

`Alert::confirm()` and `Alert::confirmDelete()` now have **separate config blocks**:

- `sweetalert.confirm` — question icon, blue confirm button (used by `Alert::confirm()`)
- `sweetalert.confirm_delete` — warning icon, red confirm button + loader (used by `Alert::confirmDelete()`)

Re-publish the config to get the new `confirm` block:

```bash
php artisan alert:publish --config --force
```

### New AlertBuilder Methods

| Method | Description |
|---|---|
| `->theme(string $theme)` | Apply a SweetAlert2 theme to this alert |
| `->preset(string $name)` | Apply a named preset from `sweetalert.presets` |
| `->preDenyRoute(string $route)` | Server-side deny validation route (mirrors preConfirmRoute) |
| `->validationMessage(string $msg)` | Message shown when pre-confirm fails |
| `->progressStepsDistance(string $distance)` | CSS gap between progress step circles |

### New Button Methods

| Method | Description |
|---|---|
| `->showLoaderOnDeny()` | Spinner on deny button during async operations |
| `->focusDeny()` | Set initial focus to the deny button |
| `->returnFocus()` | Return focus to triggering element on close |
| `->confirmButtonAriaLabel(string $label)` | ARIA label for the confirm button |
| `->denyButtonAriaLabel(string $label)` | ARIA label for the deny button |
| `->cancelButtonAriaLabel(string $label)` | ARIA label for the cancel button |
| `->returnInputValueOnDeny()` | Return input value when deny is clicked |

### New Styling Methods

| Method | Description |
|---|---|
| `->titleText(string $text)` | Set title as plain text (no HTML rendering) |
| `->target(string $selector)` | Render modal inside a specific DOM element |
| `->topLayer(bool $enable = true)` | Render in browser top-layer |
| `->scrollbarPadding(bool $enable = true)` | Control scrollbar-gap compensation |
| `->draggable(bool $enable = true)` | Make the modal draggable |
| `->loaderHtml(string $html)` | Custom HTML for the loader spinner |
| `->closeButtonHtml(string $html)` | Custom HTML for the close button |

### New Timer Methods

| Method | Description |
|---|---|
| `->stopKeydownPropagation(bool $stop = true)` | Stop/allow keyboard events propagating through the dialog |
| `->keydownListenerCapture(bool $capture = true)` | Use capture phase for internal keydown listener |

### New InputType Cases

`InputType::Search` and `InputType::DatetimeLocal` have been added, bringing the total to 19 supported input types.

### Framework Integrations

- **Livewire v4** — `SweetAlertTrait` dispatches browser events directly from Livewire components. See [Livewire Integration](/guide/livewire).
- **Inertia v3** — `ShareSweetAlertWithInertia` middleware uses the native `Inertia::flash()` API. See [Inertia Integration](/guide/inertia).
- **Vue 3 / React** — Client-side composable and hook included. See [Inertia Integration](/guide/inertia).
