<?php
require '../init.php';

$a=$_GET['a'];

switch($a){	
case 'add':
	if(empty($_POST['title'])){
		mass('标题必须填写！','#CB351A',1);
		exit;
	}
	$arr = upload('pic',UPLOAD_PATH);
	$path = $arr[0];
	if(!$path){
		mass('图片上传失败，请重新上传！','#CB351A',0);
		exit;
	}
	$small = thumb($path,90,30);
	$large = thumb($path,900,300);
	
	if(	!$small || !$large ){
		unlink($small);
		unlink($large);
		unlink($path);
		mass('图片缩放失败，请重新上传！','#CB351A',0);
		exit;		
	}
	$filename = basename($path);	
	$title=$_POST['title'];
	$describe=empty($_POST['describe'])?'':$_POST['describe'];
	//$describe=strip_tags($describe);
	$status=empty($_POST['status'])?0:1;
	$addtime=time();
	
	$table="title,status,addtime,image,`describe`";
	$value="'{$title}',{$status},{$addtime},'{$filename}','{$describe}'";
	$sql="INSERT INTO ".PRE."about({$table}) values({$value})";
	//echo $sql;exit;
	$result = mysql_query($sql);
	if($result && mysql_affected_rows()>0){
		mass('发布成功!','#437ccf',1,'index.php');
		exit;
	}else{
		unlink($small);
		unlink($large);
		unlink($path);
		mass('文章添加失败！','#437ccf',0);
		exit;		
	}
	break;
case 'status':
		$id = $_GET['id'];
		$status = $_GET['status'];
		$sql = "UPDATE ".PRE."about SET status={$status} WHERE id = {$id}";
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
		$sql ="SELECT image,status FROM ".PRE."about WHERE id={$id}";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$rows=mysql_fetch_assoc($result);
			if($rows['status']==1){
				mass('前台显示文章不允许删除！','#437ccf',0);
				exit;
			}
			$image=$rows['image'];
			$path = UPLOAD_PATH;
			$path .= substr($image,0,4).'/';
			$path .= substr($image,4,2).'/';
			$path .= substr($image,6,2).'/';
			$src = $path.$image;
			$small = $path.'90x30_'.$image;
			$large = $path.'900x300_'.$image;
			@unlink($small);
			@unlink($large);
			@unlink($src);		
		}
		$sql = "DELETE FROM ".PRE."about WHERE id={$id}";
		$result = mysql_query($sql);
		if($result){
			//echo '删除成功，请<a href="index.php">返回</a>！';
			header('location:index.php');
		}else{
			header('location:index.php');
		}
		break;
case 'edit':
	if(empty($_POST['title'])){
		mass('标题必须填写！','#CB351A',1);
		exit;
	}
	
		if(!empty($_FILES['pic']['tmp_name'][0])){
			$arr = upload('pic',UPLOAD_PATH);
			
			$path = $arr[0];
			if(!$path){
				mass('图片上传失败，请重新上传！','#437ccf',0);
				exit;
			}
			
			$small = thumb($path,90,30);
			$large = thumb($path,900,300);
		
			if(	!$small || !$large ){
				unlink($small);
				unlink($large);
				unlink($path);
				mass('图片缩放失败，请重新上传！','#437ccf',0);
				exit;		
			}
			$filename = basename($path);
		}
		
		$id = $_POST['id'];
		$title=$_POST['title'];
		$describe=empty($_POST['describe'])?'':$_POST['describe'];
		$status=empty($_POST['status'])?0:1;
		if(empty($filename)){
			$sql="UPDATE ".PRE."about SET title='{$title}',`describe`='{$describe}',status={$status} WHERE id={$id}";
		}else{
			$sql="SELECT image FROM ".PRE."about WHERE id={$id}";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)>0){
				$row=mysql_fetch_assoc($result);
				$path= UPLOAD_PATH;
				$path .=substr($row['image'],0,4).'/';
				$path .=substr($row['image'],4,2).'/';
				$path .=substr($row['image'],6,2).'/';
				$small =$path.'90x30_'.$row['image'];
				$large =$path.'900x300_'.$row['image'];
				$src=$path.$row['image'];
				unlink($small);
				unlink($large);
				unlink($src);				
			}
			$sql="UPDATE ".PRE."about SET title='{$title}',`describe`='{$describe}',status={$status},image='{$filename}' WHERE id={$id}";
		}
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			mass('修改成功','#437ccf',1,'index.php');
			exit;
		}else{
			unlink($small);
			unlink($large);
			unlink($path);
			mass('文章没有被修改，请修改后再提交');
			exit;	
		}
		
break;
}