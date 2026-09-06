<?php

namespace RealRashid\SweetAlert\Contracts;

interface SessionStoreInterface
{
    /**
     * Flash a key/value pair to the session.
     */
    public function flash(string $key, mixed $data): void;

    /**
     * Put a key/value pair in the session.
     */
    public function put(string $key, mixed $data): void;

    /**
     * Get a value from the session.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Check if a key exists in the session.
     */
    public function has(string $key): bool;

    /**
     * Remove a key from the session.
     */
    public function forget(string $key): void;
}
