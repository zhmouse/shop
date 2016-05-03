<?php

require '../init.php';
//var_dump($_SESSION['admin']);
//用户
$sql="SELECT COUNT(id) num FROM ".PRE."user GROUP BY type";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$user=array();
	while($rows=mysql_fetch_assoc($result)){
		$user[]=$rows;
	}
	//var_dump($user);exit;
	$user_total=$user[0]['num']+$user[1]['num']+$user[2]['num'];//总数
}

$u1=$user[0]['num']/$user_total*360;
$u2=$user[1]['num']/$user_total*360;
$u3=$user[2]['num']/$user_total*360;
$y1='普通用户（'.$user[0]['num'].'人）';
$y2='普通管理员（'.$user[1]['num'].'人）';
$y3='超级管理员（'.$user[2]['num'].'人）';
arcimage($u1,$u2,$u3,0,0,$y1,$y2,$y3,'','');