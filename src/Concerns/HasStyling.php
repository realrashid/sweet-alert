<?php

namespace RealRashid\SweetAlert\Concerns;

/**
 * Trait HasStyling - Provides visual styling configuration methods.
 *
 * This trait enables developers to customize the visual appearance
 * of alerts including width, padding, background, color, custom CSS
 * classes, and icon customization.
 */
trait HasStyling
{
    /**
     * Set the modal window width (e.g., '32rem', '500px', '50%').
     */
    public function width(string $width = '32rem'): static
    {
        $this->config['width'] = $width;

        return $this->reflash();
    }

    /**
     * Set the modal window padding.
     */
    public function padding(string $padding = '1.25rem'): static
    {
        $this->config['padding'] = $padding;

        return $this->reflash();
    }

    /**
     * Set the modal window background color.
     */
    public function background(string $color = '#fff'): static
    {
        $this->config['background'] = $color;

        return $this->reflash();
    }

    /**
     * Set the modal text color.
     */
    public function color(string $color): static
    {
        $this->config['color'] = $color;

        return $this->reflash();
    }

    /**
     * Set whether to automatically set height to auto.
     */
    public function heightAuto(bool $enabled = true): static
    {
        $this->config['heightAuto'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set custom CSS classes for various parts of the modal.
     *
     * Available keys: container, popup, header, title, closeButton,
     * icon, image, content, input, actions, confirmButton, cancelButton, footer
     */
    public function customClass(array $classes): static
    {
        $existing = $this->config['customClass'] ?? [];
        $this->config['customClass'] = array_merge($existing, $classes);

        return $this->reflash();
    }

    /**
     * Set a custom icon using HTML (e.g., Font Awesome icons).
     */
    public function iconHtml(string $html): static
    {
        $this->config['iconHtml'] = $html;

        return $this->reflash();
    }

    /**
     * Set the icon color.
     */
    public function iconColor(string $color): static
    {
        $this->config['iconColor'] = $color;

        return $this->reflash();
    }

    /**
     * Add a custom image to the alert.
     */
    public function imageUrl(string $url, ?int $width = null, ?int $height = null, ?string $alt = null): static
    {
        $this->config['imageUrl'] = $url;

        if ($width !== null) {
            $this->config['imageWidth'] = $width;
        }

        if ($height !== null) {
            $this->config['imageHeight'] = $height;
        }

        $this->config['imageAlt'] = $alt ?? $this->config['title'] ?? '';

        return $this->reflash();
    }

    /**
     * Add an image with full configuration.
     */
    public function addImage(string $url, int $width = 400, int $height = 200, ?string $alt = null): static
    {
        $this->config['imageUrl'] = $url;
        $this->config['imageWidth'] = $width;
        $this->config['imageHeight'] = $height;
        $this->config['imageAlt'] = $alt ?? $this->config['title'] ?? '';

        return $this->reflash();
    }

    /**
     * Add footer HTML content to the alert.
     */
    public function footer(string $html): static
    {
        $this->config['footer'] = $html;

        return $this->reflash();
    }

    /**
     * Set the grow direction for the popup.
     */
    public function grow(string $direction = 'false'): static
    {
        $this->config['grow'] = $direction;

        return $this->reflash();
    }

    /**
     * Set the backdrop configuration.
     */
    public function backdrop(mixed $backdrop = true): static
    {
        $this->config['backdrop'] = $backdrop;

        return $this->reflash();
    }

    /**
     * Allow or disallow the ESC key to close the modal.
     */
    public function allowEscapeKey(bool $allow = true): static
    {
        $this->config['allowEscapeKey'] = $allow;

        return $this->reflash();
    }

    /**
     * Allow or disallow clicking outside the modal to close it.
     */
    public function allowOutsideClick(bool $allow = true): static
    {
        $this->config['allowOutsideClick'] = $allow;

        return $this->reflash();
    }

    /**
     * Alias for stopKeydownPropagation() (defined in HasTimer).
     */
    public function stopPropagation(bool $enabled = true): static
    {
        return $this->stopKeydownPropagation($enabled);
    }

    /**
     * Set the popup title as plain text (XSS-safe alternative to title()).
     */
    public function titleText(string $text): static
    {
        $this->config['titleText'] = $text;

        return $this->reflash();
    }

    /**
     * Set the container element the popup is appended to.
     * Accepts a CSS selector string (e.g. '#app', 'body').
     */
    public function target(string $selector): static
    {
        $this->config['target'] = $selector;

        return $this->reflash();
    }

    /**
     * Push the popup to the browser's Top Layer (above all other elements).
     */
    public function topLayer(bool $enabled = true): static
    {
        $this->config['topLayer'] = $enabled;

        return $this->reflash();
    }

    /**
     * Disable body padding adjustment when the page scrollbar is hidden on open.
     */
    public function scrollbarPadding(bool $enabled = true): static
    {
        $this->config['scrollbarPadding'] = $enabled;

        return $this->reflash();
    }

    /**
     * Make the popup window draggable by the user.
     */
    public function draggable(bool $enabled = true): static
    {
        $this->config['draggable'] = $enabled;

        return $this->reflash();
    }

    /**
     * Set custom HTML content for the loading spinner.
     */
    public function loaderHtml(string $html): static
    {
        $this->config['loaderHtml'] = $html;

        return $this->reflash();
    }

    /**
     * Set custom HTML for the close button (default '&times;').
     */
    public function closeButtonHtml(string $html): static
    {
        $this->config['closeButtonHtml'] = $html;

        return $this->reflash();
    }
}
