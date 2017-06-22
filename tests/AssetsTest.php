<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 22/06/2017
 * Time: 10:29
 */

namespace Geelik\AssetsManager\Test;

use Geelik\AssetsManager\Facades\AssetsManager;
use Tests\TestCase;

class AssetsTest extends TestCase{
	
	public function setUp(){
		$this->createApplication();
	}
	
	public function testVueNotIncluded(){
		config(['assets.groups' => [
			'js' => array(
				'jquery'          => array('http://code.jquery.com/jquery.min.js', null),
				'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
				'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
				'vue'             => array('js/vue.js')
			)
		]]);
		
		$assets = AssetsManager::get('js', 'jquery-tab-plus');
		
		$this->assertArrayNotHasKey('vue', $assets);
	}
	
	public function testAssetsOrder(){
		config(['assets.groups' => [
			'js' => array(
				'jquery'          => array('http://code.jquery.com/jquery.min.js', null),
				'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
				'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
				'vue'             => array('js/vue.js')
			)
		]]);
		
		$assets = AssetsManager::get('js', 'jquery-tab-plus');
		$keys = array_keys($assets);
		
		$this->assertEquals(3, count($assets));
		$this->assertEquals('jquery', $keys[0]);
		$this->assertEquals('jquery-tab', $keys[1]);
		$this->assertEquals('jquery-tab-plus', $keys[2]);
	}
	
	public function testCorrectHttps(){
		config(['assets' => [
			'use_https' => true,
			'groups' => [
				'js' => [
					'jquery'          => array('https://code.jquery.com/jquery.min.js', null),
					'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
					'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
					'vue'             => array('js/vue.js')
				]
			]
		]]);
		
		$html = AssetsManager::getAssetsHtml('js', 'jquery-tab-plus');
		preg_match_all('/src="(https:.+)"/', $html, $matches);
		$this->assertEquals(3, count($matches[0]));
	}
	
	public function testCorrectHttp(){
		config(['assets' => [
			'use_https' => false,
			'groups' => [
				'js' => [
					'jquery'          => array('http://code.jquery.com/jquery.min.js', null),
					'jquery-tab'      => array('js/jquery-tab.js', array('jquery')),
					'jquery-tab-plus' => array('js/jquery-tab-plus.js', array('jquery-tab')),
					'vue'             => array('js/vue.js')
				]
			]
		]]);
		
		$html = AssetsManager::getAssetsHtml('js', 'jquery-tab-plus');
		preg_match_all('/src="(http:.+)"/', $html, $matches);
		$this->assertEquals(3, count($matches[0]));
	}
	
}