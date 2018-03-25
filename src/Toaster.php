<?php

namespace RealRashid\SweetAlert;

use RealRashid\SweetAlert\Storage\SessionStore;

class Toaster
{
    /**
     * Session storage.
     *
     * @var RealRashid\SweetAlert\Storage\Session
     */
    protected $session;

    /**
     * Configuration options.
     *
     * @var array
     */
    protected $config;

    public function __construct(SessionStore $session)
    {
        $this->setDefaultConfig();

        $this->session = $session;
    }

    /**
     * Sets all default config options for an alert.
     *
     * @return void
     */
    protected function setDefaultConfig()
    {
        $this->config = [
            'timer' => env('SWEET_ALERT_AUTOCLOSE', 3000),
            'title' => '',
            'text' => '',
            'showConfirmButton' => false,
        ];
    }

    /**
     * Flash a message.
     *
     * @param  string $title
     * @param  string $text
     * @param  array  $type
     *
     * @return void
     */
    public function alert($title = '', $text = '', $type = null)
    {
        $this->config['title'] = $title;

        $this->config['text'] = $text;

        if (!is_null($type)) {
            $this->config['type'] = $type;
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * Display a success typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function success($title = '', $text)
    {
        $this->alert($title, $text, 'success');

        return $this;
    }

    /*
     **
     * Display a info typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function info($title = '', $text)
    {
        $this->alert($title, $text, 'info');

        return $this;
    }

    /*
     **
     * Display a warning typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function warning($title = '', $text)
    {
        $this->alert($title, $text, 'warning');

        return $this;
    }

    /*
     **
     * Display a question typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function question($title = '', $text)
    {
        $this->alert($title, $text, 'question');

        return $this;
    }

    /*
     **
     * Display a error typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function error($title = '', $text)
    {
        $this->alert($title, $text, 'error');

        return $this;
    }

    /*
     **
     * Display a html typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $type
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function html($title, $code, $type)
    {
        $this->config['title'] = $title;

        $this->config['html'] = $code;

        if (!is_null($type)) {
            $this->config['type'] = $type;
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * Display a toast alert message with any typed.
     *
     * @param string $title
     * @param string $type
     * @param string $position
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function toast($title, $type, $position = 'top-right')
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['showCloseButton'] = true;
        $this->config['type'] = $type;
        $this->config['position'] = $position;

        $this->flash();

        return $this;
    }

    /*
     **
     * Convert any alert method to Toast
     *
     * @param string $position
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function toToast($position = 'top-right')
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = false;
        $this->config['position'] = $position;

        $this->flash();

        return $this;
    }

    /*
     **
     * Add footer section to alert()
     *
     * @param string $code
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function footer($code)
    {
        $this->config['footer'] = $code;

        $this->flash();

        return $this;
    }

    /*
     **
     * make any alert persistent
     *
     * @param bool $showConfirmBtn
     * @param bool $showCloseBtn
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function persistent($showConfirmBtn = true, $showCloseBtn = false)
    {
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();

        if ($showConfirmBtn) {
            $this->showConfirmButton();
        }

        if ($showCloseBtn) {
            $this->showCloseButton();
        }

        $this->flash();

        return $this;
    }

    /*
     **
     * can any alert after param $milliseconds
     *
     * @param bool $milliseconds
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function autoClose($milliseconds = 5000)
    {
        $this->config['timer'] = $milliseconds;

        $this->flash();

        return $this;
    }

    /*
     **
     * Display confirm button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function showConfirmButton($btnText = 'Ok', $btnColor = '#3085d6')
    {
        $this->config['showConfirmButton'] = true;
        $this->config['confirmButtonText'] = $btnText;
        $this->config['confirmButtonColor'] = $btnColor;
        $this->config['allowOutsideClick'] = false;
        $this->removeTimer();
        $this->flash();

        return $this;
    }

    /*
     **
     * Display cancel button on alert
     *
     * @param string $btnText
     * @param string $btnColor
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function showCancelButton($btnText = 'Cancel', $btnColor = '#aaa')
    {
        $this->config['showCancelButton'] = true;
        $this->config['cancelButtonText'] = $btnText;
        $this->config['cancelButtonColor'] = $btnColor;
        $this->removeTimer();
        $this->flash();

        return $this;
    }

    /*
     **
     * Display close button on alert
     *
     * @param string $closeButtonAriaLabel
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function showCloseButton($closeButtonAriaLabel = 'aria-label')
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        $this->flash();

        return $this;
    }

    /**
     * Remove the timer from config option.
     *
     * @return void
     */
    protected function removeTimer()
    {
        if (array_key_exists('timer', $this->config)) {
            unset($this->config['timer']);
        }
    }

    /**
     * Flash the config options for alert.
     *
     * @return void
     */
    public function flash()
    {
        foreach ($this->config as $key => $value) {
            $this->session->flash("alert.config.{$key}", $value);
        }
        $this->session->flash('alert.config', $this->buildConfig());
    }

    /**
     * Build Flash config options for flashing.
     *
     * @return void
     */
    public function buildConfig()
    {
        $config = $this->config;

        return json_encode($config);
    }
}
