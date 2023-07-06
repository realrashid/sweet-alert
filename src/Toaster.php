<?php

namespace RealRashid\SweetAlert;

use RealRashid\SweetAlert\Storage\SessionStore;

class Toaster
{
    /**
     * Session storage.
     *
     * @var RealRashid\SweetAlert\Storage\Session
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    protected $session;

    /**
     * Configuration options.
     *
     * @var array
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    protected $config;

    /**
     * Setting up the session
     *
     * @param SessionStore $session
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function __construct(SessionStore $session)
    {
        $this->setDefaultConfig();
        $this->session = $session;
    }

    /**
     * The default configuration for alert
     *
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    protected function setDefaultConfig()
    {
        $this->config = [
            'title' => '',
            'text' => '',
            'timer' => config('sweetalert.timer'),
            'background' => config('sweetalert.background'),
            'width' => config('sweetalert.width'),
            'heightAuto' => config('sweetalert.height_auto'),
            'padding' => config('sweetalert.padding'),
            'showConfirmButton' => config('sweetalert.show_confirm_button'),
            'showCloseButton' => config('sweetalert.show_close_button'),
            'confirmButtonText' => __(config('sweetalert.button_text.confirm')),
            'cancelButtonText' => __(config('sweetalert.button_text.cancel')),
            'timerProgressBar' => config('sweetalert.timer_progress_bar'),
            'customClass' => [
                'container' => config('sweetalert.customClass.container'),
                'popup' => config('sweetalert.customClass.popup'),
                'header' => config('sweetalert.customClass.header'),
                'title' => config('sweetalert.customClass.title'),
                'closeButton' => config('sweetalert.customClass.closeButton'),
                'icon' => config('sweetalert.customClass.icon'),
                'image' => config('sweetalert.customClass.image'),
                'content' => config('sweetalert.customClass.content'),
                'input' => config('sweetalert.customClass.input'),
                'actions' => config('sweetalert.customClass.actions'),
                'confirmButton' => config('sweetalert.customClass.confirmButton'),
                'cancelButton' => config('sweetalert.customClass.cancelButton'),
                'footer' => config('sweetalert.customClass.footer')
            ]
        ];
    }

    /**
     * The default configuration for middleware alert.
     *
     * @return $config
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function middleware()
    {
        unset($this->config['position'], $this->config['heightAuto'], $this->config['width'], $this->config['padding'], $this->config['showCloseButton']);

        if (!config('sweetalert.middleware.autoClose')) {
            $this->removeTimer();
        } else {
            unset($this->config['timer']);
            $this->config['timer'] = config('sweetalert.middleware.timer');
        }
        $this->config['position'] = config('sweetalert.middleware.toast_position');
        $this->config['showCloseButton'] = config('sweetalert.middleware.toast_close_button');

        $this->flash();

        return $this;
    }

    /**
     * Flash an alert message.
     *
     * @param  string $title
     * @param  string $text
     * @param  array  $icon
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function alert($title = '', $text = '', $icon = null)
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }
        $this->flash();
        return $this;
    }

    /**
     * Show confirm alert before deleting data.
     *
     * @param  string $title
     * @param  string $text
     * @param  string $deleteUrl
     * @param  string $deleteMethod
     * @return void
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function confirmDelete($title, $text = null)
    {
        // Set the configuration options for the confirmation popup
        $this->config['title'] = $title;
        if (!is_null($text)){
            $this->config['text'] = $text;
        }

        $this->config['showCloseButton'] = config('sweetalert.confirm_delete_show_close_button');
        $this->config['showCancelButton'] = config('sweetalert.confirm_delete_show_cancel_button');
        $this->config['confirmButtonText'] = config('sweetalert.confirm_delete_confirm_button_text');
        $this->config['cancelButtonText'] = config('sweetalert.confirm_delete_cancel_button_text');
        $this->config['confirmButtonColor'] = config('sweetalert.confirm_delete_confirm_button_color');
        $this->config['icon'] = config('sweetalert.confirm_delete_icon');
        $this->config['showLoaderOnConfirm'] = config('sweetalert.confirm_delete_show_loader_on_confirm');
        $this->config['allowEscapeKey'] = false;
        $this->config['allowOutsideClick'] = false;

        if (array_key_exists('timer', $this->config)) {
            unset($this->config['timer']);
        }

        if (array_key_exists('showConfirmButton', $this->config)) {
            unset($this->config['showConfirmButton']);
        }


        $this->flash('delete');
        return $this;
    }


    /**
     * Display a success typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function success($title = '', $text = '')
    {
        $this->alert($title, $text, 'success');
        return $this;
    }

    /**
     * Display a info typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function info($title = '', $text = '')
    {
        $this->alert($title, $text, 'info');
        return $this;
    }

    /**
     * Display a warning typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function warning($title = '', $text = '')
    {
        $this->alert($title, $text, 'warning');
        return $this;
    }

    /**
     * Display a question typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function question($title = '', $text = '')
    {
        $this->alert($title, $text, 'question');
        $this->showCancelButton();
        return $this;
    }

    /**
     * Display a error typed alert message with a text and a title.
     *
     * @param string $title
     * @param string $text
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function error($title = '', $text = '')
    {
        $this->alert($title, $text, 'error');
        return $this;
    }

    /**
     * Display a message with a custom image and CSS animation disabled.
     *
     * @param string $title
     * @param string $text
     * @param string $imageUrl
     * @param integer $imageWidth
     * @param integer $imageHeight
     * @param string $imageAlt
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function image($title, $text, $imageUrl, $imageWidth, $imageHeight, $imageAlt = null)
    {
        $this->config['title'] = $title;
        $this->config['text'] = $text;
        $this->config['imageUrl'] = $imageUrl;
        $this->config['imageWidth'] = $imageWidth;
        $this->config['imageHeight'] = $imageHeight;
        if (!is_null($imageAlt)) {
            $this->config['imageAlt'] = $imageAlt;
        } else {
            $this->config['imageAlt'] = $title;
        }
        $this->config['animation'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Display a html typed alert message with html code.
     *
     * @param string $title
     * @param string $code
     * @param string $icon
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function html($title = '', $code = '', $icon = '')
    {
        $this->config['title'] = $title;
        $this->config['html'] = $code;
        if (!is_null($icon)) {
            $this->config['icon'] = $icon;
        }

        $this->flash();
        return $this;
    }

    /**
     * Display a toast message
     *
     * @param string $title
     * @param string $icon
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function toast($title = '', $icon = '')
    {
        $this->config['toast'] = true;
        $this->config['title'] = $title;
        $this->config['icon'] = $icon;
        $this->config['position'] = config('sweetalert.toast_position');
        $this->config['showCloseButton'] = true;
        $this->config['showConfirmButton'] = false;

        unset($this->config['heightAuto']);
        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to Toast
     *
     * @param string $position
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function toToast($position = '')
    {
        $this->config['toast'] = true;
        $this->config['showCloseButton'] = true;
        if (!empty($position)) {
            $this->config['position'] = $position;
        } else {
            $this->config['position'] = config('sweetalert.toast_position');
        }
        $this->config['showConfirmButton'] = false;
        unset($this->config['width'], $this->config['padding']);

        $this->flash();
        return $this;
    }

    /**
     * Convert any alert modal to html
     *
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function toHtml()
    {
        $this->config['html'] = $this->config['text'];
        unset($this->config['text']);

        $this->flash();
        return $this;
    }

    /**
     * Add a custom image to alert
     *
     * @param string $imageUrl
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function addImage($imageUrl)
    {
        $this->config['imageUrl'] = $imageUrl;
        $this->config['showCloseButton'] = true;
        unset($this->config['icon']);

        $this->flash();
        return $this;
    }

    /**
     * Add footer section to alert()
     *
     * @param string $code
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function footer($code)
    {
        $this->config['footer'] = $code;

        $this->flash();
        return $this;
    }

    /**
     * positioned alert dialog
     *
     * @param string $position
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function position($position = 'top-end')
    {
        $this->config['position'] = $position;

        $this->flash();
        return $this;
    }

    /**
     * Modal window width
     * including paddings
     * (box-sizing: border-box).
     * Can be in px or %. The default width is 32rem
     *
     * @param string $width
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function width($width = '32rem')
    {
        $this->config['width'] = $width;

        $this->flash();
        return $this;
    }

    /**
     * Modal window padding.
     * The default padding is 1.25rem.
     *
     * @param string $padding
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function padding($padding = '1.25rem')
    {
        $this->config['padding'] = $padding;

        $this->flash();
        return $this;
    }

    /**
     * Modal window background
     * (CSS background property).
     * The default background is '#fff'.
     *
     * @param string $background
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function background($background = '#fff')
    {
        $this->config['background'] = $background;

        $this->flash();
        return $this;
    }

    /**
     * Set to false if you want to
     * focus the first element in tab
     * order instead of "Confirm"-button by default.
     *
     * @param boolean $focus
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function focusConfirm($focus = true)
    {
        $this->config['focusConfirm'] = $focus;
        unset($this->config['focusCancel']);

        $this->flash();
        return $this;
    }

    /**
     * Set to true if you want to focus the
     * "Cancel"-button by default.
     *
     * @param boolean $focus
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function focusCancel($focus = false)
    {
        $this->config['focusCancel'] = $focus;
        unset($this->config['focusConfirm']);

        $this->flash();
        return $this;
    }

    /**
     * Custom animation with [Animate.css](https://daneden.github.io/animate.css/)
     * CSS classes for animations when showing a popup (fade in):
     * CSS classes for animations when hiding a popup (fade out):
     *
     * @param string $showAnimation
     * @param string $hideAnimation
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function animation($showAnimation, $hideAnimation)
    {
        if (!config('sweetalert.animation.enable')) {
            config(['sweetalert.animation.enable' => true]);
        }
        $this->config['showClass'] = ['popup' => "animate__animated {$showAnimation}"];
        $this->config['hideClass'] = ['popup' => "animate__animated {$hideAnimation}"];

        $this->flash();
        return $this;
    }

    /**
     * Persistent the alert modal
     *
     * @param boolean $showConfirmBtn
     * @param boolean $showCloseBtn
     * @author Rashid Ali <realrashid05@gmail.com>
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

    /**
     * auto close alert modal after
     * specifid time
     *
     * @param integer $milliseconds
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function autoClose($milliseconds = 5000)
    {
        $this->config['timer'] = $milliseconds;

        $this->flash();
        return $this;
    }

    /**
     * Display confirm button
     *
     * @param string $btnText
     * @param string $btnColor
     * @author Rashid Ali <realrashid05@gmail.com>
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

    /**
     * Display cancel button
     *
     * @param string $btnText
     * @param string $btnColor
     * @author Rashid Ali <realrashid05@gmail.com>
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

    /**
     * Display close button
     *
     * @param string $closeButtonAriaLabel
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function showCloseButton($closeButtonAriaLabel = 'aria-label')
    {
        $this->config['showCloseButton'] = true;
        $this->config['closeButtonAriaLabel'] = $closeButtonAriaLabel;

        $this->flash();
        return $this;
    }

    /**
     * Hide close button from alert or toast
     *
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function hideCloseButton()
    {
        $this->config['showCloseButton'] = false;

        $this->flash();
        return $this;
    }

    /**
     * Apply default styling to buttons.
     * If you want to use your own classes (e.g. Bootstrap classes)
     * set this parameter to false.
     *
     * @param boolean $buttonsStyling
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function buttonsStyling($buttonsStyling)
    {
        $this->config['buttonsStyling'] = $buttonsStyling;

        $this->flash();
        return $this;
    }

    /**
     * Use any HTML inside icons (e.g. Font Awesome)
     *
     * @param string $iconHtml
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function iconHtml($iconHtml)
    {
        $this->config['iconHtml'] = $iconHtml;

        $this->flash();
        return $this;
    }

    /**
     *  If set to true, the timer will have a progress bar at the bottom of a popup.
     * Mostly, this feature is useful with toasts.
     *
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function timerProgressBar()
    {
        $this->config['timerProgressBar'] = true;

        $this->flash();
        return $this;
    }

    /**
     * Reverse buttons position
     *
     * @author Faber44 <https://github.com/Faber44>
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
     * @author Rashid Ali <realrashid05@gmail.com>
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
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function flash($type = 'config')
    {
        foreach ($this->config as $key => $value) {
            $this->session->flash("alert.$type.{$key}", $value);
        }
        $this->session->flash("alert.$type", $this->buildConfig());
    }

    /**
     * Build Flash config options for flashing.
     *
     * @author Rashid Ali <realrashid05@gmail.com>
     */
    public function buildConfig()
    {
        $config = $this->config;
        return json_encode($config);
    }
}
