# npm / Asset Bundling

By default, the package loads SweetAlert2 from a public CDN with zero configuration. If you're already using **Vite** or **Laravel Mix** and want SweetAlert2 bundled with your own assets, switch to `npm` mode.

## CDN (Default)

Out of the box, the `@sweetAlert` Blade directive injects SweetAlert2 from jsdelivr:

```php
// config/sweetalert.php
'js' => [
    'source'       => 'cdn',
    'version'      => '11',
    'cdn_provider' => 'jsdelivr',
],
```

No setup needed — just add `@sweetAlert` to your layout and you're done.

### Changing the CDN Provider

| Provider | Environment variable | Example URL |
|---|---|---|
| `jsdelivr` (default) | `SWEET_ALERT_CDN_PROVIDER=jsdelivr` | `cdn.jsdelivr.net/npm/sweetalert2@11/...` |
| `unpkg` | `SWEET_ALERT_CDN_PROVIDER=unpkg` | `unpkg.com/sweetalert2@11/...` |
| `cdnjs` | `SWEET_ALERT_CDN_PROVIDER=cdnjs` | `cdnjs.cloudflare.com/ajax/libs/sweetalert2/...` |
| `custom` | `SWEET_ALERT_CDN_PROVIDER=custom` | Set `SWEET_ALERT_CDN_JS` to your URL |

### Pin a Specific Version

```env
SWEET_ALERT_VERSION=11.14.1
```

## npm / Vite

To bundle SweetAlert2 yourself:

### 1. Install SweetAlert2

```bash
npm install sweetalert2
```

### 2. Import in your JS entry file

```js
// resources/js/app.js
import Swal from 'sweetalert2';
window.Swal = Swal; // Required — the package expects a global Swal
```

### 3. Import the package's integration script

After publishing package assets (`php artisan alert:publish --assets`), import the helper module:

```js
// resources/js/app.js
import Swal from 'sweetalert2';
window.Swal = Swal;

import './../../vendor/realrashid/sweet-alert/resources/js/sweet-alert.js';
// or after publishing:
// import './sweet-alert/sweet-alert.js';
```

### 4. Tell the package not to inject its own `<script>` tag

```env
SWEET_ALERT_JS_SOURCE=npm
SWEET_ALERT_NEVER_LOAD_JS=true
```

Or in `config/sweetalert.php`:

```php
'js' => [
    'source' => 'npm',
],
'never_load_js' => true,
```

::: tip CSS
When `never_load_js` is `true`, the package also skips injecting the SweetAlert2 CSS. Import it yourself if needed:

```js
import 'sweetalert2/dist/sweetalert2.min.css';
```
:::

### 5. Build

```bash
npm run build
# or for development
npm run dev
```

## Manual Mode

For complete control — nothing is injected by the package:

```env
SWEET_ALERT_JS_SOURCE=manual
SWEET_ALERT_NEVER_LOAD_JS=true
```

You are responsible for loading SweetAlert2 and `window.Swal` however you choose. The `@sweetAlert` directive will still render the `<script>` block that reads from the session and calls `Swal.fire()`, but it will not inject any `<script src="...">` tag.

## Inertia / SSR

When using Inertia with SSR (server-side rendering), there is no `<body>` in the traditional sense, and the `@sweetAlert` directive should not be used. Instead:

1. Set `never_load_js=true` and `source=npm`
2. Install SweetAlert2 via npm
3. Use the `useSweetAlert` composable/hook for your frontend framework (see [Inertia Integration](/guide/inertia))

## The Integration Script

The `resources/js/sweet-alert.js` file included with the package provides the
**`SweetAlertLaravel` global** — a utility object with `close()`, `confirm()`,
`cancel()`, `deny()`, `isVisible()` and `fire()` helpers for driving an open
popup from your own code.

The Livewire and browser-event listeners are **not** in this file. They are
rendered by the `@sweetAlert` directive instead, because this file is only on
the page once its assets have been published and the directive has decided to
load them — which it does not do on a page with no pending alert, and a
Livewire alert has none by definition.
