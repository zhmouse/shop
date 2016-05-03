<?php

require '../init.php';

//订单状态
$sql="SELECT status,COUNT(id) num FROM ".PRE."order WHERE status>0 GROUP BY status";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$status=array();
	while($rows=mysql_fetch_assoc($result)){
		$status[]=$rows;
	}
}


//var_dump($status);exit;
//有效订单
$sql="SELECT COUNT(id) num FROM ".PRE."order WHERE status>0 AND status<6";
$result = mysql_query($sql);
if($result && mysql_num_rows($result)){
	$ord=mysql_fetch_assoc($result);
}
foreach($status as $val){
switch($val['status']){						
		case '1':
			$s1=$val['num']/$ord['num']*360;
			$t1='等待买家付款（'.$val['num'].'）';
			break;
		case '2':
			$s2=$val['num']/$ord['num']*360;
			$t2='已付款未发货（'.$val['num'].'）';
			break;
		case '3':
			$s3=$val['num']/$ord['num']*360;
			$t3='等待买家收货（'.$val['num'].'）';
			break;
		case '4':
			$s4=$val['num']/$ord['num']*360;
			$t4='买家已收货（'.$val['num'].'）';
			break;
		case '5':
			$s5=$val['num']/$ord['num']*360;
			$t5='买家已评价（'.$val['num'].'）';
			break;
	}
}

arcimage($s1,$s2,$s3,$s4,$s5,$t1,$t2,$t3,$t4,$t5);