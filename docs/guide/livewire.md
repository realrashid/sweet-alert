# Livewire Integration

The package provides first-class support for **Livewire v4** via `SweetAlertTrait`. Because Livewire components update the DOM without full page reloads, session-based alerts are not guaranteed to fire. The trait solves this by dispatching a **browser event** from your component, which the listener rendered by the `@sweetAlert` directive picks up immediately.

::: tip No pending alert, no problem
A Livewire alert never touches the session, so the page it lands on has nothing
flashed and SweetAlert2 has not been loaded. The directive's listener fetches it
the moment an alert arrives, so nothing extra is needed — but the `@sweetAlert`
directive does have to be in your layout.
:::

## Requirements

- Livewire v4+
- `@sweetAlert` directive in your layout (already required for session-based alerts)

## Installation

No extra installation steps — `SweetAlertTrait` is included with the package. Just add it to your component.

## Usage

Add `SweetAlertTrait` to any Livewire component:

```php
use Livewire\Component;
use RealRashid\SweetAlert\Concerns\SweetAlertTrait;

class CreatePost extends Component
{
    use SweetAlertTrait;

    public function save(): void
    {
        // ... save logic ...

        $this->dispatchAlert(
            $this->sweetAlert()
                ->success('Saved!', 'Your post has been created.')
        );
    }
}
```

## Builder Methods

The trait provides three builder resolvers that return a fresh, unconfigured builder ready for fluent chaining:

| Method | Returns | Use for |
|---|---|---|
| `$this->sweetAlert()` | `AlertBuilder` | Modal alerts |
| `$this->sweetToast()` | `ToastBuilder` | Toast notifications |
| `$this->sweetInput()` | `InputBuilder` | Input dialogs |

And three dispatch methods that serialise the configured builder and fire it as a browser event:

| Method | Description |
|---|---|
| `$this->dispatchAlert(AlertBuilder $builder)` | Dispatch a modal alert |
| `$this->dispatchToast(ToastBuilder $builder)` | Dispatch a toast notification |
| `$this->dispatchInput(InputBuilder $builder)` | Dispatch an input dialog |

## Examples

### Success Toast After Save

```php
public function save(): void
{
    $this->post->save();

    $this->dispatchToast(
        $this->sweetToast()
            ->title('Changes saved!')
            ->success()
            ->autoClose(3000)
            ->position('bottom-end')
    );
}
```

### Warning Modal with Confirm Button

```php
public function delete(): void
{
    $this->post->delete();

    $this->dispatchAlert(
        $this->sweetAlert()
            ->title('Deleted')
            ->warning()
            ->text('The post has been moved to trash.')
            ->confirmButton('OK')
    );
}
```

### Error on Failure

```php
public function process(): void
{
    try {
        // ... business logic ...
        $this->dispatchAlert(
            $this->sweetAlert()->success('Done!', 'Processing complete.')
        );
    } catch (\Exception $e) {
        $this->dispatchAlert(
            $this->sweetAlert()->error('Failed', $e->getMessage())
        );
    }
}
```

## How It Works

1. `dispatchAlert()` / `dispatchToast()` / `dispatchInput()` call `$this->dispatch('sweetalert', config: [...])` — a Livewire v4 browser event.
2. The `@sweetAlert` directive renders a listener that registers itself during the `livewire:init` lifecycle hook.
3. When the event fires, the listener loads SweetAlert2 if the page does not already have it, then shows the alert — no session involved.

## Combining with Session Alerts

You can mix both approaches. Use `->flash()` for alerts that must survive a redirect, and `dispatchAlert()` for in-component interactions that don't redirect:

```php
public function update(): void
{
    $this->authorize('update', $this->post);

    $this->post->update($this->form);

    if ($this->shouldRedirect) {
        // Flash to session — survives the redirect
        $this->sweetAlert()->success('Updated!')->flash();
        return redirect()->route('posts.index');
    }

    // No redirect — dispatch as browser event
    $this->dispatchAlert(
        $this->sweetAlert()->success('Updated!', 'Your changes are live.')
    );
}
```

::: tip
`dispatchAlert()` does **not** call `->flash()`. The builder config is dispatched directly to the browser. `->flash()` stores data in the session for the *next* request — use it before redirects only.
:::
