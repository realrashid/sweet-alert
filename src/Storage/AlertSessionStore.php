<?php

namespace RealRashid\SweetAlert\Storage;

use Illuminate\Contracts\Session\Session;
use RealRashid\SweetAlert\Contracts\SessionStoreInterface;

class AlertSessionStore implements SessionStoreInterface
{
    /**
     * Create a new alert session store instance.
     */
    public function __construct(protected Session $session) {}

    /**
     * Flash a key/value pair to the session.
     */
    public function flash(string $key, mixed $data): void
    {
        $this->session->flash($key, $data);
    }

    /**
     * Put a key/value pair in the session.
     */
    public function put(string $key, mixed $data): void
    {
        $this->session->put($key, $data);
    }

    /**
     * Get a value from the session.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session->get($key, $default);
    }

    /**
     * Check if a key exists in the session.
     */
    public function has(string $key): bool
    {
        return $this->session->has($key);
    }

    /**
     * Remove a key from the session.
     */
    public function forget(string $key): void
    {
        $this->session->forget($key);
    }
}
