<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Component:	User
*Class:		Profile
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Profile extends Controller{

	function main(){
		$this->component('menu','navbar','main');		
	}
}