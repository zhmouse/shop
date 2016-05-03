<!doctype html>
<html>
	<head>
		<title>兄弟连新手练习安装测试</title>
		<meta charset="UTF-8">
		<style>
		.w{width:1200px;margin:0 auto;}
		.fl{float:left}
		.fr{float:right}
		#main .reg .app{padding:40px;width:588px;height:400px;background:url(./images/app.jpg) no-repeat 40px 100px;}
		#main .reg .contact{ width: 430px;height: auto; margin: 40px 40px 40px 0;padding: 30px;border:1px solid #f5f5f5;box-shadow:1px 1px 5px #f5f5f5}
		#main .reg .contact  h3{font-size: 22px;color: #444;font-weight: normal;border-bottom:2px solid #CB351A; margin-bottom:20px}
		#main .reg .contact *:focus{outline :none;}
		#main .reg .contact img {vertical-align:middle;margin-left:5px;margin-top:40px;border-radius: 5px;}
		#main .reg .contact p{list-style: none;padding: 6px 0;}
		#main .reg .contact p input[type=text],
		#main .reg .contact p input[type=password]{width: 220px;height: 25px;border :1px solid #aaa;padding: 3px 8px;border-radius: 5px;transition: padding .25s;-o-transition: padding  .25s;-moz-transition: padding  .25s;-webkit-transition: padding  .25s;}
		#main .reg .contact p input:focus{border-color: #CB351A;}
		#main .reg .contact p input:focus{padding-right: 70px;}
		#main .reg .contact p button{height:36px;width:240px;border:none;background:#4DB7F5;color:white;border-radius: 5px;font-size:22px;cursor: pointer}
		#main .reg .tips{color: rgba(0, 0, 0, 0.5);padding-left: 10px;overflow:hidden}
		#main .reg .tips a{cursor:pointer}		
		</style>
	</head>
	<body>
	<div id ="main">
		<div class="reg w">
			<div class="app fl">

			</div>
			<div class="contact fr" >

	<?php 
$files="inc/config.php"; 
if(!file_exists($files)){
	touch($files);
}

if(!is_writable($files)){ 
echo "<font color='red'>不可写！！！</font>"; 
}
//var_dump($_POST);exit;
if(!empty($_POST['install'])){ 
$str="<?php\n";
foreach($_POST as $key=>$val){
if($key=="test" || $key=="install") continue;
$str.="define('$key','$val');\n";
}
$str.="define('CHARSET','utf8');\n";
$str.='?>';

$handle = fopen($files, "w+"); 
fwrite($handle, $str); 
//===================== 
include_once ("inc/config.php"); //嵌入配置文件 
if (!@$link = mysql_connect(HOST, USER, PWD)) { //检查数据库连接情况 
	echo "数据库连接失败! 请返回上一页检查连接参数 <a href=install.php>返回修改</a>"; 
} else { 
	$sql="CREATE DATABASE IF NOT EXISTS ".DBNAME;
	$result=mysql_query($sql); 
	if($result){
		echo '数据库创建成功<br>';
	}else{
		if(substr_count(mysql_error(),'exists')){
			exit( '数据库已存在!');
		}else{
			exit('数据库创建失败：'.mysql_error());
		}
	}

	mysql_select_db(DBNAME); 
	mysql_set_charset(CHARSET);

	$tables=file_get_contents('s36.sql');
	$match='/create\s+table.+?;/is';
	preg_match_all($match,$tables,$arr);

	foreach($arr[0] as $val){		
		$match='/\s`(\w+)`\s+\(/'; //找到表名提示用
		preg_match($match,$val,$name);
		$tname=$name[1];
		$nname=PRE.substr($tname,4);
		$val=str_replace($tname,$nname,$val);
		$result=mysql_query($val);
		if($result){
			echo $nname.'创建成功<br>';
		}else{
			echo $nname.'创建失败<br>';
		}
	}
							
	$sql="INSERT INTO ".PRE."user(name,password,type) values ('admin',md5('123456'),2)";
	if(mysql_query($sql)){
		echo '管理员创建成功<br>';
	}else{
		exit('管理员创建失败');
	}

	
	if(empty($_POST['test'])){
		$sql="INSERT INTO ".PRE."about (`id`, `title`) VALUES(7, '关于我们'),(8, '退换货政策'),(9, '什么是闪购'),(10, '用户服务协议'),(11, '积分获得')";
		mysql_query($sql);
		$sql="INSERT INTO ".PRE."category (`id`, `name`, `pid`, `path`, `display`) VALUES
(72, '五级润滑油', 71, '0,26,31,70,71,', 1),
(61, 'CD/DVD', 42, '0,27,42,', 1),
(62, '低音炮', 42, '0,27,42,', 1),
(63, '发烧改装件', 42, '0,27,42,', 1),
(64, '智能驾驶', 27, '0,27,', 1),
(70, '壳牌润滑油', 31, '0,26,31,', 1),
(71, '四级润滑油', 70, '0,26,31,70,', 1),
(26, '维修保养', 0, '0,', 1),
(27, '车载电器', 0, '0,', 1),
(28, '美容清洗', 0, '0,', 1),
(29, '汽车装饰', 0, '0,', 1),
(30, '安全自驾', 0, '0,', 1),
(31, '润滑油', 26, '0,26,', 1),
(32, '滤清器', 26, '0,26,', 1),
(33, '刹车片盘', 26, '0,26,', 1),
(34, '防冻液', 26, '0,26,', 1),
(35, '轮胎', 26, '0,26,', 1),
(36, '雨刷', 26, '0,26,', 1),
(37, '行车记录', 27, '0,27,', 1),
(38, '导航仪', 27, '0,27,', 1),
(39, '倒车雷达', 27, '0,27,', 1),
(40, '吸尘器', 27, '0,27,', 1),
(41, '净化器', 27, '0,27,', 1),
(42, '时尚影音', 27, '0,27,', 1),
(43, '清洁剂', 28, '0,28,', 1),
(44, '玻璃水', 28, '0,28,', 1),
(45, '洗车工具', 28, '0,28,', 1),
(46, '车蜡', 28, '0,28,', 1),
(47, '补漆笔', 28, '0,28,', 1),
(48, '坐垫座套', 29, '0,29,', 1),
(49, '脚垫', 29, '0,29,', 1),
(50, '空气净化', 29, '0,29,', 1),
(51, '车衣', 29, '0,29,', 1),
(52, '香水', 29, '0,29,', 1),
(53, '充气泵', 30, '0,30,', 1),
(54, '灭火器', 30, '0,30,', 1),
(55, '工兵铲', 30, '0,30,', 1),
(56, '除雪铲', 30, '0,30,', 1),
(57, '方向盘锁', 30, '0,30,', 1),
(58, '安全锤', 30, '0,30,', 1),
(59, '拖车绳', 30, '0,30,', 1),
(60, '电瓶线', 30, '0,30,', 1)";
		mysql_query($sql);
	}else{
		$tables=file_get_contents('s36.sql');
		$match='/insert\s+into.+?\);/is';
		preg_match_all($match,$tables,$arr);
		foreach($arr[0] as $val){
			$match='/insert\s+into\s+`(\w+)`\s+\(/i'; //表名\s+\(/'
			preg_match($match,$val,$name);
			$tname=$name[1];
			$nname=PRE.substr($tname,4);
			$val=str_replace($tname,$nname,$val);
			$result=mysql_query($val);
			if($result){
				echo $nname.'数据添加成功<br>';
			}else{
				exit( $nname.'数据添加失败<br>');
			}
		}							
	}

	//echo "<script>alert('安装成功!');location.href='index.php'</script>"; 
	echo '<div style="font-size:16px;color:#CB351A;">系统安装完成!</div>';
	echo '现在你可以前往：<a href="index.php">首页</a>&nbsp;&nbsp;&nbsp;<a href="admin/index.php">后台管理</a><br>';
	echo '后台管理：用户名 admin 密码 123456';
	rename("install.php","install.lock");
	exit;
	} 
}
?> 

			
				<form action="" method="POST">
				<h3>兄弟连新手练习安装测试</h3>
				<p>填写主机：<input type="text" name="HOST" value="localhost"/></p> 
				<p>用 户 &nbsp;名：<input type="text" name="USER" value="root"/></p> 
				<p>密　　码：<input type="text" name="PWD" value=""/></p> 
				<p>数据库名：<input type="text" name="DBNAME" value=""/></p> 
				<p>数据前缀：<input type="text" name="PRE" value="s36_"/></p>
				<p>测试数据：<input type="checkbox" name="test" value="1"> 安装测试数据 </p> 
				<p><button type="submit" name="install" value="1">安装</button></p>
				</form>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	</body>
</html>
<?php

	
?>	