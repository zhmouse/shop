<?php
$uid=!empty($_GET[id])?$_GET[id]:$_SESSION['home']['id'];
//$uid=$_SESSION['home']['id'];

if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "AND order_id LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."order WHERE status>0 AND user_id={$uid} {$where}";
//echo $sql;exit;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
$total = $rows['total'];
$num = 10;
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

$sql = "SElECT id,order_id,name,phone,email,address,total,user_id,status,addtime,note FROM ".PRE."order WHERE status>0 AND user_id={$uid} {$where} ORDER BY addtime DESC LIMIT {$offset},{$num}";
//echo $sql;exit;
//}
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$orderlist = array();
	while($rows = mysql_fetch_assoc($result)){
		$orderlist[] = $rows;
	}
}

?>

<div class="mainlist fr">
		<h3>我的订单</h3>		
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<?php foreach($orderlist as $val):?>
			<tr>
				<th colspan="6" style="text-align:left">订单号：<?php echo $val['order_id']?>
					<p>
					<?php 
					switch($val['status']){
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
			<?php 
			$sql="SELECT o.id oid,o.price,o.num,g.name gname,g.id gid,g.cate_id,i.name iname FROM ".PRE."order_goods o,".PRE."goods g,".PRE."image i WHERE o.goods_id=g.id AND g.id=i.goods_id AND i.is_face=1 AND o.order_id={$val['id']}";
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
				<td style="text-align:center;width:100px">小计：&yen;<?php echo $gval['price']*$gval['num']?>
								
				</td>
				<td style="text-align:center;width:80px">
					<?php if(($val['status']==4)||($val['status']==5)):?>
						<?php //查询是否已评价
							$sql="SELECT id FROM ".PRE."reply WHERE goods_id={$gval['gid']} AND order_id={$val['id']} AND user_id={$uid} AND status>0";
							//echo $sql;exit;
							$result = mysql_query($sql);
							if($result && mysql_num_rows($result)>0){
								$reply='追加';$class='graybtn';
							}else{
								$reply='评价';$class='lightbtn';
							}					
						?>
					
					<a href="user.php?u=reply&id=<?php echo $gval['gid']?>&oid=<?php echo $val['id']?>" class="<?php echo $class?>" style="margin-top:0;"><?php echo $reply?></a>
					<?php else:?>
					<a href="<?php echo 'view.php?id='.$gval['cate_id'].'&gid='.$gval['gid'];?>">查看</a>
					<?php endif;?>	
				
				
				</td>
			</tr>
			<?php endforeach;?>
			<tr style="height:60px">
			<td colspan="6"  style="text-align:left">订单创建：
				<?php echo date('Y-m-d H:i:s',$val['addtime'])?>
				<?php if($val['status']==6):?>
					<a href="do_order.php?a=del&id=<?php echo $val['id']?>" class="lightbtn"  onclick="return confirm('确定删除该订单?')">删除订单</a>
				<?php endif;?>
				
				<?php if($val['status']==1):?>
					<a href="order.php?u=pay&id=<?php echo $val['id']?>" class="lightbtn"  onclick="return confirm('该订单确定付款?')">立即付款</a>
				<?php endif;?>
				
				<?php if($val['status']==3):?>
					<a href="do_user.php?a=confirm&id=<?php echo $val['id']?>" class="lightbtn" onclick="return confirm('该订单确定收货?')">确认收货</a>
				<?php endif;?>
				
				<?php if($val['status']==4):?>
					<!--<a href="" class="lightbtn">立即评价</a>-->
				<?php endif;?>
				
				<?php if($val['status']<3 && $val['status']>0):?>
				<a href="do_order.php?a=no&id=<?php echo $val['id']?>" class="graybtn" onclick="return confirm('确定取消该订单?')">取消订单</a>
				<?php endif;?>
				<a href="user.php?u=detail&id=<?php echo $val['id']?>" class="graybtn">订单详情</a>
				
				<p style="float:right">共<span><?php echo $num?></span>件商品
					RMB:<span>&yen;<?php echo number_format($val['total'],2)?></span></p>
			</td></tr>
			<?php endforeach;?>
			
			<tr>
				<td valign="top" class="fenye" colspan="6">共 <?php echo $total?> 条订单 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="user.php?u=myorder&page=1<?php echo $url?>">首页</a>&nbsp;&nbsp;<a href="user.php?u=myorder&page=<?php echo $prev.$url?>">上一页</a>&nbsp;&nbsp;<a href="user.php?u=myorder&page=<?php echo $next.$url?>">下一页</a>&nbsp;&nbsp;<a href="user.php?u=myorder&page=<?php echo $amount.$url?>">尾页</a></td>
			</tr>
			
		</table>
</div>
