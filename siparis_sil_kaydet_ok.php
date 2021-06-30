<?php

include("conn.php");
session_start();


$sip_id=$_POST['sip_id'];

if(isset($_SESSION['yetki'])){
if($_SESSION['yetki']==0){



$sonuc=$db->prepare("DELETE FROM siparis WHERE sip_id='$sip_id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	
header("Location: siparis.php");
}
else{
	
	header("Location: login.php");
	
}
}
else{
	
	header("Location: logout.php");	
}
?>