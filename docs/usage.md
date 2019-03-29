# Usage

#### Using Facade

Import Alert Facade first!

```php
use RealRashid\SweetAlert\Facades\Alert;
```
or

```php
Use Alert;
```
in your controller method
 ```php
 Alert::alert('Title', 'Message', 'Type');
 ```
 ```php
 Alert::success('Success Title', 'Success Message');
 ```
 ```php
 Alert::info('Info Title', 'Info Message');
 ```
 ```php
 Alert::warning('Warning Title', 'Warning Message');
 ```
 ```php
 Alert::error('Error Title', 'Error Message');
 ```
 ```php
 Alert::question('Question Title', 'Question Message');
 ```
 ```php
 Alert::image('Image Title!','Image Description','Image URL','Image Width','Image Height');
 ```
 ```php
 Alert::html('Html Title', 'Html Code', 'Type');
 ```
 ```php
 Alert::toast('Toast Message', 'Toast Type', 'Toast Position');
 ```

### Using the helper function

#### Alert

 ```php
 alert('Title','Lorem Lorem Lorem', 'success');
 ```

 ```php
 alert()->success('Title','Lorem Lorem Lorem');
 ```

 ```php
 alert()->info('Title','Lorem Lorem Lorem');
 ```

 ```php
 alert()->warning('Title','Lorem Lorem Lorem');
 ```

  ```php
 alert()->error('Title','Lorem Lorem Lorem');
 ```

 ```php
 alert()->question('Title','Lorem Lorem Lorem');
 ```

 ```php
 alert()->image('Image Title!','Image Description','Image URL','Image Width','Image Height');
 ```

 ```php
 alert()->html('<i>HTML</i> <u>example</u>'," You can use <b>bold text</b>, <a href='//github.com'>links</a> and other HTML tags ",'success');
 ```

#### Toast

 ```php
 toast('Your Post as been submited!','success','top-right');
 ```
