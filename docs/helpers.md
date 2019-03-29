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
toast('Success Toast','success','top-right')->autoClose(5000);
```

##### Position

`
position($position = 'top-end')
`

``` php
// example:
alert('Title','Lorem Lorem Lorem', 'success')->position('top-left');
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
