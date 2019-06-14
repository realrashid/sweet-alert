# Helper Methods

##### Persistent Alert

`
persistent($showConfirmBtn = true, $showCloseBtn = false)
`

``` php
// example:
alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.')->persistent(true,false);
```

##### AutoClose Alert

`
autoClose($milliseconds = 5000)
`

``` php
// example:
toast('Success Toast','success')->autoClose(5000);
```

##### Position

`
position($position = 'top-end')
`
> Modal window and Toast position, can be **'top'**, **'top-start'**, **'top-end'**,
**'center'**, **'center-start'**, **'center-end'**, **'bottom'**, **'bottom-start'**, or **'bottom-end'**.

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->position('top-end');
```

##### ShowConfirmButton

`
showConfirmButton($btnText = 'Ok', $btnColor = '#3085d6')
`

``` php
// example:
alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.')->showConfirmButton('Confirm', '#3085d6');
```

##### ShowCancelButton

`
showCancelButton($btnText = 'Cancel', $btnColor = '#aaa')
`

``` php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton('Cancel', '#aaa');
```

##### ShowCloseButton

`
showCloseButton($closeButtonAriaLabel = 'aria-label')
`

``` php
// example:
toast('Post Updated','success','top-right')->showCloseButton();
```

##### HideCloseButton

`
hideCloseButton()
`

``` php
// example:
toast('Post Updated','success','top-right')->hideCloseButton();
```

##### ReverseButtons

`
reverseButtons()
`

``` php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')
->showConfirmButton('Yes! Delete it', '#3085d6')
->showCancelButton('Cancel', '#aaa')->reverseButtons();
```

##### Footer

`
footer($HTMLcode)
`

``` php
// example:
alert()->error('Oops...', 'Something went wrong!')->footer('<a href="#">Why do I have this issue?</a>');
```

##### ToToast

`
toToast($position = 'top-right')
`

``` php
// example:
alert()->success('Post Created', 'Successfully')->toToast();
```

##### ToHTML

`
toHtml()
`

``` php
// example:
alert()->success('Post Created', '<strong>Successfully</strong>')->toHtml();
```

##### AddImage

`
addImage($imageUrl)
`
> This will remove alert type and add close button

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->addImage('https://unsplash.it/400/200');
```

##### Width

`
width('32rem')
`
> Modal window width, including paddings (box-sizing: border-box).
  Can be in px or %.
  The default width is 32rem.

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->width('720px');
```

##### Padding

`
padding('1.25rem')
`
> Modal window padding. Can be in px or %. The default padding is 1.25rem.

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->padding('50px');
```

##### Background

`
background('#fff')
`
> Modal window background (CSS background property). The default background is '#fff'.

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->background('#fff');
```

##### Animation

`
animation(true)
`
> If set to false, modal CSS animation will be disabled.

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->animation(false);
```

##### Focus Confirm

`
focusConfirm(true)
`
> Set to false if you want to focus the first element in tab order instead of "Confirm"-button by default.

``` php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton()->showConfirmButton()->focusConfirm(true);
```

##### Focus Cancel

`
focusCancel(false)
`
> Set to true if you want to focus the "Cancel"-button by default.

``` php
// example:
alert()->question('Are you sure?','You won\'t be able to revert this!')->showCancelButton()->showConfirmButton()->focusCancel(true);
```
