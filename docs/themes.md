# Themes

SweetAlert2 comes with a variety of themes to choose from. You can customize the look and feel of your alerts by choosing one of the pre-built themes.

#### Dark

The `dark` theme is a dark-themed alert box with white text. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="dark"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-dark.png" alt="DarkTheme">
</p>

#### Minimal

The `minimal` theme is a simple alert box with minimal styling. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="minimal"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-minimal.png" alt="MinimalTheme">
</p>


#### Borderless

The `borderless` theme is a simple alert box with no border. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="borderless"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-borderless.png" alt="BorderlessTheme">
</p>


#### Bootstrap 4

The `bootstrap-4` theme is a theme that mimics the look and feel of Bootstrap 4 alerts. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="bootstrap-4"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-bootstrap-4.png" alt="BootstrapTheme">
</p>


#### Bulma

The `bulma` theme is a theme that mimics the look and feel of Bulma alerts. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="bulma"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-bulma.png" alt="BulmaTheme">
</p>


#### Material UI

The `material-ui` theme is a theme that mimics the look and feel of Material UI alerts. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="material-ui"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-material-ui.png" alt="MaterialTheme">
</p>


#### WordPress Admin

The `wordpress-admin` theme is a theme that mimics the look and feel of WordPress admin alerts. To use this theme, add the following environment variable to your .env file:

```
SWEET_ALERT_THEME="wordpress-admin"
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/themes/theme-wordpress-admin.png" alt="WordpressTheme">
</p>


#### Customizing Theme

Each theme has a set of customizable classes, allowing you to modify the appearance of your alerts further. You can customize these classes by setting the corresponding environment variable in your .env file. Here's a list of the customizable classes:

```php
'customClass' => [
    'container' => env('SWEET_ALERT_CONTAINER_CLASS'),
    'popup' => env('SWEET_ALERT_POPUP_CLASS'),
    'header' => env('SWEET_ALERT_HEADER_CLASS'),
    'title' => env('SWEET_ALERT_TITLE_CLASS'),
    'closeButton' => env('SWEET_ALERT_CLOSE_BUTTON_CLASS'),
    'icon' => env('SWEET_ALERT_ICON_CLASS'),
    'image' => env('SWEET_ALERT_IMAGE_CLASS'),
    'content' => env('SWEET_ALERT_CONTENT_CLASS'),
    'input' => env('SWEET_ALERT_INPUT_CLASS'),
    'actions' => env('SWEET_ALERT_ACTIONS_CLASS'),
    'confirmButton' => env('SWEET_ALERT_CONFIRM_BUTTON_CLASS'),
    'cancelButton' => env('SWEET_ALERT_CANCEL_BUTTON_CLASS'),
    'footer' => env('SWEET_ALERT_FOOTER_CLASS'),
],
```

To customize a class, simply set the corresponding environment variable to your custom class name. For example:

```
SWEET_ALERT_CONTAINER_CLASS="my-custom-container"
```

This will set the `container` class of your alerts to `my-custom-container`.
