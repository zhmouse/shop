<?php
require 'header.php';
//var_dump($_SESSION['cart']);
?>
<div id="main">
<div class="order w">
	<div class="step">
        <ul>
            <li class="hover"><p>1</p><span>我的购物车</span></li>
            <li><p>2</p><span>订单确认</span></li>
            <li><p>3</p><span>付款</span></li>
            <li><p>4</p><span>购买完成</span></li>
			<div class="clear"></div>
        </ul>
    </div>
	
	<div class="listtab">
		<?php if(!empty($_SESSION['cart'])):?>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<th>商品图</th>
				<th>商品名称</th>
				<th>价格</th>
				<th width="120">数量</th>
				<th>小计</th>
				<th width="180">操作</th>
			</tr>
			<?php
			$tprice=0;
			foreach($_SESSION['cart'] as $key=>$val){
				$tprice += $val['num']*$val['price'];
				$img_url=UPLOAD_URL;
				$img_url.=substr($val['iname'],0,4).'/';
				$img_url.=substr($val['iname'],4,2).'/';
				$img_url.=substr($val['iname'],6,2).'/';
				$img_url.=$size[0].'x'.$size[1].'_'.$val['iname'];
			?>			
			<tr>
				<td><a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><img src="<?php echo $img_url?>"></a></td>
				<td class="tl"><a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><?php echo $val['gname']?></a></td>
				<td><span>&yen;<?php echo $val['price']?></span></td>
				<td class="num">
					<a href="do_cart.php?a=jian&gid=<?php echo $key?>">-</a>
					<span><?php echo $val['num']?></span>
					<a href="do_cart.php?a=jia&gid=<?php echo $key?>">+</a>
				</td>
				<td><span>&yen;<?php echo number_format($val['num']*$val['price'],2)?></span></td>
				<td><a href="do_cart.php?a=del&gid=<?php echo $key?>">删除</a></td>
			</tr>
			<?php $i++;}?>
			<tr>
				<td colspan="6">
					<a href="do_cart.php?a=delete" class="graybtn">清空购物车</a>
					<p>共<span><?php echo $cart_total?></span>件商品RMB:<span>&yen;<?php echo number_format($tprice,2)?></span></p>
					<div class="clear"></div>
				</td>
			</tr>
			
		</table>
	
		<div>
			<a href="order.php?u=order" class="submit">去结算</a>
			<a href="index.php" class="reset">继续选购</a>
			<div class="clear"></div>
		</div>
	</div>
		<?php else: ?>
			<div class="ctips"><p>购物车内没有任何商品，请先<a href="index.php">选购</a>！</p></div>
		<?php endif;?>	
</div>
</div>
<?php require 'footer.php'; ?>