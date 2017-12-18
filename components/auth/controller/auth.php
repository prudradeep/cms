<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	Auth
*Class:		Auth
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Auth extends Controller{

	function main(){
		if(isset($_SESSION[SESSION_KEY])){
			header('Location:'.BASE.'home');
		}
		$this->view('index');
	}

	function logout(){
		$this->helper->destroySession();
		header('Location:'.BASE);
	}
}