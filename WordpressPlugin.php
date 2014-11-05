<?php

class WordpressPlugin{

	const PLUGIN_FILE = __FILE__;

	protected $pluginName;
	protected $pluginVersion;
	protected $pluginDir;
	protected $pluginFile = __FILE__;
	protected $ajaxPrefix = '';
	protected $menus = array(); // page_title, menu_title, capability, page
	protected $scripts = array(); // name, path, deps, version,in_footer,in_admin, in_public


	private static $instance = NULL;



	private function build(){
		if(!$this->pluginName) throw new Exception('missing "pluginName"');
		if(!$this->pluginVersion) throw new Exception('missing "pluginVersion"');
		if(!$this->pluginDir) throw new Exception('missing "pluginDir"');

		add_action( 'admin_menu', array($this,'adminMenu') );
		add_action( 'admin_enqueue_scripts', array($this,'enqueueScriptAdmin') );
		add_action( 'wp_enqueue_scripts', array($this,'enqueueScriptPublic') );
	}


	public function getPluginUrl($path){
		return plugins_url( $path, self::PLUGIN_FILE);
	}



	private function setPluginVersion($val){
		$this->pluginVersion = $val;
	}
	private function getPluginVersion(){
		return $this->pluginVersion;
	}
	private function getPluginDirectory(){
		return $this->pluginDir;
	}


	private function enqueueScript($inAdmin,$inPublic){
		foreach($this->scripts as $v){


			$name = $v['name'];
			$path = $v['path'];


			if(!$name) throw new Exception('missing "name"');
			if(!$path) throw new Exception('missing "path"');

			$in_public = isset($v['in_public'])?$v['in_public']:true;
			$in_admin = isset($v['in_admin'])?$v['in_admin']:true;

			if(($in_admin && $inAdmin) || ($in_public && $inPublic)){
				$deps = isset($v['deps'])?$v['deps']:array();
				$version = isset($v['version'])?$v['version']:$this->getPluginVersion();
				$in_footer = isset($v['in_footer'])?$v['in_footer']:true;

				if(substr($path,0,4)!='http' && substr($path,0,5)!='https' && substr($path,0,2)!='//'){
					$path = $this->getPluginUrl($path);
				}

				wp_enqueue_script( $name, $path, $deps,$version,$in_footer );
			}


		}
		
	}

	public function xhrReturn($function){
		$params = json_decode(file_get_contents('php://input'));
		echo json_encode($function($this,$params));
		exit;
	}

	public static function make(){
		if(self::$instance==NULL){
			$class = get_called_class();
			$ins = new $class();
			self::$instance = &$ins;
		}
		self::$instance->build();

		return self::getInstance();
	}

	public static function getInstance(){
		return self::$instance;
	}

	public function enqueueScriptAdmin(){
		$this->enqueueScript(true,false);
	}
	public function enqueueScriptPublic(){
		$this->enqueueScript(false,true);
	}


	public function addAjax($name,$function,$isAdmin = true){

		if($isAdmin){
			add_action( 'wp_ajax_'.$name,function() use ($function){
				$this->xhrReturn($function);
			});
		}else{
			add_action( 'wp_ajax_nopriv_'.$name,function() use ($function){
				$this->xhrReturn($function);
			});
		}
	}

	public function adminMenu(){
		foreach($this->menus as $v){
			$pageTitle = $v['page_title'];
			$page = $v['page'];

			if(!$pageTitle) throw new Exception('missing "page_title"');
			if(!$page) throw new Exception('missing "page"');


			$capability = isset($v['capability'])?$v['capability']:'manage_options';
			$menuTitle = isset($v['menu_title'])?$v['menu_title']:$pageTitle;

			add_menu_page($pageTitle, $menuTitle, $capability, $this->getPluginDirectory().'/'.$page);
		}
		
	}

	public function setOption($name,$value){
		return update_option( $this->getPluginDirectory().$name, $value );
	}
}