<?php

include("conn.php");
session_start();


$id=$_POST['id'];


if($_SESSION['yetki']==0){



$sonuc=$db->prepare("DELETE FROM kullanicilar WHERE id='$id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	
header("Location: kullanicilar.php");
}
else{
	
	header("Location: login.php");
	
}

?>