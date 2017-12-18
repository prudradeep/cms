<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Componenet:Status
*Class:		Status
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Status extends Controller{
	
	function __construct(){} //Ignore parent constructor
	
	function main(){
		header('location:'.BASE.'api/');
	}
}