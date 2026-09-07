# Changelog

All notable changes to the Laravel SweetAlert package will be documented in this file.

## v8.0.0 - 2026-09-07

### Added
- **Complete rewrite** from scratch with a modern, fluent builder pattern API
- `image()` and `toToast()`, restored from the released API.
- `when()` and `unless()` on every builder, so a conditional does not break the chain.
- `macro()` on every builder, for registering your own house style once.
- `AlertBuilder` class with full method chaining support
- `ToastBuilder` class with toast-specific defaults
- `InputBuilder` class with all SweetAlert2 input types (text, email, password, select, checkbox, etc.)
- **Deny button support** (`denyButton()`) - third button type previously unavailable
- **Progress steps** for multi-step dialogs
- **Pre-confirm route pattern** for server-side validation via AJAX
- PHP 8.3+ **Enums** for type-safe configuration (`AlertType`, `InputType`, `Position`)
- **Trait-based composition** (`HasTimer`, `HasPosition`, `HasAnimation`, `HasButtons`, `HasStyling`)
- `AlertConfig` immutable value object for configuration serialization
- `AlertFlasher` class for clean session management
- **`@sweetAlert` Blade directive** — the modern way to include SweetAlert2 in your layout (replaces `@include('sweetalert::alert')`)
- **VitePress documentation** (replacing Docsify)
- **Pest PHP** test suite with architecture tests
- **Laravel Pint** code style enforcement
- `alert:install` and `alert:publish` artisan commands
- Simplified configuration file with grouped settings
- Backward compatibility shim layer for smooth migration from v7
- **`data-confirm` guarded actions** — any link or form can ask before it goes
  through, with the method set by `data-confirm-method` and the copy by
  `data-confirm-title`, `data-confirm-text`, `data-confirm-icon`,
  `data-confirm-button` and `data-confirm-cancel`. Closes #183, which asked for
  a confirmation dialog for updates rather than only deletes. SweetAlert2 is
  fetched on the first click, so a page with only a guarded link ships nothing
  until someone uses it, and if it cannot be fetched the browser's own
  `confirm()` is used rather than letting the action through unasked. Turn the
  listener off with `confirm.auto`.
- **`submitTo()`** on every builder — sends the answer to a route. Until now an
  alert could only ask a question: the dialog closed and whatever was chosen or
  typed was gone. On confirm the package submits a form with the CSRF token, the
  method you asked for, and an input alert's value under the field you name.
- **`topLayer()`** — moves a popup into the browser's top layer, above every
  element on the page regardless of `z-index`. This is the answer to a toast
  that renders behind a navbar or a modal (#158): it was rendering all along,
  just underneath something.
- **Laravel Boost guidelines and skill, rewritten for v8.** The v7 files
  described an API that no longer exists, so an assistant following them wrote
  code that silently did nothing. They now cover the builder, guarded actions,
  `submitTo()`, Livewire and Inertia, and list the v7 → v8 traps. A test renders
  the guideline the way Boost does and asserts it comes out as prose — the
  unescaped-directive bug that v7.3.1 fixed (#190) can no longer come back.
- **`alert:upgrade` artisan command** — migrates a v7 codebase to the v8 API.
  Reads your code with PHP's tokeniser rather than searching for text, so a
  `->html()` call on an unrelated object, or the old binding name mentioned in a
  comment, is reported rather than edited. `--dry-run` shows the diff and writes
  nothing; `--path` scopes the scan; anything it cannot rewrite safely is listed
  for you to handle. `vendor/` is never touched.

### Fixed
- `data-confirm-delete` now guards a link on every page load. The listener used
  to be rendered only on the request where `confirmDelete()` had been flashed,
  so on any other render the link was an ordinary link and the browser opened
  the URL with a GET — hitting the destroy route with the wrong method, or
  worse. Its defaults now come from the config, and a flashed `confirmDelete()`
  still customises the dialog for that request. Closes #174.
- A guarded action using `data-confirm-method="GET"` appended the CSRF token to
  the URL, where it would sit in browser history, travel in `Referer` headers and
  be written to access logs. `form.method` is lowercased by the DOM, so the guard
  meant to skip the token on a GET never matched. Same fault fixed in `submitTo()`.
- A guarded link with `target="_blank"` submitted in the current tab instead of a
  new one; the target is now carried onto the generated form.
- The `ToSweetAlert` middleware docs said the plain `success`/`error`/… flash
  keys produced toasts. They produce modals; only the `toast_*` keys produce
  toasts. Corrected, and both paths now have tests.
- **Livewire alerts now appear at all.** The bridge that listens for them lived
  in the publishable JS asset, which the directive only loads on a page that
  already has a session alert pending — and a Livewire alert has none by
  definition. Nothing was ever registered, and nothing ever fired. The bridge is
  now emitted by the directive itself and fetches SweetAlert2 on demand. The
  documented plain-JS door (`window.dispatchEvent(new CustomEvent('sweetalert',
  …))`) was dead for the same reason and works now too.
- **Inertia alerts now reach the page.** Three faults stacked: the middleware
  flashed after the response had already resolved its props, so the alert never
  arrived; it shared the session envelope rather than the configuration inside
  it, so anything that did arrive rendered blank; and the client listened for an
  `inertia:flash` event on `props.flash`. The middleware now shares a lazily
  resolved `sweetalert` prop, and the composables read it on `inertia:success` —
  chosen over `inertia:navigate` because visiting the URL you are already on
  emits no `navigate`, which is exactly the save-and-stay case.
- The Livewire trait no longer shows an alert twice. `sweetAlert()`,
  `sweetToast()` and `sweetInput()` returned builders that flashed to the
  session as well as dispatching the browser event, so the same alert appeared
  again on the next full page load. They now return explicitly composed
  builders; call `flash()` yourself if you want the session copy too.
- Chaining after a legacy call no longer loses the chained value. `Alert::success('Title')->html('…')`
  rendered with no body and `Alert::toast('Saved')->success()` rendered with no icon, because the
  core setters did not rewrite the session after the alert had already been flashed.
- `theme()` now actually themes the alert. Two separate causes: the theme stylesheet was appended to
  `<head>`, which puts it *before* the package's own stylesheet when the directive sits at the end of
  the body as documented; and the config shipped a `background` default, which SweetAlert2 applies as
  an inline style that beats every stylesheet. Every alert was white regardless of the theme chosen.
- `width`, `padding` and `background` are no longer sent unless you set them, so the active theme
  controls the popup. Set any of them per alert or in the config to override the theme deliberately.
- `position()`, `width()`, `padding()` and `background()` accept no arguments again, as they did in v7.
- Code style and static analysis run in their own job now. They were part of the
  dependency matrix, so the `prefer-lowest` jobs checked the code against Pint
  v1.0.0 from 2022 and failed on rules that no longer exist. Neither check varies
  with the Laravel version.
- CI ran no `prefer-lowest` job at all — every combination was excluded. The matrix now covers
  Laravel 11, 12 and 13 at both lowest and stable.
- The docs site shipped two VitePress configs; the one VitePress actually loaded had no `base`, so
  every asset 404'd on GitHub Pages. The stale config is gone.
- `.gitignore` only anchored `/node_modules`, so `docs/node_modules` was not ignored.
- `LICENSE.md` is no longer `export-ignore`d, so it ships in the Composer dist archive.
- `phpstan.neon.dist` used `checkMissingIterableValueType`, which PHPStan 2.x rejects outright.

### Changed
- Minimum PHP version requirement raised to **8.3**
- Minimum Laravel version requirement raised to **11.0**
- `Toaster` class replaced by `AlertBuilder` (with shim)
- Configuration file restructured with grouped settings
- Session flash format changed to `AlertConfig` JSON
- Blade view enhanced to support inputs and pre-confirm
- `@include('sweetalert::alert')` still works, but `@sweetAlert` is now the recommended directive

### Removed
- Support for PHP 7.2 - 8.2
- Support for Laravel 5.6 - 10.x
- `symfony/thanks` dev dependency
- Monolithic `Toaster.php` class (replaced by `AlertBuilder`)

### Breaking Changes
- PHP 8.3+ required (was 7.2+)
- Laravel 11+ required (was 5.6+)
- Session key structure changed (backward compat shim handles this)
- Config file structure changed (migration guide provided)
- `html($title, $code, $icon)` is now `html($html)`. **`alert:upgrade` rewrites this** — worth running,
  because PHP ignores the surplus arguments, so an un-migrated call does not error.
- `view($title, $view, $data, $mergeData, $icon)` is now `view($view, $data, $mergeData)`.
  Also handled by `alert:upgrade`.
- A config file published under v7 still pins `background` to `#fff` and will defeat theming until
  those keys are nulled. `alert:upgrade` does this for you.

## v7.3.2 - 2026-03-29
- Laravel 12 compatibility fix for Alert facade (Closes #181)
- Fixed: Corrected `SweetAlertServiceProvider::register()` — the `bind()` call was incorrectly passing a third argument (`ToSweetAlert` class string as `$shared`), causing `Alert::warning` and other facade methods to fail under Laravel 12's stricter container resolution
- Added `@method` PHPDoc annotations to the `Alert` facade for full IDE and static-analysis support

## v7.3.1 - 2026-03-28
- Fixed: Prevent Blade from compiling example route in resources/boost/guidelines/core.blade.php by escaping Blade braces in the example (use @{{ … }}). Fixes #190 — reported by @mohammedterfa.

## v7.3.0 - 2026-03-19
- Added Laravel 13 Support
- Updated bundled SweetAlert2
- Added Laravel Boost AI guidelines and Boost Skill
- Docs Updated

## v7.2.0 - 2024-06-15
- Added Laravel 11 Support
- Upgraded SweetAlert2 to latest version
- Bug Fixes
- Docs Updated

## v7.1.0 - 2023-08-08
- Upgraded SweetAlert2 to latest version
- Bug Fixes
- Docs Updated

## v7.0.0 - 2023-04-20
- Added confirmDelete function
- Added themes feature
- Upgraded SweetAlert2 to latest version
- Bug Fixes
- Docs Updated

## v6.0.0 - 2023-02-15
- Added Laravel 10 Support
- Bug Fixes
- Docs Updated

## v5.1.0 - 2022-05-28
- Added Laravel 9 Support
- Bug Fixes
- Docs Updated

## v5.0.0 - 2022-02-04
- Added Laravel 9 Support
- Bug Fixes
- Upgraded SweetAlert2 to latest version
- Docs Updated
