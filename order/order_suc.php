<?php
$id=$_GET['id'];
$sql = "SElECT id,order_id,name,phone,email,address,total,user_id,status,addtime,note FROM ".PRE."order WHERE id={$id}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$row = mysql_fetch_assoc($result);
}

?>
<div class="order w">
	<div class="step">
        <ul>
            <li class="hover"><p>1</p><span>我的购物车</span></li>
            <li class="hover"><p>2</p><span>订单确认</span></li>
            <li class="hover"><p>3</p><span>付款</span></li>
            <li><p>4</p><span>购买完成</span></li>
			<div class="clear"></div>
        </ul>
    </div>
	<div class="inputtab">
	<form method="post" id="payform" action="order.php?u=pay&id=<?php echo $id?>">
	<input type="hidden" name="addtime" value="<?php echo time()?>">
	<h3>订单提交成功</h3>
		<div class="ctips"><p>订单号为：<span><?php echo $row['order_id']?></span></p></div>
		<table width="90%" style="margin:0 auto" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td style="width:100px">收货地址</td>
			<td><?php echo str_replace(',','',$row['address'])?></td>
		<tr>
			<td>收货人</td>
			<td><?php echo $row['name']?></td>
		</tr>
		<tr>
			<td>联系电话</td>
			<td><?php echo $row['mobile']?><?php echo $row['phone']?></td>
		</tr>
		</table>
	
	</div>
	
	<div class="listtab mt15">
		
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<th>商品图</th>
				<th>商品名称</th>
				<th>价格</th>
				<th>数量</th>
				<th width="180">小计</th>				
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
				
			</tr>
			<?php endforeach;?>
			<tr>
				
				<td  colspan="5" style="text-align:right">
					<a href="do_order.php?a=no&id=<?php echo $id?>" class="graybtn">取消订单</a>
					<p>共<span><?php echo $num?></span>件商品RMB:<span>&yen;<?php echo number_format($row['total'],2)?></span></p>
					<div class="clear"></div>
				</td>
				
			</tr>
			
		</table>
		<div>	
			<input id="pay" type="button" value="立即支付">
			<div class="clear"></div>
		</div>
		
		<form>
	</div>
</div>