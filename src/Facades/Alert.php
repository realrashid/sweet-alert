<?php

namespace RealRashid\SweetAlert\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \RealRashid\SweetAlert\Toaster alert(string $title = '', string $text = '', string $icon = null)
 * @method static \RealRashid\SweetAlert\Toaster success(string $title = '', string $text = '')
 * @method static \RealRashid\SweetAlert\Toaster info(string $title = '', string $text = '')
 * @method static \RealRashid\SweetAlert\Toaster warning(string $title = '', string $text = '')
 * @method static \RealRashid\SweetAlert\Toaster question(string $title = '', string $text = '')
 * @method static \RealRashid\SweetAlert\Toaster error(string $title = '', string $text = '')
 * @method static \RealRashid\SweetAlert\Toaster html(string $title = '', string $code = '', string $icon = '')
 * @method static \RealRashid\SweetAlert\Toaster image(string $title, string $text, string $imageUrl, int $imageWidth, int $imageHeight, string $imageAlt = null)
 * @method static \RealRashid\SweetAlert\Toaster toast(string $title = '', string $icon = '')
 * @method static \RealRashid\SweetAlert\Toaster confirmDelete(string $title, string $text = null)
 * @method static \RealRashid\SweetAlert\Toaster persistent(bool $showConfirmBtn = true, bool $showCloseBtn = false)
 * @method static \RealRashid\SweetAlert\Toaster autoClose(int $milliseconds = 5000)
 * @method static \RealRashid\SweetAlert\Toaster showConfirmButton(string $btnText = 'Ok', string $btnColor = '#3085d6')
 * @method static \RealRashid\SweetAlert\Toaster showCancelButton(string $btnText = 'Cancel', string $btnColor = '#aaa')
 * @method static \RealRashid\SweetAlert\Toaster showCloseButton(string $closeButtonAriaLabel = 'aria-label')
 * @method static \RealRashid\SweetAlert\Toaster hideCloseButton()
 * @method static \RealRashid\SweetAlert\Toaster position(string $position = 'top-end')
 * @method static \RealRashid\SweetAlert\Toaster width(string $width = '32rem')
 * @method static \RealRashid\SweetAlert\Toaster padding(string $padding = '1.25rem')
 * @method static \RealRashid\SweetAlert\Toaster background(string $background = '#fff')
 * @method static \RealRashid\SweetAlert\Toaster animation(string $showAnimation, string $hideAnimation)
 * @method static \RealRashid\SweetAlert\Toaster toToast(string $position = '')
 * @method static \RealRashid\SweetAlert\Toaster toHtml()
 * @method static \RealRashid\SweetAlert\Toaster addImage(string $imageUrl)
 * @method static \RealRashid\SweetAlert\Toaster footer(string $code)
 * @method static \RealRashid\SweetAlert\Toaster focusConfirm(bool $focus = true)
 * @method static \RealRashid\SweetAlert\Toaster focusCancel(bool $focus = false)
 * @method static \RealRashid\SweetAlert\Toaster buttonsStyling(bool $buttonsStyling)
 * @method static \RealRashid\SweetAlert\Toaster iconHtml(string $iconHtml)
 * @method static \RealRashid\SweetAlert\Toaster timerProgressBar()
 * @method static \RealRashid\SweetAlert\Toaster reverseButtons()
 * @method static \RealRashid\SweetAlert\Toaster middleware()
 * @method static \RealRashid\SweetAlert\Toaster view(string $title, string $view, array $data = [], array $mergeData = [], string $icon = '')
 *
 * @see \RealRashid\SweetAlert\Toaster
 */
class Alert extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'alert';
    }
}
