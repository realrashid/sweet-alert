<?php

namespace RealRashid\SweetAlert\Storage;

use Illuminate\Session\Store;

class AlertSessionStore implements SessionStore
{
    /**
     * @var Store
     */
    private $session;

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    /**
     * Set a session key and value.
     *
     * @param  mixed $key
     * @param  string $data
     *
     * @return mixed
     */
    public function flash($key, $data)
    {
        $this->session->flash($key, $data);
    }

    /**
     * Put a session key and value.
     *
     * @param  mixed $key
     * @param  string $data
     *
     * @return mixed
     */
    public function put($key, $data)
    {
        $this->session->put($key, $data);
    }

    // /**
    //  * Flash multiple key/value pairs.
    //  *
    //  * @param  array  $data
    //  * @return void
    //  */
    // public function flashMany(array $data)
    // {
    //     foreach ($data as $key => $value) {
    //         $this->flash($key, $value);
    //     }
    // }
    //
    // /**
    //  * Get a value from session storage.
    //  *
    //  * @param  string $key
    //  * @return string
    //  */
    // public function get($key)
    // {
    //     return $this->session->get($key);
    // }
}
