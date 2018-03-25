<p align="center">
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/realrashid/sweet-alert"><img src="https://poser.pugx.org/realrashid/sweet-alert/license.svg" alt="License"></a>
<a href="https://www.patreon.com/realrashid"><img alt="Support me on Patreon" src="http://ionicabizau.github.io/badges/patreon.svg"></a>
<a href="https://liberapay.com/realrashid/donate"><img alt="Support me on Liberapay" src="https://liberapay.com/assets/widgets/donate.svg"></a>
</p>

> note: if you are using sweet-alert v1.0 you can get READMEfor v1.0 from [here](https://github.com/realrashid/sweet-alert/blob/1.0/readme.md)

# Introduction

A BEAUTIFUL, RESPONSIVE, CUSTOMIZABLE, ACCESSIBLE (WAI-ARIA) REPLACEMENT FOR JAVASCRIPT'S POPUP BOXES

ZERO DEPENDENCIES

<p align="center">
    <img src="https://raw.github.com/sweetalert2/sweetalert2/master/assets/sweetalert2.gif" width="562" height="388">
</p>

# Install

To get started with SweetAlert2, use Composer to add the package to your project's dependencies:

```
composer require realrashid/sweet-alert
```

## Configuration

> Note, Optional in `Laravel 5.5 or +`

After installing the sweet-alert package, register the
`RealRashid\SweetAlert\SweetAlertServiceProvider::class`
in your `config/app.php` configuration file:

```php
'providers' => [
    // Other service providers...

    RealRashid\SweetAlert\SweetAlertServiceProvider::class,
],
```

Also, add the `Alert` facade to the `aliases` array in your `app` configuration file:

```php
'Alert' => RealRashid\SweetAlert\Facades\Alert::class,
```

## Import SweetAlert 2 Library

in your master layout

```javascript
<script src="https://unpkg.com/sweetalert2@7.18.0/dist/sweetalert2.all.js"></script>
```

and include sweetalert view

`@include('sweetalert::alert')`

> Include the sweetalert view below the cdn link in your layout!

## Usage

#### Using Facade

Import Alert Facade first!

`use RealRashid\SweetAlert\Facades\Alert;` or
`Use Alert;` in your controller

*   `Alert::alert('Title', 'Message', 'Type');`
*   `Alert::success('Success Title', 'Success Message');`
*   `Alert::info('Info Title', 'Info Message');`
*   `Alert::warning('Warning Title', 'Warning Message');`
*   `Alert::error('Error Title', 'Error Message');`
*   `Alert::question('Question Title', 'Question Message');`
*   `Alert::html('Html Title', 'Html Code', 'Type');`
*   `Alert::toast('Toast Message', 'Toast Type', 'Toast Position');`

### Using the helper function

#### Alert

*   `alert('Title','Lorem Lorem Lorem', 'success');`

*   `alert()->success('Title','Lorem Lorem Lorem');`

*   `alert()->info('Title','Lorem Lorem Lorem');`

*   `alert()->warning('Title','Lorem Lorem Lorem');`

*   `alert()->question('Title','Lorem Lorem Lorem');`

*   `alert()->error('Title','Lorem Lorem Lorem');`

*   `alert()->html('<i>HTML</i> <u>example</u>'," You can use <b>bold text</b>, <a href='//github.com'>links</a> and other HTML tags ",'success');`

#### Toast

*   `toast('Your Post as been submited!','success','top-right');`

### Demo

#### Success Alert

```php
/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
*/
public function store(PostRequest $request)
{
    $post = Post::create($request->all());

    if ($post) {
        alert()->success('Post Created', 'Successfully');
        return redirect()->route('posts.index');
    }
}
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/SuccessAlert1.png" alt="SuccessAlert">
</p>

#### Error Alert

```php

 /**
 * Get the failed login response instance.
 *
 * @param  \Illuminate\Http\Request  $request
* @return \Symfony\Component\HttpFoundation\Response
*
* @throws \Illuminate\Validation\ValidationException
*/
protected function sendFailedLoginResponse(Request $request)
{
    alert()->error('Oops...', 'Something went wrong!');

    throw ValidationException::withMessages([
        $this->username() => [trans('auth.failed')],
    ]);
}

```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/ErrorAlert.png" alt="ErrorAlert">
</p>

#### Success Toast

``` php
/**
 * Remove the specified resource from storage.
 *
 * @param  \App\Post  $post
 * @return \Illuminate\Http\Response
*/
public function destroy($id)
{
    $post = Post::find($id);

    $post->delete();

    if ($post) {
        toast('Post Deleted Successfully','success','top-right');
        return redirect()->route('posts.index');
    }
}
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/SuccessToast1.png" alt="SuccessToast">
</p>


## Methods Definition

#### Alert Methods

|         Method        	|             Argument           	|
|:---------------------:	|:------------------------------:	|
|       `alert()`       	|    `$title, $message, $type`   	|
|  `alert()->success()` 	|       `$title, $message`       	|
|   `alert()->info()`   	|       `$title, $message`       	|
|  `alert()->warning()` 	|       `$title, $message`       	|
|   `alert()->error()`  	|       `$title, $message`       	|
| `alert()->question()` 	|       `$title, $message`       	|
|    `alert()->html()`   	| `$htmltitle, $htmlCode, $type` 	|
|      `toast()`     	    | `$message, $type, $position` 	    |

#### Chain Methods

|      Chain Method     	|                     Argument                     	|                                             Snippet                                             	|
|:---------------------:	|:------------------------------------------------:	|:-----------------------------------------------------------------------------------------------:	|
|     `persistent()`    	| `$showConfirmBtn =  true, $showCloseBtn = false` 	|         `alert()->success('Alert Persistent', 'Successfully')->persistent(false,true);`         	|
|     `autoClose()`     	|              `$milliseconds = 5000`              	|           `alert()->info('I am going to close after', '5 seconds')->autoClose(5000);`           	|
| `showConfirmButton()` 	|     `$btnText = 'Ok', $btnColor = '#3085d6'`     	|          `alert()->info('Info', 'Alert')->showConfirmButton('Button Text','#3085d6');`          	|
|  `showCancelButton()` 	|     `$btnText = 'Cencel', $btnColor = '#aaa'`    	| `alert()->question('Is Post Created', 'Successfully?)->showCancelButton('Button Text','#aaa');` 	|
|  `showCloseButton()`  	|      `$closeButtonAriaLabel = 'aria-label'`      	|        `alert()->warning('Post Created', 'Successfully')->showCloseButton('aria-label');`       	|
|       `footer()`      	|                    `$htmlcode`                   	|    `alert()->error('Oops...', 'Something went wrong!')->footer('<a href>Why do I have this issue?</a>');`   	|
|      `toToast()`      	|             `$position = 'top-right'`            	|                  `alert()->success('Post Created', 'Successfully')->toToast();`                 	|
|                       	|                                                  	|                                                                                                 	|
|                       	|                                                  	|                                                                                                 	|
> can also support multiple chaining

``` php

alert()
    ->error('Oops...', 'Something went wrong!')
    ->footer('<a href>Why do I have this issue?</a>')
    ->showConfirmButton()
    ->showCancelButton()
    ->showCloseButton()
    ->autoClose(5000);

```

## Screenshots

#### Alert

##### Success Alert

``` php
alert()->success('SuccessAlert','Lorem ipsum dolor sit amet.');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/SuccessAlert.png" alt="SuccessAlert">
</p>

##### Info Alert

``` php
alert()->info('InfoAlert','Lorem ipsum dolor sit amet.');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/InfoAlert.png" alt="InfoAlert">
</p>

##### Warning Alert

``` php
alert()->warning('WarningAlert','Lorem ipsum dolor sit amet.');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/WarningAlert.png" alt="WarningAlert">
</p>

##### Question Alert

``` php
alert()->question('QuestionAlert','Lorem ipsum dolor sit amet.');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/QuestionAlert.png" alt="QuestionAlert">
</p>

##### Error Alert

``` php
alert()->error('ErrorAlert','Lorem ipsum dolor sit amet.');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/ErrorAlert.png" alt="ErrorAlert">
</p>

##### Html Alert

``` php
alert()->html('<i>HTML</i> <u>example</u>',"
  You can use <b>bold text</b>,
  <a href='//github.com'>links</a>
  and other HTML tags
",'success');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/HtmlAlert.png" alt="HtmlAlert">
</p>

#### Toast

##### Success Toast

``` php
toast('Success Toast','success','top-right');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/SuccessToast.png" alt="SuccessToast">
</p>

##### Info Toast

``` php
toast('Info Toast','info','top-right');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/InfoToast.png" alt="InfoToast">
</p>

##### Warning Toast

``` php
toast('Warning Toast','warning','top-right');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/WarningToast.png" alt="WarningToast">
</p>

##### Question Toast

``` php
toast('Question Toast','question','top-right');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/QuestionToast.png" alt="QuestionToast">
</p>

##### Error Toast

``` php
toast('Error Toast','error','top-right');
```

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/toast/ErrorToast.png" alt="ErrorToast">
</p>

## Contributing

Please see [CONTRIBUTING](https://github.com/realrashid/sweet-alert/blob/master/CONTRIBUTING.md) and [CODE_OF_CONDUCT](https://github.com/realrashid/sweet-alert/blob/master/CODE_OF_CONDUCT.md) for details.

## Credits

*   [SweetAlert2](https://github.com/sweetalert2/sweetalert2)

## Connect with Me

*   Website: http://realrashid.com
*   Email: realrashid05@gmail.com
*   Twitter: http://twitter.com/rashidali05
*   Facebook: https://www.facebook.com/rashidali05
*   GitHub: https://github.com/realrashid

## License

SweetAlert2 is open-sourced software licensed under the MIT License (MIT). Please see [License File](LICENSE.md) for more information.

<p align="center"> <b>Made :heart: with Pakistan<b> </p>
