<?php

namespace RealRashid\SweetAlert\Facades;

use Illuminate\Support\Facades\Facade;
use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Builders\InputBuilder;
use RealRashid\SweetAlert\Builders\ToastBuilder;
use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\InputType;
use RealRashid\SweetAlert\Enums\Position;

/**
 * @see AlertBuilder
 *
 * Core Configuration
 *
 * @method static AlertBuilder title(string $title)
 * @method static AlertBuilder text(string $text)
 * @method static AlertBuilder icon(string|AlertType $type)
 * @method static AlertBuilder success(string $title = '', string $text = '')
 * @method static AlertBuilder error(string $title = '', string $text = '')
 * @method static AlertBuilder warning(string $title = '', string $text = '')
 * @method static AlertBuilder info(string $title = '', string $text = '')
 * @method static AlertBuilder question(string $title = '', string $text = '')
 * @method static AlertBuilder html(string $html)
 * @method static AlertBuilder toHtml()
 * @method static AlertBuilder view(string $view, array $data = [], array $mergeData = [])
 * @method static AlertBuilder submitTo(string $url, string $method = 'POST', string $field = 'value')
 *
 * Factory Methods
 * @method static ToastBuilder toast(string $title = '', ?string $icon = null)
 * @method static InputBuilder input(string $title = '', string|InputType $inputType = InputType::Text)
 * @method static AlertBuilder confirm(string $title = '', ?string $text = null)
 * @method static AlertBuilder confirmDelete(string $title, ?string $text = null)
 * @method static AlertBuilder make()
 * @method static AlertBuilder reset()
 *
 * Alert-specific Methods
 * @method static AlertBuilder preConfirmRoute(string $route)
 * @method static AlertBuilder preDenyRoute(string $route)
 * @method static AlertBuilder progressSteps(array $steps)
 * @method static AlertBuilder currentProgressStep(int $index)
 * @method static AlertBuilder progressStepsDistance(string $distance)
 * @method static AlertBuilder validationMessage(string $message)
 * @method static AlertBuilder preset(string $name)
 * @method static AlertBuilder theme(string $theme)
 *
 * Backward Compatibility
 * @method static AlertBuilder alert(string $title = '', string $text = '', ?string $icon = null)
 *
 * Conditionable & Macroable
 * @method static AlertBuilder when(mixed $value, ?callable $callback = null, ?callable $default = null)
 * @method static AlertBuilder unless(mixed $value, ?callable $callback = null, ?callable $default = null)
 *
 * Flash & Serialize
 * @method static AlertBuilder flash(string $type = 'config')
 * @method static array toArray()
 * @method static string toJson()
 * @method static array getConfig()
 *
 * HasTimer
 * @method static AlertBuilder timer(int $milliseconds)
 * @method static AlertBuilder autoClose(int $milliseconds = 5000)
 * @method static AlertBuilder timerProgressBar(bool $enabled = true)
 * @method static AlertBuilder persistent(bool $showConfirmBtn = true, bool $showCloseBtn = false)
 * @method static AlertBuilder stopKeydownPropagation(bool $enabled = true)
 * @method static AlertBuilder keydownListenerCapture(bool $enabled = true)
 *
 * HasPosition
 * @method static AlertBuilder position(string|Position $position)
 * @method static AlertBuilder top()
 * @method static AlertBuilder topStart()
 * @method static AlertBuilder topEnd()
 * @method static AlertBuilder topLeft()
 * @method static AlertBuilder topRight()
 * @method static AlertBuilder center()
 * @method static AlertBuilder centerStart()
 * @method static AlertBuilder centerEnd()
 * @method static AlertBuilder centerLeft()
 * @method static AlertBuilder centerRight()
 * @method static AlertBuilder bottom()
 * @method static AlertBuilder bottomStart()
 * @method static AlertBuilder bottomEnd()
 * @method static AlertBuilder bottomLeft()
 * @method static AlertBuilder bottomRight()
 *
 * HasButtons
 * @method static AlertBuilder showConfirmButton(string $btnText = 'OK', string $btnColor = '#3085d6')
 * @method static AlertBuilder confirmButton(string $text = 'OK', string $color = '#3085d6')
 * @method static AlertBuilder showCancelButton(string $btnText = 'Cancel', string $btnColor = '#aaa')
 * @method static AlertBuilder cancelButton(string $text = 'Cancel', string $color = '#aaa')
 * @method static AlertBuilder showDenyButton(string $btnText = 'Deny', string $btnColor = '#dd6b55')
 * @method static AlertBuilder denyButton(string $text = 'Deny', string $color = '#dd6b55')
 * @method static AlertBuilder showCloseButton(string $closeButtonAriaLabel = 'Close this dialog')
 * @method static AlertBuilder hideCloseButton()
 * @method static AlertBuilder closeButtonAriaLabel(string $label)
 * @method static AlertBuilder reverseButtons()
 * @method static AlertBuilder buttonsStyling(bool $enabled = true)
 * @method static AlertBuilder focusConfirm(bool $focus = true)
 * @method static AlertBuilder focusCancel(bool $focus = true)
 * @method static AlertBuilder focusDeny(bool $focus = true)
 * @method static AlertBuilder showLoaderOnConfirm(bool $enabled = true)
 * @method static AlertBuilder showLoaderOnDeny(bool $enabled = true)
 * @method static AlertBuilder returnFocus(bool $enabled = true)
 * @method static AlertBuilder confirmButtonAriaLabel(string $label)
 * @method static AlertBuilder denyButtonAriaLabel(string $label)
 * @method static AlertBuilder cancelButtonAriaLabel(string $label)
 * @method static AlertBuilder returnInputValueOnDeny(bool $enabled = true)
 *
 * HasAnimation
 * @method static AlertBuilder animation(string $showAnimation, string $hideAnimation)
 * @method static AlertBuilder disableAnimation()
 * @method static AlertBuilder showClass(array $classes)
 * @method static AlertBuilder hideClass(array $classes)
 *
 * HasStyling
 * @method static AlertBuilder width(string $width)
 * @method static AlertBuilder padding(string $padding)
 * @method static AlertBuilder background(string $color)
 * @method static AlertBuilder color(string $color)
 * @method static AlertBuilder heightAuto(bool $enabled = true)
 * @method static AlertBuilder customClass(array $classes)
 * @method static AlertBuilder iconHtml(string $html)
 * @method static AlertBuilder iconColor(string $color)
 * @method static AlertBuilder imageUrl(string $url, ?int $width = null, ?int $height = null, ?string $alt = null)
 * @method static AlertBuilder addImage(string $url, int $width = 400, int $height = 200, ?string $alt = null)
 * @method static AlertBuilder footer(string $html)
 * @method static AlertBuilder grow(string $direction = 'false')
 * @method static AlertBuilder backdrop(mixed $backdrop = true)
 * @method static AlertBuilder allowEscapeKey(bool $allow = true)
 * @method static AlertBuilder allowOutsideClick(bool $allow = true)
 * @method static AlertBuilder stopPropagation(bool $enabled = true)
 * @method static AlertBuilder titleText(string $text)
 * @method static AlertBuilder target(string $selector)
 * @method static AlertBuilder topLayer(bool $enabled = true)
 * @method static AlertBuilder scrollbarPadding(bool $enabled = true)
 * @method static AlertBuilder draggable(bool $enabled = true)
 * @method static AlertBuilder loaderHtml(string $html)
 * @method static AlertBuilder closeButtonHtml(string $html)
 */
class Alert extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'alert';
    }
}
