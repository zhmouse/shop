<?php

//声明admin_path
define('ADMIN_PATH',str_replace('\\','/',dirname(__FILE__).'/'));

//声明admin_url
define('ADMIN_URL','http://'.$_SERVER['HTTP_HOST'].'/project/admin/');

//包含上级init.php
require ADMIN_PATH.'../init.php';

//自动转向
if(!isset($_SESSION['admin'])){
	header('location:'.ADMIN_URL.'login.php');
}

