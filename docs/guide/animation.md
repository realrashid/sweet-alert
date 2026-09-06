# Animation

SweetAlert2 supports custom animations for the show and hide transitions of popup dialogs. The `HasAnimation` trait integrates with [Animate.css](https://animate.style/) to provide smooth, professional animations.

## Basic Animation

Use the `animation()` method to specify both the show (entrance) and hide (exit) animation classes:

```php
Alert::title('Welcome!')
    ->success()
    ->animation('animate__bounceIn', 'animate__bounceOut')
    ->flash();
```

The `animate__` prefix is automatically added — the method wraps your class names with `animate__animated` to create the full Animate.css class string.

## Enable Animate.css

For animations to work, you need to load the Animate.css stylesheet. Set the configuration option:

```env
SWEET_ALERT_ANIMATION_ENABLE=true
```

This automatically includes the Animate.css CSS file when rendering alerts. You can also provide a custom CDN URL:

```env
SWEET_ALERT_ANIMATECSS=https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css
```

## Available Animate.css Classes

Here are some popular animation classes that work well with SweetAlert2:

### Entrance Animations (Show)

| Class | Effect |
|---|---|
| `animate__bounceIn` | Bouncy entrance |
| `animate__fadeIn` | Simple fade in |
| `animate__fadeInDown` | Fade in from top |
| `animate__fadeInUp` | Fade in from bottom |
| `animate__slideInDown` | Slide in from top |
| `animate__slideInUp` | Slide in from bottom |
| `animate__zoomIn` | Zoom in from center |
| `animate__flipInX` | 3D flip on X axis |
| `animate__rotateIn` | Rotating entrance |
| `animate__rollIn` | Rolling entrance |

### Exit Animations (Hide)

| Class | Effect |
|---|---|
| `animate__bounceOut` | Bouncy exit |
| `animate__fadeOut` | Simple fade out |
| `animate__fadeOutDown` | Fade out to bottom |
| `animate__fadeOutUp` | Fade out to top |
| `animate__slideOutDown` | Slide out to bottom |
| `animate__slideOutUp` | Slide out to top |
| `animate__zoomOut` | Zoom out to center |
| `animate__flipOutX` | 3D flip exit on X axis |
| `animate__rotateOut` | Rotating exit |
| `animate__rollOut` | Rolling exit |

## Disable Animation

To disable all animations on a specific alert:

```php
Alert::title('Instant')
    ->info()
    ->disableAnimation()
    ->flash();
```

## Advanced: Custom Show/Hide Classes

For full control over the animation classes (not using Animate.css), use `showClass()` and `hideClass()`:

```php
Alert::title('Custom Animation')
    ->showClass([
        'popup' => 'my-custom-enter-animation',
    ])
    ->hideClass([
        'popup' => 'my-custom-exit-animation',
    ])
    ->flash();
```

The `showClass` and `hideClass` options support multiple targets:

```php
Alert::title('Multi-part Animation')
    ->showClass([
        'popup' => 'animate__animated animate__fadeInDown',
        'backdrop' => 'animate__animated animate__fadeIn',
        'icon' => 'animate__animated animate__heartBeat',
    ])
    ->hideClass([
        'popup' => 'animate__animated animate__fadeOutUp',
        'backdrop' => 'animate__animated animate__fadeOut',
        'icon' => 'animate__animated animate__rotateOut',
    ])
    ->flash();
```

## Example: Slide-in Toast

```php
Alert::toast('New message received', 'info')
    ->animation('animate__slideInRight', 'animate__slideOutRight')
    ->flash();
```

## Example: Bouncy Success Modal

```php
Alert::title('Order Confirmed!')
    ->success()
    ->text('Your order has been placed successfully.')
    ->animation('animate__bounceIn', 'animate__bounceOut')
    ->confirmButton('Great!', '#28a745')
    ->flash();
```
