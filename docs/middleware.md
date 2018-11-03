# Middleware

#### Using the Middleware


First thing first 
Let register the middleware in web middleware groups by simply adding the middleware class

```php
RealRashid\SweetAlert\ToSweetAlert::class
```

into the 

`$middlewareGroups` of your `app/Http/Kernel.php` class.

### Examples

Now within your controllers, just set your return message and send the proper message and proper type.

#### Alert

Error Alert
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('error', 'Authentication Failed!');
}

```
Success Alert
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('success', 'Login Successfully!');
}

```

```php
return redirect('route')->with('type', 'message');
```

All available types
`info` `warning` `question` 

#### Toast

Error Toast
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('toast_error', 'Authentication Failed!');
}

```
Success Toast
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('toast_success', 'Login Successfully!');
}

```

```php
return redirect('route')->with('type', 'message');
```

All available types
`toast_info` `toast_warning` `toast_question` 
