<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 20/06/2017
 * Time: 09:32
 */

namespace Geelik\AssetsManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class AssetsManagerProvider extends ServiceProvider{
	
	public function boot(){
	
	}
	
	public function register()
	{
		$this->app->alias('AssetsManager', AssetsManager::class);
		
		$this->publishes(array(
			__DIR__.'/config/assets.php' => config_path('assets.php')
		));
		
		$this->registerBladeExtensions();
	}
	
	protected function registerBladeExtensions()
	{
		$this->app->afterResolving('blade.compiler', function (BladeCompiler $bladeCompiler) {
			$bladeCompiler->directive('assetsmanager', function ($arguments) {
				list($group, $handle) = explode(',', $arguments.',');
				
				$code = '<?php'.PHP_EOL;
				$code .= '$assetsList = \Geelik\AssetsManager\Facades\AssetsManager::get("'.trim($group).'", "'.trim($handle).'");'.PHP_EOL;
				$code .= 'if($assetsList != false):'.PHP_EOL;
				$code .= 'foreach ($assetsList as $src):'.PHP_EOL;
				$code .= '$isCss = ends_with($src, \'.css\');'.PHP_EOL;
				$code .= '$isJs = ends_with($src, \'.js\');'.PHP_EOL;
				$code .= '$html = null;'.PHP_EOL;
				$code .= '$asset_method = (config(\'assets.use_https\', false) == false) ? \'asset\' : \'secure_asset\';'.PHP_EOL;
				$code .= '$asset_url = (starts_with(\'https\') || starts_with(\'http\') ? $src : $asset_method($src);'.PHP_EOL;
				$code .= 'if($isCss):'.PHP_EOL;
				$code .= '$html = \'<link href="\'.$asset_url.\'" >\'.PHP_EOL;'.PHP_EOL;
				$code .= 'elseif($isJs):'.PHP_EOL;;
				$code .= '$html = \'<script src="\'.$asset_url.\'" ></script>\'.PHP_EOL;'.PHP_EOL;
				$code .= 'endif;'.PHP_EOL;
				$code .= 'if(! empty($html)):'.PHP_EOL;
				$code .= 'echo $html;'.PHP_EOL;
				$code .= 'endif;'.PHP_EOL;
				$code .= 'endforeach;'.PHP_EOL;
				$code .= 'endif;'.PHP_EOL;
				$code .= '?>'.PHP_EOL;

				return $code;
			});
		});
	}
}