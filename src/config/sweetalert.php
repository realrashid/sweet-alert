<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SweetAlert2 Local .JS File
    |--------------------------------------------------------------------------
    | By default SweetAlert2 use its local sweetalert.all.js
    | file.
    |
    |
    */

    'local' => env('SWEET_ALERT_LOCAL', true),


    /*
    |--------------------------------------------------------------------------
    | CDN LINK
    |--------------------------------------------------------------------------
    | By default SweetAlert2 use its local sweetalert.all.js
    | file.
    | However, you can use its cdn if you want.
    |
    */

    'cdn' => env('SWEET_ALERT_CDN'),

    /*
    |--------------------------------------------------------------------------
    | AutoClose Timer
    |--------------------------------------------------------------------------
    |
    | This is for the all Modal windows.
    | For specific modal just use the autoClose() helper method.
    |
    */

    'timer' => env('SWEET_ALERT_TIMER', 5000),

    /*
    |--------------------------------------------------------------------------
    | Width
    |--------------------------------------------------------------------------
    |
    | Modal window width, including paddings (box-sizing: border-box).
    | Can be in px or %.
    | The default width is 32rem.
    | This is for the all Modal windows.
    | for particular modal just use the width() helper method.
    */

    'width' => env('SWEET_ALERT_WIDTH', '32rem'),

    /*
    |--------------------------------------------------------------------------
    | Height Auto
    |--------------------------------------------------------------------------
    | By default, SweetAlert2 sets html's and body's CSS height to auto !important.
    | If this behavior isn't compatible with your project's layout,
    | set heightAuto to false.
    |
    */

    'height_auto' => env('SWEET_ALERT_HEIGHT_AUTO', true),

    /*
    |--------------------------------------------------------------------------
    | Padding
    |--------------------------------------------------------------------------
    |
    | Modal window padding.
    | Can be in px or %.
    | The default padding is 1.25rem.
    | This is for the all Modal windows.
    | for particular modal just use the padding() helper method.
    */

    'padding' => env('SWEET_ALERT_PADDING', '1.25rem'),

    /*
    |--------------------------------------------------------------------------
    | Animation
    |--------------------------------------------------------------------------
    | If set to false, modal CSS animation will be disabled.
    | For specific modal just use the animation() helper method.
    |
    */

    'animation' => env('SWEET_ALERT_ANIMATION', true),

    /*
    |--------------------------------------------------------------------------
    | ShowConfirmButton
    |--------------------------------------------------------------------------
    | If set to false, a "Confirm"-button will not be shown.
    | It can be useful when you're using custom HTML description.
    | This is for the all Modal windows.
    | For specific modal just use the showConfirmButton() helper method.
    |
    */

    'show_confirm_button' => env('SWEET_ALERT_CONFIRM_BUTTON', true),

    /*
    |--------------------------------------------------------------------------
    | ShowCloseButton
    |--------------------------------------------------------------------------
    | If set to true, a "Close"-button will be shown,
    | which the user can click on to dismiss the modal.
    | This is for the all Modal windows.
    | For specific modal just use the showCloseButton() helper method.
    |
    */

    'show_close_button' => env('SWEET_ALERT_CLOSE_BUTTON', false),

    /*
    |--------------------------------------------------------------------------
    | Toast position
    |--------------------------------------------------------------------------
    | Modal window or toast position, can be 'top',
    | 'top-start', 'top-end', 'center', 'center-start',
    | 'center-end', 'bottom', 'bottom-start', or 'bottom-end'.
    | For specific modal just use the position() helper method.
    |
    */

    'toast_position' => env('SWEET_ALERT_TOAST_POSITION', 'top-end'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    | Modal window or toast, config for the Middleware
    |
    */

    'middleware' => [

        'toast_position' => env('SWEET_ALERT_MIDDLEWARE_TOAST_POSITION', 'top-end'),

        'toast_close_button' => env('SWEET_ALERT_MIDDLEWARE_TOAST_CLOSE_BUTTON', true),
    ],

];
