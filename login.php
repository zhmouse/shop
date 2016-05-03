<?php

require './header.php';
if($_SESSION['home']){
	header('location:index.php');
}
$url=$_GET[url];
$refererUrl = !empty($url)?$url:$_SERVER['HTTP_REFERER'];
//$refererUrl = $_SERVER['HTTP_REFERER'];  
?>

	<div id ="main">
		<div class="reg w">
			<div class="app fl">
				<img src="./images/app.jpg">
			</div>
			<div class="contact fr" >				
				<form name="reg" action="dologin.php?a=login" method="post">
						<input type="hidden" name="lasttime" value="<?php echo time()?>">
						<input type="hidden" name="refererUrl" value="<?php echo $refererUrl;?>" />  
						<div class="title"><h3>用户登录</h3><span class="">还不是用户<a href="./regist.php">免费注册</a></span><div class="clear"></div></div>
						<p><input type="text" name="name" placeholder="请输入用户名" required/><span class="tips">长度5~16个字符</span></p>
						<p><input type="password" name="password" placeholder="请输入你的密码" required/><span class="tips">长度6~15个字符</span></p>
						<p><input type="text" name="vcode" placeholder="请输入验证码" required/><img src="./images/vcode.php" id="vcode" onclick="this.src=this.src+'?i='+Math.random()"><span class="tips"><a onclick="document.getElementById('vcode').src='images/vcode.php?i='+Math.random()">看不清，换一张</a></span></p>
						<p><button type="submit">登录</button></p>			
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>

<?php
require './footer.php';
?>