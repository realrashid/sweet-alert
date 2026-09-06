# AlertConfig API

`AlertConfig` is an immutable value object that encapsulates the complete configuration for a SweetAlert2 alert. It provides serialization and deserialization methods for session storage and JSON rendering in Blade views.

## Import

```php
use RealRashid\SweetAlert\Support\AlertConfig;
```

## Constructor

```php
new AlertConfig(array $config = [], string $type = 'config')
```

| Parameter | Default | Description |
|---|---|---|
| `$config` | `[]` | The SweetAlert2 configuration array |
| `$type` | `'config'` | The alert type: `'config'` or `'delete'` |

## Factory Method

### `fromJson(string $json): static`

Create an `AlertConfig` instance from a JSON string. This is used when reading alert data back from the session:

```php
$config = AlertConfig::fromJson($sessionData);
```

## Accessors

| Method | Return | Description |
|---|---|---|
| `toArray()` | `array` | Get the raw configuration array |
| `get(string $key, mixed $default = null)` | `mixed` | Get a specific config value |
| `has(string $key)` | `bool` | Check if a config key exists |
| `type()` | `string` | Get the alert type (`'config'` or `'delete'`) |

## Serialization

| Method | Return | Description |
|---|---|---|
| `toJson()` | `string` | Serialize to JSON (includes `config` and `type` wrapper) |
| `toSwalConfigJson()` | `string` | Get raw config JSON for `Swal.fire()` (no wrapper) |

## Type Check Methods

| Method | Return | Description |
|---|---|---|
| `isToast()` | `bool` | Check if this is a toast configuration |
| `hasInput()` | `bool` | Check if this has an input configuration |
| `hasPreConfirmRoute()` | `bool` | Check if this has a pre-confirm route |
| `getPreConfirmRoute()` | `?string` | Get the pre-confirm route URL |

## Immutable Transformation

### `withoutPreConfirmRoute(): static`

Return a new `AlertConfig` instance with the `preConfirmRoute` key removed. This is used by the Blade view to strip the route from the JavaScript output (since it's handled separately as a `fetch()` call):

```php
$jsConfig = $alertConfig->withoutPreConfirmRoute();
```

## JSON Structure

The `toJson()` output includes a wrapper:

```json
{
    "config": {
        "title": "Hello",
        "icon": "success",
        "text": "World"
    },
    "type": "config"
}
```

The `toSwalConfigJson()` output is the raw config only:

```json
{
    "title": "Hello",
    "icon": "success",
    "text": "World"
}
```
