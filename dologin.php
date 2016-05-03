<?php
require './init.php';
$a = $_GET['a'];
switch($a){
	case "reg":
		$name = trim($_POST['name']);
		//用户名(字母开头，允许5-16字节，允许字母数字下划线)：
		$match ='/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/';
		if(!preg_match($match,$name)){
			//echo '你输入用户名格式不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('你输入用户名格式不正确!');
			exit;
		}
		
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		if($password != $repassword){
			//echo '两次输入密码不相同，请<a href = "javascript:history.back(-1)">返回</a>重新填写！';
			mass('两次输入密码不相同!');
			exit;
		}
		//密码6-15位不包含空白字符
		$match = '/\S{6,15}/';
		if(!preg_match($match,$password)){
			//echo '你输入密码格式不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('你输入密码格式不正确!');
			exit;
		}		
		$password = md5($password);
		$type = $_POST['type'];
		if($type>$_SESSION[admin]['type']){//不能创建比自己高级别的用户
			echo '你无权添加此级别的用户，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			exit;
		}
		$email = $_POST['email'];
		//email验证
		$match = '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/';
		if(!empty($email) && !preg_match($match,$email)){//此字段非必填项，为空则不进行验证
			//echo '你输入的邮箱格式不正确，请<a href="edit.php?id='.$id.'">返回</a>重新填写!';
			mass('你输入的邮箱格式不正确!');
			exit;
		}
				
		$regtime = $_POST['regtime'];
		$refererUrl = $_POST['refererUrl'];
		
		$sql="SELECT name FROM ".PRE."user WHERE name='{$name}'";  
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			echo "<script type='text/javascript'>alert('用户名已存在');location='javascript:history.back()';</script>";
			exit;
		}
		$sql = "INSERT INTO ".PRE."user(id,name,email,password,type,regtime) VALUES(NULL,'{$name}','{$email}','{$password}','{$type}','{$regtime}')";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			//echo '用户注册成功，请<a href="index.php">返回</a>！';
			$sql = "SELECT id,name,type,display,avatar,lognum,credits,val FROM ".PRE."user WHERE name='{$name}'";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)>0){
				$rows = mysql_fetch_assoc($result);
				$_SESSION['home'] = $rows;
				$lasttime = time();
				$sql = "UPDATE ".PRE."user SET lasttime={$lasttime},lognum=lognum+1,credits=credits+100 WHERE id={$rows['id']}";//首次登陆积分加100
				$result = mysql_query($sql);
				if($result && mysql_affected_rows()>0){
					//header('location:index.php');
					$_SESSION['home']['credits'] +=100;
					$_SESSION['home']['lognum'] +=1;
					header("Location: $refererUrl");
					exit;
				}else{
					mass('数据更新失败');
					exit;
				}

			}			
			exit;
		}else{
			//echo '用户注册失败，请<a href="javascript:history.back(-1)">返回</a>检查！';
			mass('用户注册失败!');
			exit;
		}
		break;
		
		
	case "login":
		$name=trim($_POST['name']);
		$password = $_POST['password'];
		$vcode = $_POST['vcode'];
		$lasttime = $_POST['lasttime'];
		$refererUrl = $_POST['refererUrl'];

		//用户名(字母开头，允许5-16字节，允许字母数字下划线)：
		$match ='/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/';

		if(!preg_match($match,$name)){
			//echo '你输入用户名格式不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('你输入用户名格式不正确!');
			exit;
		}

		//密码6-15位不包含空白字符
		$match = '/\S{5,15}/';
		if(!preg_match($match,$password)){
			//echo '你输入密码格式不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('你输入密码格式不正确!');
			exit;
		}
		if(strtolower($vcode) != strtolower($_SESSION['vcode'])){
			//echo '你输入的验证码不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('你输入的验证码不正确!');
			exit;
		}



		$sql = "SELECT id,name,password,type,display,avatar,lognum,credits,val FROM ".PRE."user WHERE name='{$name}'";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$rows = mysql_fetch_assoc($result);
			$password = md5($password);
			
			if($password != $rows['password']){
				//echo '用户名或密码不正确，请<a href="login.php">返回</a>重新填写!';
				mass('用户名或密码不正确!');
				exit;
			}
			if($rows['display']==1){
				//echo '您的账户已被锁定，请<a href="./index.php">返回首页</a>浏览!';
				mass('您的账户已被锁定!');
				exit;
			}
			unset($rows['password']);
			$_SESSION['home'] = $rows;
					$_SESSION['home']['credits'] +=10;
					$_SESSION['home']['lognum'] +=1;
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']==1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']==2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']==3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']==4)//5
					){
						$_SESSION['home']['val']+=1;
					}
					$val=$_SESSION['home']['val'];
			$sql = "UPDATE ".PRE."user SET lasttime={$lasttime},lognum=lognum+1,credits=credits+10,val={$val} WHERE id={$rows['id']}";//每次登录加10
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
					//header('location:index.php');					
					header("Location: $refererUrl");
					exit;
				}else{
					mass('数据更新失败');
					exit;
				}
		}else{
			//echo '用户名或密码不正确，请<a href="javascript:history.back(-1)">返回</a>重新填写!';
			mass('用户名或密码不正确!');
			exit;
		}
	
	
		break;
	case "logout":
		unset($_SESSION['home']);
		//session_destroy();
		header('location:index.php');
		break;
	
}