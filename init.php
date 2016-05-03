<?php

//开启session
session_start();
header('cache-control:private,must_revalidate');

//字符集
header('content-type:text/html;charset=utf-8');

//设置时区
date_default_timezone_set('PRC');

//错误处理
error_reporting('E_ALL ^E_NOTICE');

//路径
define('PATH',str_replace('\\','/',dirname(__FILE__).'/'));
define('URL','http://'.$_SERVER['HTTP_HOST'].'/project/');
define('FONT_PATH',PATH.'/css/fonts/');
define('UPLOAD_PATH',PATH.'uploads/');
define('UPLOAD_URL',URL.'uploads/');
$size=array(50,50,220,220,800,800,350,350);//略缩图尺寸

//加载config.php
require PATH.'inc/config.php';
require PATH.'inc/function.php';
//数据库
$link = mysql_connect(HOST,USER,PWD);
if(mysql_errno()){
	echo 'Error'.mysql_errno().':'.mysql_error;
}
mysql_select_db(DBNAME);
mysql_set_charset(CHARSET);
/*已登录禁止访问注册登录页
if(($_SESSION['home'] || $_SESSION['admin']) && in_array(basename($_SERVER['PHP_SELF']),array('login.php','regist.php'))){
	echo '------------------------------';
	echo $_SERVER['HTTP_REFERER'];
	//header('location:index.php'.$_SERVER['HTTP_REFERER']);
	header('location:'.$_SERVER['HTTP_REFERER']);
}
*/