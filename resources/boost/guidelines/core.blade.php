{{--
  Laravel Boost third-party package AI guideline for realrashid/sweet-alert
  Provides concise instructions and examples for AI agents to integrate this package.
--}}

# Sweet Alert (realrashid/sweet-alert)

This package provides Blade helpers and published assets to easily display SweetAlert2 popups and toasts from Laravel applications. It publishes view templates, configuration, and JavaScript/CSS assets so applications can customize themes and loading behavior.

## Features

- Helper API: `alert()` helper and `RealRashid\SweetAlert\Facades\Alert` facade for dispatching alerts from controllers.
- Publishable resources: view templates, config, and bundled JS assets (publish tags listed below).
- Blade view integration: a Blade view injects `Swal.fire(...)` calls when `Session::has('alert.config')` or `alert()->delete()` flows are used.

## Publishable tags

- `sweetalert-view` — publishes `resources/views/vendor/sweetalert`
- `sweetalert-config` — publishes `config/sweetalert.php`
- `sweetalert-asset` — publishes the JS/CSS assets to `public/vendor/sweetalert`

Example: `php artisan vendor:publish --provider="RealRashid\SweetAlert\SweetAlertServiceProvider" --tag="sweetalert-config"`

## Typical usage

1. Install the package via Composer and (optionally) publish assets.

```php
// In a controller
use RealRashid\SweetAlert\Facades\Alert;

public function destroy($id)
{
    // Perform deletion logic...
    Alert::success('Deleted', 'Record was deleted successfully');
    return redirect()->route('items.index');
}
```

2. In Blade, the package's `alert` view will automatically enqueue the required JS/CSS depending on `config('sweetalert')` settings. The view uses `asset('vendor/sweetalert/sweetalert.all.js')` by default.

3. To add client-side confirm-on-delete behavior, the package supports `data-confirm-delete` anchors. Example:

```html
<a href="@{{ route('items.destroy', $item->id) }}" data-confirm-delete>Delete</a>
```

When the attribute is present, the package injects a `Swal.fire({ ... })` confirmation and, if confirmed, submits a hidden form to perform the deletion.

## Configuration hints

- `alwaysLoadJS`, `neverLoadJS` — controls whether the bundled JS is loaded automatically.
- `theme` — controls which SweetAlert2 theme to load from CDN when set.

## Notes for Laravel 13

- The package advertises Laravel 13 compatibility; ensure your project PHP requirement matches Laravel 13's minimum if you upgrade the application.
- This file is short and prescriptive so AI agents can provide correct integration snippets and publishing commands.
