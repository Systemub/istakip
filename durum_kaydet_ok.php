<?php

include("conn.php");
session_start();


$sip_id=$_POST['sip_id'];
$suan=date("Y-m-d");

if(isset($_SESSION['rutbe'])){
$yetkim=$_SESSION['rutbe'];

$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no where sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);

$durum=$inner_gelen['rutbe_no'];
$durum++;




if($yetkim==7){
	
$sonuc=$db->prepare("UPDATE siparis SET 	
rutbe_no=$durum
WHERE sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	
}
else{
	$sonuc=$db->prepare("UPDATE siparis SET 	
rutbe_no=$durum
WHERE sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	
	
	
	
}

header("Location: durum.php");


}
else{
	
header("Location: login.php");
	
}
?>