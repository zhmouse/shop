		<!--底部开始-->
	<div id="foot_header">				  
		<a href="about.php?id=14" target="_blank">
			<img src="./images/zp.jpg">
			<b>正品保障</b>
			<span>正品行货 放心选购</span>
			</a>
		<a href="about.php?id=12" target="_blank">
			<img src="./images/by.jpg">
			<b>满68包邮</b>
			<span>满68元  免运费</span>
			</a>
		<a href="about.php?id=10" target="_blank">
			<img src="./images/wy.jpg">
			<b>售后无忧</b>
			<span>7天无理由退货</span>
			</a>
		<a href="about.php?id=13" target="_blank">
			<img src="./images/zs.jpg">
			<b>准时送达</b>
			<span>收货时间由你做主</span>
		</a>
		<div class="clear"></div>
    </div>		
		
		<div id="footer">
			<P class="about">
	<?php
	$sql = "SElECT id,title,image,status,`describe`,addtime FROM ".PRE."about WHERE status=1 LIMIT 5";
	//echo $sql;exit;
	$result = mysql_query($sql);
	if($result && mysql_num_rows($result)>0){
		$aboutlist = array();
		while($rows=mysql_fetch_assoc($result)){
			$aboutlist[]=$rows;
		}
	}
	foreach($aboutlist as $val):
	?>
	<a href="about.php?id=<?php echo $val['id']?>"><?php echo $val['title']?></a>
	<?php endforeach;?>		
			</P>
			<p>Copyright©上海兄弟连七连（S36）. All Rights Reserved. 京ICP证000000号 <a id="back-to-top" href="#"><img src="./images/top.jpg"></a</p>
		</div>
		<!--底部结束-->		
		<?php require './inc/num.php'?>
	</body>
</html> 