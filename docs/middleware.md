# Middleware

### Using the Middleware

#### Registering the Middleware

First, you need to register the middleware in the `web` middleware group. This allows the middleware to be applied to web routes.

1. Open your `app/Http/Kernel.php` file.

2. Find the `$middlewareGroups` array. Within this array, locate the `'web'` middleware group.

3. Add `\RealRashid\SweetAlert\ToSweetAlert::class` to the list of middleware in the `'web'` group:

   ```php
   // app/Http/Kernel.php

   protected $middlewareGroups = [
       'web' => [
           // Other middleware...
           \RealRashid\SweetAlert\ToSweetAlert::class,
       ],
   ];
   ```

   This registers the `ToSweetAlert` middleware for all routes in the `web` middleware group.

#### Adding Middleware for Laravel 11

If you're using Laravel 11 specifically, you may need to adjust middleware usage as follows:

1. Navigate to your `bootstrap/app.php` file.

2. Locate where middleware is defined, typically inside the `withMiddleware` method call.

3. Modify the middleware setup to include `ToSweetAlert::class` specifically for the `web` middleware group:

   ```php
   // bootstrap/app.php

   $middleware->web(append: [
        \RealRashid\SweetAlert\ToSweetAlert::class,
    ]);
   ```
   This ensures that the `ToSweetAlert` middleware is applied correctly within the context of Laravel 11's middleware handling.

### Error messages auto displaying

Set the `SWEET_ALERT_AUTO_DISPLAY_ERROR_MESSAGES` .env value to `true` to activate the automatic displaying for the validation error messages.

Default is <strong>true</strong>.

### Examples

Now within your controllers, just set your return message and send the proper message and proper type.

#### Alert

Errors Alert

```php
public function store(Request $request)
{
	//validation
	$request->validate([
		'title' => 'required|min:3',
		'body' => 'required|min:3'
	]);
	$task = Task::create($request->all());
	return redirect('tasks')->with('success', 'Task Created Successfully!');
	// OR
	return redirect('tasks')->withSuccess('Task Created Successfully!');
}
```

Error Alert

```php
public function store(Request $request)
{
	$validator = Validator::make($request->all(), [
		'title' => 'required|min:3',
		'body' => 'required|min:3'
	]);

	if ($validator->fails()) {
		return back()->with('errors', $validator->messages()->all()[0])->withInput();
	}
	$task = Task::create($request->all());
	return redirect('tasks')->with('success', 'Task Created Successfully!');
	// OR
	return redirect('tasks')->withSuccess('Task Created Successfully!');
}
```

Success Alert

```php
public function store(Request $request)
{
	//validation
	$task = Task::create($request->all());
	return redirect('tasks')->with('success', 'Task Created Successfully!');
	// OR
	return redirect('tasks')->withSuccess('Task Created Successfully!');
}
```

```php
return redirect('route')->with('type', 'message');
// OR
return redirect('route')->withType('message');
```

All available types : `error` `success` `info` `warning` `question` .

#### Toast

Error Toast

```php
public function store(Request $request)
{
	$validator = Validator::make($request->all(), [
		'title' => 'required|min:3',
		'body' => 'required|min:3'
	]);

	if ($validator->fails()) {
		return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
	}
	$task = Task::create($request->all());
	return redirect('tasks')->with('success', 'Task Created Successfully!');
	// OR
	return redirect('tasks')->withSuccess('Task Created Successfully!');
}
```

Success Toast

```php
public function store(Request $request)
{
	$validator = Validator::make($request->all(), [
		'title' => 'required|min:3',
		'body' => 'required|min:3'
	]);

	if ($validator->fails()) {
		return back()->with('toast_error', $validator->messages()->all()[0])->withInput();
	}
	$task = Task::create($request->all());
	return redirect('tasks')->with('toast_success', 'Task Created Successfully!');
	// OR
	return redirect('tasks')->withToastSuccess('Task Created Successfully!');
}
```

```php
return redirect('route')->with('toast_type', 'message');
// OR
return redirect('route')->withToastType('message');
```

All available types `toast_error` `toast_success` `toast_info` `toast_warning` `toast_question`.

!> You can not use helper methods with Middleware but you can set default values in `config/sweetalert.php` file! **Recommend** to use the .env keys.

```
SWEET_ALERT_MIDDLEWARE_AUTO_CLOSE=false
SWEET_ALERT_MIDDLEWARE_TOAST_POSITION='top-end'
SWEET_ALERT_MIDDLEWARE_TOAST_CLOSE_BUTTON=true
SWEET_ALERT_MIDDLEWARE_ALERT_CLOSE_TIME=5000
SWEET_ALERT_AUTO_DISPLAY_ERROR_MESSAGES=true
```

> Positions **'top'**, **'top-start'**, **'top-end'**,
> **'center'**, **'center-start'**, **'center-end'**, **'bottom'**, **'bottom-start'**, or **'bottom-end'**.
