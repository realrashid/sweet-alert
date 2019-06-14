# Middleware

#### Using the Middleware


First thing first
Let register the middleware in web middleware groups by simply adding the middleware class

```php
\RealRashid\SweetAlert\ToSweetAlert::class
```

into the `$middlewareGroups` of your `app/Http/Kernel.php` class.

### Examples

Now within your controllers, just set your return message and send the proper message and proper type.

#### Alert

Error Alert
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('error', 'Authentication Failed!');
	// OR
	return redirect('login')->withError('Authentication Failed!');
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

All available types `error` `success` `info` `warning` `question` .

#### Toast

Error Toast
```php
public function FunctionName(Request $request)
{
	return redirect('login')->with('toast_error', 'Authentication Failed!');
	// OR
	return redirect('login')->withToastError('Authentication Failed!');
}
```
Success Toast
```php
public function update(Request $request, Task $task)
{
	//validation
	// Updating logic
	return redirect('tasks')->with('toast_success', 'Successfully modified the task!');
	// OR
	return redirect('tasks')->withToastSuccess('Successfully modified the task!');
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
SWEET_ALERT_MIDDLEWARE_TOAST_POSITION='top-end'
SWEET_ALERT_MIDDLEWARE_TOAST_CLOSE_BUTTON=true
```

> Positions **'top'**, **'top-start'**, **'top-end'**,
**'center'**, **'center-start'**, **'center-end'**, **'bottom'**, **'bottom-start'**, or **'bottom-end'**.

