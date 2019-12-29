# Configuration

### Include SweetAlert 2 View

Include `'sweetalert::alert'` in master layout

```php
@include('sweetalert::alert')
```

and run the below command to publish the package assets.

```bash
php artisan sweetalert:publish
```

> note: The javascript library of sweetalert2 is already loaded and included in the view with the help of above command!

!> If you don't want to use pre-loaded **sweetalert.all.js** so you can use cdn.
Just pass the `cdn` variable to above included view like below example.

```php
@include('sweetalert::alert', ['cdn' => "https://cdn.jsdelivr.net/npm/sweetalert2@9"])
```
