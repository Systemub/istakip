<?php
include("conn.php");
include("bar.php");


$sip_id=$_POST['sip_id'];



$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no where sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);



$sip_id=$_POST['sip_id'];
$sip_yeri_no=$inner_gelen['sip_yeri_no'];
$mus_ad_soyad=$inner_gelen['mus_ad_soyad'];
$mus_tel=$inner_gelen['mus_tel'];
$sip_gel_tarih=$inner_gelen['sip_gel_tarih'];
$sip_tes_tarih=$inner_gelen['sip_tes_tarih'];
$kargo_tarih=$inner_gelen['kargo_tarih'];
$urun=$inner_gelen['urun'];
$notu=$inner_gelen['notu'];
$adres=$inner_gelen['adres'];
$aciliyet_no=$inner_gelen['aciliyet_no'];
$sube=$inner_gelen['SUBE'];
$fiyat=$inner_gelen['fiyat'];
$rutbe_no=$inner_gelen['rutbe_no'];

$sonuc1=$db->prepare("SELECT * FROM yetki");
$sonuc1->execute();
$inner_ham = $sonuc1->fetchAll(PDO::FETCH_ASSOC);	



$sonuc1=$db->prepare("SELECT * FROM siparis_yeri_kopru where sip_yeri_no=$sip_yeri_no");
$sonuc1->execute();
$sip_yeri_tablo = $sonuc1->fetch(PDO::FETCH_ASSOC);



$sonuc1=$db->prepare("SELECT * FROM siparis_acilyet_kopru where aciliyet_no=$aciliyet_no'");
$sonuc1->execute();
$sip_aciliyet_tablo = $sonuc1->fetch(PDO::FETCH_ASSOC);
print_r($sip_aciliyet_tablo);


?>
<div class="container">
  <center><h2 style='background-color: #ff0000;'>SİPARİŞ SİLME</h2> <h2>iŞLEMİNİ KABUL EDİYORMUSUNUZ ?</h2></center><br>
<div class="well well-sm"><?php echo "Sipariş Gelen Yer: {$sip_yeri_tablo['sip_yeri_adi']} <br>
 Müşteri Adi Soyadi: $mus_ad_soyad <br>
 Müşteri Tel: $mus_tel <br> Sipariş Geliş Tarihi: $sip_gel_tarih <br> 
 Sipariş Teslim Tarihi: $sip_tes_tarih <br> Ürün: $urun <br> Notu: $notu <br> Adres: $adres <br> Aciliyet: {$sip_aciliyet_tablo['aciliyet_adi']} <br> Şube: $sube <br> <b>Fiyat: $fiyat</b>" ;?> </div>
   
   <center>
   <form  action="/siparis.php" method="get" style="display:inline-block;"> 
   <button type="submit" class="btn btn-danger">HAYIR</button>          
   </form>
   
   
   <form  action="/siparis_sil_kaydet_ok.php" method="post" style="display:inline-block;">
   <input type="label" hidden="on"  value=<?php echo $sip_id; ?> name="sip_id"></input>

   <button type="submit" class="btn btn-success">EVET</button>
   </form>
   </center>
  
																		
																		
																		
</div>
