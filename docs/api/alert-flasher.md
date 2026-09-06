# AlertFlasher API

`AlertFlasher` handles reading and writing `AlertConfig` objects to the Laravel session. It provides a clean API separate from the builder logic.

## Import

```php
use RealRashid\SweetAlert\Support\AlertFlasher;
```

## Constructor

```php
new AlertFlasher(SessionStoreInterface $session)
```

The `SessionStoreInterface` is typically bound to `AlertSessionStore` in the service container.

## Writing Alerts

### `flash(AlertConfig $config, string $type = 'config'): void`

Flash an `AlertConfig` to the session under the key `alert.{type}`:

```php
$flasher->flash($alertConfig, 'config');  // Stored as 'alert.config'
$flasher->flash($alertConfig, 'delete');  // Stored as 'alert.delete'
```

### `flashConfig(array $config, string $type = 'config'): void`

Flash a raw configuration array directly (wraps it in an `AlertConfig` automatically):

```php
$flasher->flashConfig(['title' => 'Hello', 'icon' => 'success']);
```

## Reading Alerts

### `hasAlert(): bool`

Check if there is any alert data in the session:

```php
if ($flasher->hasAlert()) {
    // Alert data exists
}
```

### `getAlert(): ?AlertConfig`

Get the standard alert configuration from the session:

```php
$config = $flasher->getAlert();
if ($config) {
    $title = $config->get('title');
}
```

### `getDeleteAlert(): ?AlertConfig`

Get the delete confirmation alert configuration:

```php
$deleteConfig = $flasher->getDeleteAlert();
```

## Clearing

### `clear(): void`

Remove all alert data from the session:

```php
$flasher->clear();
// Removes: alert.config, alert.delete
```
