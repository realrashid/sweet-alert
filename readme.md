# Install

Using Composer

```
composer require rashidali05/sweet-alert
```

Add the service provider to `config/app.php`

```php
RashidAli05\SweetAlert\SweetAlertServiceProvider::class,
```

Optionally include the Facade in `config/app.php` if you'd like.

```php
'Alert' => RashidAli05\SweetAlert\Facades\Alert::class,
```

> Note, there is a alert() function available, so unless you really want to use the Facade, there's no need to include it.

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
alert()->flash('Welcome back!', 'success', [
    'timer' => 3000,
    'text' => 'It\'s really great to see you again',
]);
```

Then, in your view.

```javascript
@if (alert()->ready())
    <script>
        swal({
            title: "{!! alert()->message() !!}",
            text: "{!! alert()->option('text') !!}",
            type: "{{ alert()->type() }}",
            @if (alert()->option('timer'))
                timer: {{ alert()->option('timer') }},
                showConfirmButton: false
            @endif
        });
    </script>
@endif
```

> The above example uses SweetAlert, but the flexibily of alert means you can easily use it with any JavaScript alert solution.

# Issues and contribution

Just submit an issue or pull request through GitHub. Thanks!
