# Changelog

All notable changes to `sweet-alert` will be documented in this file.

## v4.0.0 - 2021-06-14
- Bug Fixes
- Upgraded SweetAlert2 to latest version
- .gitattributes file
- Docs Updated

## v3.2.3 - 2021-05-26
- Bug Fixes
- Upgraded SweetAlert2 to latest version
- Docs Updated

## v3.2.2 - 2021-02-17
- Remove Laravel 6 Support
- Upgraded SweetAlert2 to latest version
- Docs Updated

## v3.2.1 - 2021-01-15
- Fixed PHP 8 Issues
- Upgraded SweetAlert2 to latest version
- Docs Updated

## v3.2.0 - 2020-12-02
- Added PHP8 Support
- Upgraded SweetAlert2 to latest version
- Docs Updated

## v3.1.7 - 2020-09-09
- Added Support for Laravel 8
- Upgraded SweetAlert2 to latest version
- Minor Changes
- Docs Updated

## v3.1.6 - 2020-07-17
- Added flexibility to Always load the sweetalert.all.js and Never load the sweetalert.all.js by [farhanianz](https://github.com/farhanianz)
- Upgraded SweetAlert2 to latest version
- Minor Changes in Middleware.md
- Docs Updated

## v3.1.5 - 2020-06-11
- Upgraded SweetAlert2 to latest version
- Minor Changes in Middleware
- Docs Updated

## v3.1.4 - 2020-03-31
- Upgraded SweetAlert2 to latest version
- Added ability to custom css classes
- Docs Updated

## v3.1.3 - 2020-02-22
- Upgraded SweetAlert2 to latest version

## v3.1.2 - 2020-01-24
- Upgraded SweetAlert2 to latest version
- Prepare Laravel 7

## v3.1.1 - 2020-01-07
- Upgraded SweetAlert2 to latest version
- Added ability to activate or not the middleware error messages
- Docs Updated

## v3.1.0 - 2019-12-29
- Upgraded SweetAlert2 to latest version
- Added Tidelift to funding.yaml
- Added SECURITY.md in .github/SECURITY.md
- Added timerProgressBar() method
- Added PublishCommand to easily publish the package assets
- Updated question() alert method
- Refactor SweetAlertServiceProvider class
- Refactor Toaster class
- Refactor functions.php file
- Updated PHPDocBlocks
- Docs Updated

## v3.0.1 - 2019-11-27
- Updated `animation($showClass = [], $hideClass = [])` to animation($showAnimation, $hideAnimation)
- Docs Updated
- Fixed animations issue

## v3.0 - 2019-11-20
- Upgraded SweetAlert2 to latest version
- Added option to pass SweetAlert2 CDN link from included view
- Updated `animation($showClass = [], $hideClass = [])`
- Added `buttonsStyling()` method
- Added `iconHtml()` method
- Docs Updated
- Fix some bugs

## v2.0.3 - 2019-10-13
- Added symfony/thanks
- Docs Updated
- Fix some bugs

## v2.0.2 - 2019-09-10
- Added Missing ^ indicator prevents Laravel from updating to 6.0.1 and up
- Docs Updated
- Fix some bugs

## v2.0.1 - 2019-09-06
- Added support for Laravel 6.0
- This release fixed #46
- Docs Updated
- Fix some bugs

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
