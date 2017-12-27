<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Class:		Controller
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Controller{
	protected $helper, $route, $theme, $component=NULL, $compid=0;
	
	/*
	* Initialize controller
	* $comps: Component name
	*/
	function __construct($comps){
		global $Helper, $Theme;
		self::modes();
		$this->helper = $Helper;
		$this->theme = $Theme;
		$this->component = $comps;

		//URL Authentications
		if(SITE==='site')
			self::isAuth();
		else if(SITE==='api')
			self::apiAuth();
	}

	/*
	* Check for modes
	*/
	private function modes(){
		if(MODE=='DEVELOPMENT'){
			
		}
		else if(MODE=='PRODUCTION'){
			
		}
		else if(MODE=='MAINTENANCE' && SITE !='admin'){
			header('location:./status/maintenance');
		}
	}

	/*
	* API Authentication
	*/
	private function apiAuth(){
		$debug = debug_backtrace();
		if(isset($_GET['secret'])){ //API KEY Users
			print_r($_SERVER);
			exit;
		}else{ //Session Users
			if(!isset($_SESSION[SESSION_KEY]) && $debug[2]['object']->controller!='auth' && $debug[2]['object']->component!='auth'){
				echo $this->helper->arrayToJson(['error'=>401, 'message'=>$this->helper->errorMessage(401)]);
				exit;
			}else if(isset($_SESSION[SESSION_KEY]) && $debug[2]['object']->controller!='auth' && $debug[2]['object']->component!='auth'){
				/*if(!self::checkAccess($debug[2]['object']->component,$debug[2]['object']->controller,$debug[2]['object']->method)){
					echo $this->helper->arrayToJson(['error'=>102, 'message'=>$this->helper->errorMessage(102)]);
					exit;
				}*/
			}
		}
		return;
	}

	/*
	* Load Component Views
	* $view: View name
	* $data: Data for view (Optional)
	*/
	protected function view($view, $data=[]){
		if(file_exists(COMP_PATH.DS.$this->component.DS.'view'.DS.$view.'.php'))
			require COMP_PATH.DS.$this->component.DS.'view'.DS.$view.'.php';
		else
			$this->helper->showError('"'.$view."\" view doesn't exists in ".$this->component.DS.'view');
	}

	/*
	* Load Theme Views
	* $view: View name
	* $data: Data for view (Optional)
	*/
	protected function loadView($view, $data=[]){
		if(file_exists(THEME_PATH.DS.THEME.DS.$view.'.php'))
			require_once THEME_PATH.DS.THEME.DS.$view.'.php';
		else
			$this->helper->showError('Theme "'.$view."\" view doesn't exists in theme ".THEME);
	}

	/*
	* Load Component
	* $comp: Component name
	* $cntrl: Controller name
	* $mthd: Method name
	* $params: Parameters (Optional)
	*/
	protected function component($comp, $ctrl=false, $mtd=false, $param=[]){
		if(!$ctrl || $ctrl=='') $ctrl = $comp;
		if(!$mtd || $mtd=='') $mtd = DEFAULT_METHOD;
		//Check access
		if(!self::checkAccess($comp, $ctrl, $mtd))
			return;

		$param['frame_id'] = $this->compid;
		$this->route = new Route;
		$this->route->main();
		$component=$this->route->component;
		$controller=strtolower(get_class($this->route->controller));
		$method=$this->route->method;
		if($comp==$component && $ctrl==$controller && $mtd==$method){
			$this->helper->showError("You can't include same component \"$comp=>$ctrl=>$mtd\"");
		}else{
			$this->route->comp($comp, $ctrl, $mtd,$param);
			$this->route->render();			
		}
		$this->compid++;
	}

	/*
	* Check user authenticated or not
	*/
	protected function isAuth(){
		$debug = debug_backtrace();
		if(!isset($_SESSION[SESSION_KEY]) && $debug[2]['object']->controller!='auth' && $debug[2]['object']->component!='auth'){
			header('Location: '.BASE);
			exit;
		}else if(isset($_SESSION[SESSION_KEY]) && $debug[2]['object']->controller!='auth' && $debug[2]['object']->component!='auth'){
			if(!self::checkAccess($debug[2]['object']->component,$debug[2]['object']->controller,$debug[2]['object']->method)){
				header('Location: '.BASE);
				exit;
			}
		}
	}

	/*
	* Check user accessable.
	* $comp: Component name
	* $ctrl: Controller name
	* $mtd: Method name
	*/
	private function checkAccess($comp, $ctrl=false, $mtd=false){
		if(!$ctrl || $ctrl=='') $ctrl = $comp;
		if(!$mtd || $mtd=='') $mtd = DEFAULT_METHOD;
		if($comp===$ctrl)
			$access = md5(ucfirst($comp));
		else
			$access = md5(ucfirst($comp).'/'.ucfirst($ctrl));
		$user = $_SESSION[SESSION_USER];
		$UserRights=$this->helper->model('userrights');
		$prefix=DB_PREFIX;
		$isaccess = $UserRights->sql("SELECT * FROM {$prefix}userrights WHERE pageaccess='$access' AND status=1 AND (JSON_SEARCH(usertype, 'one', $user) IS NOT NULL OR JSON_SEARCH(usertype, 'one', '*') IS NOT NULL)")->prepare()->execute();
		if($isaccess->rowCount()<=0)
			return false;
		else
			return true;
	}

	function __destruct(){
	}
}
