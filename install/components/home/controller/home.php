<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Home
*Class:		Home
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Home extends Controller{

	function main(){
		
		if(!isset($_SESSION['install'])){
			$msg = $this->helper->arrayToJson(['error'=>403, 'message'=>$this->helper->errorMessage(403)]);
			die($msg);
			exit;
		}
		$comps = array('config','menu','user','status','auth','home');
		foreach ($comps as $key => $value) {
			self::install($value);
		}
		rename(BASE_DIR.DS.'install.php', BASE_DIR.DS.'uninstall.php');
		self::config();
		$base=$_SESSION['install']['base'];
		$this->helper->destroySession();
		header('location: '.$base);
		exit;
	}

	private function install($comp_name=null){
		$base=$_SESSION['install']['base'];
		$cre = '/(.*)(TEMPORARY |TABLE |IF NOT EXISTS )`?([\w]*)`?/';
		$ire = '/(.*)INTO ([\w].*)/';
		$prefix = DB_PREFIX;
		if($comp_name!==null){
			//Check for SQLs
			if(file_exists(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json'))
				$SQLs = json_decode(file_get_contents(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json'))->sqls;
			else if(file_exists(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'uninstall.json'))
				$SQLs = json_decode(file_get_contents(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'uninstall.json'))->sqls;
			$UserRights = $this->helper->model('userrights');
			//Install SQLs
			foreach ($SQLs as $key => $value) {
				$fp = file(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'sql'.DS.$value.'.sql', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
				$query = '';
				foreach ($fp as $line) {
				    if ($line != '' && strpos($line, '--') === false) {
				        $query .= $line;
				        if (substr($query, -1) == ';') {
				            try{
						    //Create
						    $create = preg_match_all($cre, $query);
						    if($create==1)
					            	$query = preg_replace($cre, "$1$2$prefix$3", $query);
						    //Insert|Replace
						    $insert = preg_match_all($ire, $query);
						    if($insert==1)
						    	$query = preg_replace($ire, "$1$prefix$2", $query);
						    $UserRights->sql($query)->prepare()->execute();
					            $query = '';
				            }catch(Exception $e){
				            	echo "SQL Error: ".$e->getMessage();
				            }
				        }
				    }
				}
			}
			try{
				rename(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json', BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'uninstall.json');
			}catch(Exception $e){}
			return;
		}
	}

	private function config(){
		$Prerequisites = $this->helper->model('settings');
		$Prerequisites->replace(['type'=>'global','field'=>'TIMEZONE','value'=>$_SESSION['install']['timezone'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'global','field'=>'LOGO','value'=>$_SESSION['install']['logo'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'site','field'=>'title','value'=>$_SESSION['install']['title'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'site','field'=>'description','value'=>$_SESSION['install']['description'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'site','field'=>'keys','value'=>$_SESSION['install']['keys'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'site','field'=>'copyright','value'=>$_SESSION['install']['copyright'],'status'=>1])->prepare()->execute();
		$Prerequisites->replace(['type'=>'global','field'=>'BASE','value'=>$_SESSION['install']['base'],'status'=>1])->prepare()->execute();
		$Users = $this->helper->model('users');
		$Users->replace(['username'=>$_SESSION['install']['username'], 'password'=>md5($_SESSION['install']['username'].$_SESSION['install']['password']), 'usertype'=>-1,'status'=>1])->prepare()->execute();
	}
}
