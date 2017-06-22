# Laravel Assets Manager

This Laravel >=5.4 package help you manage your assets and their dependencies.

## How to use

### Configure your assets

```php
// config/assets.php
return array(
    'use_https' => false,
    'templates' => array(
        'css' => '<link rel="stylesheet" href="ASSET_SRC" />',
        'js'  => '<script type="text/javascript" src="ASSET_SRC"></script>'
    ),
    'groups' => array(
        'js' => array(
            'jquery'          => array('http://code.jquery.com/jquery.min.js', null),
            'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
            'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
            'vue'             => array('js/vue.js')
        ),
        'css' => array()
    )
);
```
| Name | Description |
|------------|-----------------------|
| use_https | If true, generate https links |
| templates | Let you define html code generated |
| groups | you can create as many group as you wants. It's just here to better organize assets |

#### Asset syntax

```php
//'asset_name'   => array('filepath', 'dependencies')
```
| Name | Description |
|------------|-----------------------|
| asset_name | Used as an identifier |
| filepath | Path of the asset, if absolute url will not be changed, if relative will be passed to `asset` (`secure_asset` if use_https is true ) helper |
| dependencies |Array representing the dependencies of this asset, can be null if no dependencies|

### Call your asset in your view

```blade
// welcome.blade.php

...
@assetsmanager('js', 'jquery-tab-plus')
...
```

This will resolve automatically the dependencies for you and print
```html 
 <script type="text/javascript" src="http://yourdomain.com/js/jquery.js"></script>
 <script type="text/javascript" src="http://yourdomain.com/js/jquery-tab.js"></script>
 <script type="text/javascript" src="http://yourdomain.com/js/jquery-tab-plus.js"></script>
```

### Get dependencies as array

```php
use Geelik\AssetsManager\Facades\AssetsManager;

AssetsManager::get('js', 'jquery-tab-plus');

/*
this return an array correctly ordered
array [
  "jquery" => "http://code.jquery.com/jquery.min.js"
  "jquery-tab" => "js/jquery-tab.js"
  "jquery-tab-plus" => "js/jquery-tab-plus.js"
]
*/
```

#### Note
__The package will search assets only in the given group (`js` in our example)__

So an asset can only depends on other assets in the same group.

## Install
```bash
composer require Geelik/laravel-assets-manager
```

 Add the service provider
 
```php
// config/app.php
'providers' => [
    ...
    Geelik\AssetsManager\AssetsManagerProvider::class,
];
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Geelik\AssetsManager\AssetsManagerProvider" --tag="config"
```

This is the contents of the published config file:

```php
return array(
    'use_https' => false,
    'templates' => array(
        'css' => '<link rel="stylesheet" href="ASSET_SRC" />',
        'js' => '<script type="text/javascript" src="ASSET_SRC"></script>'
    ),
    'groups' => array(
        'js' => array(),
        'css' => array()
    )
);

```