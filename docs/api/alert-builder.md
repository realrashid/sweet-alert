# AlertBuilder API

The `AlertBuilder` is the main fluent builder for constructing SweetAlert2 configurations. Every method returns `$this` for chaining, enabling natural method chaining that mirrors Laravel Eloquent's expressive API.

## Import

```php
use RealRashid\SweetAlert\Builders\AlertBuilder;
```

## Constructor

```php
new AlertBuilder(?AlertFlasher $flasher = null)
```

Creates a new AlertBuilder instance with default configuration from `config/sweetalert.php`. Typically accessed via the `Alert` facade rather than direct instantiation.

## Factory Methods

| Method | Return | Description |
|---|---|---|
| `make()` | `static` | Create a new builder instance from the container |
| `reset()` | `static` | Reset the builder to default configuration |

## Core Configuration

| Method | Parameters | Description |
|---|---|---|
| `title(string $title)` | The alert title | Set the heading text |
| `text(string $text)` | Description text | Set the body text below the title |
| `icon(string\|AlertType $type)` | Icon type | Set the alert icon |
| `success(string $title = '', string $text = '')` | Optional title & text | Set icon to success |
| `error(string $title = '', string $text = '')` | Optional title & text | Set icon to error |
| `warning(string $title = '', string $text = '')` | Optional title & text | Set icon to warning |
| `info(string $title = '', string $text = '')` | Optional title & text | Set icon to info |
| `question(string $title = '', string $text = '')` | Optional title & text | Set icon to question (auto-shows cancel) |
| `html(string $html)` | HTML content | Set HTML body (replaces text) |
| `toHtml()` | — | Convert text content to HTML |
| `view(string $view, array $data = [], array $mergeData = [])` | Blade view name & data | Render a Blade view as HTML content |

## Convenience Factory Methods

| Method | Return Type | Description |
|---|---|---|
| `toast(string $title = '', ?string $icon = null)` | `ToastBuilder` | Create a toast notification builder |
| `input(string $title = '', string\|InputType $inputType = InputType::Text)` | `InputBuilder` | Create an input dialog builder |
| `confirm(string $title = '', ?string $text = null)` | `static` | Create a confirm dialog with cancel button |
| `confirmDelete(string $title, ?string $text = null)` | `static` | Create a delete confirmation (auto-flashes) |
| `preConfirmRoute(string $route)` | `static` | Set server-side confirm validation route |
| `preDenyRoute(string $route)` | `static` | Set server-side deny validation route |
| `progressSteps(array $steps)` | `static` | Set progress step indicators |
| `currentProgressStep(int $index)` | `static` | Set current step index |

## Flash & Serialize

| Method | Return | Description |
|---|---|---|
| `flash(string $type = 'config')` | `static` | Flash configuration to the session |
| `toArray()` | `array` | Get filtered config array (removes empty values) |
| `toJson()` | `string` | Get config as JSON string |
| `getConfig()` | `array` | Get raw config array (including empty values) |

## Backward Compatibility

| Method | Description |
|---|---|
| `alert(string $title = '', string $text = '', ?string $icon = null)` | Legacy method — sets title/text/icon without flashing (call `->flash()` explicitly) |

## Inherited from HasTimer

| Method | Parameters | Description |
|---|---|---|
| `timer(int $milliseconds)` | Timer in ms | Set auto-close timer |
| `autoClose(int $milliseconds = 5000)` | Timer in ms | Alias for `timer()` |
| `timerProgressBar(bool $enabled = true)` | Enable/disable | Show timer progress bar |
| `persistent(bool $showConfirmBtn = true, bool $showCloseBtn = false)` | Button visibility | Disable timer, ESC, and outside click |

## Inherited from HasPosition

| Method | Parameters | Description |
|---|---|---|
| `position(string\|Position $position)` | Position value | Set alert position |
| `top()` | — | Position: top center |
| `topStart()` | — | Position: top left |
| `topEnd()` | — | Position: top right (RTL-aware) |
| `topLeft()` | — | Position: top left |
| `topRight()` | — | Position: top right |
| `center()` | — | Position: center |
| `centerStart()` | — | Position: center left (RTL-aware) |
| `centerEnd()` | — | Position: center right (RTL-aware) |
| `centerLeft()` | — | Position: center left |
| `centerRight()` | — | Position: center right |
| `bottom()` | — | Position: bottom center |
| `bottomStart()` | — | Position: bottom left (RTL-aware) |
| `bottomEnd()` | — | Position: bottom right (RTL-aware) |
| `bottomLeft()` | — | Position: bottom left |
| `bottomRight()` | — | Position: bottom right |

## Inherited from HasButtons

| Method | Parameters | Description |
|---|---|---|
| `showConfirmButton(string $text = 'OK', string $color = '#3085d6')` | Button text & color | Show confirm button |
| `confirmButton(string $text = 'OK', string $color = '#3085d6')` | Button text & color | Alias for `showConfirmButton()` |
| `showCancelButton(string $text = 'Cancel', string $color = '#aaa')` | Button text & color | Show cancel button |
| `cancelButton(string $text = 'Cancel', string $color = '#aaa')` | Button text & color | Alias for `showCancelButton()` |
| `showDenyButton(string $text = 'Deny', string $color = '#dd6b55')` | Button text & color | Show deny button |
| `denyButton(string $text = 'Deny', string $color = '#dd6b55')` | Button text & color | Alias for `showDenyButton()` |
| `showCloseButton(string $ariaLabel = 'Close this dialog')` | ARIA label | Show close button |
| `hideCloseButton()` | — | Hide close button |
| `closeButtonAriaLabel(string $label)` | ARIA label | Set close button aria-label independently |
| `reverseButtons()` | — | Reverse confirm/cancel order |
| `buttonsStyling(bool $enabled = true)` | Enable/disable | Toggle default button styling |
| `focusConfirm(bool $focus = true)` | Focus state | Focus confirm button |
| `focusCancel(bool $focus = true)` | Focus state | Focus cancel button |
| `showLoaderOnConfirm(bool $enabled = true)` | Enable/disable | Show loader on confirm button |

## Inherited from HasAnimation

| Method | Parameters | Description |
|---|---|---|
| `animation(string $show, string $hide)` | Animate.css class names | Set show/hide animations |
| `disableAnimation()` | — | Disable all animations |
| `showClass(array $classes)` | Class map | Set custom show classes |
| `hideClass(array $classes)` | Class map | Set custom hide classes |

## Inherited from HasStyling

| Method | Parameters | Description |
|---|---|---|
| `width(string $width)` | CSS value | Set modal width |
| `padding(string $padding)` | CSS value | Set modal padding |
| `background(string $color)` | CSS color | Set background color |
| `color(string $color)` | CSS color | Set text color |
| `heightAuto(bool $enabled = true)` | Enable/disable | Set height to auto |
| `customClass(array $classes)` | Class map | Set custom CSS classes |
| `iconHtml(string $html)` | HTML string | Set custom icon HTML |
| `iconColor(string $color)` | CSS color | Set icon color |
| `imageUrl(string $url, ?int $w = null, ?int $h = null, ?string $alt = null)` | Image config | Set alert image |
| `addImage(string $url, int $w = 400, int $h = 200, ?string $alt = null)` | Image config | Set image with dimensions |
| `footer(string $html)` | HTML string | Set footer content |
| `grow(string $direction = 'false')` | Direction | Set grow direction |
| `backdrop(mixed $backdrop = true)` | Bool or CSS | Set backdrop config |
| `allowEscapeKey(bool $allow = true)` | Enable/disable | Allow ESC to close |
| `allowOutsideClick(bool $allow = true)` | Enable/disable | Allow outside click to close |
| `stopPropagation(bool $enabled = true)` | Enable/disable | Stop keydown propagation |
