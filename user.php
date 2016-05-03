<?php
require 'header.php';
if(empty($_SESSION['home'])){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('location:login.php?url='.$url);
}
$u=$_GET['u'];
//var_dump($_SESSION);
?>

<div id= main>
	
	<!--面包屑导航开始-->
	<div class="nav w" >
		<p>
			<a href="./">首页</a>					
			&nbsp;&nbsp;>&nbsp;&nbsp;
			<?php 
			switch($u){
				case 'add_address':
					echo '新增收货地址';
					break;
				case 'address':
					echo '管理收货地址';
					break;
				case 'detail':
					echo '订单详情';
					break;
				case 'edit':
					echo '修改我的信息';
					break;
				case 'edit_address':
					echo '编辑地址';
					break;
				case 'myorder':
					echo '我的订单';
					break;
				case 'user':
					echo '个人资料';
					break;
				case 'reply':
					echo '添加评价';
					break;
				case 'replylist':
					echo '我的评价';
					break;
				case 'reset':
					echo '修改密码';
					break;
				
			}
			
			?>
		</p>			
	</div>
	<!--面包屑导航结束-->
	
	<div class="user w">
		<?php require './user/sider.php';?>
		<?php include './user/'.$u.'.php';?>
		<div class="clear"></div>	
	</div>
</div>

<?php require 'footer.php';?>