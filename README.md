# Laravel SweetAlert

A beautiful, responsive, customizable, accessible (WAI-ARIA) replacement for JavaScript popup boxes for Laravel — rebuilt from scratch with a fluent, Laravel-native API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/realrashid/sweet-alert.svg?style=flat-square)](https://packagist.org/packages/realrashid/sweet-alert)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/realrashid/sweet-alert/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/realrashid/sweet-alert/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/realrashid/sweet-alert.svg?style=flat-square)](https://packagist.org/packages/realrashid/sweet-alert)

## Features

- **Fluent Builder API** — Chain methods naturally like Laravel Eloquent
- **Full SweetAlert2 Coverage** — Inputs, deny buttons, pre-confirm, animations, themes, and more
- **Three Purpose-Built Builders** — `AlertBuilder`, `ToastBuilder`, and `InputBuilder`
- **PHP 8.3+ Enums** — Type-safe `AlertType`, `InputType`, and `Position` enums with IDE autocompletion
- **Livewire v4 Support** — First-class `SweetAlertTrait` for dispatching alerts as browser events
- **Inertia.js Support** — Share alerts with Vue/React via the included middleware
- **Guarded Actions** — `data-confirm` asks before any link or form goes through, no JavaScript to write
- **Laravel Boost Ready** — ships a guideline and skill so AI assistants write v8 code, not v7
- **Guided Upgrade** — `php artisan alert:upgrade` migrates a v7 codebase for you, with `--dry-run`

## Quick Start

```bash
composer require realrashid/sweet-alert
php artisan alert:install
```

Upgrading from v7? See what would change before anything is written:

```bash
php artisan alert:upgrade --dry-run
```

Add the Blade directive to your layout:

```blade
<head>
    ...
</head>
<body>
    ...
    @sweetAlert
</body>
```

Fire an alert from any controller:

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::title('Welcome!')
    ->success()
    ->text('Your account has been created.')
    ->flash();

return redirect()->route('dashboard');
```

Or use the global helper:

```php
alert('Success!', 'Your data has been saved.', 'success');

return redirect()->back();
```

## Documentation

Ready to explore all the features and options? Dive into our comprehensive documentation:

- [Getting Started](https://realrashid.github.io/sweet-alert/guide/getting-started)
- [Alerts](https://realrashid.github.io/sweet-alert/guide/alerts)
- [Toasts](https://realrashid.github.io/sweet-alert/guide/toasts)
- [Livewire Integration](https://realrashid.github.io/sweet-alert/guide/livewire)
- [API Reference](https://realrashid.github.io/sweet-alert/api/alert-builder)

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover a security vulnerability, please help us maintain the security of this project by responsibly disclosing it to us. To report a security vulnerability, please send an email to [realrashid05@gmail.com](mailto:realrashid05@gmail.com). We'll address the issue as promptly as possible.

## Credits

- [Rashid Ali](https://github.com/realrashid)
- [All Contributors](../../contributors)

## Support My Work

If you find Laravel SweetAlert helpful and would like to support my work, you can buy me a coffee. Your support will help keep this project alive and thriving. It's a small token of appreciation that goes a long way.

[![Buy me a coffee](https://cdn.buymeacoffee.com/buttons/default-orange.png)](https://www.buymeacoffee.com/realrashid)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<p align="center"> Made with ❤️ from Pakistan </p>
