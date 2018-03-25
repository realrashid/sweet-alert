If you would like to contribute enhancements or fixes, please do the following:

1. Create Laravel Project via Composer or Laravel Installer.

2. In the project root directory create folder named as packages.

3. Fork the `sweet-alert` repository and clone it in packages.

4. Now goto your `composer.json` file and add this `"RealRashid\\SweetAlert\\": "packages/realrashid/sweet-alert/src"` line under `autoload` `psr-4` section

like below

``` json
"autoload": {
    "classmap": [
        "database/seeds",
        "database/factories"
    ],
    "psr-4": {
        "App\\": "app/",
        "RealRashid\\SweetAlert\\": "packages/realrashid/sweet-alert/src"
    }
},

```
