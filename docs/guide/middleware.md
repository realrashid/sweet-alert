# Middleware

The `ToSweetAlert` middleware automatically converts Laravel's built-in flash messages into SweetAlert2 alerts. This is a zero-configuration way to get beautiful alerts from standard Laravel patterns like `redirect()->with('success', 'Done!')`.

The plain keys (`success`, `error`, …) produce a **modal**. The `toast_*` keys
produce a toast.

## Installation

Register the middleware in your application:

**Laravel 11+ (bootstrap/app.php):**

```php
use RealRashid\SweetAlert\Http\Middleware\ToSweetAlert;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        ToSweetAlert::class,
    ]);
})
```

**Laravel 10 (app/Http/Kernel.php):**

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \RealRashid\SweetAlert\Http\Middleware\ToSweetAlert::class,
    ],
];
```

## How It Works

Once registered, the middleware intercepts the following session flash keys on every request:

| Session Key | Alert Type |
|---|---|
| `success` | Success modal |
| `error` | Error modal |
| `warning` | Warning modal |
| `info` | Info modal |
| `question` | Question modal |

Additionally, it handles the following toast-specific keys for backward compatibility:

| Session Key | Alert Type |
|---|---|
| `toast_success` | Success toast |
| `toast_error` | Error toast |
| `toast_warning` | Warning toast |
| `toast_info` | Info toast |
| `toast_question` | Question toast |

## Usage

Use Laravel's standard `with()` method on redirects:

```php
// These automatically become toast notifications
return redirect()->route('posts.index')->with('success', 'Post created!');
return redirect()->back()->with('error', 'Something went wrong.');
return redirect()->route('dashboard')->with('warning', 'Low disk space.');
return redirect()->route('home')->with('info', 'New version available.');
```

The middleware converts these into toast notifications using the toast middleware defaults from `config/sweetalert.php`:

```php
'middleware' => [
    'auto_display_errors' => true,
    'toast_position' => 'top-end',
    'auto_close' => false,
    'timer' => 6000,
    'toast_close_button' => true,
],
```

## Auto-Display Validation Errors

By default, the middleware also converts Laravel validation errors into error toasts. When a form validation fails and the user is redirected back, the error messages are automatically displayed as a SweetAlert2 error toast:

```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
    ]);

    // If validation fails, user is redirected back
    // and the middleware shows the errors as a toast
}
```

### Disable Auto-Display Errors

If you prefer to handle validation errors yourself (e.g., displaying them inline in the form), disable this feature in the configuration:

```env
SWEET_ALERT_AUTO_DISPLAY_ERRORS=false
```

Or in `config/sweetalert.php`:

```php
'middleware' => [
    'auto_display_errors' => false,
],
```

## Middleware Configuration

| Option | Default | Description |
|---|---|---|
| `auto_display_errors` | `true` | Automatically show validation errors as toasts |
| `toast_position` | `'top-end'` | Default toast position for middleware toasts |
| `auto_close` | `false` | Whether middleware toasts auto-close |
| `timer` | `6000` | Auto-close timer in milliseconds |
| `toast_close_button` | `true` | Show close button on middleware toasts |

## Using Flash Messages Without Middleware

If you prefer not to use the middleware, you can still use Laravel's `with()` method and manually create alerts:

```php
// Instead of relying on the middleware
return redirect()->route('posts.index')->with('success', 'Post created!');

// Manually create the alert
Alert::toast('Post created!', 'success')->flash();
return redirect()->route('posts.index');
```

Both approaches work — the middleware just automates the conversion for convenience.
