<?php
include("conn.php");
include("bar.php");


$resim_onay=0;

$sonuc=$db->prepare("SELECT max(sip_id) as en_buyuk FROM siparis");
$sonuc->execute();
$en_buyuk_id=$sonuc->fetch(PDO::FETCH_ASSOC);	
if(isset($en_buyuk_id)){
	$arttirilmis_isim=(int)$en_buyuk_id['en_buyuk']+1;
	if(isset($_FILES['resim']['size'])){
		if($_FILES['resim']['size']>1){
		$dosya=$_FILES["resim"]["tmp_name"];
		copy($dosya, 'resimler/' . $arttirilmis_isim.'.jpg');
	    $resim_onay=1;
		}
	}
	
}





	
	
	



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







$sonuc=$db->prepare("SELECT * FROM siparis_acilyet_kopru where aciliyet_no ='$aciliyet_no'");
$sonuc->execute();
$aciliyet_tablo = $sonuc->fetch(PDO::FETCH_ASSOC);	


$sonuc=$db->prepare("SELECT * FROM siparis_yeri_kopru where sip_yeri_no ='$sip_yeri_no'");
$sonuc->execute();
$yeri_tablo = $sonuc->fetch(PDO::FETCH_ASSOC);	







?>
<div class="container">
  <center><h2>SİPARİŞ EKLEME iŞLEMİNİ KABUL EDİYORMUSUNUZ ?</h2></center><br>
<div class="well well-sm"><?php if($resim_onay==1){echo "<b>Resim Eklendi</b><br>";}else{ echo "<b>Resim Eklenemedi</b><br>";} echo "Sipariş Gelen Yer: {$yeri_tablo['sip_yeri_adi']} <br>
 Sipariş Yeri Kodu: $sip_yeri_no_kod <br>
 Müşteri Adi Soyadi: $mus_ad_soyad <br>
 Müşteri Tel: $mus_tel <br> Sipariş Geliş Tarihi: $sip_gel_tarih <br> 
 Sipariş Teslim Tarihi: $sip_tes_tarih <br> Ürün: $urun <br> Notu: $notu <br>
 Adres: $adres <br> Aciliyet: {$aciliyet_tablo['aciliyet_adi']} <br> Şube: $sube <br> <b>Fiyat: $fiyat</b>" ;?> </div>
   
   <center>
   <form  action="/kullanicilar.php" method="get" style="display:inline-block;"> 
   <button type="submit" class="btn btn-danger">HAYIR</button>          
   </form>
   
   
   <form  action="/siparis_ekle_kaydet_ok.php" method="post" style="display:inline-block;">
   
   <input type="text" hidden="on" value=<?php echo $sip_yeri_no; ?> name="sip_yeri_no"></input>
   <input type="text" hidden="on" value=<?php echo $sip_yeri_no_kod; ?> name="sip_yeri_no_kod"></input>
   <input type="text" hidden="on" value="<?php echo $mus_ad_soyad; ?>" name="mus_ad_soyad"></input>
   <input type="text" hidden="on" value=<?php echo $mus_tel; ?> name="mus_tel"></input>
   <input type="text" hidden="on" value=<?php echo $sip_gel_tarih; ?> name="sip_gel_tarih"></input>
   <input type="text" hidden="on" value=<?php echo $sip_tes_tarih; ?> name="sip_tes_tarih"></input>
   <input type="text" hidden="on" value="<?php echo $urun; ?>" name="urun"></input>
   <input type="text" hidden="on" value="<?php echo $notu; ?>" name="notu"></input>
   <input type="text" hidden="on" value="<?php echo $adres; ?>" name="adres"></input>
   <input type="text" hidden="on" value=<?php echo $aciliyet_no; ?> name="aciliyet_no"></input>
   <input type="text" hidden="on" value=<?php echo $sube; ?> name="SUBE"></input>
   <input type="text" hidden="on" value=<?php echo $fiyat; ?> name="fiyat"></input>
   <button type="submit" class="btn btn-success">EVET</button>
   </form>
   </center>
  
																		
																		
																		
</div>


