# Getting Started

## Introduction

Laravel SweetAlert is a fluent, Laravel-native integration of the [SweetAlert2](https://sweetalert2.github.io/) JavaScript library. It provides an expressive builder API that lets you create beautiful alerts, toast notifications, and input dialogs directly from your Laravel backend — no JavaScript required.

The package has been completely rewritten from the ground up with a focus on:

- **Fluent API**: Chain methods naturally, just like Laravel Eloquent
- **Full SweetAlert2 coverage**: Every option the JS library supports
- **Type safety**: PHP 8.3+ backed enums for all configuration values
- **Builder pattern**: Purpose-built builders for alerts, toasts, and inputs
- **Backward compatibility**: Existing v7 code continues to work

## Requirements

| Requirement | Version |
|---|---|
| PHP | 8.3+ |
| Laravel | 11.0+, 12.0+, 13.0+ |

## Installation

Install the package via Composer:

```bash
composer require realrashid/sweet-alert
```

### Publish Assets (Optional)

To customize the configuration, views, or JavaScript assets, publish them using the install command:

```bash
php artisan alert:install
```

This publishes three sets of assets:

| Tag | Destination | Description |
|---|---|---|
| `sweetalert-config` | `config/sweetalert.php` | Package configuration |
| `sweetalert-views` | `resources/views/vendor/sweetalert` | Blade views |
| `sweetalert-asset` | `public/vendor/sweetalert` | JavaScript files |

You can also publish assets individually:

```bash
php artisan alert:publish --config
php artisan alert:publish --views
php artisan alert:publish --assets
```

### Upgrading from v7

If this is an existing v7 application, the package can migrate the call sites
that changed shape:

```bash
php artisan alert:upgrade --dry-run
```

It prints the diff and writes nothing. See the [Upgrade Guide](/guide/upgrade-guide)
for what it covers and what it deliberately leaves for you.

### Include the Alert View

Add the `@sweetAlert` Blade directive to your main layout file, right before the closing `</body>` tag:

```blade
<body>
    <!-- Your content -->

    @sweetAlert
</body>
```

This directive handles everything: loading the SweetAlert2 JavaScript, reading flashed alert data from the session, and firing the appropriate `Swal.fire()` calls.

::: tip Backward Compatibility
The `@include('sweetalert::alert')` directive from previous versions still works. However, `@sweetAlert` is the recommended approach going forward — it's cleaner, more expressive, and consistent with Laravel's convention for package-specific Blade directives.
:::

### Register Middleware (Optional)

If you want automatic toast display for Laravel's built-in flash messages (like `success`, `error`, `warning`, `info`), register the `ToSweetAlert` middleware in your HTTP kernel or bootstrap file:

**For Laravel 11+ (bootstrap/app.php):**

```php
use RealRashid\SweetAlert\Http\Middleware\ToSweetAlert;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        ToSweetAlert::class,
    ]);
})
```

**For Laravel 10 (app/Http/Kernel.php):**

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \RealRashid\SweetAlert\Http\Middleware\ToSweetAlert::class,
    ],
];
```

## Quick Start

Once installed, you can start creating alerts immediately. Here are a few examples:

### Simple Alert

The shortest way, and the one most controllers want:

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::success('Operation Complete', 'Your changes have been saved successfully.');

return redirect()->route('posts.index');
```

That shows the alert on the next response. Nothing else to call.

Build it up instead when you want more control over the result:

```php
Alert::make()
    ->title('Operation Complete')
    ->success()
    ->text('Your changes have been saved successfully.')
    ->flash();
```

Both are supported. See [the flash() method](/guide/alerts#the-flash-method)
for when each fits.

### Toast Notification

```php
Alert::toast('Item added to cart!', 'success')
    ->position('bottom-end')
    ->autoClose(3000)
    ->flash();
```

### Confirm Dialog

```php
Alert::confirmDelete('Delete this post?', 'This action cannot be undone.');
```

### Input Dialog

```php
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('Enter your email', InputType::Email)
    ->inputPlaceholder('user@example.com')
    ->confirmButton('Subscribe')
    ->flash();
```

## How It Works

The package uses Laravel's session storage to pass alert configurations from your backend to the frontend. When you call `flash()` on any builder, the configuration is serialized to JSON and stored in the session. On the next page load, the Blade view reads this data from the session and passes it directly to `Swal.fire()`.

This server-side rendering approach means:

- **No AJAX calls** are needed to display alerts
- **No JavaScript knowledge** is required to create complex alert configurations
- **Full Laravel integration** — alerts work seamlessly with redirects, form submissions, and middleware
- **Zero client-side setup** — the Blade include handles everything
