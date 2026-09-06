# Enums

The package provides three PHP 8.3+ backed enums that bring type safety and IDE autocompletion to alert configuration. Using enums instead of string literals prevents typos, enables static analysis, and makes your code self-documenting.

## AlertType

The `AlertType` enum defines the five icon types supported by SweetAlert2:

```php
use RealRashid\SweetAlert\Enums\AlertType;

Alert::title('Done')->icon(AlertType::Success)->flash();
Alert::title('Oops')->icon(AlertType::Error)->flash();
Alert::title('Careful')->icon(AlertType::Warning)->flash();
Alert::title('FYI')->icon(AlertType::Info)->flash();
Alert::title('Sure?')->icon(AlertType::Question)->flash();
```

| Case | Value | Description |
|---|---|---|
| `AlertType::Success` | `'success'` | Green checkmark icon |
| `AlertType::Error` | `'error'` | Red X icon |
| `AlertType::Warning` | `'warning'` | Yellow triangle icon |
| `AlertType::Info` | `'info'` | Blue info icon |
| `AlertType::Question` | `'question'` | Blue question mark icon |

::: tip Shorthand Methods
You don't need to use the enum directly for icon types. The builder provides convenience methods:

```php
// These are equivalent
Alert::title('Done')->icon(AlertType::Success)->flash();
Alert::title('Done')->success()->flash();
```
:::

## InputType

The `InputType` enum defines all 19 input types supported by SweetAlert2:

```php
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('Email', InputType::Email)->flash();
Alert::input('Password', InputType::Password)->flash();
Alert::input('Country', InputType::Select)->inputOptions([...])->flash();
```

| Case | Value | HTML Element |
|---|---|---|
| `InputType::Text` | `'text'` | `<input type="text">` |
| `InputType::Email` | `'email'` | `<input type="email">` |
| `InputType::Password` | `'password'` | `<input type="password">` |
| `InputType::Number` | `'number'` | `<input type="number">` |
| `InputType::Tel` | `'tel'` | `<input type="tel">` |
| `InputType::Range` | `'range'` | `<input type="range">` |
| `InputType::Textarea` | `'textarea'` | `<textarea>` |
| `InputType::Select` | `'select'` | `<select>` |
| `InputType::Radio` | `'radio'` | Radio buttons |
| `InputType::Checkbox` | `'checkbox'` | Checkbox |
| `InputType::File` | `'file'` | `<input type="file">` |
| `InputType::Url` | `'url'` | `<input type="url">` |
| `InputType::Color` | `'color'` | `<input type="color">` |
| `InputType::Date` | `'date'` | `<input type="date">` |
| `InputType::Time` | `'time'` | `<input type="time">` |
| `InputType::Month` | `'month'` | `<input type="month">` |
| `InputType::Week` | `'week'` | `<input type="week">` |
| `InputType::Search` | `'search'` | `<input type="search">` |
| `InputType::DatetimeLocal` | `'datetime-local'` | `<input type="datetime-local">` |

## Position

The `Position` enum defines fifteen screen positions for alert placement (9 base positions + 6 physical aliases):

```php
use RealRashid\SweetAlert\Enums\Position;

Alert::toast('Saved!', 'success')->position(Position::BottomEnd)->flash();
Alert::title('Important')->position(Position::Center)->flash();
```

| Case | Value | Location |
|---|---|---|
| `Position::Top` | `'top'` | Top center |
| `Position::TopStart` | `'top-start'` | Top left |
| `Position::TopEnd` | `'top-end'` | Top right |
| `Position::Center` | `'center'` | Center |
| `Position::CenterStart` | `'center-start'` | Center left |
| `Position::CenterEnd` | `'center-end'` | Center right |
| `Position::Bottom` | `'bottom'` | Bottom center |
| `Position::BottomStart` | `'bottom-start'` | Bottom left |
| `Position::BottomEnd` | `'bottom-end'` | Bottom right |
| `Position::TopLeft` | `'top-start'` | Alias for TopStart |
| `Position::TopRight` | `'top-end'` | Alias for TopEnd |
| `Position::CenterLeft` | `'center-start'` | Alias for CenterStart |
| `Position::CenterRight` | `'center-end'` | Alias for CenterEnd |
| `Position::BottomLeft` | `'bottom-start'` | Alias for BottomStart |
| `Position::BottomRight` | `'bottom-end'` | Alias for BottomEnd |

## String vs Enum

All methods that accept enums also accept raw string values for flexibility:

```php
// Using enum (recommended)
Alert::title('Done')->icon(AlertType::Success)->flash();
Alert::input('Email', InputType::Email)->flash();
Alert::toast('Saved', 'success')->position(Position::TopEnd)->flash();

// Using string (also valid)
Alert::title('Done')->icon('success')->flash();
Alert::input('Email', 'email')->flash();
Alert::toast('Saved', 'success')->position('top-end')->flash();
```

The builder methods handle both transparently — if an enum is passed, the `->value` is extracted; if a string is passed, it's used directly.

## Enum Backed Values

All enums are backed by strings (`string` backed enums), meaning you can access the underlying value using `->value`:

```php
AlertType::Success->value;    // 'success'
InputType::Email->value;      // 'email'
Position::TopEnd->value;      // 'top-end'
```

This is useful when you need to serialize enum values for APIs, tests, or comparisons.
