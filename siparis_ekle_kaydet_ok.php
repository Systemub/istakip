<?php

include("conn.php");
include("bar.php");


$sip_yeri_no=$_POST['sip_yeri_no'];
$sip_yeri_no_kod=$_POST['sip_yeri_no_kod'];
$mus_ad_soyad=$_POST['mus_ad_soyad'];
$mus_tel=$_POST['mus_tel'];
$sip_gel_tarih=$_POST['sip_gel_tarih'];
$sip_tes_tarih=$_POST['sip_tes_tarih'];
$urun=$_POST['urun'];
$notu=$_POST['notu'];
$adres=$_POST['adres'];
$aciliyet_no=$_POST['aciliyet_no'];
$sube=$_POST['SUBE'];
$fiyat=$_POST['fiyat'];

echo $mus_ad_soyad;
if(isset($_SESSION['yetki'])){
if($_SESSION['yetki']==0){




try {
$sonuc=$db->prepare("INSERT INTO siparis (sip_yeri_no,sip_yeri_no_kod, mus_ad_soyad, mus_tel, sip_gel_tarih, sip_tes_tarih, urun, notu, adres, aciliyet_no, SUBE, fiyat,rutbe_no) VALUES ($sip_yeri_no,'$sip_yeri_no_kod','$mus_ad_soyad',$mus_tel,'$sip_gel_tarih','$sip_tes_tarih','$urun','$notu','$adres',$aciliyet_no,$sube,$fiyat,1)");
$sonuc->execute();
$inner_gelen = $sonuc->fetch();	
} catch(PDOException $e) {
        echo $e->getMessage();
}
header("Location: siparis.php");
}}else{
	
	header("Location: logout.php");
}
?>

