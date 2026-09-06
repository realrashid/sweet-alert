<?php

namespace RealRashid\SweetAlert\Builders;

use Illuminate\Support\Traits\Conditionable;
use Illuminate\Support\Traits\Macroable;
use RealRashid\SweetAlert\Concerns\HasAnimation;
use RealRashid\SweetAlert\Concerns\HasButtons;
use RealRashid\SweetAlert\Concerns\HasPosition;
use RealRashid\SweetAlert\Concerns\HasStyling;
use RealRashid\SweetAlert\Concerns\HasTimer;
use RealRashid\SweetAlert\Contracts\BuilderInterface;
use RealRashid\SweetAlert\Support\AlertConfig;
use RealRashid\SweetAlert\Support\AlertFlasher;

/**
 * AbstractAlertBuilder - Shared base for all fluent alert builder classes.
 *
 * Encapsulates the common config array, flasher dependency, factory method,
 * reset, flash, serialisation, and inspection methods so that AlertBuilder,
 * ToastBuilder, and InputBuilder stay DRY.
 */
abstract class AbstractAlertBuilder implements BuilderInterface
{
    use Conditionable;
    use HasAnimation;
    use HasButtons;
    use HasPosition;
    use HasStyling;
    use HasTimer;
    use Macroable;

    /**
     * The alert configuration array.
     */
    protected array $config = [];

    /**
     * The alert flasher instance.
     */
    protected ?AlertFlasher $flasher = null;

    /**
     * Whether this chain was started explicitly with make().
     *
     * A builder from make() is being composed, so it waits for flash(). One
     * resolved straight from the container is the legacy shape, and legacy
     * calls display immediately.
     */
    protected bool $explicit = false;

    /**
     * Whether this builder has already written to the session.
     *
     * The released package flashed on every mutator, so
     * `Alert::success('Saved')->autoClose(3000)` applied the timer. Tracking
     * this lets the same chain keep working without every setter flashing
     * unconditionally: once an alert has been shown, later changes update it.
     */
    protected bool $flashed = false;

    /**
     * Create a new builder instance.
     */
    final public function __construct(?AlertFlasher $flasher = null)
    {
        $this->flasher = $flasher ?? app(AlertFlasher::class);
        $this->setDefaultConfig();
    }

    /**
     * Subclasses define their own default configuration.
     */
    abstract protected function setDefaultConfig(): void;

    /**
     * Create a new builder instance via the service container (factory method).
     */
    public static function make(): static
    {
        $builder = new static(app(AlertFlasher::class));
        $builder->explicit = true;

        return $builder;
    }

    /**
     * Reset the builder to its default state.
     */
    public function reset(): static
    {
        $this->config = [];
        $this->setDefaultConfig();

        return $this;
    }

    /**
     * Flash the alert configuration to the session.
     */
    public function flash(string $type = 'config'): static
    {
        $config = new AlertConfig($this->config, $type);
        $this->flasher->flash($config, $type);

        $this->flashed = true;

        return $this;
    }

    /**
     * Re-write an alert that has already been shown.
     *
     * A no-op until flash() has been called, so an explicitly composed chain
     * touches the session exactly once. After a legacy call it keeps the
     * session in step with the chain, which is how the released package
     * behaved.
     */
    public function reflash(): static
    {
        return $this->flashed ? $this->flash() : $this;
    }

    /**
     * Clear everything back to defaults.
     *
     * Called before a legacy entry point so that two alerts in one request
     * cannot inherit each other's configuration — a confirmDelete followed by
     * a success() previously left "Yes, delete it!" on the success alert.
     */
    protected function resetForLegacy(): static
    {
        $this->config = [];
        $this->flashed = false;
        $this->setDefaultConfig();

        return $this;
    }

    /**
     * Send the answer to a route when the person confirms.
     *
     * Without this an alert can only be shown: the dialog closes and whatever
     * was chosen or typed goes nowhere. On confirm the package builds a form
     * with the CSRF token, the method you asked for, and — for an input alert —
     * the value under $field, then submits it.
     */
    public function submitTo(string $url, string $method = 'POST', string $field = 'value'): static
    {
        $this->config['submitTo'] = [
            'url' => $url,
            'method' => strtoupper($method),
            'field' => $field,
        ];

        return $this->reflash();
    }

    /**
     * Get the alert configuration as an array (filters null and empty strings).
     */
    public function toArray(): array
    {
        return array_filter($this->config, fn ($value) => $value !== null && $value !== '');
    }

    /**
     * Get the alert configuration as JSON.
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Get the raw config array including unfiltered defaults (for testing/inspection).
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}
