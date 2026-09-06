<?php

namespace RealRashid\SweetAlert\Concerns;

/**
 * Trait HasButtons - Provides button configuration methods.
 *
 * This trait offers fluent methods for configuring the three SweetAlert2
 * button types: confirm, cancel, and deny. It also provides methods for
 * controlling button order, styling, and focus behavior.
 */
trait HasButtons
{
    /**
     * Show the confirm button with custom text and color.
     */
    public function showConfirmButton(string $btnText = 'OK', string $btnColor = '#3085d6'): static
    {
        $this->config['showConfirmButton'] = true;
        $this->config['confirmButtonText'] = $btnText;
        $this->config['confirmButtonColor'] = $btnColor;

        $this->removeTimer();

        return $this->reflash();
    }

    /**
     * Set the confirm button text and color (alias with shorter name).
     */
    public function confirmButton(string $text = 'OK', string $color = '#3085d6'): static
    {
        return $this->showConfirmButton($text, $color);
    }

    /**
     * Show the cancel button with custom text and color.
     */
    public function showCancelButton(string $btnText = 'Cancel', string $btnColor = '#aaa'): static
    {
        $this->config['showCancelButton'] = true;
        $this->config['cancelButtonText'] = $btnText;
        $this->config['cancelButtonColor'] = $btnColor;

        $this->removeTimer();

        return $this->reflash();
    }

    /**
     * Set the cancel button text and color (alias with shorter name).
     */
    public function cancelButton(string $text = 'Cancel', string $color = '#aaa'): static
    {
        return $this->showCancelButton($text, $color);
    }

    /**
     * Show the deny button with custom text and color.
     */
    public function showDenyButton(string $btnText = 'Deny', string $btnColor = '#dd6b55'): static
    {
        $this->config['showDenyButton'] = true;
        $this->config['denyButtonText'] = $btnText;
        $this->config['denyButtonColor'] = $btnColor;

        $this->removeTimer();

        return $this->reflash();
    }

    /**
     * Set the deny button text and color (alias with shorter name).
     */
    public function denyButton(string $text = 'Deny', string $color = '#dd6b55'): static
    {
        return $this->showDenyButton($text, $color);
    }

    /**
     * Show the close button.
     */
    public function showCloseButton(string $closeButtonAriaLabel = 'Close this dialog'): static
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        return $this->reflash();
    }

    /**
     * Set the close button ARIA label without toggling visibility.
     */
    public function closeButtonAriaLabel(string $label): static
    {
        $this->config['closeButtonAriaLabel'] = $label;

        return $this->reflash();
    }

    /**
     * Hide the close button.
     */
    public function hideCloseButton(): static
    {
        $this->config['showCloseButton'] = false;

        return $this->reflash();
    }

    /**
     * Reverse the order of confirm and cancel buttons.
     */
    public function reverseButtons(): static
    {
        $this->config['reverseButtons'] = true;

        return $this->reflash();
    }

    /**
     * Apply default styling to buttons. Set to false to use your own classes.
     */
    public function buttonsStyling(bool $enabled = true): static
    {
        $this->config['buttonsStyling'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set focus on the confirm button by default.
     */
    public function focusConfirm(bool $focus = true): static
    {
        $this->config['focusConfirm'] = $focus;

        unset($this->config['focusCancel']);

        return $this->reflash();
    }

    /**
     * Set focus on the cancel button by default.
     */
    public function focusCancel(bool $focus = true): static
    {
        $this->config['focusCancel'] = $focus;

        unset($this->config['focusConfirm']);

        return $this->reflash();
    }

    /**
     * Show a loader on the confirm button (useful for async operations).
     */
    public function showLoaderOnConfirm(bool $enabled = true): static
    {
        $this->config['showLoaderOnConfirm'] = $enabled;

        return $this->reflash();
    }

    /**
     * Show a loader on the deny button (useful for async operations).
     */
    public function showLoaderOnDeny(bool $enabled = true): static
    {
        $this->config['showLoaderOnDeny'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set focus on the deny button by default.
     */
    public function focusDeny(bool $focus = true): static
    {
        $this->config['focusDeny'] = $focus;

        return $this->reflash();
    }

    /**
     * Return focus to the element that triggered the modal after it closes.
     */
    public function returnFocus(bool $enabled = true): static
    {
        $this->config['returnFocus'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set ARIA labels for all three buttons at once, or individually.
     */
    public function confirmButtonAriaLabel(string $label): static
    {
        $this->config['confirmButtonAriaLabel'] = $label;

        return $this->reflash();
    }

    public function denyButtonAriaLabel(string $label): static
    {
        $this->config['denyButtonAriaLabel'] = $label;

        return $this->reflash();
    }

    public function cancelButtonAriaLabel(string $label): static
    {
        $this->config['cancelButtonAriaLabel'] = $label;

        return $this->reflash();
    }

    /**
     * Return the input value as result.value when the Deny button is clicked.
     */
    public function returnInputValueOnDeny(bool $enabled = true): static
    {
        $this->config['returnInputValueOnDeny'] = $enabled;

        return $this->reflash();
    }
}
