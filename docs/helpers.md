# Helper Methods

##### Persistent Alert

`persistent($showConfirmBtn = true, $showCloseBtn = false)`

```php
// example:
alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.')->persistent(true,false);
```

##### AutoClose Alert

`autoClose($milliseconds = 5000)`

```php
// example:
toast('Success Toast','success')->autoClose(5000);
```

##### Position

`position($position = 'top-end')`

> Modal window and Toast position, can be **'top'**, **'top-start'**, **'top-end'**,
> **'center'**, **'center-start'**, **'center-end'**, **'bottom'**, **'bottom-start'**, or **'bottom-end'**.

```php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->position('top-end');
```

##### ShowConfirmButton

`showConfirmButton($btnText = 'Ok', $btnColor = '#3085d6')`

```php
// example:
alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.')->showConfirmButton('Confirm', '#3085d6');
```

##### ShowCancelButton

`showCancelButton($btnText = 'Cancel', $btnColor = '#aaa')`

```php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton('Cancel', '#aaa');
```

##### ShowCloseButton

`showCloseButton($closeButtonAriaLabel = 'aria-label')`

```php
// example:
toast('Post Updated','success','top-right')->showCloseButton();
```

##### HideCloseButton

`hideCloseButton()`

```php
// example:
toast('Post Updated','success','top-right')->hideCloseButton();
```

##### ReverseButtons

`reverseButtons()`

```php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')
->showConfirmButton('Yes! Delete it', '#3085d6')
->showCancelButton('Cancel', '#aaa')->reverseButtons();
```

##### Footer

`footer($HTMLcode)`

```php
// example:
alert()->error('Oops...', 'Something went wrong!')->footer('<a href="#">Why do I have this issue?</a>');
```

##### ToToast

`toToast($position = 'top-right')`

```php
// example:
alert()->success('Post Created', 'Successfully')->toToast();
```

##### ToHTML

`toHtml()`

```php
// example:
alert()->success('Post Created', '<strong>Successfully</strong>')->toHtml();
```

##### AddImage

`addImage($imageUrl)`

> This will remove alert type and add close button

```php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->addImage('https://unsplash.it/400/200');
```

##### Width

`width('32rem')`

> Modal window width, including paddings (box-sizing: border-box).
> Can be in px or %.
> The default width is 32rem.

```php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->width('720px');
```

##### Padding

`padding('1.25rem')`

> Modal window padding. Can be in px or %. The default padding is 1.25rem.

```php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->padding('50px');
```

##### Background

`background('#fff')`

> Modal window background (CSS background property). The default background is '#fff'.

```php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->background('#fff');
```

##### Animation

`animation($showAnimation, $hideAnimation)`

Custom animation with [Animate.css](https://daneden.github.io/animate.css/)

```php
// example:
alert()->info('InfoAlert','Lorem ipsum dolor sit amet.')
->animation('tada faster','fadeInUp faster');
```

> Note: Animate.css CDN link is imported in package config file,

##### Button Styling

`buttonsStyling($buttonsStyling)`

> Apply default styling to buttons.
> If you want to use your own classes (e.g. Bootstrap classes)
> set this parameter to false.

```php
// example:
alert()->success('Post Created', 'Successfully')->buttonsStyling(false);
```

##### Icon Html

`iconHtml($iconHtml)`

> Use any HTML inside icons (e.g. Font Awesome)

```php
// example:
alert()->success('Post Created', 'Successfully')->iconHtml('<i class="far fa-thumbs-up"></i>);
```

##### Focus Confirm

`focusConfirm(true)`

> Set to false if you want to focus the first element in tab order instead of "Confirm"-button by default.

```php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton()->showConfirmButton()->focusConfirm(true);
```

##### Focus Cancel

`focusCancel(false)`

> Set to true if you want to focus the "Cancel"-button by default.

```php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton()->showConfirmButton()->focusCancel(true);
```

##### Timer Progress Bar

`timerProgressBar()`

> The timer will have a progress bar at the bottom of a popup. Mostly, this feature is useful with toasts.

```php
// example:
toast('Signed in successfully','success')->timerProgressBar();
```
