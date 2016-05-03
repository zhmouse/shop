<?php
require './header.php';
if($_SESSION['home']){
	header('location:index.php');
}
$refererUrl = $_SERVER['HTTP_REFERER'];  
?>

	<div id ="main">
		<div class="reg w">
			<div class="app fl">
				<img src="./images/app.jpg">
			</div>
			<div class="contact fr" >				
				<form name="reg" action="dologin.php?a=reg" method="post">
						<input type="hidden" name="regtime" value="<?php echo time()?>">
						<input type="hidden" name="refererUrl" value="<?php echo $refererUrl;?>" />  
						<div class="title"><h3>新用户注册</h3><span class="">老用户<a href="./login.php">直接登录</a></span><div class="clear"></div></div>
						<p><input type="text" name="name" placeholder="请输入用户名" required/><span class="tips">长度5~16个字符</span></p>
						<p><input type="password" name="password" placeholder="请输入你的密码" required/><span class="tips">长度6~15个字符</span></p>
						<p><input type="password" name="repassword" placeholder="请再次输入你的密码" required/><span class="tips">两次密码需要相同</span></p>
						<p><input type="text" name="email" placeholder="请输入你的邮箱" required/><span class="tips"></span></p>
						<p><input type="text" name="vcode" placeholder="请输入验证码" required/><img src="./images/vcode.php" id="vcode" onclick="this.src=this.src+'?i='+Math.random()"><span class="tips"><a onclick="document.getElementById('vcode').src='./images/vcode.php?i='+Math.random()">看不清，换一张</a></span></p>
						<p><button type="submit">立即注册</button></p>			
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>

<?php
require './footer.php';
?>