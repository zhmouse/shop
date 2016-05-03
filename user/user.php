<?php
$id=$_GET['id'];
if($_SESSION['admin']['id']!=$id && $_SESSION['home']['id']!=$id && $_SESSION['home']['type']==0 && $_SESSION['admin']['type']=0){
	mass('只有管理员或本人才能查看此页面');
	exit;
}
//$sql="SELECT u.id uid,u.name uname,u.email,u.phone,u.sex,u.regtime,u.lognum,u.avatar,u.credits,u.val,COUNT(o.id) ototal,SUM(og.num) snum,COUNT(r.id) rtotal FROM ".PRE."user u,".PRE."order o,".PRE."reply r,".PRE."order_goods og WHERE u.id=o.user_id AND o.id=og.order_id AND u.id=r.user_id AND id={$id}";
//echo $sql;exit;
$sql="SELECT id,name,email,phone,sex,type,regtime,lognum,avatar,credits,val FROM ".PRE."user WHERE id={$id}";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$user=mysql_fetch_assoc($result);
}
$sql="SELECT COUNT(id) orderNum,SUM(total) total FROM ".PRE."order WHERE user_id={$id}";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$user=array_merge(mysql_fetch_assoc($result),$user);
}

$sql="SELECT COUNT(id) replyNum FROM ".PRE."reply WHERE user_id={$id}";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$user=array_merge(mysql_fetch_assoc($result),$user);
}

$goodsnum=0;
$sql="SELECT id FROM ".PRE."reply WHERE user_id={$id} GROUP BY goods_id";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$goodsnum=mysql_num_rows($result);
	//$user=array_merge(mysql_fetch_assoc($result),$user);
}


//var_dump($user);exit;
$avatar=URL.'avatar/';
$avatar.='96x96_'.$user['avatar'];

$sql="SELECT id,order_id,name,address,addtime,status FROM ".PRE."order WHERE user_id={$id} ORDER BY addtime DESC LIMIT 8";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$orderlist=array();
	while($rows=mysql_fetch_assoc($result)){
		$orderlist[]=$rows;
	}	
}

$sql="SELECT r.id rid,r.note,r.addtime,i.name iname,g.id gid,g.cate_id FROM ".PRE."reply r,".PRE."image i,".PRE."goods g WHERE g.id=r.goods_id AND i.goods_id=r.goods_id AND i.is_face=1 AND user_id={$id} AND pid=0 ORDER BY addtime DESC LIMIT 8";
//echo $sql;exit;
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$replylist=array();
	while($rows=mysql_fetch_assoc($result)){
		$replylist[]=$rows;
	}	
}

$image='';
//var_dump($replylist);exit;
?>
<div class="main fr">		
		<h3>个人资料</h3>
		<table width="100%" align="center" cellpadding="0" cellspacing="0">			
			<tr>
				<td>用户名：<font color="green" size="4"><?php echo $user['name']?></font></td><td rowspan="7"><img src="<?php echo $avatar?>" style="border-radius:5px"></td>			
			</tr>
			<tr>								
				<td>级别：
				<?php 
				switch($user['type']){
					case '0':
						echo '普通用户';
						break;
					case '1':
						echo '普通管理员';
						break;
					case '2':
						echo '超级管理员';
						break;
						
				}
				?>
				</td>
			</tr>
			<tr>								
				<td>性别：
				<?php 
				switch($user['sex']){
					case '0':
						echo '女';
						break;
					case '1':
						echo '男';
						break;
					case '2':
						echo '保密';
						break;
						
				}
				?>
				</td>
			</tr>
			<tr>
				<td>Email：<?php echo $user['email']?></td>								
			</tr>
			<tr>		
				<td>电话：<?php echo $user['phone']?></td>
			</tr>
			<tr>		
				<td>积分：<?php echo $user['credits']?>&nbsp;&nbsp;共对&nbsp;<?php echo $goodsnum?>&nbsp;个商品进行了&nbsp;<?php echo $user['replyNum']?>&nbsp;条评价</td>
			</tr>
			<tr>		
				<td>订单：<?php echo $user['orderNum']?>&nbsp;&nbsp;共计&nbsp;&nbsp;<font color="red" size="4">&yen;<?php echo number_format($user['total'],2)?></font></td>
			</tr>
			<tr>			
				<td>注册时间：<?php echo date('Y-m-d H:i:s',$user['regtime'])?>&nbsp;&nbsp;共登录<?php echo $user['lognum']?>次</td>
			</tr>
		</table>
		<ul class="user_val">
			<li class="<?php echo $user['val']>=1?'light':'gray'?>">LV1</li>
			<li class="<?php echo $user['val']>=2?'light':'gray'?>">LV2</li>
			<li class="<?php echo $user['val']>=3?'light':'gray'?>">LV3</li>
			<li class="<?php echo $user['val']>=4?'light':'gray'?>">LV4</li>
			<li class="<?php echo $user['val']>=5?'light':'gray'?>">LV5</li>
			<div class="clear"></div>
		</ul>
</div>
<div class="mainlist fr mt40" style="border:1px solid #dfdfdf;">
	<h4>
	<span id="de" onclick="display('detail');display('reply');this.style.borderTop='2px solid #CB351A';document.getElementById('re').style.borderTop='2px solid #dfdfdf';this.style.backgroundColor='#f9f9f9';document.getElementById('re').style.backgroundColor='#dfdfdf';" style="border-top:2px solid #CB351A;background-color:#f9f9f9">最近订单</span>
	<span id="re" onclick="display('reply');display('detail');this.style.borderTop='2px solid #CB351A';document.getElementById('de').style.borderTop='2px solid #dfdfdf';this.style.backgroundColor='#f9f9f9';document.getElementById('de').style.backgroundColor='#dfdfdf';" style="border-top:2px solid #dfdfdf;background-color:#dfdfdf">最近评价</span>
	</h4>
	<table width="100%" align="center" cellpadding="0" cellspacing="0" id="detail" style="position:relative">
		<tr>
			
			<th width="100">订单编号</th>
			<th width="80">收货人</th>
			<th>收货地址</th>
			<th width="160">购买时间</th>
			<th width="100">订单状态<a href="user.php?u=myorder&id=<?php echo $id?>" class="lightbtn">全部订单</a></th>
		</tr>
<?php foreach($orderlist as $ord): ?>
		<tr>
			
			<td  style="text-align:left"><a href="user.php?u=detail&id=<?php echo $ord['id']?>"><?php echo $ord['order_id']?></a></td>
			<td><?php echo $ord['name']?></td>
			<td  style="text-align:left"><?php echo str_replace(',','',$ord['address'])?></td>
			<td style="text-align:left"><?php echo date('Y-m-d H:i:s',$ord['addtime'])?></td>
			<td>
			<?php 
					switch($ord['status']){
						case '6':
							echo '订单已取消';
							break;						
						case '1':
							echo '等待买家付款';
							break;
						case '2':
							echo '<font color="red">已付款未发货</font>';
							break;
						case '3':
							echo '等待买家收货';
							break;
						case '4':
							echo '买家已收货';
							break;
						case '5':
							echo '买家已评价';
							break;
					}	
					?>
					
			</td>
		</tr>
		
<?php endforeach;?>
	</table>
	
	<table width="100%" align="center" cellpadding="0" cellspacing="0" id="reply" style="display:none;position:relative">
		<tr>
			
			<th style="text-align:left">评价内容</th>
			<th width="160">评价时间<a href="<?php echo URL.'user.php?u=replylist&id='.$id?>" class="lightbtn" style="position:absolute;top:-32px;right:0">全部评价</a></th>
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
			
			<td  style="text-align:left"><a  style="text-align:left;background:url(images/bg.png) no-repeat 6px -33px;padding-left:27px" href="<?php echo URL.'view.php?id='.$rep['cate_id'].'&gid='.$rep['gid'];?>"><?php echo $rep['note']?></a></td>
			<td><?php echo date('Y-m-d H:i:s',$rep['addtime'])?></td>
			<td>
			<?php if($img!=$image):?>
				<a href="<?php echo URL.'view.php?id='.$rep['cate_id'].'&gid='.$rep['gid'];?>"><img src="<?php echo $img?>"></a>
			<?php endif;?>	
			</td>
			
		</tr>
<?php $image=$img;?>
<?php endforeach;?>
	</table>
</div>


