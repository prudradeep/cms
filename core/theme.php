<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Class:		Theme
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Theme{
	function __construct(){}

	/*
	* Load Theme Views
	* $view: View file name
	* $data: Data for view (Optional)
	*/
	public static function loadView($view, $data=[]){
		global $Helper;
		if(SITE!='api')
			require_once BASE_DIR.DS.THEME_PATH.DS.THEME.DS.$view.'.php';
	}

	private static function getJsPlugs(){
		if(SITE!='api'){
			global $Helper;
			$Prerequisites = $Helper->model('settings');
			$data = $Prerequisites->select('value')->Eq('type', 'js_plugs')->andEq('status', 1)->prepare()->execute();
			while($row = $data->fetch()){
				$re = '/^http:\/\/|^https:\/\//';
				$value = $row->getValue();
				if(preg_match($re, $value))
					echo '<script src="'.$value.'"></script>';
				else
					echo '<script src="'.BASE.PLUGS.'/'.$value.'.js"></script>';
			}
		}
	}

	private static function getCssPlugs(){
		if(SITE!='api'){
			global $Helper;
			$Prerequisites = $Helper->model('settings');
			$data = $Prerequisites->select('value')->Eq('type', 'css_plugs')->andEq('status', 1)->prepare()->execute();
			while($row = $data->fetch()){
				$re = '/^http:\/\/|^https:\/\//';
				$value = $row->getValue();
				if(preg_match($re, $value))
					echo '<link href="'.$value.'" rel="stylesheet" />';
				else
					echo '<link href="'.BASE.PLUGS.'/'.$value.'.css" rel="stylesheet" />';
			}
		}
	}

	private static function getFontPlugs(){
		if(SITE!='api'){
			global $Helper;
			$Prerequisites = $Helper->model('settings');
			$data = $Prerequisites->select('value')->Eq('type', 'font_plugs')->andEq('status', 1)->prepare()->execute();
			while($row = $data->fetch()){
				$re = '/^http:\/\/|^https:\/\//';
				$value = $row->getValue();
				if(preg_match($re, $value))
					echo '<link href="'.$value.'" rel="stylesheet" />';
				else
					echo '<link href="'.BASE.PLUGS.'/'.$value.'.css" rel="stylesheet" />';
			}
		}
	}

	private static function getSiteInfo($field){
		global $Helper;
		$Prerequisites = $Helper->model('settings');
		return $Prerequisites->getValueByField($field);
	}

	function __destruct(){}
}