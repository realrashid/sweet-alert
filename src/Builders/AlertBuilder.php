<?php

namespace RealRashid\SweetAlert\Builders;

use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;

/**
 * AlertBuilder - The main fluent builder for constructing SweetAlert2 configurations.
 *
 * This class provides a fluent, chainable API for building alert configurations
 * that map directly to SweetAlert2 JavaScript options. Every method returns $this,
 * enabling natural method chaining that mirrors Laravel Eloquent's expressive API.
 *
 * Usage:
 *   Alert::title('Operation Complete')
 *       ->success()
 *       ->text('Your data has been saved.')
 *       ->showConfirmButton('Great!')
 *       ->timer(5000)
 *       ->flash();
 */
class AlertBuilder extends AbstractAlertBuilder
{
    /**
     * Set the default configuration from config values.
     */
    protected function setDefaultConfig(): void
    {
        $this->config = [
            'title' => '',
            'text' => '',
            'showConfirmButton' => config('sweetalert.show_confirm_button', true),
            'showCloseButton' => config('sweetalert.show_close_button', false),
            'confirmButtonText' => __(config('sweetalert.button_text.confirm', 'OK')),
            'cancelButtonText' => __(config('sweetalert.button_text.cancel', 'Cancel')),
            'denyButtonText' => __(config('sweetalert.button_text.deny', 'Deny')),
            'timerProgressBar' => config('sweetalert.toast.timer_progress_bar', false),
            'customClass' => [],
        ];

        if ($timer = config('sweetalert.timer')) {
            $this->config['timer'] = $timer;
        }

        if ($background = config('sweetalert.background')) {
            $this->config['background'] = $background;
        }

        if ($width = config('sweetalert.width')) {
            $this->config['width'] = $width;
        }

        if ($padding = config('sweetalert.padding')) {
            $this->config['padding'] = $padding;
        }
    }

    // ──────────────────────────────────────────────
    // Core Alert Configuration
    // ──────────────────────────────────────────────

    /**
     * Set the alert title.
     */
    public function title(string $title): static
    {
        $this->config['title'] = $title;

        return $this->reflash();
    }

    /**
     * Set the alert text description.
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

    /**
     * Set the icon to 'success'.
     */
    public function success(string $title = '', string $text = ''): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->icon(AlertType::Success);

        if ($title !== '') {
            $this->title($title);
        }

        if ($text !== '') {
            $this->text($text);
        }

        return $this->flashIfLegacy($title);
    }

    /**
     * Set the icon to 'error'.
     */
    public function error(string $title = '', string $text = ''): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->icon(AlertType::Error);

        if ($title !== '') {
            $this->title($title);
        }

        if ($text !== '') {
            $this->text($text);
        }

        return $this->flashIfLegacy($title);
    }

    /**
     * Set the icon to 'warning'.
     */
    public function warning(string $title = '', string $text = ''): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->icon(AlertType::Warning);

        if ($title !== '') {
            $this->title($title);
        }

        if ($text !== '') {
            $this->text($text);
        }

        return $this->flashIfLegacy($title);
    }

    /**
     * Set the icon to 'info'.
     */
    public function info(string $title = '', string $text = ''): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->icon(AlertType::Info);

        if ($title !== '') {
            $this->title($title);
        }

        if ($text !== '') {
            $this->text($text);
        }

        return $this->flashIfLegacy($title);
    }

    /**
     * Set the icon to 'question' and auto-show cancel button.
     */
    public function question(string $title = '', string $text = ''): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->icon(AlertType::Question);
        $this->showCancelButton();

        if ($title !== '') {
            $this->title($title);
        }

        if ($text !== '') {
            $this->text($text);
        }

        return $this->flashIfLegacy($title);
    }

    /**
     * Set HTML content instead of text.
     */
    public function html(string $html): static
    {
        $this->config['html'] = $html;
        unset($this->config['text']);

        return $this->reflash();
    }

    /**
     * Convert text content to HTML content.
     */
    public function toHtml(): static
    {
        if (isset($this->config['text'])) {
            $this->config['html'] = $this->config['text'];
            unset($this->config['text']);
        }

        return $this->reflash();
    }

    /**
     * Render a Blade view as the HTML content.
     */
    public function view(string $view, array $data = [], array $mergeData = []): static
    {
        $html = view($view, $data, $mergeData)->render();

        return $this->html($html);
    }

    // ──────────────────────────────────────────────
    // Convenience Factory Methods
    // ──────────────────────────────────────────────

    /**
     * Create a toast notification and return a ToastBuilder.
     */
    public function toast(string $title = '', ?string $icon = null): ToastBuilder
    {
        $toastBuilder = app(ToastBuilder::class);
        $toastBuilder->title($title);

        if ($icon) {
            $toastBuilder->icon($icon);
        }

        // Alert::toast('Saved', 'success') displayed a toast in every released
        // version. Use Alert::make()->toast(...) to compose one instead.
        if (! $this->explicit && $title !== '') {
            $toastBuilder->flash();
        }

        return $toastBuilder;
    }

    /**
     * Create an input alert and return an InputBuilder.
     */
    public function input(string $title = '', string|InputType $inputType = InputType::Text): InputBuilder
    {
        $inputBuilder = app(InputBuilder::class);
        $inputBuilder->title($title);
        $inputBuilder->inputType($inputType);

        return $inputBuilder;
    }

    /**
     * Create a confirm dialog with three-button support.
     */
    public function confirm(string $title = '', ?string $text = null): static
    {
        $this->title($title);

        if ($text !== null) {
            $this->text($text);
        }

        $this->config['showCancelButton'] = config('sweetalert.confirm.show_cancel_button', true);
        $this->config['confirmButtonText'] = config('sweetalert.confirm.confirm_button_text', 'Yes');
        $this->config['confirmButtonColor'] = config('sweetalert.confirm.confirm_button_color', '#3085d6');
        $this->config['cancelButtonText'] = config('sweetalert.confirm.cancel_button_text', 'Cancel');
        $this->config['showCloseButton'] = config('sweetalert.confirm.show_close_button', false);
        $this->config['icon'] = config('sweetalert.confirm.icon', 'question');

        unset($this->config['timer']);

        return $this;
    }

    /**
     * Create a confirm delete dialog.
     */
    public function confirmDelete(string $title, ?string $text = null): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->confirm($title, $text);

        $this->config['showCloseButton'] = config('sweetalert.confirm_delete.show_close_button', false);
        $this->config['showCancelButton'] = config('sweetalert.confirm_delete.show_cancel_button', true);
        $this->config['confirmButtonText'] = config('sweetalert.confirm_delete.confirm_button_text', 'Yes, delete it!');
        $this->config['cancelButtonText'] = config('sweetalert.confirm_delete.cancel_button_text', 'Cancel');
        $this->config['confirmButtonColor'] = config('sweetalert.confirm_delete.confirm_button_color', '#d33');
        $this->config['icon'] = config('sweetalert.confirm_delete.icon', 'warning');
        $this->config['showLoaderOnConfirm'] = config('sweetalert.confirm_delete.show_loader_on_confirm', true);
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;

        unset($this->config['timer']);
        unset($this->config['showConfirmButton']);

        $this->flash('delete');

        return $this;
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
     * Set progress steps for multi-step dialogs.
     */
    public function progressSteps(array $steps): static
    {
        $this->config['progressSteps'] = $steps;

        return $this->reflash();
    }

    /**
     * Set the current progress step index.
     */
    public function currentProgressStep(int $index): static
    {
        $this->config['currentProgressStep'] = $index;

        return $this->reflash();
    }

    /**
     * Set the distance between progress steps (e.g. '40px').
     */
    public function progressStepsDistance(string $distance): static
    {
        $this->config['progressStepsDistance'] = $distance;

        return $this->reflash();
    }

    /**
     * Set a pre-deny route for server-side deny validation.
     * Mirrors preConfirmRoute — the JS layer POSTs to this route on deny.
     */
    public function preDenyRoute(string $route): static
    {
        $this->config['preDenyRoute'] = $route;

        return $this->reflash();
    }

    /**
     * Override the built-in SweetAlert2 validation message for input dialogs.
     */
    public function validationMessage(string $message): static
    {
        $this->config['validationMessage'] = $message;

        return $this->reflash();
    }

    /**
     * Apply a named preset from sweetalert.presets config.
     * Merges preset values onto the current config, allowing further overrides.
     */
    public function preset(string $name): static
    {
        $preset = config("sweetalert.presets.{$name}", []);

        if (! empty($preset)) {
            $this->config = array_merge($this->config, $preset);
        }

        return $this->reflash();
    }

    /**
     * Set the SweetAlert2 theme (e.g. 'dark', 'minimal', 'bulma').
     * Overrides the global sweetalert.theme config for this alert only.
     */
    public function theme(string $theme): static
    {
        $this->config['theme'] = $theme;

        return $this->reflash();
    }

    // ──────────────────────────────────────────────
    // Backward Compatibility Shims
    // ──────────────────────────────────────────────

    /**
     * Legacy alert() method for backward compatibility.
     *
     * Sets config only — does NOT flash immediately so chaining still works.
     * Call ->flash() explicitly when done building.
     */
    /**
     * Flash immediately when called the way the released package was called.
     *
     * Every version before the rewrite displayed an alert the moment you wrote
     * `Alert::success('Saved')` — `alert()` called `flash()` internally and the
     * icon shortcuts went through it. A great many controllers rely on that one
     * line doing something.
     *
     * Dropping it would have been a silent break: no exception, no deprecation,
     * alerts simply stop appearing and nobody can tell why. So the legacy shape
     * still flashes.
     *
     * A chain started with make() does not, because there the caller has clearly
     * opted into composing something first. Calling flash() yourself afterwards
     * is harmless either way: it overwrites the same session key rather than
     * queuing a second alert.
     */
    protected function flashIfLegacy(string $title): static
    {
        if ($this->explicit || $title === '') {
            return $this;
        }

        return $this->flash();
    }

    /**
     * Display an alert whose icon is an image.
     *
     * The released signature, kept because it is what existing code calls.
     * For a chain, `imageUrl()` and `addImage()` are more expressive.
     */
    public function image(
        string $title,
        string $text,
        string $imageUrl,
        int $imageWidth = 400,
        int $imageHeight = 200,
        ?string $imageAlt = null
    ): static {
        $this->title($title);
        $this->text($text);
        $this->addImage($imageUrl, $imageWidth, $imageHeight, $imageAlt ?? $title);

        // SweetAlert2 animates the icon, which looks wrong on a photograph.
        $this->config['animation'] = false;

        return $this->flashIfLegacy($title);
    }

    /**
     * Turn this alert into a toast.
     *
     * Width and padding are dropped because a toast sizes itself; leaving a
     * modal width on one produces a toast the width of the screen.
     */
    public function toToast(string $position = ''): static
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = true;
        $this->config['showConfirmButton'] = false;
        $this->config['position'] = $position !== ''
            ? $position
            : config('sweetalert.toast_position', 'top-end');

        unset($this->config['width'], $this->config['padding']);

        return $this->explicit ? $this : $this->flash();
    }

    public function alert(string $title = '', string $text = '', ?string $icon = null): static
    {
        $this->explicit or $this->resetForLegacy();

        $this->title($title);

        if ($text) {
            $this->text($text);
        }

        if ($icon) {
            $this->icon($icon);
        }

        return $this->flashIfLegacy($title);
    }
}
