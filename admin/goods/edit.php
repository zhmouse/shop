<?php

require '../init.php';

$sql="SELECT id,name,path FROM ".PRE."category order by concat(path,id)";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    while($rows = mysql_fetch_assoc($result)){
		$space = str_repeat('&nbsp;&nbsp;',substr_count($rows['path'],',')*5 );
		$space = $space.'├─ '.$rows['name'];
		$option .= '<option value="'.$rows['id'].'" '.($rows['id'] == $_GET['cid']?'selected':'').'>'.$space.'</option>';
    }
}

$id = $_GET['id'];
$sql = "SELECT id,name,cate_id,price,stock,status,is_hot,is_new,is_best,`describe` FROM ".PRE."goods WHERE id={$id}";
//echo $sql;
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
//var_dump($rows);
?>



<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>主要内容区main</title>
<link href="../css/css.css" type="text/css" rel="stylesheet" />
<link href="../css/main.css" type="text/css" rel="stylesheet" />
<link rel="shortcut icon" href="../images/main/favicon.ico" />
<style>
body{overflow-x:hidden; background:#f2f0f5; padding:15px 0px 10px 5px;}
#searchmain{ font-size:12px;}
#search{ font-size:12px; background:#548fc9; margin:10px 10px 0 0; display:inline; width:100%; color:#FFF}
#search form span{height:40px; line-height:40px; padding:0 0px 0 10px; float:left;}
#search form input.text-word{height:24px; line-height:24px; width:180px; margin:8px 0 6px 0; padding:0 0px 0 10px; float:left; border:1px solid #FFF;}
#search form input.text-but{height:24px; line-height:24px; width:55px; background:url(../images/main/list_input.jpg) no-repeat left top; border:none; cursor:pointer; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666; float:left; margin:8px 0 0 6px; display:inline;}
#search a.add{ background:url(../images/main/add.jpg) no-repeat 0px 6px; padding:0 10px 0 26px; height:40px; line-height:40px; font-size:14px; font-weight:bold; color:#FFF;}
#search a:hover.add{ text-decoration:underline; color:#d2e9ff;}
#main-tab{ border:1px solid #eaeaea; background:#FFF; font-size:12px;}
#main-tab th{ font-size:12px; background:url(../images/main/list_bg.jpg) repeat-x; height:32px; line-height:32px;}
#main-tab td{ font-size:12px; line-height:40px;}
#main-tab td a{ font-size:12px; color:#548fc9;}
#main-tab td a:hover{color:#565656; text-decoration:underline;}
.bordertop{ border-top:1px solid #ebebeb}
.borderright{ border-right:1px solid #ebebeb}
.borderbottom{ border-bottom:1px solid #ebebeb}
.borderleft{ border-left:1px solid #ebebeb}
.gray{ color:#dbdbdb;}
td.fenye{ padding:10px 0 0 0; text-align:right;}
.bggray{ background:#f9f9f9; font-size:14px; font-weight:bold; padding:10px 10px 10px 0; width:120px;}
.main-for{ padding:10px;}
.main-for input.text-word{ width:310px; height:36px; line-height:36px; border:#ebebeb 1px solid; background:#FFF; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; padding:0 10px;}
.main-for input.text-file{ width:310px; height:36px; line-height:36px;font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; padding:0;}
.main-for label{padding-right:30px;cursor: pointer;}
.main-for .textarea{overflow:auto; border:#ebebeb 1px solid; background:#FFF; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; padding:10px;scroll}
.main-for select{ width:310px; height:36px; line-height:36px; border:#ebebeb 1px solid; background:#FFF; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#666;}
.main-for input.text-but{ width:100px; height:40px; line-height:30px; border: 1px solid #cdcdcd; background:#e6e6e6; font-family:"Microsoft YaHei","Tahoma","Arial",'宋体'; color:#969696; float:left; margin:0 10px 0 0; display:inline; cursor:pointer; font-size:14px; font-weight:bold;}
#addinfo a{ font-size:14px; font-weight:bold; *background:url(../images/main/addinfoblack.jpg) no-repeat 0 1px; padding:0px 0 0px 20px; line-height:45px;}
#addinfo a:hover{ *background:url(../images/main/addinfoblue.jpg) no-repeat 0 1px;}
#addinfo a{display:block;float:right; font-size:14px; font-weight:bold; *background:url(../images/main/addinfoblack.jpg) no-repeat 0 1px; padding:0px 10px 0px 0; line-height:45px;}
#addinfo a:hover{ *background:url(../images/main/addinfoblue.jpg) no-repeat 0 1px;}
#addinfo span{display:block;float:left;line-height:45px;font-size:14px; font-weight:bold;margin:0 10px;}
</style>
<script src="../JS/Edit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
    new nicEditor({ fullPanel: true }).panelInstance('txtContent');
});
</script>
</head>
<body>
<!--main_top-->
<table width="99%" border="0" cellspacing="0" cellpadding="0" id="searchmain">
  <tr>
    <td width="99%" align="left" valign="top">您的位置：商品管理&nbsp;&nbsp;>&nbsp;&nbsp;编辑商品&nbsp;&nbsp;>&nbsp;&nbsp;<?php echo $rows['name']?></td>
  </tr>
  <tr>
    <td align="left" valign="top" id="addinfo"><span>你正在编辑：<?php echo $rows['name']?></span>
    <a href="image.php?id=<?php echo $id?>&name=<?php echo $rows['name']?>" target="mainFrame" onFocus="this.blur()" class="right">图片管理</a>
	<div style="clear:both"></div>
    </td>
  </tr>
  <tr>
    <td align="left" valign="top">
    <form method="post" action="action.php?a=edit" enctype="multipart/form-data">
	<input type="hidden" value="<?php echo $id?>" name="id">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="main-tab">
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品名称：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="name" value="<?php echo $rows['name']?>" class="text-word">
        </td>
        </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品类别：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <select name="cate_id" id="level">
		<option value="">&nbsp;&nbsp;选择分类</option>
		<?php echo $option?>		
        </select>
        </td>
      </tr>
	  <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品价格：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="price" value="<?php echo $rows['price']?>" class="text-word">
        </td>
      </tr>
	  <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品库存：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input type="text" name="stock" value="<?php echo $rows['stock']?>" class="text-word">
        </td>
      </tr>
	  <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品状态：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <label><input type="checkbox" name="status" value="1" class="checkbox" <?php if($rows['status']==1) echo 'checked'?>>&nbsp;&nbsp;上架</label>
		<label><input type="checkbox" name="is_hot" value="1" class="checkbox" <?php if($rows['is_host']==1) echo 'checked'?>>&nbsp;&nbsp;热销</label>
		<label><input type="checkbox" name="is_new" value="1" class="checkbox" <?php if($rows['is_new']==1) echo 'checked'?>>&nbsp;&nbsp;新品</label>
		<label><input type="checkbox" name="is_best" value="1" class="checkbox" <?php if($rows['is_best']==1) echo 'checked'?>>&nbsp;&nbsp;精品</label>
        </td>
      </tr>
	  <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">商品描述：</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <textarea name="describe" rows="10" cols="80" class="textarea" id="txtContent"><?php echo $rows['describe']?></textarea>
        </td>
      </tr>
      <tr onMouseOut="this.style.backgroundColor='#ffffff'" onMouseOver="this.style.backgroundColor='#edf5ff'">
        <td align="right" valign="middle" class="borderright borderbottom bggray">&nbsp;</td>
        <td align="left" valign="middle" class="borderright borderbottom main-for">
        <input name="" type="submit" value="提交" class="text-but">
        <input name="" type="reset" value="重置" class="text-but"></td>
        </tr>
    </table>
    </form>
    </td>
    </tr>
</table>
</body>
</html>