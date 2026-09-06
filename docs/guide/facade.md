# Facade

The `Alert` facade is the primary interface for interacting with the SweetAlert package. It provides static-like access to the `AlertBuilder` while leveraging Laravel's service container for dependency injection and testability.

## Import

```php
use RealRashid\SweetAlert\Facades\Alert;
```

## Usage

All methods on the `AlertBuilder` are accessible through the facade:

```php
Alert::title('Hello World')->success()->flash();
Alert::toast('Saved!', 'success')->autoClose(3000)->flash();
Alert::input('Your name', 'text')->inputPlaceholder('John')->flash();
Alert::confirmDelete('Delete?', 'Are you sure?');
```

The facade resolves the `alert` binding from the service container, which returns an `AlertBuilder` instance. All method calls are forwarded to this instance.

## IDE Autocompletion

The `Alert` facade includes comprehensive `@method` PHPDoc annotations that provide full IDE autocompletion and static analysis support:

```php
/**
 * @method static AlertBuilder title(string $title)
 * @method static AlertBuilder text(string $text)
 * @method static AlertBuilder icon(string|AlertType $type)
 * @method static AlertBuilder success()
 * @method static AlertBuilder error()
 * @method static AlertBuilder warning()
 * @method static AlertBuilder info()
 * @method static AlertBuilder question()
 * @method static AlertBuilder html(string $html)
 * @method static ToastBuilder toast(string $title = '', ?string $icon = null)
 * @method static InputBuilder input(string $title = '', string|InputType $inputType = InputType::Text)
 * @method static AlertBuilder confirm(string $title = '', ?string $text = null)
 * @method static AlertBuilder confirmDelete(string $title, ?string $text = null)
 * @method static AlertBuilder flash(string $type = 'config')
 * @method static AlertBuilder timer(int $milliseconds)
 * @method static AlertBuilder position(string|Position $position)
 * ... and more
 */
class Alert extends Facade
```

This means your IDE will suggest all available methods, their parameter types, and return types as you type.

## Facade Root

The facade accessor is `alert`, which maps to the `AlertBuilder` binding in the service container:

```php
protected static function getFacadeAccessor(): string
{
    return 'alert';
}
```

You can also resolve the builder directly from the container:

```php
$builder = app('alert');
$builder->title('Hello')->success()->flash();
```

## Testing with the Facade

When writing tests, you can use Laravel's built-in facade faking to assert that alerts were called:

```php
use RealRashid\SweetAlert\Facades\Alert;

test('it flashes a success alert', function () {
    Alert::title('Created!')->success()->flash();

    // Assert the alert was flashed to the session
    expect(session('alert.config'))->not->toBeNull();
});
```

## Real-Time Facade

If you prefer, you can use Laravel's real-time facade feature by importing the builder class directly:

```php
use Facades\RealRashid\SweetAlert\Builders\AlertBuilder;

AlertBuilder::title('Hello')->success()->flash();
```

This provides the same static-like interface while allowing Laravel to inject a mock instance during testing.
