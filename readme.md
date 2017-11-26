<p align="center">
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/license.svg" alt="License"></a>
</p>

# Introduction

A BEAUTIFUL, RESPONSIVE, CUSTOMIZABLE, ACCESSIBLE (WAI-ARIA) REPLACEMENT FOR JAVASCRIPT'S POPUP BOXES

ZERO DEPENDENCIES

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/intro.PNG" alt="">
</p>

# Install

To get started with SweetAlert, use Composer to add the package to your project's dependencies:

```
composer require realrashid/sweet-alert
```

## Configuration

After installing the SweetAlert library, register the `RealRashid\SweetAlert\SweetAlertServiceProvider::class` in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    RealRashid\SweetAlert\SweetAlertServiceProvider::class,
],
```

Also, add the `SweetAlert` facade to the `aliases` array in your `app` configuration file:

```php
'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
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
    <script>
        swal({
            title: "{!! alert()->message() !!}"
        });
    </script>
@endif
```

## Options

You can pass additional options to the `flash` method, which are then easily accessible within your view.

```php
alert()->flash(title, modal type, options[]);
```

# Alert Types

A basic message!

in controller

```php
alert()->flash('Welcome to Laravel SweetAlert By Rashid Ali!');
```

in view

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}"
        });
    </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/basic-msg.PNG" alt="">
</p>


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
          text: "{!! alert()->option('text') !!}",
          type: "{!! alert()->type() !!}"
        });
    </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/intro.PNG" alt="">
</p>

A message with auto close timer!

in controller

```php
alert()->flash('Welcome back!', 'success', [
        'text' => 'Welcome to Laravel SweetAlert By Rashid Ali!',
        'timer' => 3000
    ]);
```

in view

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}",
            text: "{!! alert()->option('text') !!}",
            type: "{!! alert()->type() !!}",
            @if(alert()->option('timer'))
                timer: {!! alert()->option('timer') !!},
                showConfirmButton: false,
            @endif
        });
    </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/auto-close.PNG" alt="">
</p>

Custom HTML description and buttons!

in controller

```php
alert()->flash('<i>HTML</i> <u>example</u>', 'info',[
        'html' => "You can use <b>bold text</b>, \
                  <a href='https://github.com/realrashid/'>links</a> \
                  and other HTML tags",
        'showCloseButton' => true
    ]);
```

in view

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}",
            type: "{!! alert()->type() !!}",
            html: "{!! alert()->option('html') !!}",
            showCloseButton: "{!! alert()->option('showCloseButton') !!}"
        });
    </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/html-elements.PNG" alt="">
</p>

A warning message, with a function attached to the "Confirm"-buttons!

in controller

```php
alert()->flash('Are you sure?', 'warning',[
        'text' => 'You won\'t be able to revert this!',
        'showCancelButton' => true,
        'confirmButtonColor' => '#3085d6',
        'cancelButtonColor' => '#d33',
        'confirmButtonText' => 'Yes, delete it!',
        // if user clicked Yes, delete it!
        // then this will run
        'deleted' => 'Deleted!',
        'msg' => 'Your file has been deleted.',
        'type' => 'success'
    ]);
```

in view

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}",
            type: "{!! alert()->type() !!}",
            text: "{!! alert()->option('text') !!}",
            showCancelButton: "{!! alert()->option('showCancelButton') !!}",
            cancelButtonColor: "{!! alert()->option('cancelButtonColor') !!}",
            confirmButtonColor: "{!! alert()->option('confirmButtonColor') !!}",
            confirmButtonText: "{!! alert()->option('confirmButtonText') !!}",
        }).then(function () {
            swal(
                '{!! alert()->option('deleted') !!}',
                '{!! alert()->option('msg') !!}',
                '{!! alert()->option('type') !!}'
            )
        });
    </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/delete.PNG" alt="">
</p>

After clicked Yes, delete it!

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/deleted.PNG" alt="">
</p>


A message with a custom image and CSS animation disabled!

in controller

```php
alert()->flash('Sweet!', 'success',[
        'text' => 'Modal with a custom image.',
        'imageUrl' => 'https://unsplash.it/400/200',
        'imageWidth' => 400,
        'imageHeight' => 200,
        'animation' => false
    ]);
```

in view

```javascript
@if (alert()->ready())
  <script>
      swal({
          title: "{!! alert()->message() !!}",
          text: "{!! alert()->option('text') !!}",
          imageUrl: "{!! alert()->option('imageUrl') !!}",
          imageWidth: "{!! alert()->option('imageWidth') !!}",
          imageHeight: "{!! alert()->option('imageHeight') !!}",
          animation: "{!! alert()->option('animation') !!}"
      });
  </script>
@endif
```

<p align="center">
    <img src="https://github.com/realrashid/sweet-alert/blob/master/imgs/modal-with-image.PNG" alt="">
</p>

> The above examples uses SweetAlert, but the flexibily of alert means you can easily use it with any JavaScript alert solution.

## Modal Types

`success`                                                                          | `error`                                                                          | `warning`                                                                          | `info`                                                                          | `question`
---------------------------------------------------------------------------------- | -------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------- | ------------------------------------------------------------------------------- | -----------------------------------------------------------------------------------
![](https://github.com/realrashid/sweet-alert/blob/master/imgs/types/success.png) | ![](https://github.com/realrashid/sweet-alert/blob/master/imgs/types/error.png) | ![](https://github.com/realrashid/sweet-alert/blob/master/imgs/types/warning.png) | ![](https://github.com/realrashid/sweet-alert/blob/master/imgs/types/info.png) | ![](https://github.com/realrashid/sweet-alert/blob/master/imgs/types/question.png)

# Issues and Contribution

Just submit an issue or pull request through GitHub. Thanks!

## Connect with Me

- Website: http://realrashid.com
- Email: realrashid05@gmail.com
- Twitter: http://twitter.com/rashidali05
- Facebook: https://www.facebook.com/rashidali05
- GitHub: https://github.com/realrashid




# License

SweetAlert is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

<p align="center"> <b>Made :heart: with Pakistan<b> </p>


