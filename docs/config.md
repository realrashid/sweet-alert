# Configuration

### Include SweetAlert 2 View

in your master layout

```php
@include('sweetalert::alert')
```

and run the below command to publish the package assets.

```bash
php artisan vendor:publish --provider="RealRashid\SweetAlert\SweetAlertServiceProvider"
```

> note: The javascript library of sweetalert2 is already loaded and included in the view with the help of above command!

!> If you don't want to use pre-loaded **sweetalert.all.js** so you can use cdn.
Just chnage the below .env key.

```php
// By Default its true 
// Turn it to false
SWEET_ALERT_LOCAL=false
// Add sweetalert2 cdn link
SWEET_ALERT_CDN=''
```
!> You can not use local **sweetalert.all.js** or cdn together!

ex:
```php
SWEET_ALERT_LOCAL=true
SWEET_ALERT_CDN='link'
```
