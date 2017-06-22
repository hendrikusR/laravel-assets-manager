#Laravel Assets Manager

This Laravel >=5.4 package help you manage your assets and their dependencies.

##How to use

###Configure your assets

```php
// config/assets.php
return array(
    'use_https' => false,
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
@assetsmanager(js, jquery-tab-plus)
...
```

This will resolve automatically the dependencies for you and print
```html 
 <script src="http://yourdomain.com/js/jquery.js"></script>
 <script src="http://yourdomain.com/js/jquery-tab.js"></script>
 <script src="http://yourdomain.com/js/jquery-tab-plus.js"></script>
```

If you prefer to generate html yourself you can call

```php
use Geelik\AssetsManager\Facades\AssetsManager;

AssetsManager::get('js', 'jquery-tab-plus');

/*
this return an array correctly oordered
array [
  "jquery" => "http://code.jquery.com/jquery.min.js"
  "jquery-tab" => "js/jquery-tab.js"
  "jquery-tab-plus" => "js/jquery-tab-plus.js"
]
*/
```

##Install
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
php artisan vendor:publish --provider="Geelik\AssetsManager\AssetsManager" --tag="config"
```

This is the contents of the published config file:

```php
return array(
    'use_https' => false,
    'groups' => array(
        'js' => array(),
        'css' => array()
    )
);

```