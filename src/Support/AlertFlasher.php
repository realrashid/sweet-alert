<?php

namespace RealRashid\SweetAlert\Support;

use RealRashid\SweetAlert\Contracts\SessionStoreInterface;

/**
 * AlertFlasher - Handles flashing alert configuration to the session.
 *
 * This class is responsible for writing AlertConfig objects to the
 * Laravel session and reading them back, providing a clean API
 * separate from the builder logic.
 */
class AlertFlasher
{
    /**
     * The session store instance.
     */
    protected SessionStoreInterface $session;

    /**
     * Create a new AlertFlasher instance.
     */
    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Flash an alert configuration to the session.
     */
    public function flash(AlertConfig $config, string $type = 'config'): void
    {
        $key = "alert.{$type}";
        $this->session->flash($key, $config->toJson());
    }

    /**
     * Flash a configuration array directly.
     */
    public function flashConfig(array $config, string $type = 'config'): void
    {
        $alertConfig = new AlertConfig($config, $type);
        $this->flash($alertConfig, $type);
    }

    /**
     * Check if there is any alert in the session.
     */
    public function hasAlert(): bool
    {
        return $this->session->has('alert.config')
            || $this->session->has('alert.delete');
    }

    /**
     * Get the alert configuration from the session.
     */
    public function getAlert(): ?AlertConfig
    {
        if ($json = $this->session->get('alert.config')) {
            return AlertConfig::fromJson($json);
        }

        return null;
    }

    /**
     * Get the delete alert configuration from the session.
     */
    public function getDeleteAlert(): ?AlertConfig
    {
        if ($json = $this->session->get('alert.delete')) {
            return AlertConfig::fromJson($json);
        }

        return null;
    }

    /**
     * Clear all alert data from the session.
     */
    public function clear(): void
    {
        $this->session->forget('alert.config');
        $this->session->forget('alert.delete');
    }
}
