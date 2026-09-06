# Helper Functions

The package provides global helper functions for quick access to common alert patterns. These are convenient shortcuts that don't require importing the `Alert` facade.

## `alert()`

The `alert()` function returns an `AlertBuilder` instance. When called with arguments, it creates and flashes an alert immediately (backward-compatible behavior):

```php
// Return the builder instance for chaining
alert()->title('Hello')->success()->flash();

// Quick alert with title, message, and type (flashes immediately)
alert('Welcome!', 'You have logged in.', 'success');

// Return the builder without arguments
alert()->success('Done!')->flash();
```

### Signature

```php
function alert(string $title = '', string $message = '', string $type = ''): AlertBuilder
```

When the title is non-empty, the function sets the title, text, and icon, then calls `flash()` automatically — matching the fire-and-forget behavior from v7. The returned builder can still be chained further if needed, but the alert is already flashed.

When called with no arguments (or an empty title), it returns the `AlertBuilder` instance for fluent chaining. You must call `->flash()` explicitly when done building.

## `toast()`

The `toast()` function creates and returns a `ToastBuilder` instance:

```php
// Quick toast with title and type
toast('Item saved!', 'success')->flash();

// Toast with custom position
toast('Notification', 'info', 'bottom-end')->flash();

// Chain additional options
toast('Warning!', 'warning')
    ->autoClose(3000)
    ->timerProgressBar()
    ->flash();
```

### Signature

```php
function toast(string $title = '', ?string $type = null, string $position = 'top-end'): ToastBuilder
```

The `$position` parameter is provided for backward compatibility, but the recommended approach is to use the `position()` method:

```php
// Recommended
toast('Saved!', 'success')->position('bottom-end')->flash();

// Also works (legacy)
toast('Saved!', 'success', 'bottom-end')->flash();
```

## `confirmDelete()`

The `confirmDelete()` function creates a confirm delete dialog:

```php
// With title only
confirmDelete('Delete this post?');

// With title and text
confirmDelete('Delete this post?', 'This action cannot be undone.');
```

### Signature

```php
function confirmDelete(string $title = '', ?string $text = null): AlertBuilder
```

When a title is provided, the function immediately creates and flashes the delete confirmation. When called with no arguments, it returns a fresh `AlertBuilder` instance.

## Helper vs Facade

The helper functions and the `Alert` facade are interchangeable — they both resolve the same service from the container. Choose based on your preference:

```php
// Using the facade (recommended for controllers)
use RealRashid\SweetAlert\Facades\Alert;

Alert::title('Done')->success()->flash();

// Using helpers (convenient in views or quick scripts)
alert()->title('Done')->success()->flash();
```

The facade is generally preferred in controller code because it's explicit about the dependency and provides better IDE autocompletion through the `@method` annotations on the facade class.
