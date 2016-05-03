<?php
$id=$_GET['id'];
$sql = "SELECT id,name,mobile,phone,email,address,status FROM ".PRE."address WHERE id={$id}";
//echo $sql;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$row = mysql_fetch_assoc($result);
}
list($sheng,$shi,$qu,$address)=explode(',',$row['address']);
?>
<div class="main fr">
	<form method="post" action="do_user.php?a=edit_address&id=<?php echo $id?>">
		<h3>修改收货地址</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td width="100">收货人</td>
				<td><input type="text" name="name" value="<?php echo $row['name']?>"></td>
			</tr>
			<tr>
				<td>手机号码</td>
				<td><input type="text" name="mobile" value="<?php echo $row['mobile']?>"></td>
			</tr>
			<tr>
				<td>联系电话</td>
				<td><input type="text" name="phone" value="<?php echo $row['phone']?>"></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="email" value="<?php echo $row['email']?>"></td>
			</tr>
			<tr>
				<td>区域</td>
				<td>
					<div>
					省：<select id="cmbProvince" name="sheng"></select>
					市：<select id="cmbCity"  name="shi"></select>
					区：<select id="cmbArea"  name="qu"></select>
					</div>
				</td>
			</tr>
			<tr>
				<td>详细地址</td>
				<td><input type="text" name="address" value="<?php echo $address?>"></td>
			</tr>
			<tr>
				<td colspan="2"><input type="checkbox" name="status" value='1' <?php if($row['status']==1) echo 'checked';?>>设为默认发货地址</td>
			</tr>
		</table>
		<div>
			<input type="submit" value="提交">
			<input type="reset" value="重置">
			<div class="clear"></div>
		</div>
	</form>
</div>