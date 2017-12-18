<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*File:		Index
*/
if(file_exists('./install.php')){
	header('location: ./install.php');
}
require_once './core/init.php';

//Initialize phindart.
$phindart = new Init();