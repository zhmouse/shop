<?php
require '../init.php';

$a = $_GET['a'];

switch($a){
	case 'add':		
		$name = $_POST['name'];
		$pid = $_POST['pid'];
		if($pid == 0){
			$path = '0,';
		}else{
			$sql = "SELECT concat(path,id,',') path FROM ".PRE."category WHERE id={$pid}";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)){
				$rows = mysql_fetch_assoc($result);	
			}
			$path = $rows['path'];
		}		
		//$path = $_POST['path']
		$display = $_POST['display'];
		$sql = "INSERT INTO ".PRE."category(id,name,pid,path,display) VALUES(NULL,'{$name}',{$pid},'{$path}',{$display})";
		//echo $sql;
		//exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			//echo '添加成功,<a href="index.php">返回</a>或<a href="add.php?id='.$pid.'">继续添加</a>';
			mass('添加成功!点击确定继续添加或<a href="index.php">返回</a>分类列表','#437ccf',1,'add.php?id='.$pid,0);
			exit;
		}else{
			//echo '添加失败<a href="javascript:history.back()">返回</a>';
			mass('添加失败!','#437ccf');
			exit;
		}
	
		break;
	case 'display':
		$id = $_GET['id'];
		$display = $_GET['display'];
		$sql = "UPDATE ".PRE."category SET display={$display} WHERE id = {$id}";
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
		$sql = "SELECT id FROM ".PRE."category WHERE pid={$id}";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			//echo '该分类存在下级分类不能删除，请<a href="javascript:history.back()">返回</a>！';
			mass('该分类存在下级分类不能删除!','#437ccf');
			exit;
		}
		$sql = "SELECT id FROM ".PRE."goods WHERE cate_id={$id}";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			//echo '该分类存在下级分类不能删除，请<a href="javascript:history.back()">返回</a>！';
			mass('该分类下存在商品不能删除!','#437ccf');
			exit;
		}
		
		$sql ="DELETE FROM ".PRE."category WHERE id={$id}";
		$result = mysql_query($sql);
		if($result){
			//echo '删除成功，请<a href="index.php">返回</a>！';
			header('location:index.php');
		}else{
			//header('location:index.php');
			mass('删除失败!','#437ccf');
		}
		
		break;
	case 'edit':
		$id = $_POST['id'];
		$name = $_POST['name'];
		$pid = $_POST['pid'];
		if($pid == 0){
			$path = '0,';
		}else{
			$sql = "SELECT concat(path,id,',') path FROM ".PRE."category WHERE id={$pid}";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)){
				$rows = mysql_fetch_assoc($result);	
			}
			$path = $rows['path'];
		}		
		//$path = $_POST['path']
		$display = $_POST['display'];
		$sql = "UPDATE ".PRE."category SET name='{$name}',pid={$pid},path='{$path}',display={$display} WHERE id = {$id}";
		//echo $sql;
		//exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			//echo '修改成功<a href="index.php">返回</a>';
			mass('修改成功!','#437ccf',1,'index.php');
			exit;
		}else{
			//echo '修改失败<a href="javascript:history.back()">返回</a>';
			mass('修改失败!','#437ccf');
			exit;
		}
		break;
}