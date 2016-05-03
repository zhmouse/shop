<?php 

require './init.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>左侧导航menu</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="js/sdmenu.js"></script>
<script type="text/javascript">
	// <![CDATA[
	var myMenu;
	window.onload = function() {
		myMenu = new SDMenu("my_menu");
		myMenu.init();
	};
	// ]]>
</script>
<style type=text/css>
html{ SCROLLBAR-FACE-COLOR: #538ec6; SCROLLBAR-HIGHLIGHT-COLOR: #dce5f0; SCROLLBAR-SHADOW-COLOR: #2c6daa; SCROLLBAR-3DLIGHT-COLOR: #dce5f0; SCROLLBAR-ARROW-COLOR: #2c6daa;  SCROLLBAR-TRACK-COLOR: #dce5f0;  SCROLLBAR-DARKSHADOW-COLOR: #dce5f0; overflow-x:hidden;}
body{overflow-x:hidden; background:url(images/main/leftbg.jpg) left top repeat-y #f2f0f5; width:194px;}
</style>
</head>
<body onselectstart="return false;" ondragstart="return false;" oncontextmenu="return false;">
<div id="left-top">
	<div><img src="<?php echo URL.'avatar/64x64_'.$_SESSION['admin']['avatar']?>" width="44" height="44" /></div>
    <span>用户：<?php echo $_SESSION['admin']['name']?><br>角色：<?php echo $_SESSION['admin']['type']==1?'普通管理员':'超级管理员'?></span>
</div>
    <div style="float: left" id="my_menu" class="sdmenu">
      <div class="collapsed">
        <span>用户管理</span>
        <a href="./user/index.php" target="mainFrame" onFocus="this.blur()">用户列表</a>
        <a href="./user/add.php" target="mainFrame" onFocus="this.blur()">添加用户</a>
      </div>
      <div>
        <span>分类管理</span>
        <a href="./category/index.php" target="mainFrame" onFocus="this.blur()">分类列表</a>
        <a href="./category/add.php" target="mainFrame" onFocus="this.blur()">添加分类</a>
      </div>
      <div>
        <span>商品管理</span>
        <a href="./goods/index.php" target="mainFrame" onFocus="this.blur()">商品列表</a>
        <a href="./goods/add.php" target="mainFrame" onFocus="this.blur()">添加商品</a>
		<a href="./goods/send.php" target="mainFrame" onFocus="this.blur()">推送列表</a>
      </div>
      <div>
        <span>订单管理</span>
        <a href="./order/index.php" target="mainFrame" onFocus="this.blur()">订单列表</a>
        <a href="./order/recycle.php" target="mainFrame" onFocus="this.blur()">回收站</a>
      </div>
	  <div>
        <span>评价管理</span>
        <a href="./reply/index.php" target="mainFrame" onFocus="this.blur()">评价列表</a>
      </div>
	  <div>
        <span>站点管理</span>
        <a href="./about/index.php" target="mainFrame" onFocus="this.blur()">文章列表</a>
      </div>
	  <!--<div>
        <span>系统设置</span>
        <a href="main.php" target="mainFrame" onFocus="this.blur()">分组权限</a>
        <a href="main_list.html" target="mainFrame" onFocus="this.blur()">级别权限</a>
        <a href="main_info.html" target="mainFrame" onFocus="this.blur()">角色管理</a>
        <a href="main.php" target="mainFrame" onFocus="this.blur()">自定义权限</a>
      </div>-->
    </div>
</body>
</html>