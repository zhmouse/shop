<?php
require 'init.php';

$a= $_GET['a'];
switch($a){
	case 'add';
		$gid=$_POST['gid'];
		$num=$_POST['num'];
		if(!empty($_SESSION['cart'][$gid])){
			$_SESSION['cart'][$gid]['num'] += $num;
			header('location:cart.php');
			exit;
		}
		$sql="SELECT g.id gid,g.name gname,g.cate_id,g.price, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND is_face=1 AND g.id={$gid}";
		echo $sql;
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$row=mysql_fetch_assoc($result);
			$_SESSION['cart'][$gid]=$row;
			$_SESSION['cart'][$gid]['num']=$num;
			header('location:cart.php');
		}
		break;
	case 'jia':
		$gid= $_GET['gid'];
		$sql="SELECT stock FROM ".PRE."goods WHERE id={$gid}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$row= mysql_fetch_assoc($result);
			$stock=$row['stock'];
		}
		$_SESSION['cart'][$gid]['num']+=1;
		if($_SESSION['cart'][$gid]['num']>$stock){
			$_SESSION['cart'][$gid]['num']=$stock;
		}
		header('location:cart.php');
		break;

	case 'jian':
		$gid= $_GET['gid'];
		$_SESSION['cart'][$gid]['num']-=1;
		if($_SESSION['cart'][$gid]['num']<1){
			$_SESSION['cart'][$gid]['num']=1;
		}
		header('location:cart.php');
		break;	
	case 'del':
		$gid= $_GET['gid'];
		unset($_SESSION['cart'][$gid]);
		header('location:cart.php');
		break;
		
	case 'delete':
		unset($_SESSION['cart']);
		header('location:cart.php');
		break;
		
	
}