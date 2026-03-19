---
name: sweet-alert-development
description: Build and work with Sweet Alert features, including publishing assets, using the helper facade, and adding confirm-delete UI patterns.
---

# Sweet Alert Development

## When to use this skill

Use this skill when implementing or modifying UI flows that require modal confirmations, toasts, or inline alerts using the `realrashid/sweet-alert` Laravel package.

## Features

- Publish package views and assets (`vendor:publish` tags: `sweetalert-view`, `sweetalert-config`, `sweetalert-asset`).
- Programmatically trigger alerts using the `alert()` helper or `RealRashid\SweetAlert\Facades\Alert`.
- Client-side `data-confirm-delete` attribute support for anchors that need confirmation before HTTP DELETE.

## Examples

### Publishing configuration

```bash
php artisan vendor:publish --provider="RealRashid\SweetAlert\SweetAlertServiceProvider" --tag="sweetalert-config"
```

### Triggering an alert from a controller

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::success('Saved', 'Your changes were saved successfully');
return redirect()->back();
```

### Using confirm-delete anchor in Blade

```blade
<a href="{{ route('posts.destroy', $post->id) }}" data-confirm-delete>Delete post</a>
```

When the user clicks this link, SweetAlert2 will prompt for confirmation and, upon confirm, the package will submit a DELETE form.

## Implementation tips for AI agents

- Prefer `asset('vendor/sweetalert/sweetalert.all.js')` references for the bundled file to avoid breaking changes when replacing the upstream JS bundle.
- Suggest `php artisan boost:install` to users who want Laravel Boost to pick up this package's guidelines and skills automatically.
