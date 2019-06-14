# Changelog

All notable changes to `sweet-alert` will be documented in this file.

## v2.0 - 2019-06-14
- Upgraded SweetAlert2 to latest version
- Added width() helper method
- Added padding() helper method
- Added background() helper method
- Added animation() helper method
- Added focusConfirm() helper method
- Added focusCancel() helper method
- Added CDN Support
- Docs Updated
- Fix some bugs

Changes
```php
// From
public function toast($title = '', $type = '', $position = 'bottom-right'){...}
// To
public function toast($title = '', $type = ''){...}

// set the default position in package config file or use the helper method position()
```

## v1.1.2 - 2019-03-29
- Upgraded SweetAlert2 to latest version
- Added hideCloseButton() helper method
- Added reverseButtons() helper method
- Added image() method
- Added addImage() helper method
- Added position() helper method
- Docs Updated
- Fix some bugs

## v1.1.1 - 2018-03-25
Added some new methods
- `alert() method`
- `alert()->success() method`
- `alert()->info() method`
- `alert()->wanring() method`
- `alert()->question() method`
- `alert()->error() method`
- `alert()->html() method`
- `toast() method`
- `showConfirmButton() helper method`
- `showCloseButton() helper method`
- `showCancelButton() helper method`
- `persistent() helper method`
- `autoClose() helper method`
- `toToast() helper method`
- `footer() helper method`

## v1.0 - 2018-03-25
- Deprecated

## v1.0 - 2017-09-02
- initial release
