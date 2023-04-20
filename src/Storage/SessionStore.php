<?php

namespace RealRashid\SweetAlert\Storage;

interface SessionStore
{
    /**
     * Flash some data into the session.
     *
     * @param $name
     * @param $data
     */
    public function flash($name, $data);

    /**
     * Put data into the session.
     *
     * @param $name
     * @param $data
     */
    public function put($name, $data);
}
