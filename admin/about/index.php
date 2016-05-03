<?php
require '../init.php';

if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "WHERE title LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."about {$where}";
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


$sql = "SElECT id,title,image,status,`describe`,addtime FROM ".PRE."about {$where} LIMIT {$offset},{$num}";
//echo $sql;exit;

$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$aboutlist = array();
	while($rows=mysql_fetch_assoc($result)){
		$aboutlist[]=$rows;
	}
}
//var_dump($goodslist);
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
#main-tab td{ font-size:12px; line-height:40px;padding:0 5px;}
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
<script>
function display(targetid){  
	if (document.getElementById){  
		target=document.getElementById(targetid);  
		if (target.style.display==""){  
			target.style.display="none";  
		} else {  
		target.style.display="";  
		}  
	}  
}
</script> 
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="searchmain">
  <tr>
    <td width="99%" align="left" valign="top">您的位置：站点管理&nbsp;&nbsp;>&nbsp;&nbsp;文章列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="90%" align="left" valign="middle">
	         <form method="get" action="index.php">
	         <span>文章：</span>
	         <input type="text" name="word" value="<?php echo $_GET['word']?>" class="text-word">
	         <input name="" type="submit" value="查询" class="text-but">
	         </form>
         </td>
  		  <td width="10%" align="center" valign="middle" style="text-align:right; width:150px;"><a href="add.php" target="mainFrame" onFocus="this.blur()" class="add">添加文章</a></td>
  		</tr>
	</table>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr>
        <th width="40" align="center" valign="middle" class="borderright">编号</th>
		<th width="80" align="center" valign="middle" class="borderright">缩略图</th>
        <th align="center" valign="middle" class="borderright">标题</th>
        <th width="50" align="center" valign="middle" class="borderright">状态</th>
		<th width="160" align="center" valign="middle" class="borderright">创建时间</th>	
        <th width="100" align="center" valign="middle">操作</th>
      </tr>
	  <?php 
	  $i = 1;
	  foreach($aboutlist as $val){
	  ?>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $offset + $i++;?></td>
		<td align="center" valign="middle" class="borderright borderbottom">
			<?php 
			$img_url= UPLOAD_URL;
			$img_url .=substr($val['image'],0,4).'/';
			$img_url .=substr($val['image'],4,2).'/';
			$img_url .=substr($val['image'],6,2).'/';
			$small =$img_url.'90x30_'.$val['image'];
			$middle =$img_url.'900x300_'.$val['image'];
			//$img_url .=$size[0].'x'.$size[1].'_'.$val['iname'];
			?>
 			
			<div style="position:relative;" onMouseout="display('<?php echo 'img'.$i;?>')" onMouseover="display('<?php echo 'img'.$i;?>')"><a href="<?php echo URL?>about.php?id=<?php echo $val['id']?>" target="_top"><img src="<?php echo $small?>"/></a>
			<div id="<?php echo 'img'.$i;?>" style="position:absolute;bottom:0;left:80px;border:1px solid #548fc9;z-index:999;display:none"><img src="<?php echo $middle?>" style="width:400px"/></div></div>
		</td>
        <td align="left" valign="middle" class="borderright borderbottom"><a href="<?php echo URL?>about.php?id=<?php echo $val['id']?>" target="_top"><?php echo $val['title']?></a></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['status']==1?'<a href="action.php?a=status&id='.$val['id'].'&status=0&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=status&id='.$val['id'].'&status=1&page='.$page.'"><img src="../images/main/no.gif"></a>'?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo date('Y-m-d H:i:s',$val['addtime'])?></td>
		<td align="center" valign="middle" class="borderbottom">
		<a href="edit.php?&id=<?php echo $val['id']?>" target="mainFrame" onFocus="this.blur()" class="add">编辑</a><span class="gray">&nbsp;|&nbsp;</span>
		<a href="action.php?a=del&id=<?php echo $val['id']?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该文章？')">删除</a></td>
		</td>
      </tr>
	  <?php 
	  $des=strip_tags($val[describe]);
	  if(!empty($des)):?>
	  <tr><td colspan="6" align="left" valign="middle" class="borderright borderbottom"><?php echo utf8substr($val[describe],0,80)?></td></tr>
	  <?php endif;?>
      <?php }?>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="fenye">共 <?php echo $total?> 篇文章 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="index.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>