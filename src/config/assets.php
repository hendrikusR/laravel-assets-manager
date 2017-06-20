<?php

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
		
		)
	)
);