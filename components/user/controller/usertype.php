<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	User
*Class:		Usertype
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Usertype extends Controller{

	function main($page=1){
		if(isset($_SESSION[SEARCHBY])){
			unset($_SESSION[SEARCHBY]);
			unset($_SESSION[SEARCHTXT]);
			unset($_SESSION[SEARCHOPER]);
		}
		$this->component('menu','navbar','main');
		$data = $this->helper->model('usertypes')->select('id','name',"if(status=1,'Active','Inactive') as status")->paginate($page, 10)->prepare();
		$Usertypes = $data->execute()->fetchAll();
		$Head = ['id'=>'Id', 'name'=>'Name','status'=>'Status'];
		$this->loadView('type',['id'=>'id', 'name'=>'Usertypes', 'action'=>BASE.$this->component.'/usertype','json'=>json_encode($Usertypes), 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Usertypes', 'tableHeads'=>$Head, 'data'=>$Usertypes, 'url'=>'/usertype', 'id'=>'id', 'pager'=>$data->getPages($page, BASE.$this->component.'/usertype'), 'page'=>$page]);
	}

	function remove($page=1){
		if(!isset($_POST['id'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/usertype/'.$page);
			exit;
		}
		try{
			$this->helper->model('usertypes')->update(['status'=>0])->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Usertype inactive successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/usertype/'.$page);
		exit;
	}

	function search($page=1){
		if(isset($_POST['search_txt'])){
			if(!isset($_POST['search_by'])) $sby = 'id';
			else $sby = $_POST['search_by'];
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
			header('location: '.BASE.$this->component.'/usertype/'.$page);
			exit;
		}
		$this->component('menu','navbar','main');
		$data = $this->helper->model('usertypes')->select('id','name',"if(status=1,'Active','Inactive')")->$oper($sby, $stxt)->paginate($page, 10)->prepare();
		$Usertypes = $data->execute()->fetchAll();
		$Head = ['id'=>'Id', 'name'=>'Name','status'=>'Status'];
		$this->loadView('type',['id'=>'id', 'name'=>'Usertypes', 'action'=>BASE.$this->component.'/usertype','json'=>json_encode($Usertypes), 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Usertypes', 'tableHeads'=>$Head, 'data'=>$Usertypes, 'url'=>'/usertype', 'id'=>'id', 'pager'=>$data->getPages($page, BASE.$this->component.'/usertype/search'), 'page'=>$page]);
	}

	function insert($page=1){
		if(!isset($_POST['name'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/usertype/'.$page);
			exit;
		}
		try{
			$this->helper->model('usertypes')->create($_POST)->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Usertype added successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/usertype/'.$page);
		exit;
	}

	function update($page=1){
		if(!isset($_POST['name'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/usertype/'.$page);
			exit;
		}
		$data['name'] = $_POST['name'];
		$data['status'] = $_POST['status'];
		try{
			$this->helper->model('usertypes')->update($data)->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Usertype updated successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/usertype/'.$page);
		exit;
	}

	function download(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Usertypes.csv');
		if(!isset($_SESSION[SEARCHBY]))
			$Usertypes =  $this->helper->model('usertypes')->select('id','name', "if(status=1,'Active','Inactive') as status")->prepare()->execute();
		else{
			$sby = $_SESSION[SEARCHBY];
			$stxt = $_SESSION[SEARCHTXT];
			$oper = $_SESSION[SEARCHOPER];
			$Usertypes =  $this->helper->model('usertypes')->select('id','name', "if(status=1,'Active','Inactive') as status")->$oper($sby, $stxt)->prepare()->execute();
		}
		$Head = ['id'=>'Id', 'name'=>'Name','status'=>'Status'];
		fputcsv($fp, $Head);
		while($row = $Usertypes->fetch())
			fputcsv($fp, $row);
		exit;
	}

	function format(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Usertypes.csv');
		$Head = ['name'=>'Name','status'=>'Status'];
		fputcsv($fp, $Head);
		exit;
	}

	function upload(){
		print_r($_FILES);
		print_r($_POST);
	}

}