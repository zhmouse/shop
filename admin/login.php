<?php
require '../init.php';//前台文件
if($_SESSION['admin']){
	header('location:index.php');
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>兄弟连新手练习后台管理登录界面-www.s36.cn</title>
    <link href="css/alogin.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <form id="form1" runat="server" action="dologin.php" method="post">
	<input type="hidden" name="lasttime" value="<?php echo time();//最后登录时间?>">
    <div class="Main">
        <ul>
            <li class="top"></li>
            <li class="top2"></li>
            <li class="topA"></li>
            <li class="topB"><span><img src="images/login/logo.gif" alt="" style="" /></span></li>
            <li class="topC"></li>
            <li class="topD">
                <ul class="login">
                    <li><span class="left login-text">用户名：</span> <span style="left">
                        <input id="Text1" type="text" class="txt" name="name"/>  
                     
                    </span></li>
                    <li><span class="left login-text">密码：</span> <span style="left">
                       <input id="Text2" type="password" class="txt" name="password" />  
                    </span></li>
					<li><span class="left login-text">验证码：</span> <span style="left">
                       <input id="Text3" type="text" class="txtCode" name="vcode" />
					   <img src="../images/vcode.php" onclick="this.src=this.src+'?i='+Math.random()">  
                    </span></li>
                </ul>
            </li>
            <li class="topE"></li>
            <li class="middle_A"></li>
            <li class="middle_B"></li>
            <li class="middle_C"><span class="btn"><input name="" type="image" src="images/login/btnlogin.gif" /></span></li>
            <li class="middle_D"></li>
            <li class="bottom_A"></li>
            <li class="bottom_B">兄弟连新手练习&nbsp;&nbsp;www.s36.cn</li>
        </ul>
    </div>
    </form>
</body>
</html>