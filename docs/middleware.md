# Middleware

### Using the Middleware

First thing first
Let register the middleware in web middleware groups by simply adding the middleware class

```php
\RealRashid\SweetAlert\ToSweetAlert::class,
```

into the `$middlewareGroups` of your `app/Http/Kernel.php` file.

### Error messages auto displaying

Set the `SWEET_ALERT_AUTO_DISPLAY_ERROR_MESSAGES` .env value to `true` to activate the automatic displaying for the validation error messages.

By default, this is not activated.

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
