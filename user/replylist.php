<?php
$id=!empty($_GET['id'])?$_GET['id']:$_SESSION['home']['id'];


if($_GET['word'] != ''){
	$word = $_GET['word'];
	$where = "AND name LIKE '%{$word}%'";
	$url = "&word={$word}";
}

$sql = "SELECT COUNT(id) total FROM ".PRE."reply WHERE user_id={$id} AND pid=0 {$where}";
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




$sql="SELECT r.id rid,r.note,r.addtime,i.name iname,g.id gid,g.cate_id FROM ".PRE."reply r,".PRE."image i,".PRE."goods g WHERE g.id=r.goods_id AND i.goods_id=r.goods_id AND i.is_face=1 AND user_id={$id} AND pid=0 {$where} ORDER BY addtime DESC LIMIT {$offset},{$num}";
//echo $sql;exit;
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$replylist=array();
	while($rows=mysql_fetch_assoc($result)){
		$replylist[]=$rows;
	}	
}
$image='';
?>

<div class="mainlist fr">
		<h3>我的评价</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">			
			<tr>
			<th style="text-align:left">评价内容</th>
			<th width="160">评价时间<a href="" class="lightbtn" style="position:absolute;top:-32px;right:0">全部评价</a></th>
			<th width="80">相关商品</th>
			</tr>
<?php foreach($replylist as $rep): 
$img=UPLOAD_URL;
$img.= substr($rep['iname'],0,4).'/';
$img.= substr($rep['iname'],4,2).'/';
$img.= substr($rep['iname'],6,2).'/';
$img.= $size[0].'x'.$size[1].'_'.$rep['iname'];

?>
		
		<tr>
		
			<td style="text-align:left;"><a style="text-align:left;background:url(images/bg.png) no-repeat 6px -33px;padding-left:27px" href="<?php echo URL.'view.php?id='.$rep['cate_id'].'&gid='.$rep['gid'];?>"><?php echo $rep['note']?></a></td>
			<td><?php echo date('Y-m-d H:i:s',$rep['addtime'])?></td>
			<td>
		<?php if($img!=$image):?>
			<a href="<?php echo URL.'view.php?id='.$rep['cate_id'].'&gid='.$rep['gid'];?>"><img src="<?php echo $img?>"></a>
		<?php endif;?>	
		</td>
		</tr>
<?php 
$sql="SELECT note,addtime,u.name FROM ".PRE."reply r,".PRE."user u WHERE u.id=r.user_id AND pid={$rep['rid']}";
//echo $sql;exit;
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	while($rows=mysql_fetch_assoc($result)){
?>
<tr>
			
			<td  style="text-align:left">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $rows['name']?>&nbsp;&nbsp;<font color="red">回复：</font><?php echo $rows['note']?></td>
			<td><?php echo date('Y-m-d H:i:s',$rows['addtime'])?></td>
			<td></td>
		</tr>	
<?php
	}
}
$image=$img;
?>
	
		

		
		
<?php endforeach;?>	
		<tr>
				<td valign="top" class="fenye" colspan="3">共 <?php echo $total?> 条订单 <?php echo $page?>/<?php echo $amount?> 页&nbsp;&nbsp;<a href="user.php?u=replylist&page=1<?php echo $url?>">首页</a>&nbsp;&nbsp;<a href="user.php?u=replylist&page=<?php echo $prev.$url?>">上一页</a>&nbsp;&nbsp;<a href="user.php?u=replylist&page=<?php echo $next.$url?>">下一页</a>&nbsp;&nbsp;<a href="user.php?u=replylist&page=<?php echo $amount.$url?>">尾页</a></td>
			</tr>
		</table>
</div>