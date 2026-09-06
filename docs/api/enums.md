# Enums API Reference

The package provides three PHP 8.3+ backed string enums for type-safe configuration.

## AlertType

```php
namespace RealRashid\SweetAlert\Enums;

enum AlertType: string
{
    case Success = 'success';
    case Error = 'error';
    case Warning = 'warning';
    case Info = 'info';
    case Question = 'question';
}
```

| Case | Value | Description |
|---|---|---|
| `Success` | `'success'` | Green checkmark — operation completed |
| `Error` | `'error'` | Red X — something went wrong |
| `Warning` | `'warning'` | Yellow triangle — be careful |
| `Info` | `'info'` | Blue circle — informational |
| `Question` | `'question'` | Blue question mark — confirmation needed |

## InputType

```php
namespace RealRashid\SweetAlert\Enums;

enum InputType: string
{
    case Text = 'text';
    case Email = 'email';
    case Password = 'password';
    case Number = 'number';
    case Tel = 'tel';
    case Range = 'range';
    case Textarea = 'textarea';
    case Select = 'select';
    case Radio = 'radio';
    case Checkbox = 'checkbox';
    case File = 'file';
    case Url = 'url';
    case Color = 'color';
    case Date = 'date';
    case DatetimeLocal = 'datetime-local';
    case Time = 'time';
    case Month = 'month';
    case Week = 'week';
    case Search = 'search';
}
```

## Position

```php
namespace RealRashid\SweetAlert\Enums;

enum Position: string
{
    case Top = 'top';
    case TopStart = 'top-start';
    case TopEnd = 'top-end';
    case TopLeft = 'top-left';
    case TopRight = 'top-right';
    case Center = 'center';
    case CenterStart = 'center-start';
    case CenterEnd = 'center-end';
    case CenterLeft = 'center-left';
    case CenterRight = 'center-right';
    case Bottom = 'bottom';
    case BottomStart = 'bottom-start';
    case BottomEnd = 'bottom-end';
    case BottomLeft = 'bottom-left';
    case BottomRight = 'bottom-right';
}
```

## Usage Patterns

### With the `icon()` method

```php
Alert::title('Done')->icon(AlertType::Success)->flash();
```

### With the `input()` method

```php
Alert::input('Email', InputType::Email)->flash();
```

### With the `position()` method

```php
Alert::toast('Saved', 'success')->position(Position::TopEnd)->flash();
```

### Getting the string value

All enums are backed by strings. Access the underlying value with `->value`:

```php
AlertType::Success->value;     // 'success'
InputType::Select->value;      // 'select'
Position::BottomEnd->value;    // 'bottom-end'
```

### Enum from value

Create an enum instance from its string value:

```php
AlertType::from('success');    // AlertType::Success
InputType::from('email');      // InputType::Email
Position::from('top-end');     // Position::TopEnd
```

### Safe enum creation

Use `tryFrom()` to safely create an enum without throwing an exception for invalid values:

```php
AlertType::tryFrom('unknown');  // null (no exception)
```
