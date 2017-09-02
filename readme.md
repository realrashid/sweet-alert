# Introduction

A BEAUTIFUL, RESPONSIVE, CUSTOMIZABLE, ACCESSIBLE (WAI-ARIA) REPLACEMENT FOR JAVASCRIPT'S POPUP BOXES

ZERO DEPENDENCIES

[See SweetAlert in action!](https://limonte.github.io/sweetalert2/)

![](https://github.com/rashidali05/sweet-alert/blob/master/imgs/intro.PNG)

# Install

To get started with SweetAlert, use Composer to add the package to your project's dependencies:

```
composer require rashidali05/sweet-alert
```

## Configuration

After installing the SweetAlert library, register the `RashidAli05\SweetAlert\SweetAlertServiceProvider::class` in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    RashidAli05\SweetAlert\SweetAlertServiceProvider::class,
],
```

Also, add the `SweetAlert` facade to the `aliases` array in your `app` configuration file:

```php
'Alert' => RashidAli05\SweetAlert\Facades\Alert::class,
```

> Note, there is a alert() function available, so unless you really want to use the Facade, there's no need to include it.

### Import SweetAlert Libraries

in your views

```css
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.css">
```

```javascript
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.9/sweetalert2.min.js"></script>
```

# Usage

## Basic

From your application, call the `flash` method with a message and type.

```php
alert()->flash('Welcome back!', 'success');
```

Within a view, you can now check if a flash message exists and output it.

```php
@if (alert()->ready())
    <div class="alert-box {{ alert()->type() }}">
        {{ alert()->message() }}
    </div>
@endif
```

> alert is front-end framework agnostic, so you're free to easily implement the output however you choose.

## Options

You can pass additional options to the `flash` method, which are then easily accessible within your view.

```php
alert()->flash(title, alert type, [
    options => array
]);
```

# Alert Types

A success message!

in controller

```php
alert()->flash('Welcome back!', 'success', [
        'text' => 'Welcome to Laravel SweetAlert By Rashid Ali!'
    ]);
```

in view

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}",
            type: "{!! alert()->type() !!}",
            text: "{!! alert()->option('text') !!}"
        });
    </script>
@endif
```

[See SweetAlert2 in action!](https://limonte.github.io/sweetalert2/)

![](https://raw.github.com/limonte/sweetalert2/master/assets/sweetalert2.gif)

> The above example uses SweetAlert, but the flexibily of alert means you can easily use it with any JavaScript alert solution.

# ScreenShots

# Issues and contribution

Just submit an issue or pull request through GitHub. Thanks!

# License

SweetAlert is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
