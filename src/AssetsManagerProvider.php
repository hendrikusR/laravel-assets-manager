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
				list($group, $handle) = explode(',',str_replace(['(',')',' ', "'"], '', $arguments));
				return '<?php echo \Geelik\AssetsManager\Facades\AssetsManager::getAssetsHtml("'.$group.'", "'.$handle.'"); ?>';
			});
		});
	}
}