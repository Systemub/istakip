<?php
include("conn.php");
include("bar.php");

$sip_id=$_POST['sip_id'];
echo $sip_id;
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no where sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);


$sip_yeri_no=$inner_gelen['sip_yeri_no'];
$mus_ad_soyad=$inner_gelen['mus_ad_soyad'];
$mus_tel=$inner_gelen['mus_tel'];
$sip_gel_tarih=$inner_gelen['sip_gel_tarih'];
$sip_tes_tarih=$inner_gelen['sip_tes_tarih'];
$urun=$inner_gelen['urun'];
$notu=$inner_gelen['notu'];
$adres=$inner_gelen['adres'];
$aciliyet_no=$inner_gelen['aciliyet_no'];
$sube=$inner_gelen['SUBE'];

$adres=$inner_gelen['adres'];





$sonuc=$db->prepare("SELECT * FROM siparis_acilyet_kopru where aciliyet_no ='$aciliyet_no'");
$sonuc->execute();
$aciliyet_tablo = $sonuc->fetch(PDO::FETCH_ASSOC);	


$sonuc=$db->prepare("SELECT * FROM siparis_yeri_kopru where sip_yeri_no ='$sip_yeri_no'");
$sonuc->execute();
$yeri_tablo = $sonuc->fetch(PDO::FETCH_ASSOC);	







?>
<div class="container">
  <center><h2 style='background-color: #FFCC00;'>İŞİ TAMAMLAMA</h2> <h2>iŞLEMİNİ KABUL EDİYORMUSUNUZ ?</h2></center><br>
<div class="well well-sm"><?php echo "<b>
 
 Müşteri Adi Soyadi: $mus_ad_soyad <br>
 Müşteri Adres: $adres <br>
 Sipariş Geliş Tarihi: $sip_gel_tarih <br> 
 Sipariş Teslim Tarihi: $sip_tes_tarih <br> Ürün: $urun <br> Notu: $notu <br>
 Aciliyet: {$aciliyet_tablo['aciliyet_adi']} <br> </b>" ;?> </div>
   
   <center>
   <form  action="/durum.php" method="get" style="display:inline-block;"> 
   <button type="submit" class="btn btn-danger">HAYIR</button>          
   </form>
   
   
   <form  action="/durum_kaydet_ok.php" method="post" style="display:inline-block;">
   <input type="text" hidden="on" value=<?php echo $sip_id; ?> name="sip_id"></input>


   <button type="submit" class="btn btn-success">EVET</button>
   </form>
   </center>
  
																		
																		
																		
</div>


