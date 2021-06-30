<?php
include("conn.php");
include("bar.php");


$id=$_POST['id'];



$sonuc=$db->prepare("SELECT * FROM kullanicilar inner join yetki on kullanicilar.rutbe_no=yetki.rutbe_no where id='$id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	



$kullanici_adi=$inner_gelen['kullanici_adi'];
$kullanici_sifre=$inner_gelen['kullanici_sifre'];
$adi=$inner_gelen['adi'];
$soyadi=$inner_gelen['soyadi'];
$sube=$inner_gelen['SUBE'];
$rutbe_no=$inner_gelen['rutbe_no'];
$rutbe_adi=$inner_gelen['rutbe_adi'];



?>
<div class="container">
  <center><h2 style='background-color: #ff0000;'>KULLANICI SİLME</h2> <h2>iŞLEMİNİ KABUL EDİYORMUSUNUZ ?</h2></center><br>
<div class="well well-sm"><?php echo "Kullanici Adi: $kullanici_adi | Kullanici Şifre: $kullanici_sifre | Adi: $adi | Soyadi: $soyadi |  Şube: $sube | Görev: $rutbe_adi";?> </div>
   
   <center>
   <form  action="/kullanicilar.php" method="get" style="display:inline-block;"> 
   <button type="submit" class="btn btn-danger">HAYIR</button>          
   </form>
   
   
   <form  action="/kullanici_sil_kaydet_ok.php" method="post" style="display:inline-block;">
   <input type="label" hidden="on"  value=<?php echo $id; ?> name="id"></input>
 
   <button type="submit" class="btn btn-success">EVET</button>
   </form>
   </center>
  
																		
																		
																		
</div>
