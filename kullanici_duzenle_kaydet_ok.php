<?php

include("conn.php");
session_start();


$id=$_POST['id'];
$kullanici_adi=$_POST['kullanici_adi'];
$kullanici_sifre=$_POST['kullanici_sifre'];
$adi=$_POST['adi'];
$soyadi=$_POST['soyadi'];
$sube=$_POST['SUBE'];
$rutbe_no=$_POST['rutbe_no'];

if($_SESSION['yetki']==0){



$sonuc=$db->prepare("UPDATE kullanicilar SET 	
kullanici_adi='$kullanici_adi' ,
kullanici_sifre='$kullanici_sifre' ,
adi='$adi' ,
soyadi='$soyadi',
SUBE='$sube',
rutbe_no='$rutbe_no' 
WHERE id='$id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	
header("Location: kullanicilar.php");
}
else{
	
	header("Location: login.php");
	
}

?>