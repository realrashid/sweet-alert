# Buttons

SweetAlert2 supports three button types in a single dialog: confirm, cancel, and deny. The `HasButtons` trait provides fluent methods for configuring each button's visibility, text, color, focus behavior, and order.

## Confirm Button

The confirm button is the primary action button. It's shown by default in alerts and hidden by default in toasts.

### Show with Custom Text and Color

```php
Alert::title('Success!')
    ->showConfirmButton('Got it!', '#28a745')
    ->flash();
```

Calling `showConfirmButton()` also removes any auto-close timer, since the dialog requires user interaction.

### Short Alias: `confirmButton()`

```php
Alert::title('Done')
    ->confirmButton('OK', '#3085d6')
    ->flash();
```

`confirmButton()` is an alias for `showConfirmButton()` — use whichever reads better in your context.

## Cancel Button

The cancel button allows users to dismiss the dialog without taking the primary action. It's hidden by default.

```php
Alert::title('Are you sure?')
    ->warning()
    ->showCancelButton('No, go back', '#6c757d')
    ->confirmButton('Yes, proceed', '#28a745')
    ->flash();
```

### Short Alias: `cancelButton()`

```php
Alert::title('Confirm?')
    ->cancelButton('Nope')
    ->flash();
```

## Deny Button

The deny button provides a third option — distinct from both confirm and cancel. This is useful for scenarios like "Save / Don't Save / Cancel" or "Accept / Reject / Later".

```php
Alert::title('What should we do?')
    ->question()
    ->confirmButton('Save', '#28a745')
    ->denyButton("Don't save", '#dc3545')
    ->cancelButton('Cancel', '#6c757d')
    ->flash();
```

### Short Alias: `denyButton()`

```php
Alert::title('Action required')
    ->denyButton('Skip', '#ffc107')
    ->flash();
```

## Close Button

The close button appears as an "X" in the top-right corner of the dialog.

```php
Alert::title('Info')
    ->info()
    ->showCloseButton()
    ->flash();
```

### Custom Aria Label

For accessibility, you can customize the aria label:

```php
Alert::title('Notification')
    ->showCloseButton('Close this notification')
    ->flash();
```

### Hide Close Button

```php
Alert::title('Required action')
    ->hideCloseButton()
    ->flash();
```

### Standalone Aria Label

If you want to set the close button's aria-label without toggling visibility, use `closeButtonAriaLabel()`:

```php
Alert::title('Notification')
    ->showCloseButton()
    ->closeButtonAriaLabel('Dismiss notification')
    ->flash();
```

## Reverse Button Order

By default, SweetAlert2 places the confirm button on the left and the cancel button on the right. Reverse this order:

```php
Alert::title('Are you sure?')
    ->warning()
    ->showCancelButton()
    ->reverseButtons()
    ->flash();
```

## Button Styling

Control whether SweetAlert2 applies its default button styling. Set to `false` when using custom CSS classes:

```php
Alert::title('Custom styled')
    ->buttonsStyling(false)
    ->customClass([
        'confirmButton' => 'btn btn-primary',
        'cancelButton' => 'btn btn-secondary',
    ])
    ->flash();
```

## Focus Control

### Focus Confirm

Set focus to the confirm button when the dialog opens (this is the default behavior):

```php
Alert::title('Proceed?')
    ->focusConfirm()
    ->flash();
```

### Focus Cancel

Move the initial focus to the cancel button instead — useful for destructive actions where you want to make the safe choice the easiest:

```php
Alert::title('Delete everything?')
    ->warning()
    ->showCancelButton('Keep it', '#28a745')
    ->focusCancel()
    ->flash();
```

## Show Loader on Confirm

Display a loading spinner on the confirm button during asynchronous operations. This pairs well with `preConfirmRoute()`:

```php
Alert::input('Enter code', 'text')
    ->showLoaderOnConfirm()
    ->preConfirmRoute(route('validate-code'))
    ->flash();
```

The loader replaces the button text while the pre-confirm AJAX request is in progress, giving the user clear visual feedback that something is happening.

## Complete Three-Button Example

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::title('Unsaved Changes')
    ->question()
    ->text('You have unsaved changes. What would you like to do?')
    ->confirmButton('Save Changes', '#28a745')
    ->denyButton('Discard Changes', '#dc3545')
    ->cancelButton('Keep Editing', '#6c757d')
    ->showCloseButton()
    ->reverseButtons()
    ->flash();
```

## Show Loader on Deny

Display a loading spinner on the deny button during async operations — mirrors `showLoaderOnConfirm` for the deny action:

```php
Alert::title('Review Request')
    ->question()
    ->denyButton('Reject')
    ->showLoaderOnDeny()
    ->flash();
```

## Focus Deny

Set the initial focus to the deny button when the dialog opens:

```php
Alert::title('Action Required')
    ->question()
    ->confirmButton('Approve')
    ->denyButton('Reject')
    ->focusDeny()
    ->flash();
```

## Return Focus

Return focus to the element that triggered the dialog after it closes. Useful for accessibility when dialogs are opened from keyboard-navigable controls:

```php
Alert::confirm('Delete item?')
    ->returnFocus()
    ->flash();
```

## Return Input Value on Deny

When using an input dialog with a deny button, return the input's current value even when deny is clicked (SweetAlert2's `returnInputValueOnDeny`):

```php
Alert::input('Enter notes')
    ->denyButton('Save as Draft')
    ->returnInputValueOnDeny()
    ->flash();
```

## ARIA Labels

Improve accessibility by providing explicit ARIA labels for each button:

```php
Alert::confirm('Log out?')
    ->confirmButtonAriaLabel('Confirm log out')
    ->cancelButtonAriaLabel('Cancel and stay logged in')
    ->flash();
```

All three buttons are supported:

```php
Alert::title('Review')
    ->question()
    ->confirmButtonAriaLabel('Approve this item')
    ->denyButtonAriaLabel('Reject this item')
    ->cancelButtonAriaLabel('Decide later')
    ->flash();
```
