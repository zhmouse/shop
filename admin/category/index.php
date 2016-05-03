<?php
require '../init.php';
if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "WHERE name LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."category {$where}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
$total = $rows['total'];
$num = 50;
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
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(../images//main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(../images//main/add.jpg) no-repeat -3px 7px #548fc9; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:right}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab{ border:1px solid #eaeaea; background:#FFF; font-size:12px;}
#main-tab th{ font-size:12px; background:url(../images//main/list_bg.jpg) repeat-x; height:32px; line-height:32px;}
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
    <td width="99%" align="left" valign="top">您的位置：分类管理&nbsp;&nbsp;>&nbsp;&nbsp;分类列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="90%" align="left" valign="middle">
	         <form method="get" action="index.php">
	         <span>分类：</span>
	         <input type="text" name="word" value="<?php echo $_GET['word']?>" class="text-word">
	         <input name="" type="submit" value="查询" class="text-but">
	         </form>
         </td>
  		  <td width="10%" align="center" valign="middle" style="text-align:right; width:150px;"><a href="add.php" target="mainFrame" onFocus="this.blur()" class="add">添加分类</a></td>
  		</tr>
	</table>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
        <th align="center" valign="middle" class="borderright">分类名</th>
        <th align="center" valign="middle" class="borderright">是否显示</th>
        <th align="center" valign="middle">操作</th>
      </tr>
<?php
$i = 1;
$sql="SELECT id,name,pid,path,display FROM ".PRE."category {$where} order by concat(path,id) LIMIT {$offset},{$num}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    while($rows = mysql_fetch_assoc($result)){
		$id = $rows['id'];
		$pid = $rows['pid'];
		$space = str_repeat('&nbsp;&nbsp;',substr_count($rows['path'],',')*5 );
		$space = $space.'├─ '.$rows['name'];
		$display = $rows['display']==1?'<a href="action.php?a=display&id='.$id.'&display=2&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=display&id='.$id.'&display=1&page='.$page.'"><img src="../images/main/no.gif"></a>';
		$arr = array('#fff','#eee','#DDD','#ccc','#bbb','#aaa','#999');
		$num = substr_count($rows['path'],',')<7?substr_count($rows['path'],','):7;
		$bgcolor = $arr[$num-1];			
?>				
		<tr style="background:<?php echo $bgcolor?>" onMouseOut="this.style.backgroundColor='<?php echo $bgcolor?>'" onMouseOver="this.style.backgroundColor='#edf5ff'">
			<td align="center" valign="middle" class="borderright borderbottom"><?php echo $offset + $i++ ?></td>
			<td align="left" valign="middle" class="borderright borderbottom"><?php echo $space?></td>
			<td align="center" valign="middle" class="borderright borderbottom"><?php echo $display?></td>
			<td align="center" valign="middle" class="borderbottom">
				<a href="add.php?id=<?php echo $id?>" target="mainFrame" onFocus="this.blur()" class="add">添加分类</a><span class="gray">&nbsp;|&nbsp;</span>
				<a href="../goods/add.php?id=<?php echo $id?>" target="mainFrame" onFocus="this.blur()" class="add">添加商品</a><span class="gray">&nbsp;|&nbsp;</span>
				<a href="edit.php?id=<?php echo $id?>&pid=<?php echo $pid?>" target="mainFrame" onFocus="this.blur()" class="add">编辑</a><span class="gray">&nbsp;|&nbsp;</span>
				<a href="action.php?a=del&id=<?php echo $id?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该分类？')">删除</a>
			</td>
		</tr>	
<?php			
    }
}
?>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="fenye">共 <?php echo $total?> 个分类 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="index.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>