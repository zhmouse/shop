<?php

/**
	文件上传函数
	@date  2015-11-10
	@author  Xuzhiyi
	@param $name 表单的name属性值
	@param $dir 上传成功保存的目录
	@param $a 是否上传头像 默认0
	@param $allow_type  允许上传的类型
	@return  文件上传成功返回文件全路径数组 上传失败 返回false
 */ 
function upload($name='pic',$dir= './uploads',$a=0,$allow_type=array('jpg','jpeg','gif','png')){
	for($i=0;$i<count($_FILES[$name]['error']);$i++){
		//遇到无图片退出循环
		if(empty($_FILES[$name]['name'][$i])){
			break;
		}
        //1.判断错误
        if($_FILES[$name]['error'][$i]>0){
            echo '上传错误！';
            return false;
        }
        //2.获取文件后缀名
	
        $suffix = strrchr($_FILES[$name]['name'][$i],'.');
        $type=ltrim($suffix,'.');
	
        //3上传的文件类型是不是在我们要求的类型中
        if(!in_array($type,$allow_type)){
            echo '不允许上传'.$type.'类型的文件';
            return false;
        }

        //4.产生一个新的文件名
        $filename =date('Ymd').uniqid().mt_rand(0,9999).$suffix;

        //5.保存目录是否存在
        $save_path = rtrim($dir,'/');
        $save_path .='/';
		if($a==0){//判断是否上传头像
			$save_path .=date('Y/m/d');
		}
        if(!file_exists($save_path)){
            mkdir($save_path,0777,true);
        }
        $path =$save_path.'/'.$filename;
        
        if(!is_uploaded_file($_FILES[$name]['tmp_name'][$i])){
            echo '不是上传的文件！';
            return false;
        }
        if(!move_uploaded_file($_FILES[$name]['tmp_name'][$i],$path)){
            echo '文件上传失败!';
            return false;
        }
		/*
		//获取图片大小
		$image_info=getimagesize($path);
		//图片>1280,则缩小
		if($image_info[0]>1280){
			zoom($path);
		}
		//图片>600,则加水印
		if($image_info[0]>600){
			water_text($path);		
		}
		*/
		$arr[]=$path;
    }
	return $arr;       
}

//图片缩小
function zoom($image,$width=1280,$height=1280,$path= UPLOAD_PATH){
	//拿到一张图的所有资源信息
	$info=getimagesize($image);
	if(!$info){
		exit('不是图片退出');
	}
	$str=$info['mime'];
	$arr=explode('/',$str);
	//获取文件类型
	$ext=$arr[1];
	//拼接函数：创建和保存
	$create = 'imagecreatefrom'.$ext;
	$save='image'.$ext;
	//打开图片
	$img=$create($image);
	if($info[0]>$info[1]){
		$dw=$width;
		$pre = $width.'_';
		$dh=$info[1]*$width/$info[0];
	}else{
		$dh=$height;
		$pre =$height.'_';
		$dw=$info[0]*$height/$info[1];
	}
	$simg=imagecreatetruecolor($dw,$dh);
	imagecopyresampled(
		$simg,
		$img,
		0,0,0,0,
		$dw,$dh,$info[0],$info[1]
	);
	//获取原文件名
	/*
	$arr=explode('/',$image);
	$name=array_pop($arr);
	$save($simg,$path.$pre.$name);
	imagedestroy($simg);
	imagedestroy($img);
	
	$name=pathinfo($image,PATHINFO_FILENAME);
	$extension=pathinfo($image,PATHINFO_EXTENSION);
	$pre = '_'.$width.'x'.$height;
	$save($simg,$path.$name.$pre.'.'.$extension);
	imagedestroy($simg);
	imagedestroy($img);
	*/
	$filename = pathinfo($image,PATHINFO_BASENAME);
	$save($simg,$image);
	imagedestroy($simg);
	imagedestroy($img);
	return $filename;
}

/**
	图片缩略图函数
	@date  2015-11-10
	@author  Xuzhiyi
	@param $image 要处理的图片路径
	@param $width=350 略缩图宽度
	@param $height=350  略缩图高度
	@return  返回缩略图全路径，失败返回false
 */ 
function thumb($image,$width=350,$height=350){
	//拿到一张图的所有资源信息
	$image_info=getimagesize($image);
	if(!$image_info){
		echo '图片不存在！';
		return false;
	}
	$str=$image_info['mime'];
	$arr=explode('/',$str);
	//获取文件类型
	$ext=$arr[1];
	//拼接函数：创建和保存
	$create = 'imagecreatefrom'.$ext;
	$save='image'.$ext;
	//打开图片
	$img=$create($image);
	if( ($width/$height) >= ($image_info[0]/$image_info[1]) ){	
		$mw = $image_info[0];
		$mh = $height*$image_info[0]/$width;
		$dx = 0;
		$dy = ($image_info[1]- $mh)/2;			
	}else{		
		$mw = $width/$height*$image_info[1];
		$mh = $image_info[1];
		$dx = ($image_info[0]- $mw)/2;
		$dy = 0;
	}
	$simg=imagecreatetruecolor($width,$height);
	imagecopyresampled(
		$simg,
		$img,
		0,0,$dx,$dy,
		$width,$height,$mw,$mh
	);
	
	$path=dirname($image).'/'.$width.'x'.$height.'_'.basename($image);
	
	$result=$save($simg,$path);
	imagedestroy($simg);
	imagedestroy($img);
	if(!$result){
		unlink($path);
		echo '图片缩放失败！';
		return false;
	}
	return $path;
}


/**
	图片右下角文字水印函数
	@date  2015-11-10
	@author  Xuzhiyi
	@param $image 要处理的图片路径
	@param $size 水印文字大小
	@param $angle 水印文字角度
	@param $font 字体文件路径
	@param $text 水印文本
	@return  返回文件名
 */ 
function water_text($image,$size=20,$angle=0,$font='4.ttf',$text='兄弟连新手'){
	//拿到要加水印这幅图片的信息
	$image_info=getimagesize($image);
	if(!$image_info){
		exit('图片不存在！');
	}
	switch($image_info[2]){
		case 1://gif
			$img=imagecreatefromgif($image);
			$type='imagegif';
			break;
		
		case 2://jpeg
			$img =imagecreatefromjpeg($image);
			$type='imagejpeg';
			break;
		case 3://png
			$img = imagecreatefrompng($image);
			$type='imagepng';
			break;
	}
	
	$x=$image_info[0]-$size*strlen($text)*1.5/3;
	$y=$image_info[1]-$size*1.5;
	//水印颜色
	$white = imagecolorallocate($img,255,255,255);
	imagettftext($img,$size,$angle,$x,$y,$white,FONT_PATH.$font,$text);
	//保存路径
	$filename= pathinfo($image,PATHINFO_FILENAME).'_wt'.'.'.pathinfo($image,PATHINFO_EXTENSION);
	$path = pathinfo($image,PATHINFO_DIRNAME).'/'.$filename;
	$type($img,$path);
	imagedestroy($img);
	return $filename;
}

/**
	图片水印函数
	@date  2015-11-10
	@author  Xuzhiyi
	@param $image 要处理的图片路径
	@param $water 水印图片的路径
	@param $pos 要放置的九个位置
	@param $pct 水印透明度
	@return 返回文件名
 */ 
function water_pic($image='../images/6.jpg',$water='../images/5.png',$pos =1,$pct=20){
	//拿到要加水印这幅图片的信息
	$image_info=getimagesize($image);
	switch($image_info[2]){
		case 1://gif
			$img=imagecreatefromgif($image);
			$type='imagegif';
			break;
		
		case 2://jpeg
			$img =imagecreatefromjpeg($image);
			$type='imagejpeg';
			break;
		case 3://png
			$img = imagecreatefrompng($image);
			$type='imagepng';
			break;
	}
	//拿到水印图片所有信息
	$water_info=getimagesize($water);
	switch($water_info[2]){
		case 1:
			$wimg=imagecreatefromgif($water);
			break;
		case 2:
			$wimg=imagecreatefromjpeg($water);
			break;
		case 3:
			$wimg=imagecreatefrompng($water);
			break;			
	}
	//确定水印位置
	$width =$image_info[0];
	$height =$image_info[1];
	switch($pos){
		case 1:
			$x=0;
			$y=0;
			break;
		case 2:
			$x=$width/3;
			$y =0;
			break;
		case 3:
			$x=$width/3*2;
			$y=0;
			break;
		case 4:
			$x=0;
			$y=$height/3;
			break;
		case 5:
			$x=$width/3;
			$y=$height/3;
			break;
		case 6:
			$x=$width/3*2;
			$y=$height/3;
			break;
		case 7:
			$x=0;
			$y=$height/3*2;
			break;
		case 8:
			$x=$width/3;
			$y=$height/3*2;
			break;
		case 9;
			$x=$width/3*2;
			$y=$height/3*2;
			break;
		default:
			$x= mt_rand(0,$width);
			$y=mt_rand(0,$height);
	}
	imagecopymergegray(
		$img,
		$wimg,
		$x,$y,
		0,0,
		$water_info[0],
		$water_info[1],
		$pct
	);
	$filename= pathinfo($image,PATHINFO_FILENAME).'_wp'.'.'.pathinfo($image,PATHINFO_EXTENSION);
	$path = pathinfo($image,PATHINFO_DIRNAME).'/'.$filename;
	$type($img,$path);
	imagedestroy($img);
	imagedestroy($wimg);
	return $filename;
}

/**
	验证码函数
	@date  2015-11-10
	@author  Xuzhiyi
	@param $width 宽度
	@param $height 高度
	@param $num 字符个数
	@param $angle 字符倾斜
	@param $size 字符尺寸
	@param $type  验证码类型
	@param $font  验证码字体	
	@return  返回输出验证码图片
*/

function vcode($width=110,$height=36,$num=4,$angle=10,$size=18,$type=4,$font='4.ttf'){
	//1.创建画布
	$img = imagecreatetruecolor($width,$height);
	//2.分配颜色
	//3.填充背景
	imagefill(
		$img,
		0,0,
		imagecolorallocate($img,mt_rand(130,255),mt_rand(130,255),mt_rand(130,255))
	);
	//4.挥刀自宫
	//画干扰点
	for($i=0;$i<mt_rand(100,200);$i++){
		imagesetpixel(
			$img,
			mt_rand(0,$width),
			mt_rand(0,$height),
			imagecolorallocate($img,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120))            
		);
	}
	//画干扰线
	for($i=0;$i<mt_rand(0,10);$i++){
		imageline(
			$img,
			mt_rand(0,$width),
			mt_rand(0,$height),
			mt_rand(0,$width),
			mt_rand(0,$height),
			imagecolorallocate($img,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120))
		);
	}
	//写字
	switch($type){
		case 1:
			if($num>10){
				$num=10;
			}
            $str ='1234567890';
			break;
		case 2:
			if($num>26){
				$num = 26;
			}
			$str='qwertyuiopasdfghjklzxcvbnm';
			break;
		case 3:
			if($num>26){
				$num = 26;
			}
			$str='QWERTYUIOPASDFGHJKLZXCVBNM';
			break;
		case 4:
			if($num>62){
				$num =62;
			}
			$str ='23456789qwertyupasdfghjkzxcvbnmQWERTYUPASDFGHJKZXCVBNM';
			break;
	}

	$str =str_shuffle($str);
	$str =substr($str,0,$num);
	$_SESSION['vcode']=$str;
	$w = $width/$num;
	for($i=0;$i<$num;$i++){
		$x =$i*$w+5;
		$y =mt_rand($size,$height);
		imagettftext($img,$size,mt_rand(-$angle,$angle),$x,$y,
			imagecolorallocate($img,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120)),FONT_PATH.$font,$str{$i}
		);
	}
	//5.保存输出
	header("content-type:image/png;");
	imagepng($img);
	//6.销毁资源
	imagedestroy($img);
}

/**
	消息显示
	@date  2015-11-15
	@author  Xuzhiyi
	@param $content 消息内容
	@param $state 状态信息 0失败 1成功
	@param $url  跳转地址
	@param $color 颜色
	@param $auto 是否自动跳转，0 不跳转 1跳转
	@return  无返回 直接输出
*/
function mass($content,$color='#CB351A',$state=0,$url='javascript:history.back()',$auto=1){
?>
<!doctype html>
<html>
	<head>
		<title>提示消息</title>
		<meta charset="UTF-8">
		<style>
			body{background:#f6f6f6}
			div{background:#fff;padding:0;position:absolute;top:50%;left:50%;width:400px;height:180px;margin-left:-200px;margin-top:-90px;box-shadow:1px 1px 5px grey;}
			h3{width:350px;float:left;margin:0;height:40px;line-height:40px;padding:0 10px 0 40px;font-size:16px;background:<?php echo $color ?> url('<?php echo URL; ?>images/<?php echo $state ?>.gif') no-repeat 10px center;color:#fff}
			p{padding:10px}
			.middle{float:left;line-height:48px;padding:10px;text-align:center;width:380px;}
			.bottom{float:left;width:380px;width:380px;background:#f6f6f6;height:40px;line-height:40px;margin:0;padding:0 10px}
			.bottom a{float:right;dispaly:block;width:80px;height:30px;line-height:30px;margin-top:5px;background:<?php echo $color ?>;color:#fff;text-align:center;text-decoration:none}
		</style>
		<?php if($auto==1):?>
		<script>setTimeout('window.history.go(-1)',3000);</script>
		<?php endif;?>
	</head>
	<body>
		<div>
			<h3>提示消息</h3>
			<p class="middle"><?php echo $content?></p>
			<p class="bottom"><a href="<?php echo $url ?>">确定</a></p>
		</div>
	</body>
</html>
<?php
}


/*定义一个函数.解决截取中文乱码的问题
----------------------------------------*/
if (!function_exists('utf8Substr')) {
	function utf8Substr($str, $from, $len){
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
          '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
          '$1',$str);
	}
}

/*图片主要（三通道）颜色判断
------------------------------------*/
function imgColor($imgUrl) {
    $imageInfo = getimagesize($imgUrl);
    //图片类型
    $imgType = strtolower(substr(image_type_to_extension($imageInfo[2]), 1));
    //对应函数
    $imageFun = 'imagecreatefrom' . ($imgType == 'jpg' ? 'jpeg' : $imgType);
    $i = $imageFun($imgUrl);
    //循环色值
    $rColorNum=$gColorNum=$bColorNum=$total=0;
    for ($x=0;$x<imagesx($i);$x++) {
        for ($y=0;$y<imagesy($i);$y++) {
            $rgb = imagecolorat($i,$x,$y);
            //三通道
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            $rColorNum += $r;
            $gColorNum += $g;
            $bColorNum += $b;
            $total++;
        }
    }
    $rgb = array();
    $rgb['r'] = round($rColorNum/$total);
    $rgb['g'] = round($gColorNum/$total);
    $rgb['b'] = round($bColorNum/$total);
    return $rgb;
}

/*RGB 转 HEX
--------------------*/
function rgb2html($r, $g=-1, $b=-1){
    if (is_array($r) && sizeof($r) == 3)
        list($r, $g, $b) = $r;

    $r = intval($r); $g = intval($g);
    $b = intval($b);

    $r = dechex($r<0?0:($r>255?255:$r));
    $g = dechex($g<0?0:($g>255?255:$g));
    $b = dechex($b<0?0:($b>255?255:$b));

    $color = (strlen($r) < 2?'0':'').$r;
    $color .= (strlen($g) < 2?'0':'').$g;
    $color .= (strlen($b) < 2?'0':'').$b;
    return '#'.$color;
}

/*HEX 转 RGB
--------------------*/
function html2rgb($color){
    if ($color[0] == '#')
        $color = substr($color, 1);

    if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
    elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
    else
        return false;

    $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

    return array($r, $g, $b);
}

/*统计图试用
-------------------------*/
function arcimage($s1=0,$s2=0,$s3=0,$s4=0,$s5=0,$t1='',$t2='',$t3='',$t4='',$t5=''){
//1.创建画布
//imagecreatetruecolor(宽度,高度);
$img = imagecreatetruecolor(300,360);
//2.分配颜色
//iamgecolorallocate(为了谁,r,g,b);
$white = imagecolorallocate($img,255,255,255);
$black = imagecolorallocate($img,0,0,0);
$red = imagecolorallocate($img,255,0,0);
$green = imagecolorallocate($img,0,255,0);
$blue = imagecolorallocate($img,0,0,255);
$yellow = imagecolorallocate($img,255,255,0);
$pink = imagecolorallocate($img,255,0,255);
$darkred = imagecolorallocate($img,120,0,0);
$darkgreen = imagecolorallocate($img,0,120,0);
$darkblue = imagecolorallocate($img,0,0,120);
$darkyellow = imagecolorallocate($img,120,120,0);
$darkpink = imagecolorallocate($img,120,0,120);
$bg = imagecolorallocate($img,242,240,245);
//3.填充背景
//imagefill(太阳谁,坐标x,坐标y,颜色);
imagefill($img,0,0,$bg);
//4.挥刀自宫

for($i=270;$i>250;$i--){
	$s=0;
	if($s1!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s1,$darkgreen,IMG_ARC_PIE);//未付款
		$s+=$s1;
	}
	if($s2!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s2,$darkred,IMG_ARC_PIE);//已付款
		$s+=$s2;
	}
	if($s3!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s3,$darkblue,IMG_ARC_PIE);
		$s+=$s3;
	}
	if($s4!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s4,$darkyellow,IMG_ARC_PIE);
		$s+=$s4;
	}
	if($s5!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s5,$darkpink,IMG_ARC_PIE);
		$s+=$s5;
	}
}
$s=0;$h=30;
	if($s1!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s1,$green,IMG_ARC_PIE);//未付款
		imagefilledrectangle($img,200,$h,250,$h+15,$green);
		imagettftext($img,12,0,50,$h+15,$green,FONT_PATH.'msyh.ttf',$t1);
		$s+=$s1;$h+=25;
	}
	if($s2!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s2,$red,IMG_ARC_PIE);//已付款
		imagefilledrectangle($img,200,$h,250,$h+15,$red);
		imagettftext($img,12,0,50,$h+15,$red,FONT_PATH.'msyh.ttf',$t2);
		$s+=$s2;$h+=25;
	}
	if($s3!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s3,$blue,IMG_ARC_PIE);
		imagefilledrectangle($img,200,$h,250,$h+15,$blue);
		imagettftext($img,12,0,50,$h+15,$blue,FONT_PATH.'msyh.ttf',$t3);
		$s+=$s3;$h+=25;
	}
	if($s4!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s4,$yellow,IMG_ARC_PIE);
		imagettftext($img,12,0,50,$h+15,$yellow,FONT_PATH.'msyh.ttf',$t4);
		imagefilledrectangle($img,200,$h,250,$h+15,$yellow);
		$s+=$s4;$h+=25;
	}
	if($s5!=0){
		imagefilledarc($img,150,$i,250,150,$s,$s+$s5,$pink,IMG_ARC_PIE);
		imagefilledrectangle($img,200,$h,250,$h+15,$pink);
		imagettftext($img,12,0,50,$h+15,$pink,FONT_PATH.'msyh.ttf',$t5);
		$s+=$s5;$h+=25;
	}

//5.保存输出
//imagejpeg($img,'./test.jpg');
header("content-type:image/jpeg");
imagejpeg($img);
//6.销毁资源
//imagedestroy(干掉谁)
imagedestroy($img);
}



