#Laravel Assets Manager

This Laravel >=5.4 package help you manage your assets and their dependencies.

##How to use

###Configure your assets

```php
return array(
    'use_https' => false,
    'groups' => array(
        'js' => array(
            'jquery'          => array('js/jquery.js', null),
            'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
            'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
            'vue'             => array('js/vue.js')
        ),
        'css' => array(
            //'asset_name'   => array('filepath', 'dependencies')
        )
    )
);
```
|  | Description |
|------------|-----------------------|
| asset_name | Used as an identifier |
| filepath | File path passed to `asset` helper|
| dependencies |Array representing the dependencies of this asset|

### Call your asset in your view

```blade
// welcome.blade.php

...
@assetsmanager(js, jquery-tab-plus)
...
```

This will reslve automatically the dependencies for you and print
```html 
 <script src="http://yourdomain.com/js/jquery.js"></script>
 <script src="http://yourdomain.com/js/jquery-tab.js"></script>
 <script src="http://yourdomain.com/js/jquery-tab-plus.js"></script>
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
    Geelik\AssetsManager\AssetsManager::class,
];
```

You can publish the config file with:

```bash
php artisan vendor:publish --provider="Geelik\AssetsManager\AssetsManager" --tag="config"
```

This is the contents of the published config file:

```php
return array(
    'js' => array(),
    'css' => array()
);

```