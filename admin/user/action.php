<?php
require '../init.php';

$a = $_GET['a'];
switch($a){
	case 'add':
		$name = trim($_POST['name']);
		//用户名(字母开头，允许5-16字节，允许字母数字下划线)：
		$match ='/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/';
		if(!preg_match($match,$name)){
			echo '你输入用户名格式不正确，请<a href="add.php">返回</a>重新填写!';
			exit;
		}
		
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];
		if($password != $repassword){
			echo '两次输入密码不相同，请<a href = "add.php">返回</a>重新填写！';
			exit;
		}
		//密码6-15位不包含空白字符
		$match = '/\S{6,15}/';
		if(!preg_match($match,$password)){
			echo '你输入密码格式不正确，请<a href="add.php">返回</a>重新填写!';
			exit;
		}		
		$password = md5($password);
		$type = $_POST['type'];
		if($type>$_SESSION[admin]['type']){//不能创建比自己高级别的用户
			echo '你无权添加此级别的用户，请<a href="add.php">返回</a>重新填写!';
			exit;
		}
		$regtime = $_POST['regtime'];
		$sql="SELECT name FROM ".PRE."user WHERE name='{$name}'";  
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			echo "<script type='text/javascript'>alert('用户名已存在');location='javascript:history.back()';</script>";
			exit;
		}
		
		$sql = "INSERT INTO ".PRE."user(id,name,password,type,regtime,credits) VALUES(NULL,'{$name}','{$password}','{$type}','{$regtime}',100)";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			echo '用户添加成功，请<a href="index.php">返回</a>！';
			exit;
		}else{
			echo '用户添加失败，请<a href="add.php">返回</a>检查！';
		}
		
		break;
	case 'display':
		$id = $_GET['id'];
		$display = $_GET['display'];
		$sql = "UPDATE ".PRE."user SET display={$display} WHERE id = {$id} AND type != 2 AND type <= {$_SESSION['admin']['type']}";
		//echo $sql;
		//exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			header('location:index.php?page='.$_GET['page']);
		}else{
			header('location:index.php');
		}
		break;
	case 'del':
		$id = $_GET['id'];
		$sql = "DELETE FROM ".PRE."user WHERE id={$id} AND type <= {$_SESSION['admin']['type']}";
		$result = mysql_query($sql);
		if($result){
			//echo '删除成功，请<a href="index.php">返回</a>！';
			header('location:index.php');
		}else{
			header('location:index.php');
		}
		break;
	case 'edit';
		$id = $_POST['id'];
		$sex = $_POST['sex'];
		$email = $_POST['email'];
		$phone = $_POST['phone'];
		//email验证
		$match = '/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)+$/';
		if(!empty($email) && !preg_match($match,$email)){//此字段非必填项，为空则不进行验证
			echo '你输入的邮箱格式不正确，请<a href="edit.php?id='.$id.'">返回</a>重新填写!';
			exit;
		}
		//手机或电话号码
		$match_p = '/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/';//电话
		$match_m = '/^((\(\d{2,3}\))|(\d{3}\-))?13\d{9}$/';//手机
		if(!empty($phone) && !preg_match($match_p,$phone) && !preg_match($match_m,$phone)){//此字段非必填项，为空则不进行验证
			//echo '你输入的号码格式不正确，请<a href="edit.php?id='.$id.'">返回</a>重新填写!';
			echo '你输入的号码格式不正确，请<a href="javascript:history.back(-1);">返回</a>重新填写!';
			exit;
		}
		$type = $_POST['type'];
		if($type>$_SESSION[admin]['type']){//不能创建比自己高级别的用户
			echo '你无权添加此级别的用户，请<a href="edit.php?id='.$id.'">返回</a>重新填写!';
			exit;
		}
		if(empty($_POST['password']) && empty($_POST['repassword'])){
			$sql = "UPDATE ".PRE."user SET sex='{$sex}',email='{$email}',phone='{$phone}',type='{$type}' WHERE id = {$id}";
		}else{
			$password = $_POST['password'];
			$repassword = $_POST['repassword'];
			if($password != $repassword){
				echo '两次输入密码不相同，请<a href = "edit.php?id='.$id.'">返回</a>重新填写！';
				exit;
			}
			$password = md5($password);
			$sql = "UPDATE ".PRE."user SET sex='{$sex}',email='{$email}',phone='{$phone}',type='{$type}',password='{$password}' WHERE id = {$id}";
		}
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			echo '修改成功，请<a href="index.php">返回</a>！';
		}else{
			echo '修改失败,请<a href = "edit.php?id='.$id.'">返回</a>!';
			exit;
		}
		break;	
}