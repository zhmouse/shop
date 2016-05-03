
<?php

$uid = $_SESSION['home']['id'];
$sql = "SELECT id,name,sex,email,phone,type,avatar FROM ".PRE."user WHERE id = {$uid}";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}

?>
<div class="main fr">
	<form method="post" action="do_user.php?a=reset">
		<input type="hidden" name="id" value="<?php echo $uid?>">
		<h3>修改我的密码</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td>输入原密码</td>
				<td><input type="password" name="oldpassword"></td>
			</tr>
			<tr>
				<td width="100">输入新密码</td>
				<td><input type="password" name="newpassword"></td>
			</tr>
			<tr>
				<td width="100">确认密码</td>
				<td><input type="password" name="repassword"></td>
			</tr>
		</table>
		<div>
			<input type="submit" value="提交">
			<input type="reset" value="重置">
			<div class="clear"></div>
		</div>		
	</form>	
</div>