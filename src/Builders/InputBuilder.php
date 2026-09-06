<?php

namespace RealRashid\SweetAlert\Builders;

use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Exceptions\MissingRequiredParameterException;

/**
 * InputBuilder - Fluent builder for input-type alerts.
 *
 * This builder provides a fluent API for configuring SweetAlert2 input
 * dialogs, supporting all input types (text, email, password, select,
 * checkbox, etc.) with validation, placeholders, and server-side
 * pre-confirm callback support.
 *
 * Usage:
 *   Alert::input('Enter your email', InputType::Email)
 *       ->inputPlaceholder('user@example.com')
 *       ->confirmButton('Submit')
 *       ->flash();
 */
class InputBuilder extends AbstractAlertBuilder
{
    /**
     * Set input-specific default configuration.
     */
    protected function setDefaultConfig(): void
    {
        $this->config = [
            'title' => '',
            'showConfirmButton' => true,
            'showCancelButton' => true,
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Cancel',
            'showCloseButton' => false,
            'allowOutsideClick' => false,
            'customClass' => [],
        ];
    }

    // ──────────────────────────────────────────────
    // Core Alert Configuration
    // ──────────────────────────────────────────────

    /**
     * Set the input alert title.
     */
    public function title(string $title): static
    {
        $this->config['title'] = $title;

        return $this->reflash();
    }

    /**
     * Set the input alert text description.
     */
    public function text(string $text): static
    {
        $this->config['text'] = $text;

        return $this->reflash();
    }

    /**
     * Set the alert icon type.
     */
    public function icon(string|AlertType $type): static
    {
        $this->config['icon'] = $type instanceof AlertType ? $type->value : $type;

        return $this->reflash();
    }

    // ──────────────────────────────────────────────
    // Input-Specific Configuration
    // ──────────────────────────────────────────────

    /**
     * Set the input type.
     */
    public function inputType(string|InputType $type): static
    {
        $this->config['input'] = $type instanceof InputType ? $type->value : $type;

        return $this->reflash();
    }

    /**
     * Set the input placeholder text.
     */
    public function inputPlaceholder(string $text): static
    {
        $this->config['inputPlaceholder'] = $text;

        return $this->reflash();
    }

    /**
     * Set the default input value.
     */
    public function inputValue(string $value): static
    {
        $this->config['inputValue'] = $value;

        return $this->reflash();
    }

    /**
     * Set the options for select/radio input types.
     */
    public function inputOptions(array $options): static
    {
        $this->config['inputOptions'] = $options;

        return $this->reflash();
    }

    /**
     * Set HTML attributes on the input element.
     */
    public function inputAttributes(array $attributes): static
    {
        $this->config['inputAttributes'] = $attributes;

        return $this->reflash();
    }

    /**
     * Set the input validation error message (client-side).
     */
    public function inputValidator(string $message): static
    {
        $this->config['inputValidatorMessage'] = $message;

        return $this->reflash();
    }

    /**
     * Set the label text for the input.
     */
    public function inputLabel(string $label): static
    {
        $this->config['inputLabel'] = $label;

        return $this->reflash();
    }

    /**
     * Auto-focus the input when the alert is shown.
     */
    public function inputAutoFocus(bool $enabled = true): static
    {
        $this->config['inputAutoFocus'] = $enabled;

        return $this->reflash();
    }

    /**
     * Auto-trim whitespace from the input value.
     */
    public function inputAutoTrim(bool $enabled = true): static
    {
        $this->config['inputAutoTrim'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set a pre-confirm route for server-side validation.
     */
    public function preConfirmRoute(string $route): static
    {
        $this->config['preConfirmRoute'] = $route;

        return $this->reflash();
    }

    /**
     * Flash the input config to session, validating required fields.
     */
    public function flash(string $type = 'config'): static
    {
        $input = $this->config['input'] ?? null;

        if (in_array($input, ['select', 'radio']) && empty($this->config['inputOptions'])) {
            throw MissingRequiredParameterException::inputOptionsRequired();
        }

        return parent::flash($type);
    }

    /**
     * Set the input CSS class.
     */
    public function inputClass(string $class): static
    {
        if (! isset($this->config['customClass'])) {
            $this->config['customClass'] = [];
        }
        $this->config['customClass']['input'] = $class;

        return $this->reflash();
    }
}
