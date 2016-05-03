<?php
require '../init.php';
$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "AND r.note LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT id FROM ".PRE."reply WHERE note LIKE '%{$word}%' GROUP BY goods_id ";
//echo $sql;exit;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$total=array();
	while($rows = mysql_fetch_assoc($result)){
		$total[]=$rows;
	}
}
$total = count($total);
$sql ="SELECT COUNT(id) total FROM ".PRE."reply WHERE note LIKE '%{$word}%'";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$tot=mysql_fetch_assoc($result);
}

$num = 5;
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

$sql="SELECT r.status,g.name gname,g.id gid,g.cate_id,i.name iname FROM ".PRE."reply r,".PRE."goods g,".PRE."image i WHERE r.goods_id=g.id AND i.goods_id=g.id AND i.is_face=1 {$where} GROUP BY g.id LIMIT {$offset},{$num}";
//echo $sql ; exit;
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$goodslist=array();
	while($rows=mysql_fetch_assoc($result)){
		$goodslist[]=$rows;
	}
}
//var_dump($goodslist);exit;
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
#tab1 td n{position:absolute; top:20px; left:20px;display:block;width:20px;height:20px;line-height:20px;text-align:center;background:#548FC9;border-radius:10px;color:#fff;margin:5px 5px 0 0}
#tab2 td{padding:5px 5px}
#tab2 td input[type=reset]{diplay:inline-block;border:none; margin-right:10px;background:#dfdfdf;padding:0 15px;margin-top:3px;}
#tab2 td input[type=submit]{diplay:inline-block;border:none;margin-right:10px;background:#CB351A;color:#fff;padding:0 15px;margin-top:3px;}
#tab2 textarea{overflow:auto; border:#ebebeb 1px solid; background:#FFF; padding:5px;scroll}
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
    <td width="99%" align="left" valign="top">您的位置：评价管理&nbsp;&nbsp;>&nbsp;&nbsp;评价列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
   		 <td width="" align="left" valign="middle">
	         <form method="get" action="index.php">
	         <span>评价：</span>
	         <input type="text" name="word" value="<?php echo $_GET['word']?>" class="text-word">
	         <input name="" type="submit" value="查询" class="text-but">
	         </form>
         </td>
  		  <!--<td width="" align="center" valign="middle" style="text-align:right; width:150px;"><a href="add.php" target="mainFrame" onFocus="this.blur()" class="add">添加分类</a></td>-->
  		</tr>
	</table>
    </td>
  </tr>

  
 <!------tr-------->
  <tr>
  
    <td align="left" valign="top">
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
	  <?php
$i=1;
foreach($goodslist as $val):
	$img=UPLOAD_URL;
	$img.=substr($val['iname'],0,4).'/';
	$img.=substr($val['iname'],4,2).'/';
	$img.=substr($val['iname'],6,2).'/';
	$img.=$size[2].'x'.$size[3].'_'.$val['iname'];
	$sql="SELECT status FROM ".PRE."reply WHERE goods_id={$val['gid']}";
	//echo $sql;exit;
	$result=mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		$stalist=array();
		while($rows=mysql_fetch_assoc($result)){
			$stalist[]=$rows;
		}
	}
	$a=0;
	$b=0;
	$c=0;
	$renum=count($stalist);
	foreach($stalist as $n){
		switch($n['status']){
			case 1:
				$a +=1;
				break;
			case 2:
				$b +=1;
				break;
			case 3:
				$c +=1;
				break;
		}
	}	
	//$sql="SELECT id,user_id,pid,path,goods_id,status,note,addtime FROM ".PRE."reply {$where} order by concat(path,id) LIMIT {$offset},{$num}";
	$sql="SELECT r.id,r.user_id,r.pid,r.path,r.goods_id,r.order_id,r.note,r.addtime,u.name FROM ".PRE."reply r,".PRE."user u  WHERE u.id=r.user_id AND goods_id={$val['gid']} {$where} order by concat(r.path,r.id)";
	//echo $sql ; exit;
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		$replylist=array();
		while($row = mysql_fetch_assoc($result)){
			$replylist[]=$row;
		}	
	}
	
?>
		<tr>
			<td valign="top"  class="borderright borderbottom">
				<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab1">
					<tr>
						<th align="center" valign="middle">商品</th>
					</tr>
					<tr>
						<td td align="center" valign="middle" style="position:relative;width:220px"><a href="<?php echo URL?>view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" target="_top"><img src="<?php echo $img?>"></a><n><?php echo $offset+$i++?></n></td>
					</tr>
					<tr>
						<td td align="center" valign="middle"><a href="<?php echo URL?>view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" target="_top"><?php echo utf8substr($val['gname'],0,10)?></a></td>
					</tr>
					<tr><td td align="center" valign="middle"><font color="red">好评 (<?php echo $a?>)</font>&nbsp;&nbsp;&nbsp;&nbsp;<font color="green">中评 (<?php echo $b?>)</font>&nbsp;&nbsp;&nbsp;&nbsp;<font color="blue">差评 (<?php echo $c?>)</font></td></tr>
				</table>			
			
			</td>
			<td colspan="4"  valign="top"  class="borderbottom">
		
				<table width="100%" border="0" cellspacing="0" cellpadding="0" id="tab2">
				<tr>
				<th align="center" valign="middle" class="borderright">评价内容</th>
        <th align="center" valign="middle" class="borderright" width="100">用户</th>
        <th align="center" valign="middle" class="borderright" width="160">评价时间</th>
        <th align="center" valign="middle" width="80">操作</th>
      </tr>
      
<?php
foreach($replylist as $rows){		
		$id = $rows['id'];
		$pid = $rows['pid'];
		$note = $pid>0?'&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">回复：</font>'.$rows['note']:$rows['note'];		
?>		
		<tr onMouseOut="this.style.backgroundColor='<?php echo $bgcolor?>'" onMouseOver="this.style.backgroundColor='#edf5ff'">
			<td align="left" valign="middle" class="borderright borderbottom"><?php echo $note?></td>
			<td align="center" valign="middle" class="borderright borderbottom"><?php echo $rows['name']?></td>
			<td align="center" valign="middle" class="borderright borderbottom"><?php echo date('Y-m-d H:i:s',$rows['addtime'])?></td>
			<td align="center" valign="middle" class="borderbottom">
				<?php if($pid==0):?>
					<a onclick="display('reply<?php echo $id?>')" target="mainFrame" onFocus="this.blur()" class="add" style="cursor:pointer"><font color="red">回复</font></a><span class="gray">&nbsp;|&nbsp;</span>
					<a href="action.php?a=del&id=<?php echo $id?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除评价？')">删除</a>
				<?php else:?>
					<?php if($rows['user_id']==$_SESSION['admin']['id']):?>
					<a onclick="display('edit<?php echo $id?>')" target="mainFrame" onFocus="this.blur()" class="add"  style="cursor:pointer">编辑</a><span class="gray">&nbsp;|&nbsp;</span>
					<a href="action.php?a=del&id=<?php echo $id?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除评价？')">删除</a>
					<?php else:?>
					编辑<span class="gray">&nbsp;|&nbsp;删除
					<?php endif;?>
				<?php endif;?>
				
			</td>
		</tr>

<tr>
	<td colspan="4" id="reply<?php echo $id?>"  class="borderbottom" style="display:none">	
		<form method="post" action="action.php?a=reply">
		<input type="hidden" name="gid" value="<?php echo $rows['goods_id']?>">
		<input type="hidden" name="oid" value="<?php echo $rows['order_id']?>">
		<input type="hidden" name="pid" value="<?php echo $rows['id']?>">
		<input type="hidden" name="path" value="<?php echo $rows['path']?>">
		<input type="hidden" name="url" value="<?php echo $url?>">
		<p><textarea rows="3" cols="50" name="note" placeholder="回复内容"></textarea></p>
		<p><input type="submit" value="提交">
		<input type="reset" value="重置"></p>
		</form>
	</td>
</tr>

<tr>
	<td colspan="4" id="edit<?php echo $id?>"  class="borderbottom" style="display:none">	
		<form method="post" action="action.php?a=edit">		
		<input type="hidden" name="id" value="<?php echo $rows['id']?>">
		<input type="hidden" name="url" value="<?php echo $url?>">
		<p><textarea rows="3" cols="50" name="note"><?php echo $rows['note']?></textarea></p>
		<p><input type="submit" value="提交">
		<input type="reset" value="重置"></p>
		</form>
	</td>
</tr>
		
<?php			
    }

?>
    </table>
	
			
			</td>		
		</tr>
		<?php endforeach;?>	
	</table>
	
    
    
	
	
	</td>
    </tr>
<!-------------tr-------------->

  <tr>
    <td align="left" valign="top" class="fenye">共对 <?php echo $total?> 个商品有<?php echo $tot['total']?>条评价 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="index.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="index.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>