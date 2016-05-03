<?php
require '../init.php';

$a=$_GET['a'];
switch($a){
	case 'del':
	$id=$_GET[id];
	$sql="DELETE FROM ".PRE."reply WHERE id={$id}";
	$result=mysql_query($sql);
	if($result){
		header('location:index.php');
		exit;
	}else{
		mass('删除失败！','#437ccf',0);
		exit;
	}
	
	break;
	case 'reply':
		foreach($_POST as $val){
			if($val==''){
				mass('请填写完整再行提交！');
				exit;
			}
		}
		$pid=$_POST['pid'];
		$path=$_POST['path'].$pid.',';
		$oid=$_POST['oid'];
		$gid=$_POST['gid'];
		$uid=$_SESSION['home']['id'];
		$url = $_POST['url'];
		$note=$_POST['note'];
		$addtime=time();
		$table='user_id,goods_id,order_id,note,addtime,pid,path';
		$value="'{$uid}','{$gid}','{$oid}','{$note}','{$addtime}','{$pid}','{$path}'";
		$sql="INSERT INTO ".PRE."reply({$table}) values({$value})";
		$result=mysql_query($sql);
		if($result && mysql_affected_rows()>0){
					$_SESSION['admin']['credits'] +=30;
					$_SESSION['home']['credits'] +=30;
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']==1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']==2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']==3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']==4)||//5
						($_SESSION['admin']['credits']>=200 && $_SESSION['admin']['val']==1)||//2
						($_SESSION['admin']['credits']>=1000 && $_SESSION['admin']['val']==2)||//3
						($_SESSION['admin']['credits']>=10000 && $_SESSION['admin']['val']==3)||//4
						($_SESSION['admin']['credits']>=100000 && $_SESSION['admin']['val']==4)//5
					){
						$_SESSION['home']['val']+=1;
						$_SESSION['admin']['val']+=1;
					}
					$val=$_SESSION['home']['val'];
					$sql="UPDATE ".PRE."user SET credits=credits+30,val={$val} WHERE id={$uid}";
					$result=mysql_query($sql);
			
			
			header('location:'.$url);
			exit;
		}else{
			mass('回复失败！');
			exit;
		}		
		break;
	case 'edit':
		foreach($_POST as $val){
			if($val==''){
				mass('请填写完整再行提交！');
				exit;
			}
		}
		$id=$_POST['id'];
		$url = $_POST['url'];
		$note=$_POST['note'];
		$addtime=time();
		$sql="UPDATE ".PRE."reply SET note='{$note}' WHERE id={$id}";
		$result=mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			header('location:'.$url);
			exit;
		}else{
			mass('编辑失败！');
			exit;
		}		
		break;
}
