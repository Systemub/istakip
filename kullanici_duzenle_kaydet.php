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


$sonuc=$db->prepare("SELECT * FROM yetki where rutbe_no ='$rutbe_no'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	



$rutbe_adi=$inner_gelen['rutbe_adi'];

include("bar.php");

?>
<div class="container">

    <center><h2 style='background-color: #ffcc00;'>KULLANICI DÜZENLEME</h2> <h2>iŞLEMİNİ KABUL EDİYORMUSUNUZ ?</h2></center><br>
<div class="well well-sm"><?php echo "Kullanici Adi: $kullanici_adi | Kullanici Şifre: $kullanici_sifre | Adi: $adi | Soyadi: $soyadi |  Şube: $sube | Görev: $rutbe_adi";?> </div>
   
   <center>
   <form  action="/kullanicilar.php" method="get" style="display:inline-block;"> 
   <button type="submit" class="btn btn-danger">HAYIR</button>          
   </form>
   
   
   <form  action="/kullanici_duzenle_kaydet_ok.php" method="post" style="display:inline-block;">
   <input type="label" hidden="on" value=<?php echo $id; ?> name="id"></input>
   <input type="label" hidden="on" value=<?php echo $kullanici_adi; ?> name="kullanici_adi"></input>
   <input type="label" hidden="on" value=<?php echo $kullanici_sifre; ?> name="kullanici_sifre"></input>
   <input type="label" hidden="on" value=<?php echo $adi; ?> name="adi"></input>
   <input type="label" hidden="on" value=<?php echo $soyadi; ?> name="soyadi"></input>
   <input type="label" hidden="on" value=<?php echo $sube; ?> name="SUBE"></input>
   <input type="label" hidden="on" value=<?php echo $rutbe_no; ?> name="rutbe_no"></input>
   <button type="submit" class="btn btn-success">EVET</button>
   </form>
   </center>
  
																		
																		
																		
</div>


