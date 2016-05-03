<script src="./js/jquery.js"></script>
<script src="./js/layer/layer.js"></script>
<script src="./js/address.js"></script>
<script src="./js/bxslider/bxslider.js"></script>
<link href="./js/bxslider/bxslider.css" rel="stylesheet" />
<script src="./js/script.js"></script>
<script>
function onlyNumber(o) {//限制输入数字
				var sValue = o.value;
				if (sValue == 0) {
					sValue = 1;
				}
				else {
					var aResult = [];
					var iLen = sValue.length;
					var sChar = "";
					for (var i = 0; i < iLen; i++) {
						sChar = sValue.charAt(i);
						if (sChar != " " && false == isNaN(sChar)) {
							aResult.push(sValue.charAt(i));
						}
					}
					sValue = aResult.join("") || 1;
				}
				o.value = sValue;				
				var Limit = <?php echo $limit?>;//$("#Limit").val()*1;
				if (sValue > Limit) {
					o.value = Limit || 1;
					$("#msg").text("商品热销仅剩<?php echo $limit?>件哦！");
				}
			}						
			function changeCount(o, n) {//加减值
				var Limit = <?php echo $limit?>;//库存$("#Limit").val();
				var oThis = $(o);
				var iValue = $.trim($(o).val()) * 1 + n;

				if (iValue > 0 && iValue <= Limit) {
					oThis.val(iValue);
				}
				else if (iValue <= 0) {
					oThis.val(1);
				}
				else {
					if (Limit <= 0) { oThis.val("1"); }
					else { oThis.val(Limit); }
					 $("#msg").text("商品热销仅剩<?php echo $limit?>件哦！");
				}
				return false;
			}			 
</script>
<script type="text/javascript">
	addressInit('cmbProvince', 'cmbCity', 'cmbArea', '<?php echo $sheng?>', '<?php echo $shi?>', '<?php echo $qu?>');
</script>
<?php mysql_close()?>			
