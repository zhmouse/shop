<?php
require 'init.php';
if(empty($_SESSION['home'])){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('location:login.php?url='.$url);
}
$a=$_GET['a'];
switch($a){
	case 'add':
		$user_id=$_SESSION['home']['id'];
		$addr = !empty($_POST['addr'])?$_POST['addr']:0;
		if($addr=='new' && $addr==0){
		
			//var_dump($_POST);exit;
			//接收订单数据
			foreach($_POST as $val){
				if($val==''||$val=='请选择'){
					mass('请填写完整再提交！');
					exit;
				}
			}
			$name=$_POST['name'];
			$mobile=$_POST['mobile'];
			$phone=$_POST['phone'];
			$email=$_POST['email'];
			$sheng = $_POST['sheng'];
			$shi = $_POST['shi'];
			$qu = $_POST['qu'];
			//组合地址
			$address = $sheng.','.$shi.','.$qu.','.$_POST['address'];
			$table='name,address,mobile,phone,email,user_id,status';
			$value = "'{$name}','{$address}','{$mobile}','{$phone}','{$email}','{$user_id}','{$status}'";
			$sql= "INSERT INTO ".PRE."address({$table}) values({$value})";
			//echo $sql;
			//exit;
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				//mass('收货地址添加成功','#CB351A',1,'./user.php?u=address',1);
				//exit;
			}else{
				mass('收货地址添加失败');
				exit;
			}
		}else{
			list($name,$mobile,$phone,$sheng,$shi,$qu,$address)=explode(',',$addr);
			$email='';
			$address = $sheng.','.$shi.','.$qu.','.$address;	
		}		
		$addtime = $_POST['addtime'];
		$note = $_POST['note'];
		$total=0;
		foreach($_SESSION['cart'] as $val){
			$total += $val['num']*$val['price'];
		}
		if($total==0){
			mass('此订单已生成，请不要重复提交','#CB351A',0,'index.php',0);
			exit;
		}
		//订单号生成
		$order_id=date('YmdHis').mt_rand(0,999);
		//echo $order_id;
		//exit;
		
		//拼接sql
		$table = 'order_id,name,mobile,phone,email,address,total,user_id,status,addtime,note';
		$value = '\''.$order_id.'\',\''.$name.'\',\''.$mobile.'\',\''.$phone.'\',\''.$email.'\',\''.$address.'\',\''.$total.'\','.$user_id.',1,\''.$addtime.'\',\''.$note.'\'';
		
		$sql="INSERT INTO ".PRE."order({$table}) VALUES({$value})";
		//echo $sql;
		//exit;
		$result=mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			$order_id=mysql_insert_id();
			foreach($_SESSION['cart'] as $key=>$val){
				$good_id=$key;
				$price=$val['price'];
				$num=$val['num'];
				
				$table ='goods_id,price,num,order_id';
				$value = '\''.$key.'\',\''.$price.'\',\''.$num.'\',\''.$order_id.'\'';
				$sql="INSERT INTO ".PRE."order_goods({$table}) VALUES({$value})";
				$result=mysql_query($sql);
				if($result && mysql_affected_rows()>0){
					unset($_SESSION['cart']);
				}else{
					mass('添加订单商品失败');
					exit;
				}				
			}
			header('location:order.php?u=order_suc&id='.$order_id);
		}else{
			mass('添加订单失败');
			exit;
		}
		unset($_POST);
		break;
	case 'no'://取消订单
		$oid=$_GET['id'];
		$id=$_SESSION['home']['id'];
		$sql="UPDATE ".PRE."order SET status=6 WHERE id={$oid} AND status<3 ";
		$result=mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			mass('订单已取消！','#CB351A',1,'./user.php?u=myorder',1);
			exit;
		}
		break;
	case 'del':
		$oid=$_GET['id'];
		$id=$_SESSION['home']['id'];
		$sql="UPDATE ".PRE."order SET status=concat('-',status) WHERE id={$oid} AND status=6 AND user_id={$id}";
		//echo $sql;exit;
		$result=mysql_query($sql);
		if($result){
			header('location:user.php?u=myorder');
			exit;
		}else{
			mass('订单删除失败！');
		}
		break;
}