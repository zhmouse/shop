<?php require 'header.php';

$cid= !empty($_GET['id'])?$_GET['id']:'';
$gid = $_GET['gid'];
$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];



//简单商品浏览数
$sql = "UPDATE s36_goods SET views=views+1 WHERE id={$gid}";
mysql_query($sql);

//路径
$sql="SELECT concat(path,id) bpath FROM ".PRE."category WHERE id={$cid}";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $row = mysql_fetch_assoc($result);
    $bpath = $row['bpath'];
}

//面包导航
$sql = "SELECT id,name FROM ".PRE."category WHERE id in({$bpath}) ORDER BY FIND_IN_SET(id,'{$bpath}')";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $nav_list =array();
    while($row = mysql_fetch_assoc($result)){
        $nav_list[]=$row;
    }
}

//查询商品信息
$sql="SELECT id,name,price,stock,sell,`describe`,views FROM ".PRE."goods WHERE id={$gid} AND stock>0 AND status=1";
//echo $sql;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $row = mysql_fetch_assoc($result);
}
$limit = $row['stock'];//库存限制
//var_dump($row);
//查询图片信息
$sql="SELECT id,name,is_face FROM ".PRE."image WHERE goods_id={$gid} ORDER BY is_face DESC LIMIT 6";
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $img_list =array();
    while($rows = mysql_fetch_assoc($result)){
        $img_list[]=$rows;
    }
}
//var_dump($img_list);

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

//新品
$sql = "SELECT g.id gid,g.name gname,g.cate_id,g.price,i.id iid, i.name iname FROM ".PRE."goods g,".PRE."image i WHERE g.id=i.goods_id AND i.is_face=1 AND g.stock>0 AND g.status=1 AND is_new=1 AND g.cate_id in({$id_list}) ORDER BY addtime DESC LIMIT 3";
//echo $sql;exit;
$result =mysql_query($sql);
if($result && mysql_num_rows($result)>0){
    $new_list = array();
    while($rownew=mysql_fetch_assoc($result)){
        $new_list[]=$rownew;
    }
}


?>
<?php 
					$sql="SELECT r.id,r.pid,r.path,r.order_id,r.status,r.note,r.addtime,u.avatar,u.name  FROM ".PRE."reply r,".PRE."user u WHERE u.id=r.user_id AND r.goods_id={$gid} ORDER BY concat(r.path,r.id)";
					$result=mysql_query($sql);
					if($result && mysql_num_rows($result)>0){
						$replylist=array();
						while($rowsreply= mysql_fetch_assoc($result)){
							$replylist[]=$rowsreply;
						}
					}
					$a=0;
					$b=0;
					$c=0;
					$renum=count($replylist);
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
		<!--主体开始-->
		<div id="main">
			<!--面包屑导航开始-->
			<div class="nav w" >
				<p>
					<a href="./">首页</a>
					<?php foreach($nav_list as $val){?>
					&nbsp;&nbsp;>&nbsp;&nbsp;<a href="catelist.php?id=<?php echo $val['id']?>"><?php echo $val['name']?></a>
					<?php }?>
					&nbsp;&nbsp;>&nbsp;&nbsp;<?php echo $row['name']?>
				</p>			
			</div>
			<!--面包屑导航结束-->
			
			<div class="productInfo w">
				<div class="preview fl">
					<div class="picZoomer">
						<?php 
						$img_url = UPLOAD_URL;
						$img_url .=substr($img_list[0]['name'],0,4).'/';
						$img_url .=substr($img_list[0]['name'],4,2).'/';
						$img_url .=substr($img_list[0]['name'],6,2).'/';
						//$img_url .=$img_list[0]['name'];
						$img_url .=$size[4].'x'.$size[5].'_'.$img_list[0]['name'];					
						?>
						<img src="<?php echo $img_url?>" alt="">
					</div>
					<ul class="piclist">
						<?php foreach($img_list as $val){ 
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['name'],0,4).'/';
						$img_url .=substr($val['name'],4,2).'/';
						$img_url .=substr($val['name'],6,2).'/';
						$src =$img_url.$val['name'];
						$info=getimagesize($src);
						//var_dump($info);exit;
						
						if($info[0]!=$info[1]){
							continue;
						}
						//echo $info[0].'---'.$info[1];exit; 
						$small=$img_url.$size[0].'x'.$size[1].'_'.$val['name'];
						
						?>
						<li><img src="<?php echo $small?>" alt=""></li>
						<?php }?>
					</ul>
				</div>
				<div class="goodsName fr">
					<h1><?php echo $row['name']?></h1>
					<span><?php echo utf8substr($row['describe'],0,60)?></span>
				</div>
				<div class="goodsPay fl">				
					<ul>
						<li class="bgGray">
							<div>
							<p class="price">市 场 价：<span>&yen;<?php echo $row['price']?></span></p>
							<p class="views">商品浏览：<span><?php echo $row['views']?></span>&nbsp;&nbsp;商品评论：<span><?php echo $renum ?></span></p>
							</div>
						</li>
						<form method="post" action="do_cart.php?a=add">
						<input type="hidden" name="gid" value="<?php echo $row['id']?>">
						<li class="sumNum">
							<div class="dt">购买数量：</div>
							<div class="dd">
								<a style="border-right:none;" onclick="changeCount('#num',-1)">-</a>
								<input name="num" id="num"  type="text" value="1" maxlength="3" onkeyup="onlyNumber(this);" autocomplete="off"/>
								<a style="border-left:none;" onclick="changeCount('#num',1)">+</a>
								<span>（库存<span class="stock"><?php echo $row['stock']?></span>件&nbsp;&nbsp;已销售<span class="stock"><?php echo $row['sell']?></span>件）</span>
								<p id="msg"></p>
							</div>
						</li>
						<li class="buyBtn" >
							<input type="submit" value="加入购物车">
						</li>	
						<div class="clear"></div>
						</form>
					</ul>           								
				</div>			
				<div class="clear"></div>			
			</div>
			<div class="detail w mt40">
				<div class="new fl">
				<h3>推荐新品</h3>
				<ul>
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
						<p><?php echo utf8substr($val['gname'],0,30)?></p>
					</li>
					<?php }?>
					<div class="clear"></div>
				</ul>
				</div>
				<div class="reply fr">
				
					<h3>
						<span id="de" onclick="display('detail');display('reply');this.style.borderTop='2px solid #CB351A';document.getElementById('re').style.borderTop='2px solid #dfdfdf';" style="border-top:2px solid #CB351A">图文详情</span>
						<span id="re" onclick="display('reply');display('detail');this.style.borderTop='2px solid #CB351A';document.getElementById('de').style.borderTop='2px solid #dfdfdf';" style="border-top:2px solid #dfdfdf">商品评价&nbsp;&nbsp;&nbsp;&nbsp;<font color="red" size="3">好评 (<?php echo $a?>)</font>&nbsp;&nbsp;&nbsp;&nbsp;<font color="green" size="3">中评 (<?php echo $b?>)</font>&nbsp;&nbsp;&nbsp;&nbsp;<font color="blue" size="3">差评 (<?php echo $c?>)</font></span>
<!--------------------------------->

						

						
						<?php
					$uid = $_SESSION['home']['id'];
					$sql="SELECT od.id oid,od.status FROM ".PRE."order od,".PRE."order_goods og WHERE od.id=og.order_id AND od.user_id={$uid} AND og.goods_id={$gid} AND od.status>3";
					//echo $sql;
					$result= mysql_query($sql);
					if($result && mysql_num_rows($result)>0):
						$rep=mysql_fetch_assoc($result);
						$oid=$rep['oid'];
						$status=$rep['status'];
						if($status==5){
							$reply='追加评价';$class='graybtn';
						}else{
							$reply='我要评价';$class='lightbtn';
						}					
						?>					
					<a href="user.php?u=reply&id=<?php echo $gid?>&oid=<?php echo $oid?>" class="<?php echo $class?>"><?php echo $reply?></a>
					<?php endif;?>					
						
						
						
						
						
<!---------------------------------->						
						
						<div class="clear"></div>
					
					</h3>
					<ul class="rep" id="reply" style="display:none">					
					<?php
					$d=1;
					foreach($replylist as $val):
					$img_url=URL.'avatar/';
					$img_url.='64x64_'.$val['avatar'];
					if($val['pid']==0)://不使用多级回复只用pid判断
					?>	
		<!----------------user------------->

						<li>
							<p>
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
				
							</p>
							<p><?php echo $val['note']?></p>							
							<img src="<?php echo $img_url?>" style="border-radius:5px">
						</li>
		<!-----------user--------------->
		<?php else:?>
		<!-----------admin--------------->
						<li>
							<p><font color="red"><?php echo $val['name']?></font> 在 <?php echo date('Y-m-d H:i:s',$val['addtime'])?>回复。</p>
							<p><font color="red"><?php echo $val['note']?></font></p>
						</li>
		<!-----------admin--------------->
		<?php endif;?>
						<?php if($_SESSION['home']['type']>0 && $val['pid']==0):?>
							<li>
							<p><a class="replybtn" onclick="display('review<?php echo $d?>')">管理员回复</a></p>							
							</li>
							<li class="view_reply" id="review<?php echo $d?>" style="display:none">		
							<form method="post" action="do_user.php?a=readmin">
							<input type="hidden" name="gid" value="<?php echo $gid?>">
							<input type="hidden" name="oid" value="<?php echo $val['order_id']?>">
							<input type="hidden" name="pid" value="<?php echo $val['id']?>">
							<input type="hidden" name="path" value="<?php echo $val['path']?>">
							<input type="hidden" name="url" value="<?php echo $url?>">
							<p><textarea rows="3" cols="50" name="note" placeholder="回复内容"></textarea></p>
							<p><input type="submit" value="提交">
							<input type="reset" value="重置"></p>
							</form>
							</li>								
							<?php endif;?>
							<?php $d++?>
					<?php endforeach;?>		
					</ul>
					<div id="detail">
						<?php echo $row['describe']?>
						<?php 
						array_shift($img_list);
						foreach($img_list as $val){ 
						$img_url = UPLOAD_URL;
						$img_url .=substr($val['name'],0,4).'/';
						$img_url .=substr($val['name'],4,2).'/';
						$img_url .=substr($val['name'],6,2).'/';
						$img_url .=$val['name'];
						//$img_url .=$size[0].'x'.$size[1].'_'.$val['name'];						
						?>
						<img src="<?php echo $img_url?>" alt="" style="width:800px;border-radius:5px">
						<?php }?>
					
					
					
						
					</div>
				</div>
				<div class="clear"></div>			
			</div>
		</div>
		<!--主体结束-->
<?php require 'footer.php';require 'num.php'?>
		