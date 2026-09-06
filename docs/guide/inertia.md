# Inertia Integration

An Inertia page has no Blade view for the alert to render into, so the package
shares the configuration as a page prop and a small composable shows it.

## Requirements

- `inertiajs/inertia-laravel` v2 or v3
- The `@sweetAlert` directive in your root Blade template — that is what puts the
  listener on the page, and it loads SweetAlert2 on demand when an alert arrives

## Installation

### 1. Register the Middleware

Register `ShareSweetAlertWithInertia` **after** Inertia's own middleware.

**Laravel 11+ (`bootstrap/app.php`):**

```php
use RealRashid\SweetAlert\Http\Middleware\ShareSweetAlertWithInertia;

->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        ShareSweetAlertWithInertia::class,
    ]);
})
```

**Laravel 10 (`app/Http/Kernel.php`):**

```php
protected $middlewareGroups = [
    'web' => [
        // ...
        \Inertia\Middleware\HandleInertiaRequests::class,
        \RealRashid\SweetAlert\Http\Middleware\ShareSweetAlertWithInertia::class,
    ],
];
```

### 2. Add the Client-Side Helper

The middleware puts a pending alert on the page as the `sweetalert` prop. The
composable watches for it and shows it — on the first load and after every
later visit.

#### Vue 3

Call `useSweetAlert()` once in your root `App.vue` or layout component:

```vue
<script setup>
import { useSweetAlert } from 'vendor/realrashid/sweet-alert/resources/js/useSweetAlert';
// or after publishing assets:
// import { useSweetAlert } from '@/sweet-alert/useSweetAlert';

useSweetAlert();
</script>
```

#### React

Call `useSweetAlert()` in your root layout component:

```jsx
import { useSweetAlert } from 'vendor/realrashid/sweet-alert/resources/js/useSweetAlert.jsx';
// or after publishing assets:
// import { useSweetAlert } from '@/sweet-alert/useSweetAlert.jsx';

export default function Layout({ children }) {
    useSweetAlert();
    return <main>{children}</main>;
}
```

## Usage (Controller)

Use the standard `Alert` facade. The middleware handles forwarding the session data to Inertia — no code changes in your controllers:

```php
use RealRashid\SweetAlert\Facades\Alert;

public function store(Request $request): \Inertia\Response
{
    $post = Post::create($request->validated());

    Alert::toast('Post created!', 'success')->flash();

    return to_route('posts.index');
}
```

## Reading Flash Data Manually

If you prefer not to use the composable/hook, you can read the flash prop directly from the Inertia page object.

### Vue 3

```vue
<script setup>
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();

router.on('navigate', () => {
    const { sweetalert } = page.props;
    if (sweetalert) {
        Swal.fire(sweetalert);
    }
});
</script>
```

### React

```jsx
import { usePage, router } from '@inertiajs/react';
import { useEffect } from 'react';

export default function Layout({ children }) {
    const { flash } = usePage().props;

    useEffect(() => {
        if (flash?.sweetalert) {
            Swal.fire(flash.sweetalert);
        }
    }, [flash]);

    return <main>{children}</main>;
}
```

## How It Works

1. Your controller calls `Alert::toast('...')->flash()`, which stores the config in `alert.config` in the session.
2. On the next request, `ShareSweetAlertWithInertia::handle()` pulls `alert.config` from the session and calls `Inertia::flash('sweetalert', $config)`.
3. Inertia serialises the prop into the page object.
4. `useSweetAlert()` picks it up and shows it.

It listens to Inertia's own `inertia:success` event rather than watching
`usePage().props`. Watching the prop looks tidier but does not work — the page
object is replaced rather than mutated, so a watcher fires once on the first
load and then stays silent while every later alert is missed. `inertia:success`
is used in preference to `inertia:navigate` because visiting the URL you are
already on does not emit `navigate`, which is exactly what a save-and-stay form
does.

::: tip No extra session keys
The middleware calls `session()->pull()` — it removes the data from the session after reading it, so the alert fires exactly once per `->flash()` call.
:::

## Custom Alert Handler

Override the default `Swal.fire()` call for single alerts:

```vue
<script setup>
import { useSweetAlert } from './useSweetAlert';

useSweetAlert({
    onAlert: (config) => {
        Swal.fire({ ...config, customClass: { popup: 'my-popup' } });
    }
});
</script>
```
