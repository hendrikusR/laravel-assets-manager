<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 20/06/2017
 * Time: 09:40
 */

namespace Geelik\AssetsManager\Facades;


use Illuminate\Support\Facades\Facade;

class AssetsManager extends Facade {
	protected static function getFacadeAccessor() { return 'AssetsManager'; }
}