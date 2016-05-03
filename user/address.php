<?php
$uid=$_SESSION['home']['id'];
$sql = "SELECT id,name,mobile,phone,email,address,status FROM ".PRE."address WHERE user_id={$uid}";
//echo $sql;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$addresslist = array();
	while($rows = mysql_fetch_assoc($result)){
		$addresslist[] = $rows;
	}
}

?>

<div class="mainlist fr">
		<h3>我的收货地址</h3>		
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr><th width="50">默认</th>
				<th width="90">收货人</th>
				<th>手机号码</th>
				<th>联系电话</th>
				<th>Email</th>
				<th>收货地址</th>
			</tr>
			<?php foreach($addresslist as $val):
			?>	
			<tr>
				<td><input type="radio" name="status" <?php echo $val['status']==1?'checked':'onclick="location=\'do_user.php?a=status&id='.$val['id'].'\'"';?>></td>
				<td><?php echo $val['name']?></td>
				<td><?php echo $val['mobile']?></td>
				<td><?php echo $val['phone']?></td>
				<td><?php echo $val['email']?></td>
				<td class="tl"><?php echo str_replace(',','',$val['address'])?></td>
			</tr>
			<tr>
				<td colspan="6"><a href="user.php?u=edit_address&id=<?php echo $val['id']?>" class="lightbtn">编辑</a><a href="do_user.php?a=del_address&id=<?php echo $val['id']?>" class="graybtn">删除</a></td>
			</tr>
			<?php endforeach;?>
		</table>
</div>