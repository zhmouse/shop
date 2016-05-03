<?php
require '../init.php';

$id = $_GET['id'];
$gname= $_GET['name'];
$url = "&id={$id}&name={$gname}";
if(!empty($_GET['n'])){
	$n= $_GET['n'];
	$str = '<input type="file" name="pic[]" class="text-file">';
	$input = str_repeat($str,$n-1);
}



//$input = !empty($_GET['n'])?'<input type="file" name="pic[]" class="text-file"><input type="file" name="pic[]" class="text-file">':'';
$sql = "SELECT COUNT(id) total FROM ".PRE."image where goods_id = {$id}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
$total = $rows['total'];
$num = 16;
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


//查询所有相关图片
$sql="SELECT id,name,is_face FROM ".PRE."image where goods_id = {$id}";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $i_list=array();
    while($rows=mysql_fetch_assoc($result)){
        $i_list[]=$rows;
    }
} 
//var_dump($i_list);
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
#search form input.text-file{height:24px; line-height:24px; width:180px; margin:8px 6px 6px 0; padding:0; float:left; }
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(../images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 6px 0 0 ; display:inline;}
#search a.add{ *background:url(../images/main/add.jpg) no-repeat -3px 7px #548fc9; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:right;cursor:pointer}
#search .left{ padding:0 10px;width:100px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF; float:left}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab,#main-tab1{ border:1px solid #eaeaea; background:#FFF; font-size:12px;}
#main-tab th,#main-tab1 th{ font-size:12px; background:url(../images/main/list_bg.jpg) repeat-x; height:32px; line-height:32px;}
#main-tab td,#main-tab1 td{ font-size:12px; line-height:40px;}
#main-tab td a,#main-tab1 td a{ font-size:12px; color:#548fc9;}
#main-tab td a:hover,#main-tab1 td a:hover{color:#565656; text-decoration:underline;}

.bordertop{ border-top:1px solid #ebebeb}
.borderright{ border-right:1px solid #ebebeb}
.borderbottom{ border-bottom:1px solid #ebebeb}
.borderleft{ border-left:1px solid #ebebeb}
.gray{ color:#dbdbdb;}
td.fenye{ padding:10px 0 0 0; text-align:right;}
.bggray{ background:#f9f9f9}

.box{*width:500px; height:500px; border:1px solid #000; position:fixed; left:120px; top:50%;*margin-left:-100px;margin-top:-250px;_position:absolute;_top:expression(eval(document.documentElement.clientHeight/2+document.documentElement.scrollTop)); _left:expression(eval(document.documentElement.clientWidth/2+document.documentElement.scrollLeft); }
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
    <td width="99%" align="left" valign="top">您的位置：商品管理&nbsp;&nbsp;>&nbsp;&nbsp;<a href = "index.php">商品列表</a>&nbsp;&nbsp;>&nbsp;&nbsp;<span style="color:#548fc9">[<?php echo $gname?>] </span>的图片列表</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="search">
  		<tr>
			<td align="center" valign="middle" ><span class="left">上传图片：</span></td>
   		 <td width="80%" align="left" valign="middle">
	         <form method="post" enctype="multipart/form-data" action="action.php?a=addimg<?php echo $url?>" >
	         <input type="file" name="pic[]"  class="text-file"><?php echo $input?>
	         <input name="" type="submit" value="上传" class="text-but">
			 <?php if(empty($_GET['n'])):?>
			 <a href="image.php?n=3<?php echo $url?>"><input type="button" value="多文件" class="text-but"></a>
			 <?php else:?>
			 <a href="image.php?<?php echo $url?>"><input type="button" value="单文件" class="text-but"></a>
			 <?php endif;?>
	         </form>
         </td>
			<td width="150px" align="center" valign="middle" style="text-align:right; width:150px;"><a target="mainFrame" onFocus="this.blur()" class="add" onclick="display('main-tab');display('main-tab1');">略图模式</a></td>
  		</tr>
	</table>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    <table width="100%" border="0" cellspacing="0" cellpadding="5" id="main-tab1"  style="display:none">
		
			<?php 
			  $j = 1;
			  foreach($i_list as $val){
				if($j%4==1) echo '</tr>';
			?>
			<td align="center" onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
				<table width="<?php echo $size[2]?>" align="center" cellspacing="10">
					<tr>
						<td colspan="3" align="center">
							<?php 
							$img_url= UPLOAD_URL;
							$img_url .=substr($val['name'],0,4).'/';
							$img_url .=substr($val['name'],4,2).'/';
							$img_url .=substr($val['name'],6,2).'/';
							$large=$img_url.$val['name'];
							$small=$img_url.$size[2].'x'.$size[3].'_'.$val['name'];
							?> 			
							
				<img src="<?php echo $small?>"  onclick="display('<?php echo 'img'.$i;?>')"/>
<div style="display:none;"  class="box" id="<?php echo 'img'.$i;?>" >
<p><input type="button" value="关闭" onclick="display('<?php echo 'img'.$i;?>')" style="position:absolute;right:0;top;0px;z-index: 10;"/></p>
<img onclick="display('<?php echo 'img'.$i;?>')" src="<?php echo $large?>" style="height:100%;position:relative" />			
							
            
							
							
							
							
							
						</td>					
					</tr>					
					<tr>
						<td width="<?php echo $size[2]-80;?>"><?php echo $offset + $j;?></td>
						<td width="40">
							<?php echo $val['is_face']==1?'<img src="../images/main/yes.gif">':'<a href="action.php?a=is_face&iid='.$val['id'].$url.'&is_face=1"><img src="../images/main/no.gif"></a>'?>
						</td>
						<td width="40">
							<?php if($val['is_face']==1):?>
							删除
							<?php else:?>
							<a href="action.php?a=delimg&id=<?php echo $val['id']?>&is_face=<?php echo $val['is_face']?>&gid=<?php echo $id?>&name=<?php echo $gname?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该图片？')">删除</a>
							<?php endif;?>						
						</td>
					</tr>
				</table>
			</td>
			<?php if($j%4==0) echo '</tr>';$j++?>
		<?php }?>
		
		<tr></table>
		
		
	
	
	<table width="100%" border="0" cellspacing="0" cellpadding="5" id="main-tab">
      <tr>
        <th align="center" width="50" valign="middle" class="borderright">编号</th>
        <th align="center" width="<?php echo $size[0]?>" valign="middle" class="borderright">缩略图</th>
		<th align="center" valign="middle" class="borderright">图片名称</th>
		<th align="center" valign="middle" class="borderright">封面</th>		
        <th align="center" valign="middle">操作</th>
      </tr>
	  <?php 
	  $i = 1;
	  foreach($i_list as $val){
	  ?>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $offset + $i++;?></td>
		<td align="center" valign="middle" class="borderright borderbottom" style="positon:relative">
			<?php 
			$img_url= UPLOAD_URL;
			$img_url .=substr($val['name'],0,4).'/';
			$img_url .=substr($val['name'],4,2).'/';
			$img_url .=substr($val['name'],6,2).'/';
			$large=$img_url.$val['name'];
			$small=$img_url.$size[0].'x'.$size[1].'_'.$val['name'];
			?> 			
							
<img src="<?php echo $small?>"  onclick="display('<?php echo 'img'.$i;?>')"/>
<div style="display:none;"  class="box" id="<?php echo 'img'.$i;?>" >
<p><input type="button" value="关闭" onclick="display('<?php echo 'img'.$i;?>')" style="position:absolute;right:0;top;0px;z-index: 10;"/></p>
<img onclick="display('<?php echo 'img'.$i;?>')" src="<?php echo $large?>" style="height:100%;position:relative" />

</div>			
			
		</td>
		<td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['name']?></td>
        <td align="center" valign="middle" class="borderright borderbottom"><?php echo $val['is_face']==1?'<img src="../images/main/yes.gif">':'<a href="action.php?a=is_face&page='.$page.'&iid='.$val['id'].$url.'&is_face=1"><img src="../images/main/no.gif"></a>'?></td>

        <td align="center" valign="middle" class="borderbottom">
		<?php if($val['is_face']==1):?>
		删除
		<?php else:?>
		<a href="action.php?a=delimg&id=<?php echo $val['id']?>&is_face=<?php echo $val['is_face']?>&gid=<?php echo $id?>&name=<?php echo $gname?>" target="mainFrame" onFocus="this.blur()" class="add" onclick="return confirm('确定删除该图片？')">删除</a>
		<?php endif;?>
		</td>
      </tr>
      <?php }?>
    </table></td>
    </tr>
  <tr>
    <td align="left" valign="top" class="fenye">共 <?php echo $total?> 张图片 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="image.php?page=1<?php echo $url?>" target="mainFrame" onFocus="this.blur()">首页</a>&nbsp;&nbsp;<a href="image.php?page=<?php echo $prev.$url?>" target="mainFrame" onFocus="this.blur()">上一页</a>&nbsp;&nbsp;<a href="image.php?page=<?php echo $next.$url?>" target="mainFrame" onFocus="this.blur()">下一页</a>&nbsp;&nbsp;<a href="image.php?page=<?php echo $amount.$url?>" target="mainFrame" onFocus="this.blur()">尾页</a></td>
  </tr>
</table>
</body>
</html>