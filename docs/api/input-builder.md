# InputBuilder API

The `InputBuilder` is a specialized builder for creating input dialogs. It provides methods for configuring all SweetAlert2 input types, validation, placeholders, and server-side pre-confirm callbacks.

## Import

```php
use RealRashid\SweetAlert\Builders\InputBuilder;
```

## Creation

Input dialogs are typically created via the `Alert` facade:

```php
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('Enter your email', InputType::Email)
    ->inputPlaceholder('user@example.com')
    ->flash();
```

## Default Configuration

| Setting | Default | Why |
|---|---|---|
| `showConfirmButton` | `true` | Users need to submit their input |
| `showCancelButton` | `true` | Users need to cancel |
| `confirmButtonText` | `'Submit'` | Action-oriented label |
| `cancelButtonText` | `'Cancel'` | Standard cancel label |
| `showCloseButton` | `false` | Close button is redundant with cancel |
| `allowOutsideClick` | `false` | Prevent accidental dismissal |
| `customClass` | `[]` | No custom classes by default |

## Factory Methods

| Method | Return | Description |
|---|---|---|
| `make()` | `static` | Create a new builder from the container |
| `reset()` | `static` | Reset to default configuration |

## Core Configuration

| Method | Parameters | Description |
|---|---|---|
| `title(string $title)` | Dialog title | Set the heading text |
| `text(string $text)` | Description text | Set body text below the title |
| `icon(string\|AlertType $type)` | Icon type | Set the dialog icon |

## Input-Specific Methods

| Method | Parameters | Description |
|---|---|---|
| `inputType(string\|InputType $type)` | Input type | Set the input type (text, email, select, etc.) |
| `inputPlaceholder(string $text)` | Placeholder text | Set input placeholder |
| `inputValue(string $value)` | Default value | Pre-fill the input |
| `inputOptions(array $options)` | Options array | Set options for select/radio inputs |
| `inputAttributes(array $attributes)` | HTML attributes | Set input HTML attributes |
| `inputValidator(string $message)` | Error message | Set client-side validation message |
| `inputLabel(string $label)` | Label text | Set input label |
| `inputAutoFocus(bool $enabled = true)` | Enable/disable | Auto-focus the input |
| `inputAutoTrim(bool $enabled = true)` | Enable/disable | Auto-trim whitespace |
| `preConfirmRoute(string $route)` | Route URL | Set server-side validation route |
| `inputClass(string $class)` | CSS class | Set input CSS class |

## Flash & Serialize

| Method | Return | Description |
|---|---|---|
| `flash(string $type = 'config')` | `static` | Flash to session |
| `toArray()` | `array` | Get filtered config |
| `toJson()` | `string` | Get config as JSON |
| `getConfig()` | `array` | Get raw config array |

## Inherited Traits

`InputBuilder` uses the same traits as `AlertBuilder` and inherits all their methods:

- **HasTimer**: `timer()`, `autoClose()`, `timerProgressBar()`, `persistent()`
- **HasPosition**: `position()`, `top()`, `topStart()`, `topEnd()`, `center()`, `centerStart()`, `centerEnd()`, `bottom()`, `bottomStart()`, `bottomEnd()`
- **HasButtons**: `showConfirmButton()`, `confirmButton()`, `showCancelButton()`, `cancelButton()`, `showDenyButton()`, `denyButton()`, `showCloseButton()`, `hideCloseButton()`, `reverseButtons()`, `buttonsStyling()`, `focusConfirm()`, `focusCancel()`, `showLoaderOnConfirm()`
- **HasAnimation**: `animation()`, `disableAnimation()`, `showClass()`, `hideClass()`
- **HasStyling**: `width()`, `padding()`, `background()`, `color()`, `heightAuto()`, `customClass()`, `iconHtml()`, `iconColor()`, `imageUrl()`, `addImage()`, `footer()`, `grow()`, `backdrop()`, `allowEscapeKey()`, `allowOutsideClick()`, `stopPropagation()`

See the [AlertBuilder API](./alert-builder) for full method details on inherited traits.
