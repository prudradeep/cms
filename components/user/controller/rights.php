<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	User
*Class:		Rights
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Rights extends Controller{

	function main(){
		$this->component('menu','navbar','main');
		$UserTypes = $this->helper->model('usertypes')->select('id','name')->Eq('status',1)->prepare()->execute()->fetchAll();
		$UserRights = $this->helper->model('userrights')->select('usertype', 'page', 'status')->prepare()->execute()->fetchAll();
		$components = $this->helper->getDir(COMP_PATH);
		$comps= array();
		foreach ($components as $key => $value) {
			foreach ($value['items'][0]['items'] as $k => $v) {
				$comps[ucfirst($value['name'])][ucfirst($v['name'])]['name'] = ucfirst($v['name']);
			}
			if(isset($value['items'][1]) && $value['items'][1]['name']=='install'){
				$comps[ucfirst($value['name'])]['install'] = $value['items'][1]['install'];
				$comps[ucfirst($value['name'])]['uninstall'] = false;
			}
			else if(isset($value['items'][1]) && $value['items'][1]['name']=='uninstall'){
				$comps[ucfirst($value['name'])]['install'] = false;
				$comps[ucfirst($value['name'])]['uninstall'] = $value['items'][1]['install'];
			}
		}
		$rights = [];
		foreach ($UserRights as $key => $value) {
			$rights[$value['page']] = json_decode($value['usertype']);
			$exp = explode("/",$value['page']);
			if(isset($exp[1]))
				$comps[$exp[0]][$exp[1]]['status'] = $value['status'];
			else
				$comps[$exp[0]][$exp[0]]['status'] = $value['status'];
		}
		$this->view('rights', ['usertypes'=>$UserTypes, 'comps'=>$comps, 'data'=>$rights, 'url'=>'/rights']);
	}

	function submit(){
		foreach ($_POST as $key => $value) {
			$data['page'] = $key;
			$data['pageaccess'] = md5($key);
			if(array_key_exists("status", $value)){
				$data['status'] = 1;
			}else{
				$data['status'] =0;
			}
			unset($value['status']);
			if(array_key_exists("'all'", $value))
				$data['usertype'] = json_encode(array('*'));
			else{
				$data['usertype'] = '["'.implode('","', array_keys($value)).'"]';
			}
			$this->helper->model('userrights')->replace($data)->prepare()->execute();
		}
		header('location: '.BASE.'user/rights');
	}

	function install($comp_name=null){
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==BASE."user/rights" && $comp_name!==null){
			//Check for SQLs
			$SQLs = json_decode(file_get_contents(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json'))->sqls;
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
					            $UserRights->sql($query)->prepare()->execute();
					            $query = '';
				            }catch(Exception $e){
				            	$_SESSION[SESSION_MSG] = "SQL Error: ".$e->getMessage();
				            	header('location: '.BASE.'user/rights');
								exit;
				            }
				        }
				    }
				}
			}
			rename(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json', BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'uninstall.json');
			$_SESSION[SESSION_MSG] = 'Component installed successfully!';
			header('location: '.BASE.'user/rights');
			exit;
		}else{
			$_SESSION[SESSION_MSG] = 'Invalid Request!';
			header('location: '.BASE.'user/rights');
			exit;
		}
	}

	function uninstall($comp_name=null){
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==BASE."user/rights" && $comp_name!==null){
			$UserRights = $this->helper->model('userrights');
			try{
				$UserRights->update(['status'=>0])->Like('page', $comp_name."%")->prepare()->execute();
			}catch(Exception $e){
            	echo "SQL Error: ".$e->getMessage();
            	header('location: '.BASE.'user/rights');
				exit;
            }
			rename(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'uninstall.json', BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json');
			$_SESSION[SESSION_MSG] = 'Component uninstalled successfully!';
			header('location: '.BASE.'user/rights');
			exit;
		}else{
			$_SESSION[SESSION_MSG] = 'Invalid Request!';
			header('location: '.BASE.'user/rights');
			exit;
		}
	}
	function remove($comp_name=null){
		if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER']==BASE."user/rights" && $comp_name!==null){
			//Check for SQLs
			$SQLs = json_decode(file_get_contents(BASE_DIR.DS.COMP_PATH.DS.$comp_name.DS.'install.json'))->sqls;
			$UserRights = $this->helper->model('userrights');
			//Remove SQLs
			$SQLs = array_reverse($SQLs);
			foreach ($SQLs as $key => $value) {
	            $query = "DROP TABLE IF EXISTS ".$value;
	            try{
		            $UserRights->sql($query)->prepare()->execute();
	            }catch(Exception $e){
	            	$_SESSION[SESSION_MSG] = "SQL Error: ".$e->getMessage();
	            	header('location: '.BASE.'user/rights');
					exit;
	            }
			}
			try{
				$UserRights->delete()->Like('page', $comp_name."%")->prepare()->execute();
			}catch(Exception $e){
            	echo "SQL Error: ".$e->getMessage();
            	header('location: '.BASE.'user/rights');
				exit;
            }
			//Remove files
			self::dlinkFiles(BASE_DIR.DS.COMP_PATH.DS.$comp_name);
			$_SESSION[SESSION_MSG] = 'Component files deleted successfully!';
			header('location: '.BASE.'user/rights');
			exit;
		}else{
			$_SESSION[SESSION_MSG] = 'Invalid Request!';
			header('location: '.BASE.'user/rights');
			exit;
		}
	}

	function upload(){
		if ($_FILES['upload_file']['size'] > 0) {			
			
			/* Upload file to tmp directory. */
			$targetFile = 'tmp/'.$_FILES['upload_file']['name'];
			$filename = $_FILES['upload_file']['name'];
			if(file_exists($targetFile))
				unlink($targetFile);
			move_uploaded_file($_FILES["upload_file"]["tmp_name"], $targetFile);
			
			/* Get absolute path of uploaded file. */
			$path = dirname(dirname(__FILE__));
			$targetFile = BASE_DIR.DS.$targetFile;

			$ext = pathinfo($targetFile, PATHINFO_EXTENSION);
			if($ext==='zip'){
				$zip = new ZipArchive;
				/*Read component information.*/
				$install = json_decode(file_get_contents("zip://$targetFile#install.json"));
				if ($zip->open($targetFile) === TRUE) {
				    /*Extract files*/
				    if(!file_exists(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name)){
				    	self::extFiles($zip, $install, $targetFile);
						/*Install component and SQLs*/
						self::install($install->comp_name);
				    }else if(file_exists(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS."install.json")){
				    	self::dlinkFiles(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name);
				    	self::extFiles($zip, $install, $targetFile);
						/*Install component and SQLs*/
						self::install($install->comp_name);
				    }else if(file_exists(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS."uninstall.json")){
				    	$uninstall = json_decode(file_get_contents(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS."uninstall.json"));
				    	/*Compare version*/
				    	if($uninstall->version < $install->version){
				    		//Update files.
				    		self::dlinkFiles(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name);
				    		self::extFiles($zip, $install, $targetFile);
				    		rename(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS.'install.json', BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS.'uninstall.json');
				    		$_SESSION[SESSION_MSG] = 'Component updated successfully!';
							header('location: '.BASE.'user/rights');
							exit;
				    	}else if($uninstall->version > $install->version){
				    		$_SESSION[SESSION_MSG] = 'Component version is old!';
							header('location: '.BASE.'user/rights');
							exit;
				    	}else{
				    		$_SESSION[SESSION_MSG] = 'Component already exists!';
							header('location: '.BASE.'user/rights');
							exit;
				    	}
				    }
				}
			}
			
		}
	}

	private function extFiles($zip=null, $install=null, $targetFile=null){
		if($zip==null || $install==null || $targetFile==null)
			return;
		mkdir(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name);
    	$zip->extractTo(BASE_DIR.DS.COMP_PATH.DS.$install->comp_name.DS);
    	$zip->close();
		unlink($targetFile);
	}

	private function dlinkFiles($path=null){
		if($path==null)
			return;
		if (PHP_OS === 'WINNT'){
		    exec(sprintf("rd /s /q %s", escapeshellarg($path)));
		}else{
		    exec(sprintf("rm -rf %s", escapeshellarg($path)));
		}
	}
}