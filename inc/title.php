<?php

$filename=basename($_SERVER['SCRIPT_NAME']);
switch($filename){
	case 'user.php':
		$u=$_GET['u'];
		switch($u){
					case 'add_address':
						echo '新增收货地址';
						break;
					case 'address':
						echo '管理收货地址';
						break;
					case 'detail':
						echo '订单详情';
						break;
					case 'edit':
						echo '修改我的信息';
						break;
					case 'edit_address':
						echo '编辑地址';
						break;
					case 'myorder':
						echo '我的订单';
						break;
					case 'user':
						echo '个人资料';
						break;
					case 'reply':
						echo '添加评价';
						break;
					case 'replylist':
						echo '我的评价';
						break;
					case 'reset':
						echo '修改密码';
						break;
					
				}
		break;
	case "view.php":
		$gid=$_GET['gid'];
		$sql="SELECT name FROM ".PRE."goods WHERE id={$gid}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$title=mysql_fetch_assoc($result);
			echo $title['name'];
		}
		break;
	case "catelist.php":
		$id=$_GET['id'];
			if($id=="best"){
				echo '精益求精';
			}elseif($id=="new"){
				echo '初夏新品';
			}elseif($id=="hot"){
				echo '热销商品';
			}else{
				$sql="SELECT name FROM ".PRE."category WHERE id={$id}";
				$result=mysql_query($sql);
				if($result && mysql_num_rows($result)>0){
					$title=mysql_fetch_assoc($result);
					echo $title['name'];
				}
			}
		break;
	case "about.php":
		$id=$_GET['id'];
		$sql="SELECT title FROM ".PRE."about WHERE id={$id}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$title=mysql_fetch_assoc($result);
			echo $title['title'];
		}
		break;
	default:
		echo "首页";	
}