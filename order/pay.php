<?php
$id=$_GET[id];
$uid=$_SESSION['home']['id'];
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
            <li class="hover"><p>4</p><span>购买完成</span></li>
			<div class="clear"></div>
        </ul>
    </div>
	<div class="inputtab">
<?php
$sql="UPDATE ".PRE."order SET status=2 WHERE id={$id}";
$result=mysql_query($sql);
if($result && mysql_affected_rows()>0):	
					$_SESSION['home']['credits'] +=$row['total'];
					if(
						($_SESSION['home']['credits']>=200 && $_SESSION['home']['val']==1)||//2
						($_SESSION['home']['credits']>=1000 && $_SESSION['home']['val']==2)||//3
						($_SESSION['home']['credits']>=10000 && $_SESSION['home']['val']==3)||//4
						($_SESSION['home']['credits']>=100000 && $_SESSION['home']['val']==4)//5
					){
						$_SESSION['home']['val']+=1;
					}
					$val=$_SESSION['home']['val'];
	$sql="UPDATE ".PRE."user SET credits=credits+{$row['total']},val={$val} WHERE id={$uid}";
	$result=mysql_query($sql);
	//$_SESSION['home']['credits'] += $row['total'];
	
?>	
	<h3>您已成功付款!</h3>
		<div class="ctips"><p>您已成功支付&nbsp;&nbsp;<span>&yen;<?php echo $row['total']?></span>,并为您增加了&nbsp;&nbsp;<?php echo $row['total']?>&nbsp;&nbsp;积分！</p></div>
		<table width="90%" style="margin:0 auto" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>您的订单号：<?php echo $row['order_id']?></td>
		<tr>
		<tr>
			<td>您的宝贝很快到达您的手中！</td>
		<tr>
			<td>祝您购物愉快！</td>
		</tr>
		<tr>
			<td>你还可以&nbsp;<a href="index.php" style="color:#edd28b">继续购物</a>&nbsp;或&nbsp;<a href="user.php?u=myorder" style="color:#edd28b">查看已买到的宝贝</a>&nbsp;</td>
		</tr>
		</table>
	



<?php else:?>

	<h3>支付失败！</h3>
	<div class="ctips"><p>小二已在处理，请耐心等待！</p></div>

<?php endif;?>
	</div>
</div>