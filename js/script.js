/*  首页横幅
/* ------------------------------------ */
$(document).ready(function(){
  $('.bxslider').bxSlider({
	  mode:'vertical',
	  controls:false,
	  pager:false,
  auto: true,
});
});

/*  详情图片
/* ------------------------------------ */
$('.bxslider1').bxSlider({
  pagerCustom: '#bx-pager',
  nextSelector: '#slider-next',
  prevSelector: '#slider-prev',
  nextText: 'Onward →',
  prevText: '← Go back'
});

/*  注册弹层
/* ------------------------------------ */
$('#reg').on('click', function(){
    layer.open({
        type: 2,
        title: '注册',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['360px' , '420px'],
        content: 'regist.php'
    });
});

/*  登录弹层
/* ------------------------------------ */
$('#log').on('click', function(){
    layer.open({
        type: 2,
        title: '登录',
        maxmin: true,
        shadeClose: true, //点击遮罩关闭层
        area : ['360px' , '420px'],
        content: 'login.php'
    });
});


/*  订单确认
/* ------------------------------------ */
$('#order').on('click', function(){
layer.confirm('请确定订单信息是否准确？然后点击确认提交，系统将为你分配订单号，并及时发货!', {
    btn: ['确定提交','返回修改'] //按钮
},function(){
	//$('#form').submit();
	document.getElementById("orderform").submit();
	layer.closeAll('dialog');
	});
});

/*  支付确认
/* ------------------------------------ */
$('#pay').on('click', function(){
layer.confirm('是否确定付款？', {
    btn: ['确定支付','稍后再说'] //按钮
},function(){
	//$('#form').submit();
	document.getElementById("payform").submit();
	layer.closeAll('dialog');
	});
});
/*  返回顶部
/* ------------------------------------ */
$('a#back-to-top').click(function() {
	$('html, body').animate({scrollTop:0},'slow');
	return false;
});

/*  图片放大镜
/* ------------------------------------ */
 $(function() {
	$('.picZoomer').picZoomer();
	//切换图片
	$('.piclist li').on('click',function(event){
		var $pic = $(this).find('img');
		$('.picZoomer-pic').attr('src',$pic.attr('src').replace(/50x50_/,'800x800_'));
	});
});


/*  导航菜单
/* ------------------------------------ */
function display(targetid){  
	if (document.getElementById){  
		target=document.getElementById(targetid);  
		if (target.style.display==""){  
			target.style.display="none";  
		} else {  
		target.style.display="";  
		}  
	}  
}

/*  控制全选/取消全选
/* ------------------------------------ */
function check_all(obj,cName){ 
    var checkboxs = document.getElementsByName(cName); 
    for(var i=0;i<checkboxs.length;i++){checkboxs[i].checked = obj.checked;} 
} 
