<?php

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
    | CDN Link
    |--------------------------------------------------------------------------
    |
    | By default, SweetAlert2 uses its local sweetalert2.min.js file.
    | However, you can use a CDN if you prefer.
    |
    */

    'cdn' => env('SWEET_ALERT_CDN'),

    /*
    |--------------------------------------------------------------------------
    | JavaScript Loading Control
    |--------------------------------------------------------------------------
    |
    | always_load_js: Always include the SweetAlert2 JS on every page.
    | never_load_js: Never include the JS (handle it yourself with Mix/Vite).
    |
    | alwaysLoadJs = true  & neverLoadJs = true  => JS will NOT be loaded
    | alwaysLoadJs = true  & neverLoadJs = false => JS will be loaded
    | alwaysLoadJs = false & neverLoadJs = false => JS loaded only when alert is set
    |
    */

    'always_load_js' => env('SWEET_ALERT_ALWAYS_LOAD_JS', false),
    'never_load_js' => env('SWEET_ALERT_NEVER_LOAD_JS', false),

    /*
    |--------------------------------------------------------------------------
    | Default Modal Settings
    |--------------------------------------------------------------------------
    |
    | These values are used as defaults for all modal windows.
    | Override them per-alert using the fluent API methods.
    |
    */

    'timer' => env('SWEET_ALERT_TIMER', 5000),

    /*
    | Width, padding and background are sent to SweetAlert2 only when you set
    | them. SweetAlert2 turns each one into an inline style on the popup, and an
    | inline style beats every stylesheet — so a hard-coded background here
    | would override the theme you picked and every alert would stay white.
    | Leave them null and the active theme's own CSS decides.
    */
    'width' => env('SWEET_ALERT_WIDTH'),
    'padding' => env('SWEET_ALERT_PADDING'),
    'background' => env('SWEET_ALERT_BACKGROUND'),
    'show_confirm_button' => env('SWEET_ALERT_SHOW_CONFIRM_BUTTON', true),
    'show_close_button' => env('SWEET_ALERT_SHOW_CLOSE_BUTTON', false),

    /*
    |--------------------------------------------------------------------------
    | Button Text
    |--------------------------------------------------------------------------
    |
    | Default text for the modal buttons. Translations will be handled
    | by Laravel at runtime.
    |
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
    |
    | Default configuration for toast notifications.
    |
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
    |
    | Configuration for the ToSweetAlert middleware behavior.
    |
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
    | Confirm Dialog Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration for general confirm dialogs, and for the guarded
    | links and forms marked with data-confirm.
    |
    | `auto` emits the small listener that makes data-confirm and
    | data-confirm-delete work on every page. Turn it off if you would rather
    | wire the confirmation up yourself.
    |
    */

    'confirm' => [
        'auto' => env('SWEET_ALERT_AUTO_CONFIRM', true),
        'icon' => 'question',
        'title' => 'Are you sure?',
        'text' => '',
        'confirm_button_text' => 'Yes',
        'confirm_button_color' => '#3085d6',
        'cancel_button_text' => 'Cancel',
        'show_cancel_button' => true,
        'show_close_button' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Confirm Delete Settings
    |--------------------------------------------------------------------------
    |
    | Default configuration for the confirmDelete() dialog.
    |
    */

    'confirm_delete' => [
        'icon' => 'warning',
        'title' => 'Are you sure?',
        'text' => 'This cannot be undone.',
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
    |
    | Custom animation using Animate.css.
    |
    */

    'animation' => [
        'enabled' => env('SWEET_ALERT_ANIMATION_ENABLE', false),
        'animatecss' => env('SWEET_ALERT_ANIMATECSS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | SweetAlert2 theme. Set to 'default' for the standard theme,
    | or use any theme from @sweetalert2/theme-* package.
    |
    */

    'theme' => env('SWEET_ALERT_THEME', 'light'),

    /*
    |--------------------------------------------------------------------------
    | Presets
    |--------------------------------------------------------------------------
    |
    | Named presets that can be applied with ->preset('name').
    | Each preset is an array of SweetAlert2 config options that
    | are merged onto the current builder configuration.
    |
    | Example:
    |   Alert::preset('danger')->title('Oops!')->flash();
    |
    */

    'presets' => [
        // 'danger' => [
        //     'icon' => 'error',
        //     'confirmButtonColor' => '#d33',
        //     'showCancelButton' => true,
        // ],
        // 'success-toast' => [
        //     'toast' => true,
        //     'position' => 'top-end',
        //     'icon' => 'success',
        //     'timer' => 3000,
        //     'timerProgressBar' => true,
        //     'showConfirmButton' => false,
        // ],
    ],

    /*
    |--------------------------------------------------------------------------
    | JavaScript Source Settings
    |--------------------------------------------------------------------------
    |
    | source: 'cdn' (default), 'npm' (set never_load_js=true and manage Swal
    |          yourself via npm), 'manual' (full control, nothing injected).
    | version: pinned SweetAlert2 version used when source='cdn'.
    | cdn_provider: 'jsdelivr' (default), 'unpkg', 'cdnjs', 'custom'.
    | custom_cdn_js: only used when cdn_provider='custom'.
    |
    */

    'js' => [
        'source' => env('SWEET_ALERT_JS_SOURCE', 'cdn'),
        'version' => env('SWEET_ALERT_VERSION', '11'),
        'cdn_provider' => env('SWEET_ALERT_CDN_PROVIDER', 'jsdelivr'),
        'custom_cdn_js' => env('SWEET_ALERT_CDN_JS'),
    ],

];
