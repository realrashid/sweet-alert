# Configuration

After publishing the configuration file, you'll find it at `config/sweetalert.php`. Every option can also be overridden using environment variables, making it easy to customize behavior per-environment without modifying the config file directly.

## Full Configuration Reference

```php
return [

    /*
    |--------------------------------------------------------------------------
    | Default Alert Type
    |--------------------------------------------------------------------------
    |
    | This value determines the default alert type when none is specified.
    |
    */

    'default' => env('SWEET_ALERT_DEFAULT', 'alert'),

    /*
    |--------------------------------------------------------------------------
    | JavaScript Loading Control
    |--------------------------------------------------------------------------
    |
    | always_load_js: Always include the SweetAlert2 JS on every page.
    | never_load_js: Never include the JS (handle it yourself with Mix/Vite).
    |
    */

    'always_load_js' => env('SWEET_ALERT_ALWAYS_LOAD_JS', false),
    'never_load_js' => env('SWEET_ALERT_NEVER_LOAD_JS', false),

    /*
    |--------------------------------------------------------------------------
    | Default Modal Settings
    |--------------------------------------------------------------------------
    */

    'timer' => env('SWEET_ALERT_TIMER', 5000),
    'width' => env('SWEET_ALERT_WIDTH', '32rem'),
    'padding' => env('SWEET_ALERT_PADDING', '1.25rem'),
    'background' => env('SWEET_ALERT_BACKGROUND', '#fff'),
    'show_confirm_button' => env('SWEET_ALERT_SHOW_CONFIRM_BUTTON', true),
    'show_close_button' => env('SWEET_ALERT_SHOW_CLOSE_BUTTON', false),

    /*
    |--------------------------------------------------------------------------
    | Button Text
    |--------------------------------------------------------------------------
    */

    'button_text' => [
        'confirm' => env('SWEET_ALERT_CONFIRM_BUTTON_TEXT', 'OK'),
        'cancel' => env('SWEET_ALERT_CANCEL_BUTTON_TEXT', 'Cancel'),
        'deny' => env('SWEET_ALERT_DENY_BUTTON_TEXT', 'Deny'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Toast Settings
    |--------------------------------------------------------------------------
    */

    'toast' => [
        'position' => env('SWEET_ALERT_TOAST_POSITION', 'top-end'),
        'auto_close' => env('SWEET_ALERT_TOAST_AUTO_CLOSE', 5000),
        'show_close_button' => env('SWEET_ALERT_TOAST_CLOSE_BUTTON', true),
        'show_confirm_button' => false,
        'timer_progress_bar' => env('SWEET_ALERT_TOAST_PROGRESS_BAR', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Middleware Settings
    |--------------------------------------------------------------------------
    */

    'middleware' => [
        'auto_display_errors' => env('SWEET_ALERT_AUTO_DISPLAY_ERRORS', true),
        'toast_position' => env('SWEET_ALERT_MIDDLEWARE_TOAST_POSITION', 'top-end'),
        'auto_close' => env('SWEET_ALERT_MIDDLEWARE_AUTO_CLOSE', false),
        'timer' => env('SWEET_ALERT_MIDDLEWARE_TIMER', 6000),
        'toast_close_button' => env('SWEET_ALERT_MIDDLEWARE_TOAST_CLOSE_BUTTON', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirm Delete Settings
    |--------------------------------------------------------------------------
    */

    'confirm_delete' => [
        'icon' => 'warning',
        'confirm_button_text' => 'Yes, delete it!',
        'confirm_button_color' => '#d33',
        'cancel_button_text' => 'Cancel',
        'show_close_button' => false,
        'show_cancel_button' => true,
        'show_loader_on_confirm' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Animation Settings
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Confirm Dialog Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration for general confirm dialogs (not delete).
    |
    */

    'confirm' => [
        'icon' => 'question',
        'confirm_button_text' => 'Yes',
        'confirm_button_color' => '#3085d6',
        'cancel_button_text' => 'Cancel',
        'show_cancel_button' => true,
        'show_close_button' => false,
    ],

    'animation' => [
        'enabled' => env('SWEET_ALERT_ANIMATION_ENABLE', false),
        'animatecss' => env('SWEET_ALERT_ANIMATECSS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    */

    'theme' => env('SWEET_ALERT_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | JavaScript Source Settings
    |--------------------------------------------------------------------------
    |
    | source: 'cdn' (default), 'npm' (manage Swal yourself via npm),
    |         'manual' (full control, nothing injected).
    | version: pinned SweetAlert2 version used when source='cdn'.
    | cdn_provider: 'jsdelivr' (default), 'unpkg', 'cdnjs', 'custom'.
    | custom_cdn_js: only used when cdn_provider='custom'.
    |
    */

    'js' => [
        'source'        => env('SWEET_ALERT_JS_SOURCE', 'cdn'),
        'version'       => env('SWEET_ALERT_VERSION', '11'),
        'cdn_provider'  => env('SWEET_ALERT_CDN_PROVIDER', 'jsdelivr'),
        'custom_cdn_js' => env('SWEET_ALERT_CDN_JS'),
    ],

];
```

## JavaScript Loading

The package gives you fine-grained control over when the SweetAlert2 JavaScript is loaded. This is managed by two configuration options that work together:

| `always_load_js` | `never_load_js` | Result |
|---|---|---|
| `true` | `true` | JS will **not** be loaded |
| `true` | `false` | JS loaded on **every** page |
| `false` | `false` | JS loaded **only** when an alert is flashed |

The default behavior (`false` / `false`) is the most efficient: the SweetAlert2 JavaScript is only included when there is actually an alert to display. This avoids unnecessary network requests on pages that don't need the library.

If you're already bundling SweetAlert2 through Vite or Mix, set `never_load_js` to `true` and include the library in your own build pipeline:

```env
SWEET_ALERT_NEVER_LOAD_JS=true
```

## JavaScript Source (`js` block)

The `js` block controls how and where SweetAlert2's JavaScript is loaded.

```php
'js' => [
    'source'        => env('SWEET_ALERT_JS_SOURCE', 'cdn'),   // 'cdn', 'npm', 'manual'
    'version'       => env('SWEET_ALERT_VERSION', '11'),       // SweetAlert2 version
    'cdn_provider'  => env('SWEET_ALERT_CDN_PROVIDER', 'jsdelivr'), // 'jsdelivr', 'unpkg', 'cdnjs', 'custom'
    'custom_cdn_js' => env('SWEET_ALERT_CDN_JS'),              // Only when cdn_provider='custom'
],
```

### `source` options

| Value | Description |
|---|---|
| `cdn` (default) | Load SweetAlert2 from a public CDN automatically |
| `npm` | You manage SweetAlert2 via npm/Vite — set `never_load_js=true` |
| `manual` | Nothing is injected; full control over loading |

### CDN Providers

| Value | URL pattern |
|---|---|
| `jsdelivr` (default) | `https://cdn.jsdelivr.net/npm/sweetalert2@{version}/dist/sweetalert2.all.min.js` |
| `unpkg` | `https://unpkg.com/sweetalert2@{version}/dist/sweetalert2.all.min.js` |
| `cdnjs` | Resolves from cdnjs.cloudflare.com |
| `custom` | Uses `custom_cdn_js` URL verbatim |

### Pin a specific version

```env
SWEET_ALERT_VERSION=11.14.1
```

### Use a custom CDN URL

```env
SWEET_ALERT_CDN_PROVIDER=custom
SWEET_ALERT_CDN_JS=https://your-cdn.example.com/sweetalert2.min.js
```

## Confirm Dialog Defaults

The `confirm` block sets defaults for general confirm dialogs used with `Alert::confirm()`. This is separate from `confirm_delete`, which is for destructive-action dialogs.

```php
'confirm' => [
    'icon' => 'question',
    'confirm_button_text' => 'Yes',
    'confirm_button_color' => '#3085d6',
    'cancel_button_text' => 'Cancel',
    'show_cancel_button' => true,
    'show_close_button' => false,
],
```

Override per-call using the builder's fluent methods:

```php
Alert::confirm('Are you sure?', '/proceed')
    ->confirmButton('Proceed', '#28a745')
    ->cancelButton('Cancel')
    ->flash();
```

## Themes

The default theme is `light`. Set `theme` to any name from the `@sweetalert2/theme-*` packages (omit the `@sweetalert2/theme-` prefix):

```env
SWEET_ALERT_THEME=dark
```

Available themes include: `light` (default), `dark`, `borderless`, `minimal`, `material-ui`, `bootstrap-4`, `wordpress-admin`, and more. Visit the [SweetAlert2 themes repository](https://github.com/sweetalert2/themes) for the full list.

You can also override the theme per-alert without touching config:

```php
Alert::title('Night Mode')
    ->theme('dark')
    ->flash();
```

## Animation with Animate.css

To enable Animate.css animations for your alerts, set the `animation.enabled` option to `true`:

```env
SWEET_ALERT_ANIMATION_ENABLE=true
```

This automatically loads the Animate.css stylesheet. You can also provide a custom Animate.css CDN URL:

```env
SWEET_ALERT_ANIMATECSS=https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css
```

Once enabled, you can use the `animation()` method on any builder to specify show/hide animation classes:

```php
Alert::title('Welcome!')
    ->success()
    ->animation('animate__bounceIn', 'animate__bounceOut')
    ->flash();
```

## Environment Variables Summary

| Variable | Default | Description |
|---|---|---|
| `SWEET_ALERT_DEFAULT` | `alert` | Default alert type |
| `SWEET_ALERT_CDN` | `null` | CDN URL for SweetAlert2 JS |
| `SWEET_ALERT_ALWAYS_LOAD_JS` | `false` | Always load the JS on every page |
| `SWEET_ALERT_NEVER_LOAD_JS` | `false` | Never load the JS (use your own) |
| `SWEET_ALERT_TIMER` | `5000` | Default auto-close timer (ms) |
| `SWEET_ALERT_WIDTH` | `32rem` | Default modal width |
| `SWEET_ALERT_PADDING` | `1.25rem` | Default modal padding |
| `SWEET_ALERT_BACKGROUND` | `#fff` | Default background color |
| `SWEET_ALERT_SHOW_CONFIRM_BUTTON` | `true` | Show confirm button by default |
| `SWEET_ALERT_SHOW_CLOSE_BUTTON` | `false` | Show close button by default |
| `SWEET_ALERT_CONFIRM_BUTTON_TEXT` | `OK` | Confirm button text |
| `SWEET_ALERT_CANCEL_BUTTON_TEXT` | `Cancel` | Cancel button text |
| `SWEET_ALERT_DENY_BUTTON_TEXT` | `Deny` | Deny button text |
| `SWEET_ALERT_TOAST_POSITION` | `top-end` | Default toast position |
| `SWEET_ALERT_TOAST_AUTO_CLOSE` | `5000` | Default toast auto-close (ms) |
| `SWEET_ALERT_TOAST_CLOSE_BUTTON` | `true` | Show close button on toasts |
| `SWEET_ALERT_TOAST_PROGRESS_BAR` | `true` | Show timer progress bar on toasts |
| `SWEET_ALERT_AUTO_DISPLAY_ERRORS` | `true` | Auto-display validation errors |
| `SWEET_ALERT_ANIMATION_ENABLE` | `false` | Enable Animate.css |
| `SWEET_ALERT_ANIMATECSS` | `null` | Custom Animate.css CDN URL |
| `SWEET_ALERT_THEME` | `light` | SweetAlert2 theme name |
| `SWEET_ALERT_JS_SOURCE` | `cdn` | JS source: `cdn`, `npm`, `manual` |
| `SWEET_ALERT_VERSION` | `11` | SweetAlert2 CDN version |
| `SWEET_ALERT_CDN_PROVIDER` | `jsdelivr` | CDN provider: `jsdelivr`, `unpkg`, `cdnjs`, `custom` |
| `SWEET_ALERT_CDN_JS` | `null` | Custom CDN JS URL (when `cdn_provider=custom`) |
