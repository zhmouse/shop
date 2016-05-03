<?php
if(file_exists('install.php')){
	header('location:install.php');
}
require './header.php';

//热销
$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_hot=1 ORDER BY addtime DESC LIMIT 4";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $hot_list = array();
    while($row=mysql_fetch_assoc($result)){
        $hot_list[]=$row;
    }
}

//新品
$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_new=1 ORDER BY addtime DESC LIMIT 15";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $new_list = array();
    while($row=mysql_fetch_assoc($result)){
        $new_list[]=$row;
    }
}

//精品
$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_best=1 ORDER BY addtime DESC LIMIT 10";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $best_list = array();
    while($row=mysql_fetch_assoc($result)){
        $best_list[]=$row;
    }
}

//推送
$sql = "SElECT id,title,image,goods_id,cate_id,status,`describe`,addtime FROM ".PRE."send WHERE status=1 ORDER BY addtime DESC LIMIT 3";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $send_list = array();
    while($row=mysql_fetch_assoc($result)){
        $send_list[]=$row;
    }
}
?>
		<!--主体开始-->
		<div id="main">
			<!-- 商品推送开始-->
			
			<div class="adv">				
				<ul class="bxslider">
				<?php foreach($send_list as $val):
				$img_url=UPLOAD_URL;
				$img_url .=substr($val['image'],0,4).'/';
				$img_url .=substr($val['image'],4,2).'/';
				$img_url .=substr($val['image'],6,2).'/';
				$img_url .='1200x320_'.$val['image'];
				$bg=imgColor($img_url);
				$bg=rgb2html($bg['r'],$bg['g'],$bg['b']);
				?>
					<li style="background:#FCF2E9;background:<?php echo $bg?>;" >
						<a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['goods_id']?>">
						<div style="background:url('<?php echo $img_url?>') no-repeat;width:1200px;height:320px;margin:0 auto">
						<p style="width:360px;height:244px;font-size:14px;padding:20px;color:#ccc;overflow:hidden"><?php echo $val['describe']?></p>
						<p style="width:1140px;height:36px;line-height:36px;font-size:15px;color:#fff;padding:0 30px; background:#666;opacity: .5"><?php echo $val['title']?></p>
						<!--<a href="view.php?id=<?php //echo $val['cate_id']?>&gid=<?php //echo $val['goods_id']?>">
							<img src="<?php //echo $img_url?>">
							<div style="width:320px;height:320px;position:absolute;top:20;center:-600px;z-index:3000;padding:20px;background: #666;opacity: .8;"><?php //echo $val['describe']?></div>
						</a>-->
						</div>
						</a>
					</li>
				<?php endforeach;?>
				</ul>
			</div>
			
			<!-- 商品推送结束-->
						
			<!-- 主体推荐开始-->
			<div class="top w mt40">
				<ul>
					<?php
					foreach($hot_list as $val){
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['iname'],0,4).'/';
						$img_url .=substr($val['iname'],4,2).'/';
						$img_url .=substr($val['iname'],6,2).'/';
						$img_url .=$size[2].'x'.$size[3].'_'.$val['iname'];
						
					?>				
					<li>
						<a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><img src="<?php echo $img_url?>" title="<?php echo $val['gname']?>"></a>
						<p><?php echo utf8substr($val['gname'],0,30)?></p>
					</li>
					<?php }?>
					<div class="clear"></div>
				</ul>
			</div>
			<!-- 主体推荐结束-->
			
			<!-- 分类循坏开始-->
			<div class="title w mt40">
				<h2 class="fl">初夏新品</h2>
				<a href="catelist.php?id=new" class="fr"> 查看更多>></a>
				<div class="clear"></div>
			</div>
			<div class="list">
				<ul>
					<!-- 列表循坏开始-->
					<?php
					foreach($new_list as $val){
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['iname'],0,4).'/';
						$img_url .=substr($val['iname'],4,2).'/';
						$img_url .=substr($val['iname'],6,2).'/';
						$img_url .=$size[2].'x'.$size[3].'_'.$val['iname'];
						
					?>
					<li>

						<a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><img src="<?php echo $img_url?>" title="<?php echo $val['gname']?>"></a>
						<p><a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>" ><?php echo utf8substr($val['gname'],0,30)?></a></p>
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
			<div class="title w mt40">
				<h2 class="fl">精益求精</h2>
				<a href="catelist.php?id=best" class="fr"> 查看更多>></a>
				<div class="clear"></div>
			</div>
			<div class="list">
				<ul>
					<!-- 列表循坏开始-->
					<?php
					foreach($best_list as $val){
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['iname'],0,4).'/';
						$img_url .=substr($val['iname'],4,2).'/';
						$img_url .=substr($val['iname'],6,2).'/';
						$img_url .=$size[2].'x'.$size[3].'_'.$val['iname'];
						
					?>
					<li>

						<a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><img src="<?php echo $img_url?>" title="<?php echo $val['gname']?>"></a>
						<p><a href="view.php?id=<?php echo $val['cate_id']?>&gid=<?php echo $val['gid']?>"><?php echo utf8substr($val['gname'],0,30)?></a></p>
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
			<!-- 分类循环结束-->
			
			
			
			<!--底部广告开始-->
			<!--<div class="bottom">
				<ul>
					<li><a href="#"><img src="./uploads/adv0.png"></a></li>
					<li><a href="#"><img src="./uploads/adv1.png"></a></li>
					<li><a href="#"><img src="./uploads/adv2.png"></a></li>
					<div class="clear"></div>
				</ul>			
			</div>-->
			<!--底部广告结束-->
		</div>
		<!--主体结束-->



<?php require './footer.php';?>