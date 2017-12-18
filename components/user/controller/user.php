<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	User
*Class:		User
*/

if (! defined('PHINDART')) { die('Access denied'); }

class User extends Controller{

	function main($page=1){
		if(isset($_SESSION[SEARCHBY])){
			unset($_SESSION[SEARCHBY]);
			unset($_SESSION[SEARCHTXT]);
			unset($_SESSION[SEARCHOPER]);
		}
		$this->component('menu','navbar','main');
		$data = $this->helper->model('users')->select('username','b.name',"if(a.status=1,'Active','Deleted') as status")->alias('a')->aInnerUsertypesB('usertype','Eq','id')->paginate($page, 10)->prepare();
		$json = $this->helper->model('users')->select('username','usertype','status')->paginate($page, 10)->prepare()->execute()->fetchAll();
		$Users = $data->execute()->fetchAll();
		$Head = ['username'=>'Username', 'b.name'=>'User Type','status'=>'Status'];
		$Usertypes = $this->helper->model('usertypes')->select('id','name')->prepare()->execute()->fetchAll();
		$this->view('user',['id'=>'username', 'action'=>BASE.$this->component.'/user','json'=>json_encode($json), 'usertypes'=>$Usertypes, 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Users', 'tableHeads'=>$Head, 'data'=>$Users, 'url'=>'/user', 'id'=>'username', 'pager'=>$data->getPages($page, BASE.$this->component.'/user'), 'page'=>$page]);
	}

	function remove($page=1){
		if(!isset($_POST['id'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/user/'.$page);
			exit;
		}
		try{
			$this->helper->model('users')->update(['status'=>0])->Eq('username',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'User disabled successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/user/'.$page);
		exit;
	}

	function search($page=1){
		if(isset($_POST['search_txt'])){
			if(!isset($_POST['search_by'])) $sby = 'a.username';
			else{
				if(strpos($_POST['search_by'], '.'))
					$sby = $_POST['search_by'];	
				else
					$sby = 'a.'.$_POST['search_by'];
			}
			$stxt = $_POST['search_txt'];
			$_SESSION[SEARCHBY] = $sby;
			$_SESSION[SEARCHTXT] = $stxt;
			$oper = $_POST['search_oper'];
			$_SESSION[SEARCHOPER] = $oper;
		}else if(isset($_SESSION[SEARCHBY])){
			$sby = $_SESSION[SEARCHBY];
			$stxt = $_SESSION[SEARCHTXT];
			$oper = $_SESSION[SEARCHOPER];
		}else{
			header('location: '.BASE.$this->component.'/user/'.$page);
			exit;
		}
		$this->component('menu','navbar','main');
		$data = $this->helper->model('users')->select('username','b.name',"if(a.status=1,'Active','Deleted') as status")->alias('a')->aInnerUsertypesB('usertype','Eq','id')->$oper($sby, $stxt)->paginate($page, 10)->prepare();
		$json = $this->helper->model('users')->select('username','usertype','status')->$oper($sby, $stxt)->paginate($page, 10)->prepare()->execute()->fetchAll();
		$Users = $data->execute()->fetchAll();
		$Head = ['username'=>'Username', 'b.name'=>'User Type','status'=>'Status'];
		$Usertypes = $this->helper->model('usertypes')->select('id','name')->prepare()->execute()->fetchAll();
		$this->view('user',['id'=>'username', 'action'=>BASE.$this->component.'/user','json'=>json_encode($json), 'usertypes'=>$Usertypes, 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Users', 'tableHeads'=>$Head, 'data'=>$Users, 'url'=>'/user', 'id'=>'username', 'pager'=>$data->getPages($page, BASE.$this->component.'/user/search'), 'page'=>$page]);
	}

	function insert($page=1){
		if(!isset($_POST['password']) || !isset($_POST['username'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/user/'.$page);
			exit;
		}
		try{
			$_POST['password'] = md5($_POST['username'].$_POST['password']);
			$this->helper->model('users')->create($_POST)->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'User added successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/user/'.$page);
		exit;
	}

	function update($page=1){
		if(!isset($_POST['password']) || !isset($_POST['username'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/user/'.$page);
			exit;
		}
		if(!empty($_POST['password']))
			$data['password'] = md5($_POST['username'].$_POST['password']);
		$data['status'] = $_POST['status'];
		$data['usertype'] = $_POST['usertype'];
		try{
			$this->helper->model('users')->update($data)->Eq('username',$_POST['username'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'User updated successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/user/'.$page);
		exit;
	}

	function download(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Users.csv');
		if(!isset($_SESSION[SEARCHBY]))
			$Users =  $this->helper->model('users')->select('username','b.name',"if(a.status=1,'Active','Deleted') as status")->alias('a')->aInnerUsertypesB('usertype','Eq','id')->prepare()->execute();
		else{
			$sby = $_SESSION[SEARCHBY];
			$stxt = $_SESSION[SEARCHTXT];
			$oper = $_SESSION[SEARCHOPER];
			$Users =  $this->helper->model('users')->select('username','b.name',"if(a.status=1,'Active','Deleted') as status")->alias('a')->aInnerUsertypesB('usertype','Eq','id')->$oper($sby, $stxt)->prepare()->execute();
		}
		$Head = ['username'=>'Username', 'b.name'=>'User Type','status'=>'Status'];
		fputcsv($fp, $Head);
		while($row = $Users->fetch())
			fputcsv($fp, $row);
		exit;
	}

	function format(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Users.csv');
		$Head = ['username'=>'Username', 'b.name'=>'User Type','status'=>'Status'];
		fputcsv($fp, $Head);
		exit;
	}

	function upload(){
		print_r($_FILES);
		print_r($_POST);
	}

}