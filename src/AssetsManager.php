<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 20/06/2017
 * Time: 09:34
 */

namespace Geelik\AssetsManager;


class AssetsManager {
	
	private $groups;
	private $groupsKey;
	
	public function __construct() {
		$this->groups    = config('assets.groups');
		$this->groupsKey = array_keys($this->groups);
	}
	
	public function get($group, $handle) {
		if (! empty($this->groups[$group]) && ! empty($this->groups[$group][$handle])) {
			$deps         = $this->getRequiredDependencies($group, $handle);
			$assets       = array();
			$dependencies = array();
			foreach ($deps as $asset) {
				$data                 = $this->groups[$group][$asset];
				$assets[$asset]       = $asset;
				$dependencies[$asset] = (is_array($data[1])) ? $data[1] : array();
			}
			
			$ordered = $this->topological_sort($assets, $dependencies);
			
			foreach ($ordered as $dep) {
				$ordered[$dep] = $this->groups[$group][$dep][0];
			}
			
			return $ordered;
		}
		
		return false;
	}
	
	public function getAssetsHtml($group, $handle) {
		$assetsList = $this->get($group, $handle);
		$html = '';
		
		if ($assetsList != false){
			foreach ($assetsList as $src){
				$isCss        = ends_with($src, '.css');
				$isJs         = ends_with($src, '.js');
				$asset_method = (config('assets.use_https', false) == false) ? 'asset' : 'secure_asset';
				$asset_url    = (starts_with($src, 'https') || starts_with($src, 'http')) ? $src : $asset_method($src);
				
				if ($isCss) {
					$template = config('assets.templates.css', '<link href="ASSET_SRC" />');
					$html .= str_replace('ASSET_SRC', $asset_url, $template).PHP_EOL;
				}
				elseif ($isJs){
					$template = config('assets.templates.js', '<script src="ASSET_SRC"></script>');
					$html .= str_replace('ASSET_SRC', $asset_url, $template).PHP_EOL;
				}
			}
		}
		
		return $html;
	}
	
	protected function getRequiredDependencies($group, $handle, $deps = array()) {
		if (! empty($this->groups[$group]) && ! empty($this->groups[$group][$handle])) {
			$asset  = $this->groups[$group][$handle];
			$deps[] = $handle;
			
			if (is_array($asset[1]) && ! empty($asset[1])) {
				foreach ($asset[1] as $sub) {
					$deps[] = $this->getRequiredDependencies($group, $sub);
				}
			}
		}
		
		return array_unique($this->flatten($deps));
	}
	
	private function process_toposort($pointer, &$dependency, &$order, &$pre_processing) {
		if (isset($pre_processing[$pointer])) {
			return false;
		}
		else {
			$pre_processing[$pointer] = $pointer;
		}
		
		foreach ($dependency[$pointer] as $i => $v) {
			if (isset($dependency[$v])) {
				if (! $this->process_toposort($v, $dependency, $order, $pre_processing)) {
					return false;
				}
			}
			$order[$v] = $v;
			unset($pre_processing[$v]);
		}
		$order[$pointer] = $pointer;
		unset($pre_processing[$pointer]);
		
		return true;
	}
	
	private function topological_sort($data, $dependency) {
		$order          = array();
		$pre_processing = array();
		$order          = array_diff_key($data, $dependency);
		$data           = array_diff_key($data, $order);
		foreach ($data as $i => $v) {
			if (! $this->process_toposort($i, $dependency, $order, $pre_processing)) {
				return false;
			}
		}
		
		return $order;
	}
	
	private function flatten(array $array) {
		$return = array();
		array_walk_recursive($array, function ($a) use (&$return) {
			$return[] = $a;
		});
		
		return $return;
	}
	
	public function __call($method, $args) {
		if (in_array($method, $this->groupsKey)) {
			array_unshift($args, $method);
			
			return call_user_func_array([$this, 'get'], $args);
		}
	}
	
}