<?php

class LightABTest extends WordpressPlugin{

	const AJAX_PREFIX = 'abtest_';

	protected $pluginName = 'Light AB Test';
	protected $pluginVersion = '0.0.0';
	protected $pluginDir = 'light-abtest';

	protected $menus = array(
		array(
			'page_title'=>'AB Test Admin',
			'menu_title'=>'AB Test Admin',
			'page'=>'admin-menu.php',
		),
	);


	protected $scripts = array(
		array(
			'name'=>'angularjs',
			'path'=>'//ajax.googleapis.com/ajax/libs/angularjs/1.3.1/angular.min.js', 
			'version'=>'1.3.1',
			'in_public'=>false,
		),
		array(
			'name'=>'light-abtest-admin',
			'path'=>'js/admin.js', 
			'deps'=>array('angularjs'),
			'in_public'=>false,
		),
	);


	public function __construct(){
		$this->addAjax(self::AJAX_PREFIX.'save',function($instance,$params){
			$listResult = false;
			$settingsResult = false;
			if($params){
				$listResult = $instance->setOption('list',$params->list);
				$settingsResult = $instance->setOption('settings',$params->settings);
			}


			return array(
				'success'=>($listResult && $settingsResult)
			);
		});
	}

}
