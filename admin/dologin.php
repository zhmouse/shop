<?php

require '../init.php';//前台文件
$name=$_POST['name'];
$password = $_POST['password'];
$vcode = $_POST['vcode'];
$lasttime = $_POST['lasttime'];

//用户名(字母开头，允许5-16字节，允许字母数字下划线)：
$match ='/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/';

if(!preg_match($match,$name)){
	//echo '你输入用户名格式不正确，请<a href="javascript:history.back()">返回</a>重新填写!';
	mass('你输入用户名格式不正确!','#437ccf');
	exit;
}

//密码6-15位不包含空白字符
$match = '/\S{5,15}/';
if(!preg_match($match,$password)){
	//echo '你输入密码格式不正确，请<a href="javascript:history.back()">返回</a>重新填写!';
	mass('你输入密码格式不正确!','#437ccf');
	exit;
}
if(strtolower($vcode) != strtolower($_SESSION['vcode'])){
	//echo '你输入的验证码不正确，请<a href="javascript:history.back()">返回</a>重新填写!';
	mass('你输入的验证码不正确!','#437ccf');
	exit;
}



$sql = "SELECT id,name,password,type,display,lasttime,avatar,lognum,credits,val FROM ".PRE."user WHERE type>0 AND name='{$name}'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
	$password = md5($password);
	
	if($password != $rows['password']){
		//echo '用户名或密码不正确，请<a href="javascript:history.back()">返回</a>重新填写!';
		mass('用户名或密码不正确!','#437ccf');
		exit;
	}
	if($rows['display']==1){
		//echo '您的账户已被锁定，请<a href="../index.php">返回首页</a>浏览!';
		mass('您的账户已被锁定!','#437ccf',0,'../index.php');
		exit;
	}
	unset($rows['password']);
	$_SESSION['admin'] = $rows;
	$_SESSION['home'] = $rows;//后台登录后前台自动登录
					$_SESSION['home']['credits'] +=10;
					$_SESSION['home']['lognum'] +=1;
					$_SESSION['admin']['credits'] +=10;
					$_SESSION['admin']['lognum'] +=1;
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
	
	
	$sql = "UPDATE ".PRE."user SET lasttime={$lasttime},lognum=lognum+1,credits=credits+10,val={$val} WHERE id={$rows['id']}";
	$result = mysql_query($sql);
	if($result && mysql_affected_rows()>0){
					//header('location:index.php');
					//$_SESSION['admin']['credits'] +=10;
					//$_SESSION['admin']['lognum'] +=1;
					
					//$_SESSION['home']['credits'] +=10;
					//$_SESSION['home']['lognum'] +=1;
					header('location:index.php');
					exit;
				}else{
					mass('数据更新失败');
					exit;
				}
	
	
	
	
}else{
	//echo '用户名或密码不正确，请<a href="javascript:history.back()">返回</a>重新填写!';
	mass('用户名或密码不正确!','#437ccf');
	exit;
}
?>