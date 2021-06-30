<?php

include("conn.php");
include("bar.php");


$sip_id=$_POST['sip_id'];
$sip_yeri_no=$_POST['sip_yeri_no'];
$sip_yeri_no_kod=$_POST['sip_yeri_no_kod'];
$mus_ad_soyad=$_POST['mus_ad_soyad'];
$mus_tel=$_POST['mus_tel'];
$sip_gel_tarih=$_POST['sip_gel_tarih'];
$sip_tes_tarih=$_POST['sip_tes_tarih'];
$kargo_tarih=$_POST['kargo_tarih'];
$urun=$_POST['urun'];
$notu=$_POST['notu'];
$adres=$_POST['adres'];
$aciliyet_no=$_POST['aciliyet_no'];
$sube=$_POST['SUBE'];
$fiyat=$_POST['fiyat'];
$rutbe_no=$_POST['rutbe_no'];


if($kargo_tarih==''){
	$kargo_tarih="NULL";
	
}
else{ $kargo_tarih="'".$kargo_tarih."'";}



if($_SESSION['yetki']==0){




try {
	
	
$sonuc=$db->prepare("UPDATE siparis SET 	
sip_yeri_no='$sip_yeri_no',
sip_yeri_no_kod='$sip_yeri_no_kod',
mus_ad_soyad='$mus_ad_soyad',
mus_tel='$mus_tel',
sip_gel_tarih='$sip_gel_tarih',
sip_tes_tarih='$sip_tes_tarih',
urun='$urun',
notu='$notu',
adres='$adres',
kargo_tarih=$kargo_tarih,
aciliyet_no='$aciliyet_no',
SUBE='$sube',
fiyat=$fiyat,
rutbe_no='$rutbe_no' 
WHERE sip_id='$sip_id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch();	
} catch(PDOException $e) {
        echo $e->getMessage();
}
header("Location: siparis.php");
}
?>

