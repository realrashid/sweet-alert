<?php

namespace RealRashid\SweetAlert\Support;

/**
 * AlertConfig - Immutable value object representing alert configuration.
 *
 * This class encapsulates the complete configuration for a SweetAlert2
 * alert, providing serialization/deserialization methods for session
 * storage and JSON rendering in Blade views.
 */
class AlertConfig
{
    /**
     * Create a new AlertConfig instance.
     */
    final public function __construct(
        protected array $config = [],
        protected string $type = 'config'
    ) {}

    /**
     * Create an AlertConfig from a JSON string.
     */
    public static function fromJson(string $json): static
    {
        $data = json_decode($json, true);

        return new static(
            $data['config'] ?? [],
            $data['type'] ?? 'config'
        );
    }

    /**
     * Get the configuration as an array.
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * Get a specific config value.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Check if a config key exists.
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->config);
    }

    /**
     * Get the alert type (config, delete, queue).
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Serialize the configuration to JSON.
     */
    public function toJson(): string
    {
        return json_encode([
            'config' => $this->config,
            'type' => $this->type,
        ]);
    }

    /**
     * Get the raw config JSON for Swal.fire() (without wrapper).
     */
    public function toSwalConfigJson(): string
    {
        return json_encode($this->config);
    }

    /**
     * Check if this is a toast configuration.
     */
    public function isToast(): bool
    {
        return ! empty($this->config['toast']);
    }

    /**
     * Check if this has an input configuration.
     */
    public function hasInput(): bool
    {
        return ! empty($this->config['input']);
    }

    /**
     * Check if this has a pre-confirm route.
     */
    public function hasPreConfirmRoute(): bool
    {
        return ! empty($this->config['preConfirmRoute']);
    }

    /**
     * Get the pre-confirm route URL.
     */
    public function getPreConfirmRoute(): ?string
    {
        return $this->config['preConfirmRoute'] ?? null;
    }

    /**
     * Remove the pre-confirm route from config (not needed in JS output).
     */
    public function withoutPreConfirmRoute(): static
    {
        $config = $this->config;
        unset($config['preConfirmRoute']);

        return new static($config, $this->type);
    }
}
