# ToastBuilder API

The `ToastBuilder` is a specialized builder for creating toast notifications. It automatically configures toast-specific defaults — `toast: true`, hidden confirm button, visible close button, `top-end` position, and a timer progress bar.

## Import

```php
use RealRashid\SweetAlert\Builders\ToastBuilder;
```

## Creation

Toasts are typically created via the `Alert` facade:

```php
Alert::toast('Message', 'success')->flash();
```

Or using the global helper:

```php
toast('Message', 'success')->flash();
```

## Default Configuration

The `ToastBuilder` applies these defaults on construction:

| Setting | Default | Source |
|---|---|---|
| `toast` | `true` | Hardcoded |
| `position` | `'top-end'` | `config('sweetalert.toast.position')` |
| `showCloseButton` | `true` | `config('sweetalert.toast.show_close_button')` |
| `showConfirmButton` | `false` | `config('sweetalert.toast.show_confirm_button')` |
| `timerProgressBar` | `true` | `config('sweetalert.toast.timer_progress_bar')` |
| `timer` | `5000` | `config('sweetalert.toast.auto_close')` |

## Factory Methods

| Method | Return | Description |
|---|---|---|
| `make()` | `static` | Create a new builder from the container |
| `reset()` | `static` | Reset to default configuration |

## Core Configuration

| Method | Parameters | Description |
|---|---|---|
| `title(string $title)` | Toast message | Set the toast title/message |
| `text(string $text)` | Description text | Set the body text |
| `icon(string\|AlertType $type)` | Icon type | Set the toast icon |
| `success()` | — | Set icon to success |
| `error()` | — | Set icon to error |
| `warning()` | — | Set icon to warning |
| `info()` | — | Set icon to info |
| `question()` | — | Set icon to question |

## Flash & Serialize

| Method | Return | Description |
|---|---|---|
| `flash(string $type = 'config')` | `static` | Flash to session |
| `toArray()` | `array` | Get filtered config |
| `toJson()` | `string` | Get config as JSON |
| `getConfig()` | `array` | Get raw config array |

## Backward Compatibility

| Method | Description |
|---|---|
| `middleware()` | Apply middleware toast settings and flash |

## Inherited Traits

`ToastBuilder` uses the same traits as `AlertBuilder` and inherits all their methods:

- **HasTimer**: `timer()`, `autoClose()`, `timerProgressBar()`, `persistent()`
- **HasPosition**: `position()`, `top()`, `topStart()`, `topEnd()`, `center()`, `centerStart()`, `centerEnd()`, `bottom()`, `bottomStart()`, `bottomEnd()`
- **HasButtons**: `showConfirmButton()`, `confirmButton()`, `showCancelButton()`, `cancelButton()`, `showDenyButton()`, `denyButton()`, `showCloseButton()`, `hideCloseButton()`, `reverseButtons()`, `buttonsStyling()`, `focusConfirm()`, `focusCancel()`, `showLoaderOnConfirm()`
- **HasAnimation**: `animation()`, `disableAnimation()`, `showClass()`, `hideClass()`
- **HasStyling**: `width()`, `padding()`, `background()`, `color()`, `heightAuto()`, `customClass()`, `iconHtml()`, `iconColor()`, `imageUrl()`, `addImage()`, `footer()`, `grow()`, `backdrop()`, `allowEscapeKey()`, `allowOutsideClick()`, `stopPropagation()`

See the [AlertBuilder API](./alert-builder) for full method details on inherited traits.
