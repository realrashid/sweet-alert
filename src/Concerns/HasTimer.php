<?php

namespace RealRashid\SweetAlert\Concerns;

/**
 * Trait HasTimer - Provides timer-related configuration methods.
 *
 * This trait is composed into the AlertBuilder and ToastBuilder classes
 * to provide fluent methods for configuring the auto-close timer,
 * progress bar, and persistence behavior of alerts.
 */
trait HasTimer
{
    /**
     * Set the auto-close timer in milliseconds.
     */
    public function timer(int $milliseconds): static
    {
        $this->config['timer'] = $milliseconds;

        return $this->reflash();
    }

    /**
     * Alias for timer() - set auto-close timer.
     */
    public function autoClose(int $milliseconds = 5000): static
    {
        return $this->timer($milliseconds);
    }

    /**
     * Show a progress bar at the bottom of the popup during the timer countdown.
     */
    public function timerProgressBar(bool $enabled = true): static
    {
        $this->config['timerProgressBar'] = $enabled;

        return $this->reflash();
    }

    /**
     * Make the alert persistent - disable auto-close, escape key, and outside click dismiss.
     */
    public function persistent(bool $showConfirmBtn = true, bool $showCloseBtn = false): static
    {
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;

        unset($this->config['timer']);

        if ($showConfirmBtn) {
            $this->config['showConfirmButton'] = true;
        }

        if ($showCloseBtn) {
            $this->config['showCloseButton'] = true;
        }

        return $this->reflash();
    }

    /**
     * Remove the timer from the configuration.
     */
    protected function removeTimer(): void
    {
        unset($this->config['timer']);
    }

    /**
     * Prevent keydown events from propagating to the document.
     * Useful when using SweetAlert2 alongside Bootstrap modals.
     */
    public function stopKeydownPropagation(bool $enabled = true): static
    {
        $this->config['stopKeydownPropagation'] = $enabled;

        return $this->reflash();
    }

    /**
     * Use capture phase for keydown listener (Bootstrap modal compatibility).
     * When true, pressing Esc closes only SweetAlert2 and not other modal layers.
     */
    public function keydownListenerCapture(bool $enabled = true): static
    {
        $this->config['keydownListenerCapture'] = $enabled;

        return $this->reflash();
    }
}
