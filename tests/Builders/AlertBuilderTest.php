<?php

use RealRashid\SweetAlert\Builders\AlertBuilder;
use RealRashid\SweetAlert\Enums\AlertType;
use RealRashid\SweetAlert\Enums\Position;

describe('AlertBuilder', function () {

    it('can be created via make() factory', function () {
        $builder = AlertBuilder::make();
        expect($builder)->toBeInstanceOf(AlertBuilder::class);
    });

    it('sets title fluently', function () {
        $builder = createAlertBuilder();
        $result = $builder->title('Test Title');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['title'])->toBe('Test Title');
    });

    it('sets text fluently', function () {
        $builder = createAlertBuilder();
        $result = $builder->text('Test text');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['text'])->toBe('Test text');
    });

    it('sets icon via AlertType enum', function () {
        $builder = createAlertBuilder();
        $builder->icon(AlertType::Success);

        expect($builder->getConfig()['icon'])->toBe('success');
    });

    it('sets icon via string', function () {
        $builder = createAlertBuilder();
        $builder->icon('warning');

        expect($builder->getConfig()['icon'])->toBe('warning');
    });

    it('provides shorthand methods for all alert types', function () {
        $types = [
            'success' => AlertType::Success,
            'error' => AlertType::Error,
            'warning' => AlertType::Warning,
            'info' => AlertType::Info,
            'question' => AlertType::Question,
        ];

        foreach ($types as $method => $expected) {
            $builder = createAlertBuilder();
            $builder->{$method}();
            expect($builder->getConfig()['icon'])->toBe($expected->value);
        }
    });

    it('question() auto-shows cancel button', function () {
        $builder = createAlertBuilder();
        $builder->question();

        expect($builder->getConfig()['icon'])->toBe('question');
        expect($builder->getConfig()['showCancelButton'])->toBeTrue();
    });

    it('supports method chaining', function () {
        $builder = createAlertBuilder();

        $result = $builder
            ->title('Chained')
            ->success()
            ->text('Chained text')
            ->timer(3000)
            ->timerProgressBar()
            ->position('top-end');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['title'])->toBe('Chained');
        expect($builder->getConfig()['icon'])->toBe('success');
        expect($builder->getConfig()['text'])->toBe('Chained text');
        expect($builder->getConfig()['timer'])->toBe(3000);
        expect($builder->getConfig()['timerProgressBar'])->toBeTrue();
        expect($builder->getConfig()['position'])->toBe('top-end');
    });

    it('sets HTML content', function () {
        $builder = createAlertBuilder();
        $builder->html('<strong>Bold</strong> text');

        expect($builder->getConfig()['html'])->toBe('<strong>Bold</strong> text');
        expect($builder->getConfig())->not->toHaveKey('text');
    });

    it('converts text to HTML', function () {
        $builder = createAlertBuilder();
        $builder->title('Test')->text('Some text');
        $builder->toHtml();

        expect($builder->getConfig()['html'])->toBe('Some text');
        expect($builder->getConfig())->not->toHaveKey('text');
    });

    it('creates a confirm dialog', function () {
        $builder = createAlertBuilder();
        $builder->confirm('Are you sure?', 'This cannot be undone.');

        expect($builder->getConfig()['title'])->toBe('Are you sure?');
        expect($builder->getConfig()['text'])->toBe('This cannot be undone.');
        expect($builder->getConfig()['showCancelButton'])->toBeTrue();
    });

    it('can be reset to default state', function () {
        $builder = createAlertBuilder();
        $builder->title('Old Title')->success();
        $builder->reset();

        expect($builder->getConfig()['title'])->toBe('');
    });

    it('serializes to array', function () {
        $builder = createAlertBuilder();
        $builder->title('Test')->success();

        $array = $builder->toArray();

        expect($array['title'])->toBe('Test');
        expect($array['icon'])->toBe('success');
    });

    it('serializes to JSON', function () {
        $builder = createAlertBuilder();
        $builder->title('Test')->success();

        $json = $builder->toJson();
        $decoded = json_decode($json, true);

        expect($decoded['title'])->toBe('Test');
        expect($decoded['icon'])->toBe('success');
    });

    it('filters empty values from toArray', function () {
        $builder = createAlertBuilder();
        $builder->title('Only Title');

        $array = $builder->toArray();

        expect($array)->toHaveKey('title');
        // Empty strings should be filtered
        expect($array['title'])->toBe('Only Title');
    });
});

describe('AlertBuilder - Position', function () {

    it('sets position via enum', function () {
        $builder = createAlertBuilder();
        $builder->position(Position::BottomEnd);

        expect($builder->getConfig()['position'])->toBe('bottom-end');
    });

    it('sets position via string', function () {
        $builder = createAlertBuilder();
        $builder->position('center');

        expect($builder->getConfig()['position'])->toBe('center');
    });

    it('provides shorthand position methods', function () {
        $builder = createAlertBuilder();
        $builder->topEnd();

        expect($builder->getConfig()['position'])->toBe('top-end');
    });
});

describe('AlertBuilder - Timer', function () {

    it('sets timer', function () {
        $builder = createAlertBuilder();
        $builder->timer(3000);

        expect($builder->getConfig()['timer'])->toBe(3000);
    });

    it('autoClose is alias for timer', function () {
        $builder = createAlertBuilder();
        $builder->autoClose(4000);

        expect($builder->getConfig()['timer'])->toBe(4000);
    });

    it('enables timer progress bar', function () {
        $builder = createAlertBuilder();
        $builder->timerProgressBar();

        expect($builder->getConfig()['timerProgressBar'])->toBeTrue();
    });

    it('persistent removes timer and disables escape/outside click', function () {
        $builder = createAlertBuilder();
        $builder->timer(5000);
        $builder->persistent();

        expect($builder->getConfig())->not->toHaveKey('timer');
        expect($builder->getConfig()['allowEscapeKey'])->toBeFalse();
        expect($builder->getConfig()['allowOutsideClick'])->toBeFalse();
    });
});

describe('AlertBuilder - Buttons', function () {

    it('shows confirm button with custom text and color', function () {
        $builder = createAlertBuilder();
        $builder->showConfirmButton('Got it!', '#00ff00');

        expect($builder->getConfig()['showConfirmButton'])->toBeTrue();
        expect($builder->getConfig()['confirmButtonText'])->toBe('Got it!');
        expect($builder->getConfig()['confirmButtonColor'])->toBe('#00ff00');
    });

    it('shows cancel button', function () {
        $builder = createAlertBuilder();
        $builder->showCancelButton('Nope', '#ff0000');

        expect($builder->getConfig()['showCancelButton'])->toBeTrue();
        expect($builder->getConfig()['cancelButtonText'])->toBe('Nope');
    });

    it('shows deny button (NEW feature)', function () {
        $builder = createAlertBuilder();
        $builder->denyButton('Archive', '#3085d6');

        expect($builder->getConfig()['showDenyButton'])->toBeTrue();
        expect($builder->getConfig()['denyButtonText'])->toBe('Archive');
        expect($builder->getConfig()['denyButtonColor'])->toBe('#3085d6');
    });

    it('shows and hides close button', function () {
        $builder = createAlertBuilder();
        $builder->showCloseButton();

        expect($builder->getConfig()['showCloseButton'])->toBeTrue();

        $builder->hideCloseButton();

        expect($builder->getConfig()['showCloseButton'])->toBeFalse();
    });

    it('reverses button order', function () {
        $builder = createAlertBuilder();
        $builder->reverseButtons();

        expect($builder->getConfig()['reverseButtons'])->toBeTrue();
    });

    it('enables showLoaderOnConfirm', function () {
        $builder = createAlertBuilder();
        $builder->showLoaderOnConfirm();

        expect($builder->getConfig()['showLoaderOnConfirm'])->toBeTrue();
    });
});

describe('AlertBuilder - Styling', function () {

    it('sets width', function () {
        $builder = createAlertBuilder();
        $builder->width('500px');

        expect($builder->getConfig()['width'])->toBe('500px');
    });

    it('sets background color', function () {
        $builder = createAlertBuilder();
        $builder->background('#000');

        expect($builder->getConfig()['background'])->toBe('#000');
    });

    it('merges custom CSS classes', function () {
        $builder = createAlertBuilder();
        $builder->customClass(['popup' => 'my-popup']);
        $builder->customClass(['title' => 'my-title']);

        expect($builder->getConfig()['customClass']['popup'])->toBe('my-popup');
        expect($builder->getConfig()['customClass']['title'])->toBe('my-title');
    });

    it('sets footer HTML', function () {
        $builder = createAlertBuilder();
        $builder->footer('<a href="/help">Help</a>');

        expect($builder->getConfig()['footer'])->toBe('<a href="/help">Help</a>');
    });

    it('sets custom icon HTML', function () {
        $builder = createAlertBuilder();
        $builder->iconHtml('<i class="fa fa-check"></i>');

        expect($builder->getConfig()['iconHtml'])->toBe('<i class="fa fa-check"></i>');
    });

    it('sets icon color', function () {
        $builder = createAlertBuilder();
        $builder->iconColor('#ff0000');

        expect($builder->getConfig()['iconColor'])->toBe('#ff0000');
    });
});

describe('AlertBuilder - Animation', function () {

    it('sets animation classes', function () {
        $builder = createAlertBuilder();
        $builder->animation('fadeIn', 'fadeOut');

        expect($builder->getConfig()['showClass']['popup'])->toBe('animate__animated fadeIn');
        expect($builder->getConfig()['hideClass']['popup'])->toBe('animate__animated fadeOut');
    });

    it('disables animation', function () {
        $builder = createAlertBuilder();
        $builder->disableAnimation();

        expect($builder->getConfig()['animation'])->toBeFalse();
    });
});

describe('AlertBuilder - Backward Compatibility', function () {

    it('alert() shim works like old API', function () {
        $builder = createAlertBuilder();
        $result = $builder->alert('Title', 'Text', 'success');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['title'])->toBe('Title');
        expect($builder->getConfig()['text'])->toBe('Text');
        expect($builder->getConfig()['icon'])->toBe('success');
    });
});

describe('AlertBuilder - BUG Regressions', function () {

    it('showConfirmButton does not force allowOutsideClick=false (BUG-7)', function () {
        $builder = createAlertBuilder();
        $builder->showConfirmButton('Yes!');

        // Before the fix, showConfirmButton() also set allowOutsideClick = false
        expect($builder->getConfig())->not->toHaveKey('allowOutsideClick');
    });

    it('confirm() reads from sweetalert.confirm config block (BUG-8)', function () {
        config(['sweetalert.confirm.icon' => 'info']);
        $builder = createAlertBuilder();
        $builder->confirm('Sure?');

        expect($builder->getConfig()['icon'])->toBe('info');
        // confirm() should NOT set showLoaderOnConfirm (that belongs to confirmDelete only)
        expect($builder->getConfig())->not->toHaveKey('showLoaderOnConfirm');
    });

    it('alert() shim does not flash prematurely — chaining still works (BUG-9)', function () {
        $builder = createAlertBuilder();

        // If alert() called flash() internally it would reset config, losing title/text
        $result = $builder->alert('Keep Me', 'And Me')->success();

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['title'])->toBe('Keep Me');
        expect($builder->getConfig()['text'])->toBe('And Me');
        expect($builder->getConfig()['icon'])->toBe('success');
    });
});

describe('AlertBuilder - New Feature Methods', function () {

    it('sets per-alert theme', function () {
        $builder = createAlertBuilder();
        $builder->theme('dark');

        expect($builder->getConfig()['theme'])->toBe('dark');
    });

    it('theme() returns builder for chaining', function () {
        $builder = createAlertBuilder();
        $result = $builder->theme('minimal');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
    });

    it('applies a named preset from config', function () {
        config(['sweetalert.presets.danger' => ['icon' => 'error', 'confirmButtonColor' => '#d33']]);
        $builder = createAlertBuilder();
        $builder->title('Delete?')->preset('danger');

        expect($builder->getConfig()['icon'])->toBe('error');
        expect($builder->getConfig()['confirmButtonColor'])->toBe('#d33');
        expect($builder->getConfig()['title'])->toBe('Delete?');
    });

    it('preset() with unknown name does not change config', function () {
        $builder = createAlertBuilder();
        $builder->title('Original')->preset('nonexistent');

        expect($builder->getConfig()['title'])->toBe('Original');
    });

    it('sets preDenyRoute', function () {
        $builder = createAlertBuilder();
        $builder->preDenyRoute('/api/deny');

        expect($builder->getConfig()['preDenyRoute'])->toBe('/api/deny');
    });

    it('sets validationMessage', function () {
        $builder = createAlertBuilder();
        $builder->validationMessage('Please enter a valid value.');

        expect($builder->getConfig()['validationMessage'])->toBe('Please enter a valid value.');
    });

    it('sets progressStepsDistance', function () {
        $builder = createAlertBuilder();
        $builder->progressStepsDistance('40px');

        expect($builder->getConfig()['progressStepsDistance'])->toBe('40px');
    });
});

describe('AlertBuilder - HasTimer New Methods', function () {

    it('sets stopKeydownPropagation', function () {
        $builder = createAlertBuilder();
        $builder->stopKeydownPropagation();

        expect($builder->getConfig()['stopKeydownPropagation'])->toBeTrue();
    });

    it('stopKeydownPropagation can be disabled', function () {
        $builder = createAlertBuilder();
        $builder->stopKeydownPropagation(false);

        expect($builder->getConfig()['stopKeydownPropagation'])->toBeFalse();
    });

    it('sets keydownListenerCapture', function () {
        $builder = createAlertBuilder();
        $builder->keydownListenerCapture();

        expect($builder->getConfig()['keydownListenerCapture'])->toBeTrue();
    });

    it('keydownListenerCapture can be disabled', function () {
        $builder = createAlertBuilder();
        $builder->keydownListenerCapture(false);

        expect($builder->getConfig()['keydownListenerCapture'])->toBeFalse();
    });

    it('stopKeydownPropagation and keydownListenerCapture are chainable', function () {
        $builder = createAlertBuilder();
        $result = $builder->stopKeydownPropagation()->keydownListenerCapture();

        expect($result)->toBeInstanceOf(AlertBuilder::class);
        expect($builder->getConfig()['stopKeydownPropagation'])->toBeTrue();
        expect($builder->getConfig()['keydownListenerCapture'])->toBeTrue();
    });
});

describe('AlertBuilder - HasButtons New Methods', function () {

    it('shows loader on deny button', function () {
        $builder = createAlertBuilder();
        $builder->showLoaderOnDeny();

        expect($builder->getConfig()['showLoaderOnDeny'])->toBeTrue();
    });

    it('sets focus to deny button', function () {
        $builder = createAlertBuilder();
        $builder->focusDeny();

        expect($builder->getConfig()['focusDeny'])->toBeTrue();
    });

    it('enables return focus after close', function () {
        $builder = createAlertBuilder();
        $builder->returnFocus();

        expect($builder->getConfig()['returnFocus'])->toBeTrue();
    });

    it('returnFocus can be disabled', function () {
        $builder = createAlertBuilder();
        $builder->returnFocus(false);

        expect($builder->getConfig()['returnFocus'])->toBeFalse();
    });

    it('sets confirm button aria label', function () {
        $builder = createAlertBuilder();
        $builder->confirmButtonAriaLabel('Confirm this action');

        expect($builder->getConfig()['confirmButtonAriaLabel'])->toBe('Confirm this action');
    });

    it('sets deny button aria label', function () {
        $builder = createAlertBuilder();
        $builder->denyButtonAriaLabel('Deny this action');

        expect($builder->getConfig()['denyButtonAriaLabel'])->toBe('Deny this action');
    });

    it('sets cancel button aria label', function () {
        $builder = createAlertBuilder();
        $builder->cancelButtonAriaLabel('Cancel this action');

        expect($builder->getConfig()['cancelButtonAriaLabel'])->toBe('Cancel this action');
    });

    it('sets returnInputValueOnDeny', function () {
        $builder = createAlertBuilder();
        $builder->returnInputValueOnDeny();

        expect($builder->getConfig()['returnInputValueOnDeny'])->toBeTrue();
    });

    it('returnInputValueOnDeny can be disabled', function () {
        $builder = createAlertBuilder();
        $builder->returnInputValueOnDeny(false);

        expect($builder->getConfig()['returnInputValueOnDeny'])->toBeFalse();
    });
});

describe('AlertBuilder - HasStyling New Methods', function () {

    it('sets titleText (XSS-safe plain text title)', function () {
        $builder = createAlertBuilder();
        $builder->titleText('Plain text title');

        expect($builder->getConfig()['titleText'])->toBe('Plain text title');
    });

    it('sets target container selector', function () {
        $builder = createAlertBuilder();
        $builder->target('#app');

        expect($builder->getConfig()['target'])->toBe('#app');
    });

    it('enables topLayer', function () {
        $builder = createAlertBuilder();
        $builder->topLayer();

        expect($builder->getConfig()['topLayer'])->toBeTrue();
    });

    it('disables topLayer', function () {
        $builder = createAlertBuilder();
        $builder->topLayer(false);

        expect($builder->getConfig()['topLayer'])->toBeFalse();
    });

    it('enables scrollbarPadding', function () {
        $builder = createAlertBuilder();
        $builder->scrollbarPadding();

        expect($builder->getConfig()['scrollbarPadding'])->toBeTrue();
    });

    it('disables scrollbarPadding', function () {
        $builder = createAlertBuilder();
        $builder->scrollbarPadding(false);

        expect($builder->getConfig()['scrollbarPadding'])->toBeFalse();
    });

    it('makes popup draggable', function () {
        $builder = createAlertBuilder();
        $builder->draggable();

        expect($builder->getConfig()['draggable'])->toBeTrue();
    });

    it('sets custom loaderHtml', function () {
        $builder = createAlertBuilder();
        $builder->loaderHtml('<div class="spinner"></div>');

        expect($builder->getConfig()['loaderHtml'])->toBe('<div class="spinner"></div>');
    });

    it('sets custom closeButtonHtml', function () {
        $builder = createAlertBuilder();
        $builder->closeButtonHtml('<i class="fa fa-times"></i>');

        expect($builder->getConfig()['closeButtonHtml'])->toBe('<i class="fa fa-times"></i>');
    });

    it('HasStyling new methods are chainable', function () {
        $builder = createAlertBuilder();
        $result = $builder
            ->titleText('Title')
            ->target('body')
            ->topLayer()
            ->draggable()
            ->loaderHtml('<span>Loading...</span>');

        expect($result)->toBeInstanceOf(AlertBuilder::class);
    });
});
