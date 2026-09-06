<?php

namespace RealRashid\SweetAlert\Builders;

use RealRashid\SweetAlert\Enums\AlertType;

/**
 * ToastBuilder - Fluent builder for toast notifications.
 *
 * Extends AbstractAlertBuilder with toast-specific defaults and convenience
 * methods. Automatically configures toast: true, hides the confirm button,
 * shows the close button, and sets the default toast position.
 *
 * Usage:
 *   Alert::toast('Item added to cart', 'success')
 *       ->position('bottom-right')
 *       ->autoClose(3000)
 *       ->flash();
 */
class ToastBuilder extends AbstractAlertBuilder
{
    /**
     * Set toast-specific default configuration.
     */
    protected function setDefaultConfig(): void
    {
        $this->config = [
            'toast' => true,
            'title' => '',
            'position' => config('sweetalert.toast.position', 'top-end'),
            'showCloseButton' => config('sweetalert.toast.show_close_button', true),
            'showConfirmButton' => config('sweetalert.toast.show_confirm_button', false),
            'timerProgressBar' => config('sweetalert.toast.timer_progress_bar', true),
            'customClass' => [],
        ];

        if ($autoClose = config('sweetalert.toast.auto_close')) {
            $this->config['timer'] = $autoClose;
        }
    }

    /**
     * Set the toast title.
     */
    public function title(string $title): static
    {
        $this->config['title'] = $title;

        return $this->reflash();
    }

    /**
     * Set the toast text description.
     */
    public function text(string $text): static
    {
        $this->config['text'] = $text;

        return $this->reflash();
    }

    /**
     * Set the toast icon type.
     */
    public function icon(string|AlertType $type): static
    {
        $this->config['icon'] = $type instanceof AlertType ? $type->value : $type;

        return $this->reflash();
    }

    /**
     * Set icon to success.
     */
    public function success(): static
    {
        return $this->icon(AlertType::Success);
    }

    /**
     * Set icon to error.
     */
    public function error(): static
    {
        return $this->icon(AlertType::Error);
    }

    /**
     * Set icon to warning.
     */
    public function warning(): static
    {
        return $this->icon(AlertType::Warning);
    }

    /**
     * Set icon to info.
     */
    public function info(): static
    {
        return $this->icon(AlertType::Info);
    }

    /**
     * Set icon to question.
     */
    public function question(): static
    {
        return $this->icon(AlertType::Question);
    }

    /**
     * Apply middleware toast settings for backward compatibility.
     */
    public function middleware(): static
    {
        if (! config('sweetalert.middleware.auto_close')) {
            $this->removeTimer();
        } else {
            unset($this->config['timer']);
            $this->config['timer'] = config('sweetalert.middleware.timer', 6000);
        }

        $this->config['position'] = config('sweetalert.middleware.toast_position', 'top-end');
        $this->config['showCloseButton'] = config('sweetalert.middleware.toast_close_button', true);

        $this->flash();

        return $this;
    }
}
