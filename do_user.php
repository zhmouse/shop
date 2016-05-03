<?php
require './init.php';
if(empty($_SESSION['home'])){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('location:login.php?url='.$url);
}

$a=$_GET['a'];
switch($a){
	case 'add_address':
		//接收订单数据
		//var_dump($_POST);
		foreach($_POST as $val){
			if($val==''||$val=='请选择'){
				mass('请完整填写收货地址！');
				exit;
			}
		}
		//echo "---------------------";
		$name=$_POST['name'];
		$mobile=$_POST['mobile'];
		$phone=$_POST['phone'];
		$email=$_POST['email'];
		$sheng = $_POST['sheng'];
		$shi = $_POST['shi'];
		$qu = $_POST['qu'];
		$status=!empty($_POST['status'])?1:0;
		//组合地址
		$address = $sheng.','.$shi.','.$qu.','.$_POST['address'];
		$user_id=$_SESSION['home']['id'];
		
		$table='name,address,mobile,phone,email,user_id,status';
		$value = "'{$name}','{$address}','{$mobile}','{$phone}','{$email}','{$user_id}','{$status}'";
		$sql= "INSERT INTO ".PRE."address({$table}) values({$value})";
		//echo $sql;
		//exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			mass('收货地址添加成功','#CB351A',1,'./user.php?u=address',1);
			exit;
		}else{
			mass('收货地址添加失败');
			exit;
		}
		break;
		
	case 'edit_address':
		$id=$_GET['id'];
		foreach($_POST as $val){
			if($val==''||$val=='请选择'){
				mass('请完整填写收货地址！');
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
		$status=!empty($_POST['status'])?1:0;
		//组合地址
		$address = $sheng.','.$shi.','.$qu.','.$_POST['address'];
		//$user_id=$_SESSION['home']['id'];
		$sql="UPDATE ".PRE."address SET name='{$name}',mobile='{$mobile}',phone='{$phone}',email='{$email}',address='{$address}' WHERE id={$id}";
		//echo $sql;exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			mass('收货地址修改成功','#CB351A',1,'./user.php?u=address',1);
			exit;
		}else{
			mass('收货地址修改失败');
			exit;
		}
		
		break;
	case 'del_address':
		$id=$_GET['id'];
		$sql="SELECT id FROM ".PRE."address WHERE id={$id}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			mass('默认收货地址不能删除');
			exit;
		}		
		$sql="DELETE FROM ".PRE."address WHERE id={$id}";
		$result=mysql_query($sql);
		if($result){
			mass('收货地址删除成功','#CB351A',1,'./user.php?u=address',1);
			exit;
		}else{
			mass('收货地址删除失败');
			exit;
		}
		break;
	case 'status':
		$id=$_GET['id'];
		$uid=$_SESSION['home']['id'];
		$sql="UPDATE ".PRE."address SET status=0 WHERE user_id={$uid}";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			$sql="UPDATE ".PRE."address SET status=1 WHERE id={$id}";
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				header('location:user.php?u=address');
				exit;
			}
		}
		break;
	case 'edit':
		$uid=$_SESSION['home']['id'];
		foreach($_POST as $val){
			if($val == ''){
				mass('请填写完整再提交！');
				exit;
			}
		}
		if(!empty($_FILES['pic']['tmp_name'][0])){
			$arr = upload('pic',PATH.'avatar/',1);
			$path = $arr[0];
			if(!$path){
				mass('头像上传失败，请重新上传！','#CB351A',0);
				exit;
			}
			
			$small = thumb($path,32,32);
			$middle = thumb($path,64,64);
			$large = thumb($path,96,96);
		
			if(	!$small || !$middle || !$large ){
				unlink($small);
				unlink($middle);
				unlink($large);
				unlink($path);
				mass('图片缩放失败，请重新上传！','#CB351A',0);
				exit;		
			}
			$filename = basename($path);
		}
		$id = $_POST['id'];
		$sex = $_POST['sex'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		if(empty($filename)){
			$sql = "UPDATE ".PRE."user SET sex='{$sex}',email='{$email}',phone='{$phone}' WHERE id = {$id}";
			//echo $sql;$filename; var_dump($_FILES);exit;
			
		}else{
			$sql="SELECT avatar FROM ".PRE."user WHERE id={$uid}";
			$result = mysql_query($sql);
				if($result && mysql_num_rows($result)>0){
					$row=mysql_fetch_assoc($result);
					if($row['avatar']!='avatar.gif'){
						//echo PATH.'avatar/32x32_'.$row['avatar'];exit;
						unlink(PATH.'avatar/32x32_'.$row['avatar']);
						unlink(PATH.'avatar/64x64_'.$row['avatar']);
						unlink(PATH.'avatar/96x96_'.$row['avatar']);
						unlink(PATH.'avatar/'.$row['avatar']);
					}
				}
			$sql = "UPDATE ".PRE."user SET sex='{$sex}',email='{$email}',phone='{$phone}',avatar='{$filename}' WHERE id = {$id}";
		}
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			//刷新SESSION
			$_SESSION['home']['sex']=$sex;
			$_SESSION['home']['email']=$email;
			$_SESSION['home']['phone']=$phone;
			if(!empty($filename)){
			$_SESSION['home']['avatar']=$filename;
			}
			mass('用户信息修改成功','#CB351A',1,'./user.php?u=edit');
			exit;
		}else{
			unlink($small);
			unlink($middle);
			unlink($large);
			unlink($path);
			mass('用户信息没有被修改，请修改后再提交');
			exit;
		}
		unset($_FILES);
		break;
	case 'reset':
		$id = $_POST['id'];
		$oldpassword = $_POST['oldpassword'];
		$newpassword = $_POST['newpassword'];
		$repassword = $_POST['repassword'];
		$sql = "SELECT password FROM ".PRE."user WHERE id={$id} AND password=md5({$oldpassword})";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			if($newpassword != $repassword){
				mass('两次输入密码不相同，请重输！');
				exit;
			}
			$sql="UPDATE ".PRE."user SET password=md5({$newpassword}) WHERE id={$id}";
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('密码修改成功','#CB351A',1,'./user.php');
				exit;
			}else{
				mass('密码修改失败');
				exit;
			}
		}else{
			mass('你输入的原密码不正确或为空,请重输！');			
			exit;
		}
		break;
	case 'confirm':
		$oid=$_GET['id'];
		$sql="UPDATE ".PRE."order SET status=4 WHERE id={$oid}";
		$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				header('location:user.php?u=myorder');
			}else{
				mass('订单确认失败');
				exit;
			}
		break;
	case 'reply':
		//var_dump($_POST);exit;
		foreach($_POST as $val){
			if($val==''){
				mass('请填写完整再行提交！');
				exit;
			}
		}
		$oid=$_POST['oid'];
		$gid=$_POST['gid'];
		$uid=$_SESSION['home']['id'];
		$status=!empty($_POST['status'])?$_POST['status']:0;
		$note=$_POST['note'];
		$addtime=time();
		$url=$_POST['url'];
		$table='user_id,goods_id,order_id,status,note,addtime';
		$value="'{$uid}','{$gid}','{$oid}','{$status}','{$note}','{$addtime}'";
		$sql="INSERT INTO ".PRE."reply({$table}) values({$value})";
		$result=mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			$sql="SELECT id FROM ".PRE."order WHERE id={$oid} AND status=5";
			//echo $sql;exit;
			$result=mysql_query($sql);
			if($result && mysql_num_rows($result)>0){
				
					$_SESSION['home']['credits'] +=30;
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']==1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']==2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']==3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']==4)//5
					){
						$_SESSION['home']['val']+=1;
					}
					$val=$_SESSION['home']['val'];
					$sql="UPDATE ".PRE."user SET credits=credits+30,val={$val} WHERE id={$uid}";
					$result=mysql_query($sql);
				
				header('location:'.$url);
			}else{			
				$sql="UPDATE ".PRE."order SET status=5 WHERE id={$oid}";
				$result=mysql_query($sql);
				if($result && mysql_affected_rows()>0){
					
					$_SESSION['home']['credits'] +=50;
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']=1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']=2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']=3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']=4)//5
					){
						$_SESSION['home']['val']+=1;
					}
					$val=$_SESSION['home']['val'];
					$sql="UPDATE ".PRE."user SET credits=credits+50,val={$val} WHERE id={$uid}";
					$result=mysql_query($sql);
					
					
					
					
					
					header('location:'.$url);
				}else{
					mass('订单状态更新失败！');
					exit;
				}
			}
		}else{
			mass('你的评价提交失败！');
			exit;
		}
		
		break;
	case 'readmin':
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
			
					$_SESSION['home']['credits'] +=30;
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']=1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']=2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']=3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']=4)//5
					){
						$_SESSION['home']['val']+=1;
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


}
