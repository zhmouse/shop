<?php
require '../init.php';
$id=$_GET['id'];
$sql = "SElECT id,order_id,name,phone,email,address,total,user_id,status,addtime,note FROM ".PRE."order WHERE id={$id}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$row = mysql_fetch_assoc($result);
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
#main-tab td{ font-size:12px; line-height:40px;padding:0 5px}
#main-tab td a{ font-size:12px; color:#548fc9;}
#main-tab td a:hover{color:#565656; text-decoration:underline;}
.bordertop{ border-top:1px solid #ebebeb}
.borderright{ border-right:1px solid #ebebeb}
.borderbottom{ border-bottom:1px solid #ebebeb}
.borderleft{ border-left:1px solid #ebebeb}
.gray{ color:#dbdbdb;}
td.fenye{ padding:10px 0 0 0; text-align:right;}
.bggray{ background:#f9f9f9}

#main-tab1{ border:1px solid #eaeaea; background:#FFF; font-size:12px;margin-top:10px;}
#main-tab1 th{ font-size:12px; background:url(../images/main/list_bg.jpg) repeat-x; height:32px; line-height:32px;padding:0 10px}
#main-tab1 th p{display:block;float:right;padding:0 10px}
#main-tab1 td p{display:block;float:right;}
#main-tab1 td span{font-size:18px;color:#f00;padding:0 5px;}
#main-tab1 td{ font-size:12px; padding:5px 10px;}
#main-tab1 td a{ font-size:12px; color:#548fc9;}
#main-tab1 td a:hover{color:#565656; text-decoration:underline;}
#main-tab1 td a.graybtn{diplay:block;float:right;margin:0 10px;background:#dfdfdf;padding:0 15px;margin-top:3px;}
#main-tab1 td a.lightbtn{diplay:block;float:right;margin:0 10px;background:#CB351A;color:#fff;padding:0 15px;margin-top:3px;}
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
    <td width="99%" align="left" valign="top">您的位置：订单管理&nbsp;&nbsp;>&nbsp;&nbsp;订单详情</td>
  </tr>
  <tr>
    <td align="left" valign="top">
    
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
<!------------------------------------------------------->
		
		<table width="100%" align="center" cellpadding="0" cellspacing="0"  id="main-tab1" >			
			<tr>
				<th colspan="6"  style="text-align:left">
					订单号：<?php echo $row['order_id']?>
					<p>
					<?php 
					switch($row['status']){
						case '6':
							echo '订单已取消';
							break;						
						case '1':
							echo '等待买家付款';
							break;
						case '2':
							echo '<font color="red">已付款未发货</font>';
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
					?>
					</p>
				</th>					
			</tr>
			<tr style="height:32px">
        <td align="center" valign="middle" class="borderbottom">商品图</td>
        <td align="center" valign="middle" class="borderbottom">商品名称</td>
        <td align="center" valign="middle" class="borderbottom">零售价</td>
        <td align="center" valign="middle" class="borderbottom">数量</td>
		<td align="center" valign="middle" class="borderbottom">小计</td>
        <td align="center" valign="middle" class="borderbottom">操作</th>
      </tr>
			<?php 
			$sql="SELECT o.id oid,o.price,o.num,g.name gname,g.id gid,g.cate_id,i.name iname FROM ".PRE."order_goods o,".PRE."goods g,".PRE."image i WHERE o.goods_id=g.id AND g.id=i.goods_id AND i.is_face=1 AND o.order_id={$id}";
			$result = mysql_query($sql);
			if($result && mysql_num_rows($result)>0){
				$goodslist = array();
				while($rows = mysql_fetch_assoc($result)){
					$goodslist[] = $rows;
				}
			}
			$num=0;
			foreach($goodslist as $gval):
				$num +=$gval['num'];
			?>
			<tr>
				
				<td><?php 
					$img_url= UPLOAD_URL;
					$img_url .=substr($gval['iname'],0,4).'/';
					$img_url .=substr($gval['iname'],4,2).'/';
					$img_url .=substr($gval['iname'],6,2).'/';
					$img_url .=$size[0].'x'.$size[1].'_'.$gval['iname'];
					?>
					<a href="<?php echo 'view.php?id='.$gval['cate_id'].'&gid='.$gval['gid'];?>">
					<img src="<?php echo $img_url?>">
					</a>
				</td>
				<td style="text-align:left">
				<a href="<?php echo 'view.php?id='.$gval['cate_id'].'&gid='.$gval['gid'];?>">
				<?php echo $gval['gname']?>
				</a>
				</td>
				<td>&yen;<?php echo $gval['price']?></td>
				<td><?php echo $gval['num']?></td>
				<td>小计：&yen;<?php echo $gval['price']*$gval['num']?></td>
				<td align="center" valign="middle"><a href="../goods/edit.php?&id=<?php echo $gval['gid']?>&cid=<?php echo $gval['cate_id']?>" target="mainFrame" onFocus="this.blur()" class="add">商品编辑</a></td>
			</tr>
			
			
			<?php endforeach;?>
			<tr>
				<th colspan="6" style="text-align:left" class="bordertop">发货信息</th>
			</tr>
			<tr style="height:32px" >
				<td colspan="6" style="text-align:left">收&nbsp;&nbsp;货&nbsp;&nbsp;人：<?php echo $row['name']?></td>
			</tr>
			<tr style="height:32px">
				<td colspan="6" style="text-align:left">联系电话：<?php echo $row['mobile']?>&nbsp;&nbsp;<?php echo $row['phone']?></td>
			</tr>
			<tr style="height:32px">
				<td colspan="6" style="text-align:left">收货地址：<?php echo str_replace(',','',$row['address'])?></td>
			</tr>
			<tr style="height:32px">
				<td colspan="6" style="text-align:left">订单备注：<span><?php echo $row['note']?></span></td>
			</tr>
			
			<tr  style="height:50px">			
				<td colspan="6"  style="text-align:left">订单创建：
					<?php echo date('Y-m-d H:i:s',$row['addtime'])?>
					
					<?php if($row['status']==2):?>
					<a href="action.php?a=send&id=<?php echo $id?>" class="lightbtn" onclick="return confirm('该订单确定发货?')">立即发货</a>
					<a href="stock.php?id=<?php echo $id?>" class="graybtn">检查缺货</a>
					<?php endif;?>
					<?php if($row['status']==5):?>
					<a href="action.php?a=del&id=<?php echo $id?>" class="graybtn" onclick="return confirm('确定删除该订单？')">删除订单</a>
					<?php endif;?>
					<?php if($row['status']<3 && $row['status']>0):?>
					<a href="action.php?a=no&id=<?php echo $id?>" class="graybtn"  onclick="return confirm('确定取消该订单？')">取消订单</a>
					<?php endif;?>
					
					<p>共<span><?php echo $num?></span>件商品RMB:<span>&yen;<?php echo number_format($row['total'],2)?></span></p>
					<div class="clear"></div>
				</td>
			</tr>
			
		</table>

<!------------------------------------------------------->
    
    
	</td>
    </tr>
  
</table>
</body>
</html>