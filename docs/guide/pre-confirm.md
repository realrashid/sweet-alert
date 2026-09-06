# Pre-Confirm & Pre-Deny Routes

The pre-confirm and pre-deny route patterns are among the most powerful features in v8. They allow you to validate user input on the server side before the SweetAlert2 dialog closes, providing real-time feedback without writing any JavaScript.

## Pre-Confirm Route

## How It Works

1. You configure an input alert with `preConfirmRoute()`, passing a Laravel route URL
2. When the user clicks the confirm button, SweetAlert2 sends an AJAX POST request to that route with the input value
3. Your route handler validates the input and returns a JSON response
4. If validation passes (`"valid": true`), the dialog closes normally
5. If validation fails (`"valid": false`), the error message is displayed inline and the dialog stays open

## Basic Usage

```php
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Enums\InputType;

Alert::input('Enter promo code', InputType::Text)
    ->preConfirmRoute(route('validate-promo'))
    ->showLoaderOnConfirm()
    ->flash();
```

## Route Handler

Create a route that accepts POST requests and returns a JSON response:

```php
// routes/web.php
Route::post('/validate-promo', [PromoController::class, 'validateCode'])
    ->name('validate-promo');
```

```php
// app/Http/Controllers/PromoController.php
public function validateCode(Request $request)
{
    $code = $request->input('value');

    $promo = PromoCode::where('code', $code)->first();

    if (! $promo) {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code does not exist.',
        ]);
    }

    if ($promo->isExpired()) {
        return response()->json([
            'valid' => false,
            'message' => 'This promo code has expired.',
        ]);
    }

    return response()->json([
        'valid' => true,
        'code' => $promo->code,
        'discount' => $promo->discount_percentage,
    ]);
}
```

## Response Format

### Success Response

When validation passes, return a JSON object with `"valid": true`. You can include additional data that will be available in the `result.value` of the SweetAlert2 promise:

```php
return response()->json([
    'valid' => true,
    'id' => $model->id,
    'name' => $model->name,
]);
```

### Failure Response

When validation fails, return a JSON object with `"valid": false` and a `message` key containing the error text:

```php
return response()->json([
    'valid' => false,
    'message' => 'The entered value is invalid.',
]);
```

## Show Loader on Confirm

Always pair `preConfirmRoute()` with `showLoaderOnConfirm()` to give the user visual feedback while the AJAX request is in progress:

```php
Alert::input('Enter verification code', InputType::Text)
    ->preConfirmRoute(route('verify-code'))
    ->showLoaderOnConfirm()
    ->flash();
```

The confirm button will show a spinning loader while waiting for the server response.

## CSRF Protection

The pre-confirm AJAX request automatically includes the Laravel CSRF token. The Blade view injects it via <code v-pre>{{ csrf_token() }}</code>, so you don't need to handle CSRF manually.

## Request Format

The pre-confirm route receives a POST request with:
- **Header**: `Content-Type: application/json`
- **Header**: `X-CSRF-TOKEN`: The Laravel CSRF token
- **Header**: `Accept`: `application/json`
- **Body**: `{ "value": "user_input_here" }`

The `value` key contains whatever the user entered in the input field.

## Advanced Examples

### Email Subscription Validation

```php
Alert::input('Subscribe to newsletter', InputType::Email)
    ->inputPlaceholder('you@example.com')
    ->preConfirmRoute(route('newsletter.validate'))
    ->showLoaderOnConfirm()
    ->confirmButton('Subscribe', '#28a745')
    ->flash();
```

```php
// Controller
public function validateEmail(Request $request)
{
    $email = $request->input('value');

    if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return response()->json([
            'valid' => false,
            'message' => 'Please enter a valid email address.',
        ]);
    }

    if (Subscriber::where('email', $email)->exists()) {
        return response()->json([
            'valid' => false,
            'message' => 'This email is already subscribed.',
        ]);
    }

    Subscriber::create(['email' => $email]);

    return response()->json([
        'valid' => true,
        'email' => $email,
    ]);
}
```

### Username Availability Check

```php
Alert::input('Choose a username', InputType::Text)
    ->inputPlaceholder('johndoe')
    ->inputAutoTrim(true)
    ->inputAttributes(['maxlength' => 20, 'pattern' => '[a-zA-Z0-9_]+'])
    ->preConfirmRoute(route('username.check'))
    ->showLoaderOnConfirm()
    ->flash();
```

### Delete Confirmation with Server Check

```php
Alert::confirmDelete('Delete this order?')
    ->preConfirmRoute(route('orders.can-delete', $order))
    ->flash();
```

This pattern checks whether the order can be deleted (e.g., it hasn't been shipped yet) before allowing the deletion.

## Without Input

You can also use `preConfirmRoute()` on non-input alerts. The `value` in the request body will be `null`, but you can still use the route for server-side checks:

```php
Alert::title('Publish this article?')
    ->question()
    ->preConfirmRoute(route('articles.can-publish', $article))
    ->showLoaderOnConfirm()
    ->flash();
```

## Under the Hood

The Blade view renders the pre-confirm route as JavaScript:

```javascript
swalConfig.preConfirm = function(value) {
    return fetch(preConfirmRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ value: value })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.valid === false) {
            Swal.showValidationMessage(data.message || 'Validation failed');
        }
        return data;
    })
    .catch(function() {
        Swal.showValidationMessage('Request failed');
    });
};
```

No manual JavaScript is required — everything is handled by the Blade template.

## Pre-Deny Route

The `preDenyRoute()` method mirrors `preConfirmRoute()` but fires when the user clicks the **deny** button. This is useful for two-action dialogs where both confirm and deny need server-side validation.

### How It Works

1. You configure an alert with a deny button and `preDenyRoute()`, passing a Laravel route URL
2. When the user clicks the deny button, SweetAlert2 sends an AJAX POST request to that route
3. Your route handler processes the denial and returns a JSON response
4. If `"valid": true`, the dialog closes with the deny result
5. If `"valid": false`, the error message is displayed inline and the dialog stays open

### Basic Usage

```php
use RealRashid\SweetAlert\Facades\Alert;

Alert::title('Review submission')
    ->question()
    ->confirmButton('Approve')
    ->denyButton('Reject')
    ->preDenyRoute(route('reviews.reject', $review))
    ->flash();
```

### Route Handler

```php
// routes/web.php
Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])
    ->name('reviews.reject');
```

```php
// app/Http/Controllers/ReviewController.php
public function reject(Request $request, Review $review)
{
    if ($review->isPublished()) {
        return response()->json([
            'valid' => false,
            'message' => 'Published reviews cannot be rejected.',
        ]);
    }

    $review->update(['status' => 'rejected']);

    return response()->json(['valid' => true]);
}
```

### Combining preConfirmRoute and preDenyRoute

You can use both on the same alert for full two-action server-side validation:

```php
Alert::title('Approve or reject this order?')
    ->question()
    ->confirmButton('Approve', '#28a745')
    ->denyButton('Reject', '#dc3545')
    ->preConfirmRoute(route('orders.approve', $order))
    ->preDenyRoute(route('orders.reject', $order))
    ->showLoaderOnConfirm()
    ->flash();
```

### Under the Hood

The Blade view renders the pre-deny route as JavaScript, mirroring the pre-confirm pattern:

```javascript
swalConfig.preDeny = function(value) {
    return fetch(preDenyRoute, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ value: value })
    })
    .then(function(response) { return response.json(); })
    .then(function(data) {
        if (data.valid === false) {
            Swal.showValidationMessage(data.message || 'Validation failed');
        }
        return data;
    })
    .catch(function() {
        Swal.showValidationMessage('Request failed');
    });
};
```
