<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Menu
*Class:		Rights
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Rights extends Controller{
	
	function main($page=1){
		$this->component('menu','navbar','main');
		$UserTypes = $this->helper->model('usertypes')->select('id','name')->Eq('status',1)->prepare()->execute()->fetchAll();
		$Menus = $this->helper->model('menus');
		$submenus = $Menus->select('id', 'B.name as parent', 'name', 'display_name', '_order', 'user_access','link')->alias('a')->Neq('a.parent',0)->aLeftMenusB('parent','Eq','id');
		$data = $Menus->select('id', '"Parent" as parent', 'name', 'display_name', '_order', 'user_access','link')->Eq('parent',0)->union(
			function() use($submenus){
				return $submenus;
		})->Order('parent', 'ASC')->Order('_order','ASC')->prepare();
		$menus = $data->execute()->fetchAll();
		foreach ($menus as $key => $value) {
			$menus[$key]['user_access'] = json_decode($value['user_access']);
		}
		$drop = $Menus->select('id','name')->prepare()->execute()->fetchAll();
		$comps = $this->helper->model('userrights')->select('page')->Eq('status',1)->prepare()->execute()->fetchAll(PDO::FETCH_COLUMN, 0);
		$this->view('rights', ['usertypes'=>$UserTypes, 'data'=>$menus, 'pager'=>'', 'comps'=>$comps, 'json'=>json_encode($menus), 'drop'=>$drop]);
	}

	function updater(){
		echo '<pre>';
		//print_r($_POST);
		foreach ($_POST as $key => $value) {
			$data['_order'] = $value['order'];
			unset($value['order']);
			if(array_key_exists("'all'", $value))
				$data['user_access'] = json_encode(array('*'));
			else{
				$data['user_access'] = '["'.implode('","', array_keys($value)).'"]';
			}
			try{
				$this->helper->model('menus')->update($data)->Eq('id',$key)->prepare()->execute();
			}catch(Exception $e){
				$_SESSION[SESSION_MSG] = $e->getMessage();
				header('location: '.BASE.$this->component.'/rights');
				return;		
			}
		}
		$_SESSION[SESSION_MSG] = 'Menu rights updated successfully!';
		header('location: '.BASE.$this->component.'/rights');
		exit;
	}

	function insert(){
		if(!isset($_POST['name'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/rights');
			exit;
		}
		$data['name'] = $_POST['name'];
		$data['display_name'] = $_POST['display_name'];
		$data['link'] = $_POST['link'];
		$data['parent'] = $_POST['parent'];
		$data['type'] = 'top';
		unset($_POST['name']);
		unset($_POST['display_name']);
		unset($_POST['link']);
		unset($_POST['parent']);
		if(array_key_exists("all", $_POST))
			$data['user_access'] = json_encode(array('*'));
		else{
			$data['user_access'] = '["'.implode('","', array_keys($_POST)).'"]';
		}
		try{
			$this->helper->model('menus')->create($data)->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Menu added successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/rights');
		exit;
	}

	function update(){
		$data['name'] = $_POST['name'];
		$data['display_name'] = $_POST['display_name'];
		$data['link'] = $_POST['link'];
		$data['parent'] = $_POST['parent'];
		$data['type'] = 'top';
		unset($_POST['name']);
		unset($_POST['link']);
		unset($_POST['parent']);
		if(array_key_exists("all", $_POST))
			$data['user_access'] = json_encode(array('*'));
		else{
			$data['user_access'] = '["'.implode('","', array_keys($_POST)).'"]';
		}
		try{
			$this->helper->model('menus')->update($data)->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Menu updated successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/rights');
		exit;
	}

	function remove(){
		if(!isset($_POST['id'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/rights');
			exit;
		}
		try{
			$this->helper->model('menus')->delete()->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Menu deleted successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/rights');
		exit;
	}

}