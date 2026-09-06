<?php

namespace RealRashid\SweetAlert\Concerns;

/**
 * Trait HasAnimation - Provides animation-related configuration methods.
 *
 * This trait enables developers to configure the show/hide animation
 * classes for alert modals, including Animate.css integration.
 */
trait HasAnimation
{
    /**
     * Set custom animation classes for showing and hiding the popup.
     *
     * Uses Animate.css class names.
     */
    public function animation(string $showAnimation, string $hideAnimation): static
    {
        $this->config['showClass'] = [
            'popup' => "animate__animated {$showAnimation}",
        ];

        $this->config['hideClass'] = [
            'popup' => "animate__animated {$hideAnimation}",
        ];

        return $this->reflash();
    }

    /**
     * Disable all animations on the popup.
     */
    public function disableAnimation(): static
    {
        $this->config['animation'] = false;

        return $this->reflash();
    }

    /**
     * Set the show class configuration.
     */
    public function showClass(array $classes): static
    {
        $this->config['showClass'] = $classes;

        return $this->reflash();
    }

    /**
     * Set the hide class configuration.
     */
    public function hideClass(array $classes): static
    {
        $this->config['hideClass'] = $classes;

        return $this->reflash();
    }
}
