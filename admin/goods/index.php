<?php
require '../init.php';

if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "g.name LIKE '%{$word}%' AND";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."goods WHERE name LIKE '%{$word}%'";
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


//$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,g.status,g.stock,g.is_hot,g.is_new,g.is_best,i.id iid,i.name iname,i.is_face FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1";
//$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,g.status,g.stock,g.is_hot,g.is_new,g.is_best,i.id iid,i.name iname,i.is_face,c.name cname FROM ".PRE."goods g,".PRE."image i,".PRE."category c WHERE g.id=i.goods_id AND i.is_face=1 AND g.cate_id=c.id";
$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,g.status,g.stock,g.sell,g.is_hot,g.is_new,g.is_best,i.id iid,i.name iname,i.is_face,c.name cname FROM ".PRE."goods g,".PRE."image i,".PRE."category c WHERE {$where} g.id=i.goods_id AND i.is_face=1 AND g.cate_id=c.id ORDER BY addtime DESC LIMIT {$offset},{$num}";
//echo $sql;exit;

$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$googslist = array();
	while($rows=mysql_fetch_assoc($result)){
		$goodslist[]=$rows;
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
.box{*width:500px; height:400px; border:1px solid #000; position:fixed; left:120px; top:50%;*margin-left:-100px;margin-top:-200px;_position:absolute;_top:expression(eval(document.documentElement.clientHeight/2+document.documentElement.scrollTop)); _left:expression(eval(document.documentElement.clientWidth/2+document.documentElement.scrollLeft); }
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
    <td width="99%" align="left" valign="top">您的位置：商品管理&nbsp;&nbsp;>&nbsp;&nbsp;商品列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="90%" align="left" valign="middle">
	         <form method="get" action="index.php">
	         <span>商品：</span>
	         <input type="text" name="word" value="<?php echo $_GET['word']?>" class="text-word">
	         <input name="" type="submit" value="查询" class="text-but">
	         </form>
         </td>
  		  <td width="10%" align="center" valign="middle" style="text-align:right; width:150px;"><a href="add.php" target="mainFrame" onFocus="this.blur()" class="add">添加商品</a></td>
  		</tr>
	</table>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
        <th align="center" width="50" valign="middle" class="borderright">缩略图</th>
        <th align="center" valign="middle" class="borderright">商品名称</th>
        <th align="center" valign="middle" class="borderright">商品类别</th>
        <th align="center" valign="middle" class="borderright">零售价</th>
		<th align="center" valign="middle" class="borderright">库存</th>
		<th align="center" valign="middle" class="borderright">销量</th>
		<th align="center" valign="middle" class="borderright">上架</th>
		<th align="center" valign="middle" class="borderright">热销</th>
		<th align="center" valign="middle" class="borderright">新品</th>
		<th align="center" valign="middle" class="borderright">精品</th>		
        <th align="center" valign="middle">操作</th>
      </tr>
	  <?php 
	  $i = 1;
	  foreach($goodslist as $val){
	  ?>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $offset + $i++;?></td>
		<td align="center" valign="middle" class="borderright borderbottom">
			<?php 
			$img_url= UPLOAD_URL;
			$img_url .=substr($val['iname'],0,4).'/';
			$img_url .=substr($val['iname'],4,2).'/';
			$img_url .=substr($val['iname'],6,2).'/';
			$small =$img_url.$size[0].'x'.$size[1].'_'.$val['iname'];
			$large =$img_url.$size[4].'x'.$size[5].'_'.$val['iname'];
			//$img_url .=$size[0].'x'.$size[1].'_'.$val['iname'];
			?>
 			
			<div style="position:relative;" onMouseout="display('<?php echo 'img'.$i;?>')" onMouseover="display('<?php echo 'img'.$i;?>')"><a href="<?php echo URL?>view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" target="_top"><img src="<?php echo $small?>"/></a>
			<div id="<?php echo 'img'.$i;?>" class="box" style="display:none"><img src="<?php echo $large?>" style="height:100%;"/></div></div>
		</td>
        <td align="left" valign="middle" class="borderright borderbottom" style="padding-left:5px"><a href="<?php echo URL?>view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" target="_top"><?php echo $val['gname']?></a></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['cname']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['price']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['stock']?></td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['sell']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['status']==1?'<a href="action.php?a=display&b=status&id='.$val['gid'].'&status=0&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=display&b=status&id='.$val['gid'].'&status=1&page='.$page.'"><img src="../images/main/no.gif"></a>'?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['is_hot']==1?'<a href="action.php?a=display&b=is_hot&id='.$val['gid'].'&is_hot=0&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=display&b=is_hot&id='.$val['gid'].'&is_hot=1&page='.$page.'"><img src="../images/main/no.gif"></a>'?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['is_new']==1?'<a href="action.php?a=display&b=is_new&id='.$val['gid'].'&is_new=0&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=display&b=is_new&id='.$val['gid'].'&is_new=1&page='.$page.'"><img src="../images/main/no.gif"></a>'?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['is_best']==1?'<a href="action.php?a=display&b=is_best&id='.$val['gid'].'&is_best=0&page='.$page.'"><img src="../images/main/yes.gif"></a>':'<a href="action.php?a=display&b=is_best&id='.$val['gid'].'&is_best=1&page='.$page.'"><img src="../images/main/no.gif"></a>'?></td>
        <td align="center" valign="middle" class="borderbottom">
		<?php
		$sql="SELECT id FROM ".PRE."send WHERE goods_id={$val['gid']}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0):
		echo '已推<span class="gray">&nbsp;|&nbsp;</span>';
		else:
		?>
		<a href="addsend.php?a=addsend&id=<?php echo $val['gid']?>" target="mainFrame" onFocus="this.blur()" class="add"><font color="red">推送</font></a><span class="gray">&nbsp;|&nbsp;</span>
		<?php endif;?>
		<a href="image.php?id=<?php echo $val['gid']?>&name=<?php echo $val['gname']?>" target="mainFrame" onFocus="this.blur()" class="add">图片管理</a><span class="gray">&nbsp;|&nbsp;</span>
		<a href="edit.php?&id=<?php echo $val['gid']?>&cid=<?php echo $val['cate_id']?>" target="mainFrame" onFocus="this.blur()" class="add">编辑</a><span class="gray">&nbsp;|&nbsp;</span>
		<a href="action.php?a=del&id=<?php echo $val['gid']?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该商品？')">删除</a></td>
		</td>
      </tr>
      <?php }?>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="fenye">共 <?php echo $total?> 种商品 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="index.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>