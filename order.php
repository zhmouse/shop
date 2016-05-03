<?php
require './header.php';
if(empty($_SESSION['home'])){
	$url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	header('location:login.php?url='.$url);
}
$uid=$_SESSION['home']['id'];
$u=$_GET['u'];
?>

<div id="main">
	<?php include 'order/'.$u.'.php';?>	
</div>

<?php require './footer.php';?>