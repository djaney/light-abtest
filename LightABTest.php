<?php

class LightABTest extends WordpressPlugin{

	const PREFIX = 'abtest_';
	const COOKIE_CASES = 'abtest_cases';
	const COOKIE_BIRTH = 'abtest_birth';
	const OPT_LIST = 'list';
	const OPT_SETTINGS = 'settings';
	const OPT_LAST_SAVE = 'last_save';
	const SHORTCODE_NAME = 'abtest';

	protected $pluginName = 'Light AB Test';
	protected $pluginVersion = '0.1.0';
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
		array(
			'name'=>'light-abtest-stats',
			'path'=>'js/stats.js', 
		),
	);


	public function __construct(){
		
		add_action('init',array($this,'initCookies'));

		$this->initAjax();

		$this->initShortcodes();

		$this->addInlineVar(self::PREFIX.'tests',$this->getOption(self::OPT_LIST));
		$this->addInlineVar(self::PREFIX.'settings',$this->getOption(self::OPT_SETTINGS));

	}

	public function initCookies(){
		// if last update is greater than cookie birth then renew
		$lastSave = $this->getOption(self::OPT_LAST_SAVE);
		$birth = $this->getCookie(self::COOKIE_BIRTH,0);



		if($lastSave > $birth || $birth==0){
			$list = $this->getOption(self::OPT_LIST);
			$this->setCookie(self::COOKIE_CASES, $this->randomizeCases($list), time() + (86400 * 30));
			$this->setCookie(self::COOKIE_BIRTH, time(), time() + (86400 * 30), '/');
		}


		
	}
	private function initShortcodes(){
		$this->addShortcode(self::SHORTCODE_NAME,function($attr,$content){
			$test = isset($attr['test'])?$attr['test']:0;
			$case = isset($attr['case'])?$attr['case']:'';

			if(LightABTest::abtest($test,$case)){
				return $content;
			}else{
				return '';
			}
		});
	}
	private function randomizeCases($list){
		$arr = array();
		if($list){
			foreach($list as $row){
				$cases  = array_map('trim', explode(',', $row->cases));
				$arr[$row->id] = $cases[array_rand($cases)];
				
			}
		}

		return $arr;
	}


	private function initAjax(){

			$this->addAjax(self::PREFIX.'save',function($instance,$params){
				$listResult = false;
				$settingsResult = false;
				if($params){
					$listResult = $instance->setOption(LightABTest::OPT_LIST,$params->list);
					$settingsResult = $instance->setOption(LightABTest::OPT_SETTINGS,$params->settings);
					$lastSave = $instance->setOption(LightABTest::OPT_LAST_SAVE,time());
				}


				return array(
					'success'=>($listResult && $settingsResult && $lastSave),
				);
			});


			$this->addAjax(self::PREFIX.'load',function($instance,$params){
				$listResult = false;
				$settingsResult = false;

				$listResult = $instance->getOption(LightABTest::OPT_LIST);
				$settingsResult = $instance->getOption(LightABTest::OPT_SETTINGS);

		

				return array(
					'success'=>true,
					'data'=>array(
						'list'=>$listResult,
						'settings'=>$settingsResult,
					),
				);




			});
	}

	public static function abtest($id,$case){


		$cookie = self::getInstance()->getCookie(self::COOKIE_CASES,array());
		if(isset($cookie[$id])){
			return strtolower($case)==strtolower($cookie[$id]);
		}else{
			return false;
		}
		
		
	}

}




