<?php
require '../init.php';
$a=$_GET['a'];
switch($a){
	case 'send'://发货处理
		$oid=$_GET['id'];
		$sql="SELECT o.num,g.stock,g.name,g.id,g.cate_id FROM ".PRE."order_goods o,".PRE."goods g WHERE o.goods_id=g.id AND o.order_id={$oid}";
		$result=mysql_query($sql);
		if($result && mysql_num_rows($result)>0){
			$numlist=array();
			while($rows=mysql_fetch_assoc($result)){
				$numlist[]=$rows;
			}
		}
		foreach($numlist as $val){
			if($val['num']>$val['stock']){
				header('location:stock.php?id='.$oid);
				exit;
			}
		}
			foreach($numlist as $val){
				$sql="UPDATE ".PRE."goods SET stock=stock-{$val['num']},sell=sell+{$val['num']} WHERE id={$val['id']}";
				$result=mysql_query($sql);
				if($result && mysql_affected_rows()>0){
				}else{
					mass('库存扣减失败','#437ccf',0);
					exit;
				}
			}
			$sql="UPDATE ".PRE."order SET status=3 WHERE id={$oid}";
			$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
			}else{
				mass('订单状态更新失败','#437ccf',0);
				exit;
			}
			mass('订单已成功发货！','#437ccf',1,'index.php');
			exit;	
		break;
	case 'del'://订单删除;
		$oid=$_GET['id'];
		$sql="UPDATE ".PRE."order SET status=concat('-',status) WHERE id={$oid}";
		//echo $sql;exit;
		$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('订单已删除！','#437ccf',1,'index.php');
				exit;
			}else{
				mass('订单删除失败！','#437ccf',0);
				exit;				
			}
		break;
	case 'restore'://订单还原
		$oid=$_GET['id'];
		$sql="UPDATE ".PRE."order SET status=abs(status) WHERE id={$oid}";
		//echo $sql;exit;
		$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('订单已还原！','#437ccf',1,'index.php');
				exit;
			}else{
				mass('订单还原失败！','#437ccf',0);
				exit;				
			}
		break;
	case 'sdelete'://彻底删除
		$oid=$_GET['id'];
		$sql="DELETE FROM ".PRE."order WHERE id={$oid}";
		//echo $sql;exit;
		$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('订单已彻底删除！','#437ccf',1,'recycle.php');
				exit;
			}else{
				mass('订单删除失败！','#437ccf',0);
				exit;				
			}
		break;
	case 'delete'://清空回收站
		$oid=$_GET['id'];
		$sql="DELETE FROM ".PRE."order WHERE status<0";
		//echo $sql;exit;
		$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('回收站已清空！','#437ccf',1,'recycle.php');
				exit;
			}else{
				mass('回收站清空失败！','#437ccf',0);
				exit;				
			}
		break;
	case 'no'://订单取消;
		$oid=$_GET['id'];
		$sql="UPDATE ".PRE."order SET status=6 WHERE id={$oid}";
		//echo $sql;exit;
		$result=mysql_query($sql);
			if($result && mysql_affected_rows()>0){
				mass('订单已取消！','#437ccf',1,'index.php');
				exit;
			}else{
				mass('订单取消失败！','#437ccf',0);
				exit;				
			}
		break;
	
}