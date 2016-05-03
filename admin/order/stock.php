<?php
require '../init.php';
$oid=$_GET['id'];
	$sql="SELECT o.num,g.stock,g.name,g.id,g.cate_id FROM ".PRE."order_goods o,".PRE."goods g WHERE o.goods_id=g.id AND o.order_id={$oid}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$numlist=array();
			while($rows=mysql_fetch_assoc($result)){
				$numlist[]=$rows;
			}
		}
$n=0;
foreach($numlist as $val){
	if($val['num']>$val['stock']){
		$n +=1;
	}
}
//echo $n;exit;
if($n==0){
	mass('库存充足！','#437ccf',1);
	exit;
}
		
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="../css/css.css" type="text/css" rel="stylesheet" />
<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="images/main/favicon.ico" />
<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#searchmain{ font-size:12px;}
#search{ font-size:12px; background:#548fc9; margin:10px 10px 0 0; display:inline; width:100%; color:#FFF; float:left}
#search form span{height:40px; line-height:40px; padding:0 0px 0 10px; float:left;}
#search form input.text-word{height:24px; line-height:24px; width:180px; margin:8px 0 6px 0; padding:0 0px 0 10px; float:left; border:1px solid #FFF;}
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(../images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(../images/main/add.jpg) no-repeat -3px 7px #548fc9; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:right}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab{ border:1px solid #eaeaea; background:#FFF; font-size:12px;margin-top:10px}
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
    <td width="99%" align="left" valign="top">您的位置：订单管理&nbsp;&nbsp;>&nbsp;&nbsp;<a href = "index.php">缺货商品列表</a></td>
  </tr>
 
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr>
        <th align="center" valign="middle" class="borderright">编号</th>
        <th align="center" valign="middle" class="borderright">商品名称</th>
        <th align="center" valign="middle" class="borderright">购买数量</th>
        <th align="center" valign="middle" class="borderright">库存数量</th>
        <th align="center" valign="middle" class="borderright">不足</th>
        <th align="center" valign="middle">操作</th>
      </tr>
	 <?php
	 $i=1;
	 foreach($numlist as $val){
			if($val['num']>$val['stock']){?>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $i?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><a href="<?php echo URL?>view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['id']?>" target="_top"><?php echo $val['name']?></a></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['num']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['stock']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['num']-$val['stock']?></td>
        <td align="center" valign="middle" class="borderbottom"><a href="../goods/edit.php?id=<?php echo $val[id]?>&cid=<?php echo $val[cate_id]?>" target="mainFrame" onFocus="this.blur()" class="add">立即补货</a></td>
      </tr>
     <?php
	 $i++;
			}
		}
	 ?>
    </table></td>
    </tr>
</table>
</body>
</html>