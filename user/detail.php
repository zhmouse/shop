<?php

$uid=$_SESSION['home']['id'];
$id=$_GET['id'];
$sql = "SElECT id,order_id,name,phone,email,address,total,user_id,status,addtime,note FROM ".PRE."order WHERE id={$id}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$row = mysql_fetch_assoc($result);
}

?>

<div class="mainlist fr">
				
		<ul class="bg">
			<li style="background:url('images/orderStep.png')  no-repeat 0 <?php echo $row['status']>=1?2:-33;?>px;">1</li>
			<li style="background:url('images/orderStep.png')  no-repeat 0 <?php echo $row['status']>=2?2:-33;?>px">2</li>
			<li style="background:url('images/orderStep.png')  no-repeat 0 <?php echo $row['status']>=3?2:-33;?>px">3</li>
			<li style="background:url('images/orderStep.png')  no-repeat 0 <?php echo $row['status']>=4?2:-33;?>px">4</li>
			<li style="background:url('images/orderStep.png')  no-repeat 0 <?php echo $row['status']>=5?2:-33;?>px">5</li>
			<div class="clear"></div>
		</ul>
		<ul>
			<li>未付款</li>
			<li>等待卖家发货</li>
			<li>卖家已发货</li>
			<li>已收货</li>
			<li>已评价</li>
			<div class="clear"></div>
		</ul>
		<h3>订单详情</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">			
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
				<td style="text-align:center;width:80px">
					<?php if(($row['status']==4)||($row['status']==5)):?>
						<?php //查询是否已评价
							$sql="SELECT id FROM ".PRE."reply WHERE goods_id={$gval['gid']} AND order_id={$row['id']} AND user_id={$uid} AND status>0";
							//echo $sql;exit;
							$result = mysql_query($sql);
							if($result && mysql_num_rows($result)>0){
								$reply='追加';$class='graybtn';
							}else{
								$reply='评价';$class='lightbtn';
							}					
						?>
					
					<a href="user.php?u=reply&id=<?php echo $gval['gid']?>&oid=<?php echo $row['id']?>" class="<?php echo $class?>" style="margin-top:0;"><?php echo $reply?></a>
					<?php else:?>
					<a href="<?php echo 'view.php?id='.$gval['cate_id'].'&gid='.$gval['gid'];?>">查看</a>
					<?php endif;?>	
				
				
				</td>
				
				
				
				
				
			</tr>
			
			
			<?php endforeach;?>
			<tr>
				<th style="width:80px">发货信息</th><th  colspan="5"></th>
			</tr>
			<tr>
				<td>收&nbsp;&nbsp;货&nbsp;&nbsp;人</td><td colspan="5" style="text-align:left"><?php echo $row['name']?></td>
			</tr>
			<tr>
				<td>联系电话</td><td colspan="5" style="text-align:left"><?php echo $row['mobile']?>&nbsp;&nbsp;<?php echo $row['phone']?></td>
			</tr>
			<tr>
				<td>收货地址</td><td colspan="5" style="text-align:left"><?php echo str_replace(',','',$row['address'])?></td>
			</tr>
			<tr>
				<td>订单备注</td><td colspan="5" style="text-align:left"><span><?php echo $row['note']?></span></td>
			</tr>
			
			<tr  style="height:60px">			
				<td colspan="6"  style="text-align:left">订单创建：
					<?php echo date('Y-m-d H:i:s',$row['addtime'])?>
					<?php if($row['status']==6):?>
					<a href="do_order.php?a=del&id=<?php echo $id?>" class="lightbtn"  onclick="return confirm('确定删除该订单?')">删除订单</a>
					<?php endif;?>
					
					<?php if($row['status']==1):?>
					<a href="order.php?u=pay&id=<?php echo $id?>" class="lightbtn" onclick="return confirm('该订单确定付款?')">立即付款</a>
					<?php endif;?>

					<?php if($row['status']==3):?>
					<a href="do_user.php?a=confirm&id=<?php echo $id?>" class="lightbtn" onclick="return confirm('该订单确定收货?')" >确认收货</a>
					<?php endif;?>
					
					<?php if($row['status']==4):?>
					<a href="" class="lightbtn">立即评价</a>
					<?php endif;?>
					
					<?php if($row['status']<3 && $row['status']>0):?>
					<a href="do_order.php?a=no&id=<?php echo $id?>" class="graybtn"  onclick="return confirm('确定取消该订单?')">取消订单</a>
					<?php endif;?>
					
					<p>共<span><?php echo $num?></span>件商品RMB:<span>&yen;<?php echo number_format($row['total'],2)?></span></p>
					<div class="clear"></div>
				</td>
			</tr>
			
		</table>
</div>