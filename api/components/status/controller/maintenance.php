<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Status
*Class:		Maintenance
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Maintenance extends Controller{
	
	function __construct(){
		$this->helper = new Helper;
	} //Ignore parent constructor
	
	function main(){
		if(MODE!='MAINTENANCE')
			header('location:'.BASE.'api/');
		$arr = array('message'=>'System Under Maintenance');
		echo $this->helper->arrayToJson($arr);
	}
}