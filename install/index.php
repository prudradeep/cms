<?php
if (session_id() == "") session_start();
if(!defined('DEFAULT_COMP')) define('DEFAULT_COMP', 'home');
if(!defined('MODE')) define('MODE', 'DEVELOPMENT');
require_once '../core/init.php';

$app = new Init('install');