<?php

class LightABTest{

	const PLUGIN_DIR = 'light-abtest';

	public function __construct(){
		add_action( 'admin_menu', array($this,'admin_menu') );
	}
	public function admin_menu(){
		add_menu_page('Light AB Test Admin', 'Light AB Test', 'manage_options', self::PLUGIN_DIR.'/admin-menu.php');
	}

	private function getPluginUrl($path){
		return plugins_url( $path, __FILE__);
	}
}
