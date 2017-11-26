<?php

namespace RealRashid\SweetAlert;

use RealRashid\SweetAlert\Storage\Session;

class Toaster
{
    /**
     * Session storage.
     *
     * @var RealRashid\SweetAlert\Storage\Session
     */
    protected $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Flash a message.
     *
     * @param  string $message
     * @param  string $type
     * @param  array  $options
     *
     * @return void
     */
    public function flash($message, $type = null, array $options = [])
    {
        $this->session->flash([
            'alert.message' => $message,
            'alert.type' => $type,
            'alert.options' => json_encode($options),
        ]);
    }
    
    /**
     * Get the message
     *
     * @param  boolean $array
     * @return array
     */
    public function get($array = false)
    {
        return [
            'message' => $this->message(),
            'type' => $this->type(),
            'options' => $this->options($array),
        ];
    }

    /**
     * If a message is ready to be shown.
     *
     * @return bool
     */
    public function ready()
    {
        return $this->message();
    }

    /**
     * Get the stored message.
     *
     * @return string
     */
    public function message()
    {
        return $this->session->get('alert.message');
    }

    /**
     * Get the stored type.
     *
     * @return string
     */
    public function type()
    {
        return $this->session->get('alert.type');
    }

    /**
     * Get an additional stored options.
     *
     * @param  boolean $array
     * @return mixed
     */
    public function options($array = false)
    {
        return json_decode($this->session->get('alert.options'), $array);
    }

    /**
     * Get a stored option.
     *
     * @param  string $key
     * @return string
     */
    public function option($key, $default = null)
    {
        return array_get($this->options(true), $key, $default);
    }
}
