<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Config
*Class:		Config
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Config extends Controller{

	function main($page=1){
		if(isset($_SESSION[SEARCHBY])){
			unset($_SESSION[SEARCHBY]);
			unset($_SESSION[SEARCHTXT]);
			unset($_SESSION[SEARCHOPER]);
		}
		$this->component('menu','navbar','main');
		$data = $this->helper->model('settings')->select('id','type', 'field', 'value', "if(status=1,'Active','Inactive') as status")->paginate($page, 10)->prepare();
		$datb = $data->execute()->fetchAll();
		$Themer = array();
		foreach ($datb as $key => $value) {
			$Themer[] = $value->getAll();
		}
		
		$Head = ['id'=>'Id','type'=>'Type', 'field'=>'Field', 'value'=>'Value', 'status'=>'Status'];
		$this->view('theme',['id'=>'id', 'action'=>BASE.$this->component.'/config','json'=>json_encode($Themer), 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Settings', 'tableHeads'=>$Head, 'data'=>$Themer, 'url'=>'/config', 'id'=>'id', 'pager'=>$data->getPages($page, BASE.$this->component.'/config'), 'page'=>$page]);		
	}

	function remove($page=1){
		if(!isset($_POST['id'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/config/'.$page);
			exit;
		}
		try{
			$this->helper->model('settings')->update(['status'=>0])->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Setting inactive successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/config/'.$page);
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
			header('location: '.BASE.$this->component.'/config/'.$page);
			exit;
		}
		
		$this->component('menu','navbar','main');
		$data = $this->helper->model('settings')->select('id','type', 'field', 'value', "if(status=1,'Active','Inactive') as status")->$oper($sby, $stxt)->paginate($page, 10)->prepare();
		$datb = $data->execute()->fetchAll();
		$Themer = array();
		foreach ($datb as $key => $value) {
			$Themer[] = $value->getAll();
		}		
		$Head = ['id'=>'Id','type'=>'Type', 'field'=>'Field', 'value'=>'Value', 'status'=>'Status'];
		$this->view('theme',['id'=>'id', 'action'=>BASE.$this->component.'/config','json'=>json_encode($Themer), 'page'=>$page]);
		$this->loadView('master', ['heading'=>'Settings', 'tableHeads'=>$Head, 'data'=>$Themer, 'url'=>'/config', 'id'=>'id', 'pager'=>$data->getPages($page, BASE.$this->component.'/config/search'), 'page'=>$page]);
	}

	function insert($page=1){
		if(!isset($_POST['field'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/config/'.$page);
			exit;
		}
		if(empty($_POST['id']))
			unset($_POST['id']);
		try{
			$this->helper->model('settings')->create($_POST)->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Config added successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/config/'.$page);
		exit;
	}

	function update($page=1){
		if(!isset($_POST['field'])){
			$_SESSION[SESSION_MSG] = $this->helper->errorMessage(103);
			header('location: '.BASE.$this->component.'/config/'.$page);
			exit;
		}
		$data['type'] = $_POST['type'];
		$data['field'] = $_POST['field'];
		$data['value'] = $_POST['value'];
		$data['status'] = $_POST['status'];
		try{
			$this->helper->model('settings')->update($data)->Eq('id',$_POST['id'])->prepare()->execute();
			$_SESSION[SESSION_MSG] = 'Config updated successfully!';
		}catch(Exception $e){
			$_SESSION[SESSION_MSG] = $e->getMessage();
		}
		header('location: '.BASE.$this->component.'/config/'.$page);
		exit;
	}

	function download(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Config.csv');
		if(!isset($_SESSION[SEARCHBY]))
			$Prerequisites =  $this->helper->model('settings')->select('id','type', 'field', 'value', "if(status=1,'Active','Inactive') as status")->prepare()->execute();
		else{
			$sby = $_SESSION[SEARCHBY];
			$stxt = $_SESSION[SEARCHTXT];
			$oper = $_SESSION[SEARCHOPER];
			$Prerequisites =  $this->helper->model('settings')->select('id','type', 'field', 'value', "if(status=1,'Active','Inactive') as status")->$oper($sby, $stxt)->prepare()->execute();
		}
		$Head = ['id'=>'Id','type'=>'Type', 'field'=>'Field', 'value'=>'Value', 'status'=>'Status'];
		fputcsv($fp, $Head);
		foreach ($Prerequisites as $key => $value) {
			fputcsv($fp, $value->getAll());
		}
		exit;
	}

	function format(){
		ob_clean();
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=Config.csv');
		$Head = ['type'=>'Type', 'field'=>'Field', 'value'=>'Value', 'status'=>'Status'];
		fputcsv($fp, $Head);
		exit;
	}

	function upload(){
		print_r($_FILES);
		print_r($_POST);
	}

}