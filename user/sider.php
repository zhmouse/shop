<div class="sider fl">
	<ul>
		<li class="avatar">
			<img src="<?php echo URL.'avatar/96x96_'.$_SESSION['home']['avatar']?>">
			<p><?php echo $_SESSION['home']['name']?> | 
			<?php
			switch($_SESSION['home']['type']){
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
		?>	</p>
		</li>
		<li><a class="<?php if($_GET['u']=='user') echo 'u'?>" style="background:url(images/bg.png) no-repeat 0px -157px" href="user.php?u=user&id=<?php echo $_SESSION['home']['id']?>">个人资料</a></li>
		<li><a class="<?php if($_GET['u']=='myorder') echo 'u'?>" style="background:url(images/bg.png) no-repeat -268px -51px" href="user.php?u=myorder">我的订单</a></li>
		<li><a class="<?php if($_GET['u']=='replylist') echo 'u'?>" style="background:url(images/bg.png) no-repeat 6px -27px" href="user.php?u=replylist">我的评价</a></li>
		<li><a class="<?php if($_GET['u']=='address') echo 'u'?>" style="background:url(images/bg.png) no-repeat 0 -201px" href="user.php?u=address">管理收货地址</a></li>
		<li><a class="<?php if($_GET['u']=='add_address') echo 'u'?>" style="background:url(images/bg.png) no-repeat 6px -27px" href="user.php?u=add_address">新增收货地址</a></li>
		<li><a style="background:url(images/cats.png) no-repeat 0 -34px" href="cart.php" target="_blank">我的购物车</a></li>
		<li><a class="<?php if($_GET['u']=='edit') echo 'u'?>" style="background:url(images/bg.png) no-repeat -268px -3px" href="user.php?u=edit">修改我的信息</a></li>
		<li><a class="<?php if($_GET['u']=='reset') echo 'u'?>" style="background:url(images/bg.png) no-repeat 0 -245px" href="user.php?u=reset">修改密码</a></li>
	</ul>
</div>