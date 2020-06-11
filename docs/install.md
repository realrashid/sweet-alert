# Installation

To get started with SweetAlert2, use Composer to add the package to your project's dependencies:

```bash
composer require realrashid/sweet-alert
```

!> The Below Step is Optional in Laravel 5.5 or above!

After installing the sweet-alert package, register the service provider

```php
RealRashid\SweetAlert\SweetAlertServiceProvider::class,
```

in your `config/app.php` configuration file:

```php
'providers' => [
    /*
    * Package Service Providers...
    */
    RealRashid\SweetAlert\SweetAlertServiceProvider::class,
],
```

Also, add the `Alert` facade to the `aliases` array in your `app` configuration file:

```php
'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
```
