# Confirm Alert

<p align="center">
    <img src="https://raw.githubusercontent.com/realrashid/sweet-alert/master/imgs/alert/confirmDelete.gif" alt="ConfirmDelete">
</p>

#### Description

This function shows a confirmation alert before deleting data. It accepts two parameters: `$title` and `$text`. <br>
When called, it will set the configuration options for the confirmation popup and store them in a session flash data.

#### Parameters

`$title`
This parameter sets the title of the confirmation popup. It is a required parameter.

`$text`
This parameter sets the text of the confirmation popup. It is a optional parameter.

#### Usage

To use the `confirmDelete` function, you need to call it in your controller method in my case its `index` , passing in the required parameters.<br>
For example:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);

        $title = 'Delete User!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('users.index', compact('users'));
    }
}
```

> This allows the confirmation alert to be displayed after a clicking the delete button.

In your view, you can add the `data-confirm-delete` attribute to the delete button to trigger the confirmation popup. <br> The confirmation popup will use the configuration options set in the session flash data.  <br>
For example:

```blade
<a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger" data-confirm-delete="true">Delete</a>

```

When the delete button is clicked, the confirmation popup will be displayed. <br> If the user confirms the delete action, the delete request will be sent to the users/destroy route. <br> If the user cancels the delete action, the confirmation popup will be closed and nothing will happen. <br>

#### Environment Variables

The following environment variables can be used to customize the confirm alert:

`SWEET_ALERT_CONFIRM_DELETE_CONFIRM_BUTTON_TEXT` <br>
This variable sets the text of the confirmation button. Default is <strong>Yes, delete it!</strong>.

`SWEET_ALERT_CONFIRM_DELETE_CANCEL_BUTTON_TEXT` <br>
This variable sets the text of the cancel button. Default is <strong>Cancel</strong>.

`SWEET_ALERT_CONFIRM_DELETE_SHOW_CANCEL_BUTTON` <br>
This variable sets whether to show the cancel button in the popup. Possible values are true or false. Default is <strong>true</strong>.

`SWEET_ALERT_CONFIRM_DELETE_SHOW_CLOSE_BUTTON` <br>
This variable sets whether to show the close button in the popup. Possible values are true or false. Default is <strong>false</strong>.

`SWEET_ALERT_CONFIRM_DELETE_ICON` <br>
This variable sets icon of the popup. Default is <strong>warning</strong>.

`SWEET_ALERT_CONFIRM_DELETE_SHOW_LOADER_ON_CONFIRM` <br>
This variable sets whether to show a loader in delete button of the popup. Possible values are true or false. Default is <strong>true</strong>.

You can customize the configuration options of the confirmation popup by setting the environment variables in your `.env` file. <br> For example:

```
SWEET_ALERT_CONFIRM_DELETE_CONFIRM_BUTTON_TEXT='Yes, delete it!'
SWEET_ALERT_CONFIRM_DELETE_CANCEL_BUTTON_TEXT='No, cancel'
SWEET_ALERT_CONFIRM_DELETE_SHOW_CANCEL_BUTTON=true
SWEET_ALERT_CONFIRM_DELETE_SHOW_CLOSE_BUTTON=false
SWEET_ALERT_CONFIRM_DELETE_ICON='warning'
SWEET_ALERT_CONFIRM_DELETE_SHOW_LOADER_ON_CONFIRM=true

```
