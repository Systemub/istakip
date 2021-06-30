<?php

include("conn.php");
include("bar.php");



$kullanici_adi=$_POST['kullanici_adi'];
$kullanici_sifre=$_POST['kullanici_sifre'];
$adi=$_POST['adi'];
$soyadi=$_POST['soyadi'];
$sube=$_POST['SUBE'];
$rutbe_no=$_POST['rutbe_no'];


echo  $kullanici_adi;
echo  "<br>";
echo  $kullanici_sifre;
echo "<br>";
echo  $adi;
echo "<br>";
echo  $soyadi;
echo "<br>";
echo  $sube;
echo  "<br>";
echo  $rutbe_no;

  try {
$sonuc=$db->prepare("INSERT INTO  kullanicilar (kullanici_adi, kullanici_sifre, adi, soyadi, SUBE, rutbe_no)
 VALUES ('$kullanici_adi','$kullanici_sifre','$adi','$soyadi','$sube','$rutbe_no')");
$sonuc->execute();
$inner_gelen = $sonuc->fetch();	
  } catch(PDOException $e) {
        echo $e->getMessage();
    }
header("Location: kullanicilar.php");

?>