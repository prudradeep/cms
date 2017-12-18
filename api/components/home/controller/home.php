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
		$arr = array('message'=>'API Home Page');
		echo $this->helper->arrayToJson($arr);
	}
}