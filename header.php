<?php
require './init.php';
//导航激活
$cid= !empty($_GET['id'])?$_GET['id']:'';
$sql = "SELECT id,path FROM ".PRE."category WHERE id={$cid}";
$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		$row=mysql_fetch_assoc($result);
		$num = substr_count($row['path'],',');
		if($num==1){
			$aid=$row['id'];
		}else{
			$aid=explode(',',$row['path'])[1];
		}
	}
//购物车数量
$cart_total=0;
if(!empty($_SESSION['cart'])){	
	foreach($_SESSION['cart'] as  $key=>$val){
		$cart_total += $val['num'];
	}
}

$filename=basename($_SERVER['SCRIPT_NAME']);
?>

<!DOCTYPE html> 
	<head>
		<title><?php require('./inc/title.php')?> - 兄弟连新手练习</title>
		<meta charset="UTF-8">
		<link rel="shortcut icon" href="./images/favicon.png" type="image/vnd.microsoft.icon">
		<link rel="icon" href="./images/favicon.png" type="image/vnd.microsoft.icon">
		<meta name="description" content="兄弟连新手练习">
		<meta name="keywords" content="兄弟连 新手 练习">
		<link rel="stylesheet" href="./css/style.css" type="text/css">
	</head>

	<body class="bg">
		<!--头部开始-->
		<div id="header">
			<div class="top w mt15">
				<div class="logo fl">
					<a href="./"><img src="./images/logo.png"></a>
				</div>
				<div class="search fl">
				<?php if($filename=="user.php"):?>
					<form action="user.php" method="get">
						<input type="hidden" name="u" value="myorder">
						<input type="text" name="word" value="<?php echo $_GET['word']?>" placeholder="请输入您要查询的订单号">
						<input type="submit" value="搜索">
						<div class="clear"></div>
					</form>
				
				<?php else:?>
				
					<!--<form action="catelist.php?id=<?php //echo $_GET['id']?>" method="get">为何不能传值-->
					<form action="catelist.php" method="get">
						<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
						<input type="text" name="word" value="<?php echo $_GET['word']?>" placeholder="请输入您要查询的内容">
						<input type="submit" value="搜索">
						<div class="clear"></div>
					</form>
				<?php endif;?>
				</div>
				<div class="cart fr">
					<div class="icon">
						<i class="left"></i>
						<i class="count"><?php echo $cart_total;?></i>
						<a href="cart.php">我的购物车</a>
						<i class="right">&gt;</i>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<!--头部菜单开始-->
			<div class="nav w mt10">		
				<div class="left fl">
					<ul class="class1">
						<li><a href="./" <?php if(empty($aid)) echo 'class="active"'?>>首页</a>						
						<?php
						$i = 1;
						$prev = 1;
						$sql="SELECT id,name,pid,path,display FROM ".PRE."category WHERE display=1 order by concat(path,id)";
						//echo $sql;exit;
						$result = mysql_query($sql);
						if($result && mysql_num_rows($result)>0){
							while($rows = mysql_fetch_assoc($result)){
								$id = $rows['id'];
								$pid = $rows['pid'];
								$active = ($id==$aid)?'class="active"':'';//激活导航
								$num = substr_count($rows['path'],',');
								$li='cat'.$i;
								if($num==$prev){
									echo '</li>';	
								}
								if($num>$prev){
									$cnum = $num>3?'3':$num;
									$ul='cat'.($i-1);$class='class'.$cnum;
									echo '<ul id="'.$ul.'" style="display:none" class="'.$class.'">';
								}
								if($num<$prev){	
									//echo '</ul></li>';
									echo str_repeat('</ul></li>',($prev-$num));
								}
								echo '<li onMouseOut="display(\''.$li.'\');this.style.backgroundColor=\'\';this.firstChild.style.color=\'\'" onMouseOver="display(\''.$li.'\');this.style.backgroundColor=\'#CB351A\';this.firstChild.style.color=\'#fff\'"><a href="catelist.php?id='.$id.'" '.$active.'>'.$rows['name'].'</a>';
								$prev = $num;
								$i++;
							}
						}
						?>
						</li>
						<div class="clear"></div>
					</ul>
				</div>
				<div class="right fr">
					<?php if(!isset($_SESSION['home'])): ?>
					<div class="logout">
					<a href="./regist.php" class="reg">注册</a>
					<a href="./login.php" class="log">登录</a>
					</div>
					<?php else: ?>
					<a href="user.php?u=myorder"><img src="<?php echo URL.'avatar/32x32_'.$_SESSION['home']['avatar']?>"><span><?php echo $_SESSION['home']['name']?>（积分：<font color="#CB351A"><strong><?php echo $_SESSION['home']['credits']?></strong></font>&nbsp;|&nbsp;等级：<font color="#538ec6"><strong><?php echo 'LV'.$_SESSION['home']['val']?></strong></font>）<span></a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
						<?php if($_SESSION['home'][type]>0): ?>
							<a href="./admin">后台管理</a><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span>
						<?php endif; ?>	
					<a href="./dologin.php?a=logout">退出</a>
					<?php endif; ?>
				</div>
				<div class="clear"></div>
			</div>
			<!--头部菜单结束-->
		</div>
		<!--头部结束-->