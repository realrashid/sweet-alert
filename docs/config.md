# Configuration

> Optional in Laravel 5.5 or above

After installing the sweet-alert package, register the

```php
RealRashid\SweetAlert\SweetAlertServiceProvider::class
```
in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    RealRashid\SweetAlert\SweetAlertServiceProvider::class,
],
```

Also, add the `Alert` facade to the `aliases` array in your `app` configuration file:

```php
'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
```

# Include SweetAlert 2 View

in your master layout

```php
@include('sweetalert::alert')
```

and run the below command to publish the sweetalert.all.js in your public assets.

```bash
php artisan vendor:publish --provider="RealRashid\SweetAlert\SweetAlertServiceProvider"
```

> note: the javascript library of sweetalert.all.js is already loaded in above included view

