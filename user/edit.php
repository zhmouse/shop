
<?php

$uid = $_SESSION['home']['id'];
$sql = "SELECT id,name,sex,email,phone,type,avatar FROM ".PRE."user WHERE id = {$uid}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}

?>
<div class="main fr">
	<form method="post" action="do_user.php?a=edit" enctype="multipart/form-data">
		<input type="hidden" name="id" value="<?php echo $uid?>">
		<h3>修改我的资料</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td>会员级别</td>
				<td>
		<?php
			switch($rows['type']){
				case 0:
					echo '普通用户';
					break;
				case 1:
					echo '普通管理员';
					break;
				case 2:
					echo '超级管理员';
					break;
			}
		?></td>
			</tr>
			<tr>
				<td width="100">用户名</td>
				<td><?php echo $rows['name']?></td>
			</tr>
			<tr>
				<td width="100">头像</td>
				<td><img src="<?php echo URL.'avatar/64x64_',$rows['avatar']?>"></td>
			</tr>
			<tr>
				<td width="100"></td>
				<td><input type="file" name="pic[]"></td>
			</tr>
			<tr>
				<td>性别</td>
				<td>
				<input type="radio" name="sex" value="0" class="text-word radio" <?php if($rows['sex']==0) echo 'checked'?> >女
				<input type="radio" name="sex" value="1" class="text-word radio" <?php if($rows['sex']==1) echo 'checked'?> >男
				<input type="radio" name="sex" value="2" class="text-word radio" <?php if($rows['sex']==2) echo 'checked'?> >保密
				</td>
			</tr>
			<tr>
				<td>联系电话</td>
				<td><input type="text" name="phone" value="<?php echo $rows['phone']?>"></td>
			</tr>
			<tr>
				<td>Email</td>
				<td><input type="text" name="email"  value="<?php echo $rows['email']?>"></td>
			</tr>
		</table>
		<div>
			<input type="submit" value="提交">
			<input type="reset" value="重置">
			<div class="clear"></div>
		</div>
		
	</form>	
</div>