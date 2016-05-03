<?php
$id=$_GET[id];
$oid=$_GET[oid];
$uid= $_SESSION['home'][id];
$sql="SELECT g.id gid,g.name gname,g.cate_id,i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.id={$id}";

$url=!empty($_GET[url])?$_GET[url]:'';
$refererUrl = !empty($url)?$url:$_SERVER['HTTP_REFERER'];

//echo $sql;exit;
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$row= mysql_fetch_assoc($result);
}

//var_dump($row);exit;
$img_url=UPLOAD_URL;
$img_url.=substr($row['iname'],0,4).'/';
$img_url.=substr($row['iname'],4,2).'/';
$img_url.=substr($row['iname'],6,2).'/';
$img_url.=$size[2].'x'.$size[3].'_'.$row['iname'];
//echo $img_url;exit;
$sql="SELECT r.pid,r.status,r.note,r.addtime,u.avatar,u.name  FROM ".PRE."reply r,".PRE."user u WHERE u.id=r.user_id AND r.goods_id={$id} ORDER BY concat(r.path,r.id)";
$result=mysql_query($sql);
if($result && mysql_num_rows($result)>0){
	$replylist=array();
	while($rows= mysql_fetch_assoc($result)){
		$replylist[]=$rows;
	}
}
$a=0;
$b=0;
$c=0;
foreach($replylist as $n){
	switch($n['status']){
		case 1:
			$a +=1;
		break;
		case 2:
			$b +=1;
		break;
		case 3:
			$c +=1;
		break;
	}
}
?>


<div class="main fr">
	<h3>商品评论</h3>
	<form method="post" action="do_user.php?a=reply">
		<input type="hidden" name="gid" value="<?php echo $id?>">
		<input type="hidden" name="oid" value="<?php echo $oid?>">
		<input type="hidden" name="url" value="<?php echo $refererUrl?>">
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<td>
					<textarea rows="5" cols="80" name="note" placeholder="请在此输入您对商品的使用感受，对其他用户很有帮助哦!"></textarea>
				</td>				
				<td rowspan="2"><a href="<?php echo 'view.php?id='.$row['cate_id'].'&gid='.$row['gid'];?>"><img src="<?php echo $img_url?>" width="160"></a></td>
			</tr>
			<tr>
				<td>
<?php //查询是否已评价
							$sql="SELECT status FROM ".PRE."reply WHERE goods_id={$id} AND order_id={$oid} AND user_id={$uid} AND status>0";
							//echo $sql;exit;
							$result = mysql_query($sql);
							if($result && mysql_num_rows($result)>0):?>
							
								&nbsp;&nbsp;&nbsp;&nbsp;好评 (<?php echo $a?>)&nbsp;&nbsp;&nbsp;&nbsp;中评 (<?php echo $b?>)&nbsp;&nbsp;&nbsp;&nbsp;差评 (<?php echo $c?>)	
							<?php else:?>
							<input type="radio" name="status" value="1">好评 (<?php echo $a?>)
				<input type="radio" name="status" value="2">中评 (<?php echo $b?>)
				<input type="radio" name="status" value="3">差评 (<?php echo $c?>)	
								
							<?php endif;?>
						
						
							
				</td>
			</tr>			
		</table>
		<div>
			<input type="submit" value="提交">
			<input type="reset" value="重置">
			<div class="clear"></div>
		</div>
	</form>
</div>
<div class="mainlist fr mt15">		
		<table width="100%" align="center" cellpadding="0" cellspacing="0">
			<tr>
				<th colspan="2"><a href="<?php echo 'view.php?id='.$row['cate_id'].'&gid='.$row['gid'];?>"><?php echo $row['gname']?></a></th>				
			</tr>
<?php
foreach($replylist as $val):
$img_url=URL.'avatar/';
$img_url.='64x64_'.$val['avatar'];
if($val['pid']==0):
?>			
			<tr>
				<td rowspan="2" width="64"><img src="<?php echo $img_url?>" style="border-radius:5px"></td>
				<td style="text-align:left">				
				<?php 
					switch($val['status']){
						case 1:
							echo '<font color="red" size="3">好评</font><br>';
							break;
						case 2:
							echo '<font color="green" size="3">中评</font><br>';
							break;
						case 3:
							echo '<font color="blue" size="3">差评</font><br>';
							break;
					}
				?>
				<?php echo $val['name']?> 在 <?php echo date('Y-m-d H:i:s',$val['addtime'])?>评价。
				</td>
			</tr>
			<tr>
				<td style="text-align:left"><?php echo $val['note']?></td>
			</tr>
			
			<?php else:?>
			<tr>
				<td rowspan="2" width="64"></td>
				<td style="text-align:left">				
				<font color="red"><?php echo $val['name']?></font> 在 <?php echo date('Y-m-d H:i:s',$val['addtime'])?>回复。
				</td>
			</tr>
			<tr>
				<td style="text-align:left"><font color="red"><?php echo $val['note']?></font></td>
			</tr>
			
			<?php endif;?>
<?php endforeach;?>			
		</table>
</div>


