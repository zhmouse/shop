<?php
require 'header.php';
/*
if(empty($_SESSION['home'])){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('location:login.php?url='.$url);
}

$u=$_GET['u'];
*/
//var_dump($_SESSION);
$id=$_GET['id'];
$sql = "SElECT id,title,image,status,`describe`,addtime FROM ".PRE."about WHERE status=1";
//echo $sql;exit;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$aboutlist = array();
	while($rows=mysql_fetch_assoc($result)){
		$aboutlist[]=$rows;
	}
}

$sql = "SElECT id,title,image,status,`describe`,addtime FROM ".PRE."about WHERE status=1 AND id={$id}";
//echo $sql;exit;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$about=mysql_fetch_assoc($result);
}

$img=UPLOAD_URL;
$img.=substr($about['image'],0,4).'/';
$img.=substr($about['image'],4,2).'/';
$img.=substr($about['image'],6,2).'/';
$img.='900x300_'.$about['image'];
?>

<div id= main>
	
	<!--面包屑导航开始-->
	<div class="nav w" >
		<p>
			<a href="./">首页</a>					
			&nbsp;&nbsp;>&nbsp;&nbsp;<?php echo $about['title']?>
		</p>			
	</div>
	<!--面包屑导航结束-->
	
	<div class="user w">
		<div class="mainlist fl">
		<h3><?php echo $about['title']?></h3>
		<p><?php echo date('Y年m月d日 H点i分s秒',$about['addtime'])?><p>
		<p style="text-align:center;margin-top:15px"><img src="<?php echo $img?>" style="border-radius:5px;"></p>
		<div class="about">
			<?php echo $about['describe']?>
		</div>
		
		</div>
	
		<div class="sider fr">
			<ul>
				<li class="avatar">
		<?php if(!empty($_SESSION['home'])):?>
						<img src="<?php echo URL.'avatar/96x96_'.$_SESSION['home']['avatar']?>">
						<p><?php echo $_SESSION['home']['name']?> | 
					<?php
					switch($_SESSION['home']['type']){
						case 0:
							echo '普通用户';
							break;
						case 1:
							echo '普通管理员';
							break;
						case 2:
							echo '超级管理员';
							break;
					}
				?>	</p>
				
		<?php else:?>
			<img src="images/sider.gif"><p>兄弟连新手</p>
		<?php endif;?>
				</li>
				<?php foreach($aboutlist as $val):?>
				<li><a style="background:url(images/bg.png) no-repeat 6px -27px" href="about.php?id=<?php echo $val['id']?>" <?php if($id==$val['id']) echo 'class="n"';?> ><?php echo $val['title']?></a></li>
				<?php endforeach;?>
			</ul>
		</div>
		<div class="clear"></div>	
	</div>
</div>

<?php require 'footer.php';?>