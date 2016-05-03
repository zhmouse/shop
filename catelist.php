<?php
require 'header.php';
$cid= !empty($_GET['id'])?$_GET['id']:'';
//$cid=$_GET['id'];

//路径
$sql="SELECT concat(path,id) bpath FROM ".PRE."category WHERE id={$cid}";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $row = mysql_fetch_assoc($result);
    $bpath = $row['bpath'];
}

//面包导航
$sql = "SELECT id,name FROM ".PRE."category WHERE id in({$bpath}) ORDER BY FIND_IN_SET(id,'{$bpath}')";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $nav_list =array();
    while($row = mysql_fetch_assoc($result)){
        $nav_list[]=$row;
    }
}

//获取子分类列表
$sql="SELECT id FROM ".PRE."category WHERE path LIKE '{$bpath}%'";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $ids = array();
    while($rown = mysql_fetch_assoc($result)){
        $ids[]=$rown;
    }
}

$id_list = "{$cid}";
if(!empty($ids)){
    foreach($ids as $val){
        $id_list .=','.$val['id'];
    }
}

//分页查询
if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "AND g.name LIKE '%{$word}%'";
	$url = "&word={$word}";
}
if(!empty($cid)){
	if($cid=='new'){
		$sql = "SELECT count(g.id) total FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_new=1 {$where}";
	}elseif($cid=='best'){
		$sql = "SELECT count(g.id) total FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_best=1 {$where}";
	}else{//$cid为空搜索所有	
		$sql= "SELECT count(g.id) total FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND g.status=1 AND g.stock>0 AND i.is_face=1 AND g.cate_id in({$id_list}) {$where}";
	}
	//echo $sql;

}else{
	
	$sql= "SELECT count(g.id) total FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND g.status=1 AND g.stock>0 AND i.is_face=1 {$where}";
	
}
$result = mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$rows = mysql_fetch_assoc($result);
}
$total = $rows['total'];


$num = 20;
$amount =ceil($total/$num);
$page = (int)$_GET['page'];
if($page<1){
	$page = 1;
}
if($page>$amount){
	$page = $amount;
}
$next = $page + 1;
$prev = $page - 1;
$offset = $prev*$num;


//获取商品

if(!empty($cid)){
	if($cid=='new'){
		$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_new=1 {$where} ORDER BY addtime DESC LIMIT {$offset},{$num}";
	}elseif($cid=='best'){
		$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_best=1 {$where} ORDER BY addtime DESC LIMIT {$offset},{$num}";
	}else{	
		$sql="SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid,i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND g.status=1 AND g.stock>0 AND i.is_face=1 AND g.cate_id in({$id_list}) {$where}  ORDER BY addtime DESC LIMIT {$offset},{$num}";
	}
}else{//$cid为空搜索所有
	
	$sql="SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid,i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND g.status=1 AND g.stock>0 AND i.is_face=1 {$where}  ORDER BY addtime DESC LIMIT {$offset},{$num}";			
	//echo $sql;
}

$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $goods_list =array();
    while($row = mysql_fetch_assoc($result)){
        $goods_list[]=$row;
    }
}
?>
		<div id="main">
			<!--面包屑导航开始-->
			<div class="nav w" >
				<p>
					<a href="./">首页</a>
					<?php foreach($nav_list as $val){?>
					&nbsp;&nbsp;>&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $val['id']?>"><?php echo $val['name']?></a>
					<?php }?>
					<?php 
					if(empty($cid)){
						echo '&nbsp;&nbsp;>&nbsp;&nbsp;搜索全部';
					}
					if($cid=='new'){
						echo '&nbsp;&nbsp;>&nbsp;&nbsp;初夏新品';
					}
					if($cid=='best'){
						echo '&nbsp;&nbsp;>&nbsp;&nbsp;精益求精';
					}					
					?>
				</p>			
			</div>
			<!--面包屑导航结束-->
			
			<!--分类商品导航开始-->
			<div class="list">
				<ul>
					<!-- 列表循坏开始-->
					<?php
					foreach($goods_list as $val){
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['iname'],0,4).'/';
						$img_url .=substr($val['iname'],4,2).'/';
						$img_url .=substr($val['iname'],6,2).'/';
						$img_url .=$size[2].'x'.$size[3].'_'.$val['iname'];						
					?>
					<li>
						<a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><img src="<?php echo $img_url?>" title="<?php echo $val['gname']?>"></a>
						<p style="overflow:hidden;text-overflow:ellipsis;"><a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" ><?php echo utf8substr($val['gname'],0,30)?></a></p>
						<p><span>&yen;<?php echo $val['price']?></span></p>
						<!--
						<div ><span class="hot">新<br>品</span></div>
						-->
					</li>
					<?php }?>
					<!-- 列表循坏结束-->
					<div class="clear"></div>
				</ul>			
			</div>
			<!--分类商品导航结束-->
			
			<!--分页开始-->
			<div class="fenye w">
				<p>共 <?php echo $total?> 个商品 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $cid ?>&page=1<?php echo $url?>">首页</a>&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $cid ?>&page=<?php echo $prev.$url?>">上一页</a>&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $cid ?>&page=<?php echo $next.$url?>">下一页</a>&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $cid ?>&page=<?php echo $amount.$url?>" >尾页</a></p>
			</div>
			<!--分页结束-->
		</div>

<?php
require 'footer.php';
?>