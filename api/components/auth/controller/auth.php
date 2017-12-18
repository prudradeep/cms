<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Auth
*Class:		Auth
*/

if (! defined('PHINDART')) { die('Access denied'); }

class auth extends Controller{
	
	function main(){
		echo $this->helper->arrayToJson(['error'=>400, 'message'=>$this->helper->errorMessage(400)]);
	}

	function login(){
		if(!isset($_POST['username']) || !isset($_POST['password'])){
			echo $this->helper->arrayToJson(['error'=>103, 'message'=>$this->helper->errorMessage(103)]);
			exit;
		}
		$Users = $this->helper->model('users');
		$pass = md5($_POST['username'].$_POST['password']);
		try{
			$user = $Users->select('a.usertype','b.home', 'b.status')->alias('a')->aInnerUsertypesB('usertype','Eq','id')->Eq('a.username',$_POST['username'])->andEq('a.password',$pass)->andEq('a.status',1)->limit(1)->prepare()->execute()->fetch();
			if($user['status']===0){
				echo $this->helper->arrayToJson(['message'=>'Access denied, contact your admin!']);
				return;
			}
			else if(isset($user['usertype'])){
				$_SESSION[SESSION_KEY]=$user;
				$_SESSION[SESSION_USER]=$user['usertype'];
			}
			echo $this->helper->arrayToJson($user);
		}catch(Exception $e){
			echo $this->helper->arrayToJson(['error'=>$e->getCode(), 'message'=>$e->getMessage()]);
		}
	}
}