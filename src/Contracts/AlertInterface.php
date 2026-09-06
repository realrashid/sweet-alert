<?php

namespace RealRashid\SweetAlert\Contracts;

use RealRashid\SweetAlert\Enums\AlertType;

interface AlertInterface
{
    /**
     * Set the alert title.
     */
    public function title(string $title): static;

    /**
     * Set the alert text description.
     */
    public function text(string $text): static;

    /**
     * Set the alert icon type.
     */
    public function icon(string|AlertType $type): static;

    /**
     * Flash the alert configuration to the session.
     */
    public function flash(): static;

    /**
     * Get the alert configuration as an array.
     */
    public function toArray(): array;

    /**
     * Get the alert configuration as JSON.
     */
    public function toJson(): string;
}
