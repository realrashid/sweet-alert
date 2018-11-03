# Methods Definition

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
