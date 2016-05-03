<?php
require '../init.php';

$a=$_GET['a'];

switch($a){
	case 'add':
		//判断表单内容是否为空
		//var_dump($_POST);
		foreach($_POST as $val){
			if($val == ''){
				mass('商品信息未填写完整！','#437ccf',0);
				exit;
			}
		}
		//处理文件上传
		$arr = upload('pic',UPLOAD_PATH);
		$path = $arr[0];
		if(!$path){
			mass('上传失败，请重新上传！','#437ccf',0);
			exit;
		}
		
		$small = thumb($path,$size[0],$size[1]);
		$middle = thumb($path,$size[2],$size[3]);
		$large = thumb($path,$size[4],$size[5]);
	
		if(	!$small || !$middle || !$large ){
			unlink($small);
			unlink($middle);
			unlink($large);
			unlink($path);
			mass('图片缩放失败，请重新上传！','#437ccf',0);
			exit;		
		}
		//接收数据
		$name = $_POST['name'];
		$cate_id=$_POST['cate_id'];
		$price=$_POST['price'];
		$stock=$_POST['stock'];
		$status = empty($_POST['status'])?0:1;
		$is_hot = empty($_POST['is_hot'])?0:1;
		$is_new = empty($_POST['is_new'])?0:1;
		$is_best = empty($_POST['is_best'])?0:1;
		$describe = $_POST['describe'];
		$addtime = time();
		$filename = basename($path);
		$sql="INSERT INTO ".PRE."goods(name,cate_id,price,stock,`status`,is_hot,is_new,is_best,addtime,`describe`) VALUES('{$name}',{$cate_id},{$price},{$stock},{$status},{$is_hot},{$is_new},{$is_best},{$addtime},'{$describe}')";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			$goods_id = mysql_insert_id();
			$sql = "INSERT INTO ".PRE."image(name,goods_id,is_face) VALUES('{$filename}',{$goods_id},1)";
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('添加成功!点击确定继续添加或<a href="index.php">返回</a>分类列表','#437ccf',1,'add.php?id='.$cate_id,0);
				exit;
			}else{
				$sql ="DELETE FROM ".PRE."goods WHERE id={$goods_id}";
				$result=mysql_query($sql);
				if($result){
				unlink($small);
				unlink($middle);
				unlink($large);
				unlink($path);
				mass('商品图片添加失败！','#437ccf',0);
				exit; 
				}				
			}
		}else{
			mass('商品添加失败！','#437ccf',0);
			exit;    
		}
		break;
	case 'display':
		$b = $_GET['b'];
		$id = $_GET['id'];
		$$b = $_GET[$b];
		$sql = "UPDATE ".PRE."goods SET {$b}={$$b} WHERE id = {$id}";
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			header('location:index.php?page='.$_GET['page']);
		}else{
			header('location:index.php');
		}
		break;
	case 'del':
		$id = $_GET['id'];
		$sql = "SELECT name FROM ".PRE."image WHERE goods_id={$id}";
		//echo $sql;
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$img_list = array();
			while($rows = mysql_fetch_assoc($result)){
				$img_list[] = $rows;
			}
		}
		//var_dump($img_list);
		foreach($img_list as $val){
			$path = UPLOAD_PATH;
			$path .= substr($val[name],0,4).'/';
			$path .= substr($val[name],4,2).'/';
			$path .= substr($val[name],6,2).'/';
			$src = $path.$val[name];
			$small = $path.$size[0].'x'.$size[1].'_'.$val[name];
			$middle = $path.$size[2].'x'.$size[3].'_'.$val[name];
			$large = $path.$size[4].'x'.$size[5].'_'.$val[name];
			@unlink($small);
			@unlink($middle);
			@unlink($large);
			@unlink($src);			
		}
		$sql = "DELETE FROM ".PRE."image WHERE goods_id={$id}";
		$result =mysql_query($sql);
        if($result){
            $sql="DELETE FROM ".PRE."goods WHERE id={$id}";
            $result =mysql_query($sql);
            if($result){
                header('location:index.php');exit;
            }else{               
                header('location:index.php');exit;
            }
        }else{            
                header('location:index.php');exit;
        }
		break;
	case 'delimg';
		$id = $_GET['id'];
        $is_face = $_GET['is_face'];
		$gid=$_GET['gid'];
		$gname=$_GET['name'];
        if($is_face == 1){
            mass('封面图片不能删除！','#437ccf',0);
			exit;
        }
		
		$sql = "SELECT name FROM ".PRE."image WHERE id={$id}";
		//echo $sql;
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$rows = mysql_fetch_assoc($result);		
			$path = UPLOAD_PATH;
			$path .= substr($rows[name],0,4).'/';
			$path .= substr($rows[name],4,2).'/';
			$path .= substr($rows[name],6,2).'/';
			$src = $path.$rows[name];
			$small = $path.$size[0].'x'.$size[1].'_'.$rows[name];
			$middle = $path.$size[2].'x'.$size[3].'_'.$rows[name];
			$large = $path.$size[4].'x'.$size[5].'_'.$rows[name];
			@unlink($small);
			@unlink($middle);
			@unlink($large);
			@unlink($src);			
			$sql="DELETE FROM ".PRE."image WHERE id={$id}";
			$result =mysql_query($sql);
			if($result){
				header('location:image.php?id='.$gid.'&name='.$gname);exit;
			}else{
				
				header('location:image.php?id='.$gid.'&name='.$gname);exit;
			}
		}else{
			header('location:image.php?id='.$gid.'&name='.$gname);exit;
		}
        break;
		
		
	 case 'addimg':
		//var_dump($_FILES);
		//exit;
		/*
		if(empty($_FILES['pic']['tmp_name'][0])){
			mass('请选择上传的文件！','#437ccf',0);	
			exit;
		}
		*/
        $gid = $_GET['id'];
		$gname = $_GET['name'];
        //上传并缩放
        $arr = upload('pic',UPLOAD_PATH);
		
		if(empty($arr)){
			mass('请选择上传的文件！','#437ccf',0);	
			exit;
		}
		//var_dump($arr);exit;
		for($i=0;$i<count($arr);$i++){		
			$path = $arr[$i];
			/*
			if(!$path){
				mass('上传失败，请重新上传！','#437ccf',0);
				exit;
			}
			*/
			$small = thumb($path,$size[0],$size[1]);
			$middle = thumb($path,$size[2],$size[3]);
			$large = thumb($path,$size[4],$size[5]);
		
			if(	!$small || !$middle || !$large ){
				unlink($small);
				unlink($middle);
				unlink($large);
				unlink($path);
				mass('图片缩放失败，请重新上传！','#437ccf',0);
				exit;		
			}
			$filename = basename($path);
			$sql = "INSERT INTO ".PRE."image(name,goods_id,is_face) VALUES('{$filename}',{$gid},0)";
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('添加成功!点击确定继续添加或<a href="index.php">返回</a>商品列表','#437ccf',1,'image.php?id='.$gid.'&name='.$gname,0);
				//exit;
			}else{
				unlink($small);
				unlink($middle);
				unlink($large);
				unlink($path);
				mass('商品图片添加失败！','#437ccf',0);
				exit; 				
			}		
		}
		
        break;
		
	case 'is_face';
		$iid = $_GET['iid'];
		$is_face = $_GET['is_face'];
		$gid = $_GET['id'];
		$gname =$_GET['name'];
		$sql = "UPDATE ".PRE."image SET is_face={$is_face} WHERE id = {$iid}";
		//echo $sql;exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			$sql= "UPDATE ".PRE."image SET is_face=0 WHERE goods_id={$gid} AND is_face=1 and id!={$iid}";
			$result = mysql_query($sql);
			if($result && mysql_affected_rows()>0){
			
				header('location:image.php?page='.$_GET['page'].'&id='.$gid.'&name='.$gname);
			}else{
				header('location:image.php?id='.$gid.'&name='.$gname);
			}
		}else{
			header('location:image.php?id='.$gid.'&name='.$gname);
		}
		break;

	case 'edit':
		foreach($_POST as $val){
			if($val == ''){
				mass('商品信息未填写完整！','#437ccf',0);
				exit;
			}
		}
		$id = $_POST['id'];
		$name = $_POST['name'];
		$cate_id=$_POST['cate_id'];
		$price=$_POST['price'];
		$stock=$_POST['stock'];
		$status = empty($_POST['status'])?0:1;
		$is_hot = empty($_POST['is_hot'])?0:1;
		$is_new = empty($_POST['is_new'])?0:1;
		$is_best = empty($_POST['is_best'])?0:1;
		$describe = $_POST['describe'];
		$sql = "UPDATE ".PRE."goods SET name='{$name}',cate_id={$cate_id},price={$price},stock={$stock},`status`={$status},is_hot={$is_hot},is_new={$is_new},is_best={$is_best},`describe`='{$describe}' WHERE id={$id}";
		//echo $sql;exit;
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
	
case 'addsend':
	if(empty($_POST['title'])){
		mass('标题必须填写！','#CB351A',1);
		exit;
	}
	$arr = upload('pic',UPLOAD_PATH);
	$path = $arr[0];
	if(!$path){
		mass('推送图片上传失败，请重新上传！','#CB351A',0);
		exit;
	}
	$small = thumb($path,120,32);
	$large = thumb($path,1200,320);
	
	if(	!$small || !$large ){
		unlink($small);
		unlink($large);
		unlink($path);
		mass('图片缩放失败，请重新上传！','#CB351A',0);
		exit;		
	}
	$filename = basename($path);	
	$goods_id=$_POST['id'];
	$cate_id=$_POST['cate_id'];
	$title=$_POST['title'];
	$describe=empty($_POST['describe'])?'':$_POST['describe'];
	$status=empty($_POST['status'])?0:1;
	$addtime=time();
	
	$table="goods_id,cate_id,title,`describe`,status,addtime,image";
	$value="{$goods_id},{$cate_id},'{$title}','{$describe}',{$status},{$addtime},'{$filename}'";
	$sql="INSERT INTO ".PRE."send({$table}) values({$value})";
	$result = mysql_query($sql);
	if($result && mysql_affected_rows()>0){
		mass('推送成功!','#437ccf',1,'index.php');
		exit;
	}else{
		unlink($small);
		unlink($large);
		unlink($path);
		mass('推送添加失败！','#CB351A',0);
		exit;		
	}
	break;
case 'sendsta':
		$id = $_GET['id'];
		$status = $_GET['status'];
		$sql = "UPDATE ".PRE."send SET status={$status} WHERE id = {$id}";
		//echo $sql;
		//exit;
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			header('location:send.php?page='.$_GET['page']);
		}else{
			header('location:send.php');
		}
	break;
case 'delsend':
		$id = $_GET['id'];
		$sql ="SELECT image FROM ".PRE."send WHERE id={$id}";
		$result = mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$rows=mysql_fetch_assoc($result);
			$image=$rows['image'];
			$path = UPLOAD_PATH;
			$path .= substr($image,0,4).'/';
			$path .= substr($image,4,2).'/';
			$path .= substr($image,6,2).'/';
			$src = $path.$image;
			$small = $path.'120x32_'.$image;
			$large = $path.'1200x320_'.$image;
			@unlink($small);
			@unlink($large);
			@unlink($src);		
		}
		$sql = "DELETE FROM ".PRE."send WHERE id={$id}";
		$result = mysql_query($sql);
		if($result){
			//echo '删除成功，请<a href="index.php">返回</a>！';
			header('location:send.php');
		}else{
			header('location:send.php');
		}
		break;
case 'editsend':
	if(empty($_POST['title'])){
		mass('标题必须填写！','#CB351A',1);
		exit;
	}
	
		if(!empty($_FILES['pic']['tmp_name'][0])){
			$arr = upload('pic',UPLOAD_PATH);
			
			$path = $arr[0];
			if(!$path){
				mass('推荐图片上传失败，请重新上传！','#437ccf',0);
				exit;
			}
			
			$small = thumb($path,120,32);
			$large = thumb($path,1200,320);
		
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
			$sql="UPDATE ".PRE."send SET title='{$title}',`describe`='{$describe}',status={$status} WHERE id={$id}";
		}else{
			$sql="SELECT image FROM ".PRE."send WHERE id={$id}";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)>0){
				$row=mysql_fetch_assoc($result);
				$path= UPLOAD_PATH;
				$path .=substr($row['image'],0,4).'/';
				$path .=substr($row['image'],4,2).'/';
				$path .=substr($row['image'],6,2).'/';
				$small =$path.'120x32_'.$row['image'];
				$large =$path.'1200x320_'.$row['image'];
				$src=$path.$row['image'];
				unlink($small);
				unlink($large);
				unlink($src);				
			}
			$sql="UPDATE ".PRE."send SET title='{$title}',`describe`='{$describe}',status={$status},image='{$filename}' WHERE id={$id}";
		}
		$result = mysql_query($sql);
		if($result && mysql_affected_rows()>0){
			mass('商品推送修改成功','#437ccf',1,'send.php');
			exit;
		}else{
			unlink($small);
			unlink($large);
			unlink($path);
			mass('商品推送没有被修改，请修改后再提交');
			exit;	
		}
		
break;
}