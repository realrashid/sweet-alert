# Advanced Usage

This guide covers advanced patterns and techniques for getting the most out of the Laravel SweetAlert package.

## Custom CSS Classes

Apply custom CSS classes to any part of the SweetAlert2 popup for complete visual control. Combine with `buttonsStyling(false)` to replace SweetAlert2's default button styles:

```php
Alert::title('Bootstrap Styled')
    ->success()
    ->customClass([
        'popup' => 'modal-content',
        'header' => 'modal-header',
        'title' => 'modal-title',
        'content' => 'modal-body',
        'actions' => 'modal-footer',
        'confirmButton' => 'btn btn-primary',
        'cancelButton' => 'btn btn-secondary',
    ])
    ->buttonsStyling(false)
    ->flash();
```

### Tailwind CSS Integration

```php
Alert::title('Tailwind Styled')
    ->success()
    ->customClass([
        'popup' => 'rounded-lg shadow-xl',
        'title' => 'text-xl font-bold text-gray-800',
        'confirmButton' => 'bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded',
        'cancelButton' => 'bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold py-2 px-4 rounded',
    ])
    ->buttonsStyling(false)
    ->flash();
```

## HTML Content

Use `html()` for rich content inside alerts — tables, lists, images, and more:

```php
Alert::title('Order Details')
    ->html('
        <table class="table">
            <tr><td>Order #</td><td>12345</td></tr>
            <tr><td>Item</td><td>Widget Pro</td></tr>
            <tr><td>Total</td><td>$99.99</td></tr>
        </table>
    ')
    ->success()
    ->width('30rem')
    ->flash();
```

### Using Blade Views

For complex HTML, render a Blade view directly into the alert:

```php
Alert::title('Invoice')
    ->view('invoices.alert-preview', ['invoice' => $invoice])
    ->width('40rem')
    ->flash();
```

The view is rendered server-side, so you have full access to Laravel's templating engine, including `@foreach`, `@if`, components, and more.

## Multiple Custom Class Calls

The `customClass()` method merges values across multiple calls, so you can build up classes incrementally:

```php
Alert::title('Step by Step')
    ->customClass(['popup' => 'rounded-xl'])
    ->customClass(['title' => 'font-bold'])
    ->customClass(['confirmButton' => 'btn-primary'])
    ->buttonsStyling(false)
    ->flash();
```

## Direct Configuration Access

For debugging or testing, you can inspect the raw configuration array:

```php
$builder = Alert::title('Test')->success();

// Get the full config array
$config = $builder->getConfig();

// Get filtered config (removes empty values)
$array = $builder->toArray();

// Get JSON representation
$json = $builder->toJson();
```

This is particularly useful in tests:

```php
test('alert has correct icon', function () {
    $builder = AlertBuilder::make()->title('Test')->success();

    expect($builder->getConfig()['icon'])->toBe('success');
});
```

## Builder Reset

Reset a builder instance to its default state:

```php
$builder = Alert::title('First')->success();
$builder->flash();

// Reuse the same builder
$builder->reset();
$builder->title('Second')->error()->flash();
```

## Factory Method

Create a fresh builder instance using the `make()` factory method:

```php
$builder = AlertBuilder::make();
$builder->title('Hello')->success()->flash();
```

This resolves a new instance from the service container, so it includes all default configuration values.

## Combining Features

The real power of the fluent API is combining features naturally:

```php
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Enums\Position;

// Multi-step input dialog with server validation
Alert::input('Enter your API key', InputType::Password)
    ->text('You can find your API key in the dashboard settings.')
    ->inputPlaceholder('sk-...')
    ->inputAutoTrim(true)
    ->preConfirmRoute(route('api.validate-key'))
    ->showLoaderOnConfirm()
    ->confirmButton('Validate', '#28a745')
    ->cancelButton('Cancel')
    ->showCloseButton()
    ->position(Position::Center)
    ->animation('animate__fadeInDown', 'animate__fadeOutUp')
    ->background('#f8f9fa')
    ->customClass([
        'popup' => 'rounded-xl shadow-2xl',
        'confirmButton' => 'btn btn-success',
    ])
    ->buttonsStyling(false)
    ->footer('<a href="/docs/api-keys">Need help?</a>')
    ->flash();
```

## Programmatic Control from JavaScript

The package includes a `SweetAlertLaravel` JavaScript object that provides programmatic control over the active popup:

```javascript
// Close the current popup
SweetAlertLaravel.close();

// Click the confirm button
SweetAlertLaravel.confirm();

// Click the cancel button
SweetAlertLaravel.cancel();

// Click the deny button
SweetAlertLaravel.deny();

// Check if a popup is visible
SweetAlertLaravel.isVisible();

// Fire a custom popup
SweetAlertLaravel.fire({ title: 'Hello', icon: 'info' });
```

This object is automatically available on the `window` when you use the `@sweetAlert` Blade directive.

## Custom JavaScript Integration

If you need to handle the result of a SweetAlert2 dialog in JavaScript (e.g., for a deny button action), you can listen for the result using the `Swal.fire()` promise. However, since the package renders the Swal.fire() call automatically, you'll need to use the `didOpen` or `didClose` callbacks via `customClass` or a custom view.

For more advanced JavaScript integration, consider:

1. Using `never_load_js` to prevent automatic JS loading
2. Including SweetAlert2 via Vite/Mix
3. Writing custom JavaScript to handle dialog results

This gives you full control over the dialog lifecycle while still using the PHP builders for configuration.
