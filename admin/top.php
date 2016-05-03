<?php
require './init.php';
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>后台页面头部</title>
<link href="css/css.css" type="text/css" rel="stylesheet" />
</head>
<body onselectstart="return false" oncontextmenu=return(false) style="overflow-x:hidden;">
<!--禁止网页另存为-->
<noscript><iframe scr="*.htm"></iframe></noscript>
<!--禁止网页另存为-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="header">
  <tr>
    <td rowspan="2" align="left" valign="top" id="logo"><img src="images/main/logo.png" width="74" height="64"></td>
    <td align="left" valign="bottom">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="bottom" id="header-name">兄弟连新手练习</td>
        <td align="right" valign="top" id="header-right">
        	<a href="logout.php" target="_top" onFocus="this.blur()" class="admin-out">注销</a>
            <a href="index.php" target="top" onFocus="this.blur()" class="admin-home">管理首页</a>
        	<a href="../index.php" target="_blank" onFocus="this.blur()" class="admin-index">网站首页</a>       	
            <span>
<!-- 日历 -->
<SCRIPT type=text/javascript src="js/clock.js"></SCRIPT>
<SCRIPT type=text/javascript>showcal();</SCRIPT>
            </span>
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="left" valign="bottom"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="left" valign="top" id="header-admin">后台管理系统</td>
        <td align="left" valign="bottom" id="header-menu">
        <a href="main.php" target="mainFrame" onFocus="this.blur()" id="menuon">后台首页</a>
        <a href="user/index.php" target="mainFrame" onFocus="this.blur()">用户管理</a>
        <a href="category/index.php" target="mainFrame" onFocus="this.blur()">分类管理</a>
        <a href="goods/index.php" target="mainFrame" onFocus="this.blur()">商品管理</a>
        <a href="order/index.php" target="mainFrame" onFocus="this.blur()">订单管理</a>
        <a href="reply/index.php" target="mainFrame" onFocus="this.blur()">评价管理</a>
        <a href="goods/send.php" target="mainFrame" onFocus="this.blur()">推送管理</a>
		<a href="about/index.php" target="mainFrame" onFocus="this.blur()">站点文章</a>
        </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>