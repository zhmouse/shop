<?php
require '../init.php';
if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "WHERE name LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."user {$where}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
$total = $rows['total'];
$num = 20;
$amount =ceil($total/$num);
$page = (int)$_GET['page'];
if($page<1){
	$page = 1;
}
if($page>$amount){
	$page = $amount;
}
$next = $page + 1;
$prev = $page - 1;
$offset = $prev*$num;

$sql = "SElECT id,name,email,type,display,regtime,lasttime,avatar,lognum,credits,val FROM ".PRE."user {$where} LIMIT {$offset},{$num}";

//}
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$userlist = array();
	while($rows = mysql_fetch_assoc($result)){
		$userlist[] = $rows;
	}
}

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="../css/css.css" type="text/css" rel="stylesheet" />
<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/main/favicon.ico" />
<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#searchmain{ font-size:12px;}
#search{ font-size:12px; background:#548fc9; margin:10px 10px 0 0; display:inline; width:100%; color:#FFF; float:left}
#search form span{height:40px; line-height:40px; padding:0 0px 0 10px; float:left;}
#search form input.text-word{height:24px; line-height:24px; width:180px; margin:8px 0 6px 0; padding:0 0px 0 10px; float:left; border:1px solid #FFF;}
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(../images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(../images/main/add.jpg) no-repeat -3px 7px #548fc9; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:right}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab{ border:1px solid #eaeaea; background:#FFF; font-size:12px;}
#main-tab th{ font-size:12px; background:url(../images/main/list_bg.jpg) repeat-x; height:32px; line-height:32px;}
#main-tab td{ font-size:12px; line-height:40px;}
#main-tab td a{ font-size:12px; color:#548fc9;}
#main-tab td a:hover{color:#565656; text-decoration:underline;}
.bordertop{ border-top:1px solid #ebebeb}
.borderright{ border-right:1px solid #ebebeb}
.borderbottom{ border-bottom:1px solid #ebebeb}
.borderleft{ border-left:1px solid #ebebeb}
.gray{ color:#dbdbdb;}
td.fenye{ padding:10px 0 0 0; text-align:right;}
.bggray{ background:#f9f9f9}
</style>
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="searchmain">
  <tr>
    <td width="99%" align="left" valign="top">您的位置：用户管理&nbsp;&nbsp;>&nbsp;&nbsp;用户列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="90%" align="left" valign="middle">
	         <form method="get" action="index.php">
	         <span>用户：</span>
	         <input type="text" name="word" value="<?php echo $_GET['word']?>" class="text-word">
	         <input name="" type="submit" value="查询" class="text-but">
	         </form>
         </td>
  		  <td width="10%" align="center" valign="middle" style="text-align:right; width:150px;"><a href="add.php" target="mainFrame" onFocus="this.blur()" class="add">添加用户</a></td>
  		</tr>
	</table>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
		<th align="center" valign="middle" class="borderright">头像</th>
        <th align="center" valign="middle" class="borderright">用户名</th>
		<th align="center" valign="middle" class="borderright">Email</th>
        <th align="center" valign="middle" class="borderright">用户权限</th>
        <th align="center" valign="middle" class="borderright">锁定</th>
		<th align="center" valign="middle" class="borderright">注册时间</th>
        <th align="center" valign="middle" class="borderright">最后登录</th>
		<th align="center" valign="middle" class="borderright">登录次数</th>
		<th align="center" valign="middle" class="borderright">积分</th>
		<th align="center" valign="middle" class="borderright">等级</th>
        <th align="center" valign="middle">操作</th>
      </tr>
	  <?php
		$i = 1;
		foreach($userlist as $val){
	  ?>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $offset + $i++ ?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><a href="<?php echo URL.'user.php?&u=user&id='.$val['id']?>" target="_top"><img src="<?php echo URL.'avatar/32x32_'.$val['avatar']?>"></a></td>
		<td align="center" valign="middle" class="borderright borderbottom"><a href="<?php echo URL.'user.php?&u=user&id='.$val['id']?>" target="_top"><?php echo $val['name']?></a></td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['email']?></td>
        <td align="center" valign="middle" class="borderright borderbottom">
		<?php
			switch($val['type']){
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
		?>		
		</td>
        <td align="center" valign="middle" class="borderright borderbottom">
		<?php if( ( $val['type'] <= $_SESSION['admin']['type'] ) && ( $val['id'] != $_SESSION['admin']['id'] ) ){ //管理员不要禁用自己?>
		<?php echo $val['display']==0?'<a href="action.php?a=display&id='.$val['id'].'&display=1&page='.$page.'">正常</a>':'<a href="action.php?a=display&id='.$val['id'].'&display=0&page='.$page.'">已锁定</a>'?>
		<?php }else{?>
		<?php echo $val['display']==0?'正常':'已锁定'?>
		<?php } ?>
		
		</td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo date('Y-m-d H:i:s',$val['regtime'])?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo date('Y-m-d H:i:s',$val['lasttime'])?></td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['lognum']?></td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['credits']?></td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo 'LV'.$val['val']?></td>
        <td align="center" valign="middle" class="borderbottom">
		<?php if($val['type'] <= $_SESSION['admin']['type']){?>
			<a href="edit.php?id=<?php echo $val['id']?>" target="mainFrame" onFocus="this.blur()" class="add">编辑</a>
		<?php }else{?>
			编辑
		<?php } ?>
			<span class="gray">&nbsp;|&nbsp;</span>
		<?php if( ( $val['type'] <= $_SESSION['admin']['type'] ) && ( $val['id'] != $_SESSION['admin']['id'] ) ){ //管理员不要删除自己?>
		<a href="action.php?a=del&id=<?php echo $val['id']?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该用户？')">删除</a>
		<?php }else{?>
			删除
		<?php } ?>
		</td>
      </tr>
		<?php }?>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="fenye">共 <?php echo $total?> 位用户 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="index.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>