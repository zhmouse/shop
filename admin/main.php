<?php

require './init.php';
//var_dump($_SESSION['admin']);
//用户
$sql="SELECT COUNT(id) num FROM ".PRE."user GROUP BY type";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$user=array();
	while($rows=mysql_fetch_assoc($result)){
		$user[]=$rows;
	}
	//var_dump($user);exit;
	$user_total=$user[0]['num']+$user[1]['num']+$user[2]['num'];//总数
}
//图片
$sql="SELECT COUNT(id) num FROM ".PRE."image GROUP BY is_face";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$image=array();
	while($rows=mysql_fetch_assoc($result)){
		$image[]=$rows;
	}
	$image_total=$image[0]['num']+$image[1]['num'];//总数
}
//订单
$sql="SELECT COUNT(id) num,SUM(total) total FROM ".PRE."order";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$order=mysql_fetch_assoc($result);
}

//订单商品
$sql="SELECT SUM(num) num FROM ".PRE."order_goods";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$order_goods=mysql_fetch_assoc($result);
}
//评价
$sql="SELECT COUNT(id) num FROM ".PRE."reply WHERE pid=0";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$rows=mysql_fetch_assoc($result);
	$rep=$rows[num];//
}
//回复
$sql="SELECT COUNT(id) num FROM ".PRE."reply WHERE pid>0";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$rows=mysql_fetch_assoc($result);
	$reply=$rows[num];//
}
//商品
$sql="SELECT COUNT(id) num,SUM(stock) stock,SUM(sell) sell,SUM(sell*price) total FROM ".PRE."goods";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$goods=mysql_fetch_assoc($result);
}
//分类数
$sql="SELECT COUNT(id) num FROM ".PRE."category";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$category=mysql_fetch_assoc($result);
}

//订单状态
$sql="SELECT status,COUNT(id) num FROM ".PRE."order WHERE status>0 GROUP BY status";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$status=array();
	while($rows=mysql_fetch_assoc($result)){
		$status[]=$rows;
	}
}


//var_dump($status);exit;
//有效订单
$sql="SELECT COUNT(id) num FROM ".PRE."order WHERE status>0 AND status<6";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$ord=mysql_fetch_assoc($result);
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
<link href="css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="images/main/favicon.ico" />
<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#main{ font-size:12px;}
#main span.time{ font-size:14px; color:#528dc5; width:100%; padding-bottom:10px; float:left}
#main div.top{ width:100%; background:url(images/main/main_r2_c2.jpg) no-repeat 0 10px; padding:0 0 0 15px; line-height:35px; float:left}
#main div.sec{ width:100%; background:url(images/main/main_r2_c2.jpg) no-repeat 0 15px; padding:0 0 0 15px; line-height:35px; float:left}
#main div.third{ width:15%; background:url(images/main/main_r2_c2.jpg) no-repeat 0 10px; padding:0 0 0 15px; line-height:35px; float:left}
.left{ float:left}
#main div a{ float:left}
#main span.num{  font-size:30px; color:#538ec6; font-family:"Georgia","Tahoma","Arial";}
.left{ float:left}
div.main-tit{ font-size:14px; font-weight:bold; color:#4e4e4e; background:url(images/main/main_r4_c2.jpg) no-repeat 0 33px; width:100%; padding:30px 0 0 20px; float:left}
div.main-con{ width:100%; float:left; padding:10px 0 0 20px; line-height:36px;}
div.main-corpy{ font-size:14px; font-weight:bold; color:#4e4e4e; background:url(images/main/main_r6_c2.jpg) no-repeat 0 33px; width:100%; padding:30px 0 0 20px; float:left}
div.main-order{ line-height:30px; padding:10px 0 0 0;}
</style>
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="main">
  <tr>
    <td colspan="4" align="left" valign="top">
    <span class="time"><strong>上午好！<?php echo $_SESSION['admin']['name']?></strong><u>[<?php echo $_SESSION['admin']['type']==1?'普通管理员':'超级管理员'?>]</u></span>
    <div class="top"><span class="left">您上次的登灵时间：<?php echo date('Y-m-d H:i:s',$_SESSION['admin']['lasttime'])?>   登录IP：<?php echo $_SERVER['SERVER_ADDR']?> &nbsp;&nbsp;&nbsp;&nbsp;如非您本人操作，请及时</span><a href="index.php" target="mainFrame" onFocus="this.blur()">更改密码</a></div>
    <div class="sec">这是您第<span class="num"><?php echo $_SESSION['admin']['lognum']?></span>次,登录！&nbsp;&nbsp;&nbsp;&nbsp;您目前的积分：<font color="#538ec6"><strong><?php echo $_SESSION['admin']['credits']?></strong></font>&nbsp;&nbsp;&nbsp;&nbsp;等级：<font color="#538ec6"><strong><?php echo 'LV'.$_SESSION['admin']['val']?></strong></font></div>

	<?php foreach($status as $val):
	echo '	<div class="third"><a href="./order/index.php?s='.$val['status'].'">';
	switch($val['status']){
		case '6':
			echo '订单已取消';
			break;						
		case '1':
			echo '等待买家付款';
			break;
		case '2':
			echo '<font color="red"><strong>已付款未发货</strong></font>';
			break;
		case '3':
			echo '等待买家收货';
			break;
		case '4':
			echo '买家已收货';
			break;
		case '5':
			echo '买家已评价';
			break;
	}
		echo '（<font color="#538ec6"><strong> '.$val['num'].' </strong></font>条）</a></div>';
	?>
	
	<?php endforeach;?>
	
    </td>
  </tr>
  <tr>
	<td align="left" valign="top" width="25%">
    <div class="main-tit">订单信息</div>
    <div class="main-con">
    订单数：<font color="#538ec6"><strong><?php echo $order['num']?> </strong></font>条（订单商品数<font color="#538ec6"><strong> <?php echo $order_goods['num']?> </strong></font>个）<br/>
订单总额：<font color="red" size="3"><strong>&yen;<?php echo $order['total']?> </strong></font><br/>
评价数：<font color="#538ec6"><strong><?php echo $rep?> </strong></font>条<br/>
回复数：<font color="#538ec6"><strong><?php echo $reply?> </strong></font>条<br/>
图片附件：<font color="#538ec6"><strong><?php echo $image_total?> </strong></font>张（封面<font color="#538ec6"><strong> <?php echo $image[1]['num']?> </strong></font>张）<br/>
    </div>
    </td>
	<td align="left" valign="top" width="25%">
    <div class="main-tit">商品信息</div>
    <div class="main-con">
    商品分类数：<font color="#538ec6"><strong><?php echo $category['num']?> </strong></font>个<br/>
商品数：<font color="#538ec6"><strong><?php echo $goods['num']?> </strong></font>个<br/>
商品总库存：<font color="#538ec6"><strong><?php echo $goods['stock']?> </strong></font>个<br/>
商品总销量：<font color="#538ec6"><strong><?php echo $goods['sell']?> </strong></font>个<br/>
商品销售总额：<font color="red" size="3"><strong>&yen;<?php echo $goods['total']?> </strong></font><br/>
    </div>
    </td>
    <td align="left" valign="top" width="25%">
    <div class="main-tit">网站信息</div>
    <div class="main-con">
    用户总数：<font color="#538ec6"><strong><?php echo $user_total?> </strong></font>人<br/>
普通用户：<font color="#538ec6"><strong><?php echo $user[0]['num']?> </strong></font>人<br/>
普通管理员：<font color="#538ec6"><strong><?php echo $user[1]['num']?></strong></font> 人&nbsp;&nbsp;超级管理员：<font color="#538ec6"><strong><?php echo $user[2]['num']?> </strong></font> 人<br/>
登陆者IP：<?php echo $_SERVER['REMOTE_ADDR']?><br/>
程序编码：UTF-8<br/>
    </div>
    </td>
    <td align="left" valign="top" width="24%">
    <div class="main-tit">服务器信息</div>
    <div class="main-con">
服务器软件：<?php echo $_SERVER['SERVER_SOFTWARE']?><br/>
PHP版本：<?php echo PHP_VERSION ?><br/>
MYSQL版本：<?php echo mysql_get_server_info() ?><br/>
魔术引用：开启 (建议开启)<br/>
使用域名：<?php echo $_SERVER['SERVER_NAME']?> <br/>
    </div>
    </td>
  </tr>
  <tr>
    <td colspan="4" align="left" valign="top">
    <div class="main-corpy">系统提示</div>
    <div class="main-order">1=>如您在使用过程有发现出错请及时与我们取得联系；为保证您得到我们的后续服务，强烈建议您购买我们的正版系统或向我们定制系统！<br/>
2=>强烈建议您将IE7以上版本或其他的浏览器</div>
    </td>
  </tr>
</table>
<div style="position:absolute;top:-5px;right:20px;opacity: .5;">
<img src="images/user.php" height="180">
<img src="images/order.php" height="180" >
</div>
</body>
</html>