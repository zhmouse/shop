<div class="order w">
	<div class="step">
        <ul>
            <li class="hover"><p>1</p><span>我的购物车</span></li>
            <li class="hover"><p>2</p><span>订单确认</span></li>
            <li><p>3</p><span>付款</span></li>
            <li><p>4</p><span>购买完成</span></li>
			<div class="clear"></div>
        </ul>
    </div>
<div class="inputtab">
	<form method="post" id="orderform" action="do_order.php?a=add">
	<input type="hidden" name="addtime" value="<?php echo time()?>">
<?php	
$sql="SELECT id,name,mobile,phone,address,status FROM ".PRE."address WHERE user_id={$uid}";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$addresslist =array();
	while($rows=mysql_fetch_assoc($result)){
		$addresslist[]=$rows;
	}
?>

	<h3>选择收货地址</h3>
	<table width="100%" align="center" cellpadding="0" cellspacing="0">
<?php	foreach($addresslist as $val){
	$valaddress=$val['name'].','.$val['mobile'].','.$val['phone'].','.$val['address'];
?><tr>
	<td width="80"><input type="radio" name="addr" <?php if($val['status']==1) echo 'checked'?> value="<?php echo $valaddress?>"></td>
	<td align="left">收货人：<?php echo $val['name']?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $val['mobile']?>&nbsp;&nbsp;<?php echo $val['phone']?>
		&nbsp;&nbsp;收货地址：<?php echo str_replace(',','',$val['address'])?>
	</td></tr>
<?php		
	}?>
	<tr><td><input type="radio" name="addr" onclick="display('add')" value="new"></td><td>使用新地址</td></tr>
	<tr>
		<td></td>
		<td>
			<table id="add" width="100%" align="center" cellpadding="0" cellspacing="0" style="display:none">
			<?php require './inc/tab.php';?>			
			</table>
		</td>
	</tr>
	</table>
	
<?php }else{
?>	
		
		<h3>添加收货地址</h3>		
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<?php require './inc/tab.php';?>
		</table>
<?php }?>		
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
				<td>
					<?php echo $val['num']?>
				</td>
				<td><span>&yen;<?php echo number_format($val['num']*$val['price'],2)?></span></td>
				
			</tr>
			<?php $i++;}?>
			<tr>
				
				<td  colspan="5" style="text-align:right">
					<a href="cart.php" class="graybtn">返回购物车修改</a>
					<p>共<span><?php echo $cart_total?></span>件商品RMB:<span>&yen;<?php echo number_format($tprice,2)?></span></p>
					<div class="clear"></div>
				</td>
				
			</tr>
			
		</table>
		<div>
		
			<input id="order" type="button" value="提交订单">
			<input type="text" name="note" placeholder="订单备注">
			<div class="clear"></div>
		</div>
		
		<form>
	</div>
</div>