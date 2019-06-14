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
            'title' => '',
            'text' => '',
            'timer' => config('sweetalert.timer'),
            'width' => config('sweetalert.width'),
            'heightAuto' => config('sweetalert.height_auto'),
            'padding' => config('sweetalert.padding'),
            'animation' => config('sweetalert.animation'),
            'showConfirmButton' => config('sweetalert.show_confirm_button'),
            'showCloseButton' => config('sweetalert.show_close_button'),
        ];
    }

    /**
     * Sets all default config options for middleware alert.
     *
     * @return $config
     */
     public function middleware()
     {
        unset($this->config['position']);
        unset($this->config['heightAuto']);
        unset($this->config['width']);
        unset($this->config['padding']);
        unset($this->config['showCloseButton']);
        
        $this->config['position'] = config('sweetalert.middleware.toast_position');
        $this->config['showCloseButton'] = config('sweetalert.middleware.toast_close_button');

        $this->flash();

        return $this;
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
    public function success($title = '', $text = '')
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
    public function info($title = '', $text = '')
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
    public function warning($title = '', $text = '')
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
    public function question($title = '', $text = '')
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
    public function error($title = '', $text = '')
    {
        $this->alert($title, $text, 'error');

        return $this;
    }

    /*
     **
     * Display a message with a custom image and CSS animation disabled.
     *
     * @param string $title
     * @param string $text
     * @param string $imageUrl
     * @param string $imageWidth
     * @param string $imageAlt
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function image($title = '', $text = '', $imageUrl, $imageWidth = 400, $imageHeight = 200, $imageAlt = '')
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        $this->config['imageUrl'] = $imageUrl;
        $this->config['imageWidth'] = $imageWidth;
        $this->config['imageHeight'] = $imageHeight;
        if(!is_null($imageAlt)){
            $this->config['imageAlt'] = $imageAlt;
        }else{
            $this->config['imageAlt'] = $title;
        }
        $this->config['animation'] = false;

        $this->flash();

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
    public function html($title = '', $code = '', $type = '')
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
    public function toast($title = '', $type = '')
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['showCloseButton'] = true;
        $this->config['type'] = $type;
        $this->config['position'] = config('sweetalert.toast_position');
        $this->config['showConfirmButton'] = false;
        $this->flash();

        return $this;
    }

    /*
     **
     * Convert any alert modal to Toast
     *
     * @param string $position
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function toToast($position = '')
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = true;
        if(!empty($position)){
            $this->config['position'] = $position;
        }else{
            $this->config['position'] = config('sweetalert.toast_position');
        }
        $this->config['showConfirmButton'] = false;
        unset($this->config['width']);
        unset($this->config['padding']);
        $this->flash();

        return $this;
    }

    /*
     **
     * Convert any alert modal to html
     *
     * @return $this;
     */
    public function toHtml()
    {
        $this->config['html'] = $this->config['text'];
        unset($this->config['text']);
        $this->flash();

        return $this;
    }

    /*
     **
     * Add a custom image to alert
     *
     * @param string $imageUrl
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function addImage($imageUrl)
    {
        $this->config['imageUrl'] = $imageUrl;
        $this->config['showCloseButton'] = true;
        unset($this->config['type']);
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
     * positioned alert dialog
     *
     * @param string $position
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function position($position = 'top-end')
    {
        $this->config['position'] = $position;

        $this->flash();

        return $this;
    }

    /*
     **
     * Modal window width
     * including paddings
     * (box-sizing: border-box).
     * Can be in px or %. The default width is 32rem
     * @param string $width
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function width($width = '32rem')
    {
        $this->config['width'] = $width;

        $this->flash();

        return $this;
    }

    /*
     **
     * Modal window padding.
     * The default padding is 1.25rem.
     * @param string $padding
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function padding($padding = '1.25rem')
    {
        $this->config['padding'] = $padding;

        $this->flash();

        return $this;
    }

    /*
     **
     * Modal window background
     * (CSS background property).
     * The default background is '#fff'.
     * @param string $background
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function background($background = '#fff')
    {
        $this->config['background'] = $background;

        $this->flash();

        return $this;
    }

    /*
     **
     * Set to false if you want to
     * focus the first element in tab
     * order instead of "Confirm"-button by default.
     * @param bool $focus
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function focusConfirm($focus = true)
    {
        $this->config['focusConfirm'] = $focus;
        unset($this->config['focusCancel']);
        $this->flash();

        return $this;
    }

    /*
     **
     * Set to true if you want to focus the
     * "Cancel"-button by default.
     * @param bool $focus
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function focusCancel($focus = false)
    {
        $this->config['focusCancel'] = $focus;
        unset($this->config['focusConfirm']);
        $this->flash();

        return $this;
    }

    /*
     **
     * Set to true if you want to focus the
     * "Cancel"-button by default.
     * @param bool $animation
     * @return RealRashid\SweetAlert\Toaster::alert();
     */
    public function animation($animation = true)
    {
        $this->config['animation'] = $animation;
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
     * auto close any alert after $milliseconds
     *
     * @param int $milliseconds
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

    /*
     **
     * Hide close button from alert or toast
     *
     * @return the $this;
     */
    public function hideCloseButton()
    {
        $this->config['showCloseButton'] = false;
        $this->flash();

        return $this;
    }

    /**
     * Reverse buttons position
     *
     * @return RealRashid\SweetAlert\Toaster::alert();
     * by: https://github.com/Faber44/
     */
    public function reverseButtons()
    {
        $this->config['reverseButtons'] = true;

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
