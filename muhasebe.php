<?php 
include("conn.php");
include("bar.php");
if(!(isset($_SESSION['rutbe']))){
	
	header("location: logout.php");
	
}
if(isset($_SESSION['rutbe'])){
    if($_SESSION['rutbe']>0){
	header("location: logout.php");
	}
}


?>


<?php
if(!isset($_POST['tarih_1'])&&!isset($_POST['tarih_2'])){
	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=1 and rutbe_no=6");
$sonuc->execute();
$elden_whatsapp = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=1 and rutbe_no<6");
$sonuc->execute();
$elden_whatsapp2 = $sonuc->fetch(PDO::FETCH_ASSOC);


	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=2 and rutbe_no=6");
$sonuc->execute();
$nt_concept_com = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=2 and rutbe_no<6");
$sonuc->execute();
$nt_concept_com2 = $sonuc->fetch(PDO::FETCH_ASSOC);

	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=3 and rutbe_no=6");
$sonuc->execute();
$N11 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=3 and rutbe_no<6");
$sonuc->execute();
$N112 = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=4 and rutbe_no=6");
$sonuc->execute();
$trendyol = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=4 and rutbe_no<6");
$sonuc->execute();
$trendyol2 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=5 and rutbe_no=6");
$sonuc->execute();
$gittigidiyor = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=5 and rutbe_no<6");
$sonuc->execute();
$gittigidiyor2 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=6 and rutbe_no=6");
$sonuc->execute();
$hepsiburada = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=6 and rutbe_no<6");
$sonuc->execute();
$hepsiburada2 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=7 and rutbe_no=6");
$sonuc->execute();
$amazon = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=7 and rutbe_no<6");
$sonuc->execute();
$amazon2 = $sonuc->fetch(PDO::FETCH_ASSOC);
	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis  WHERE rutbe_no=6");
$sonuc->execute();
$GENEL = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis  WHERE rutbe_no<6");
$sonuc->execute();
$GENEL2 = $sonuc->fetch(PDO::FETCH_ASSOC);
}


if(isset($_POST['tarih_1'])&&isset($_POST['tarih_2'])){
$tarih_1=$_POST['tarih_1'];
$tarih_2=$_POST['tarih_2'];	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=1 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$elden_whatsapp = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=1 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$elden_whatsapp2 = $sonuc->fetch(PDO::FETCH_ASSOC);


	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=2 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$nt_concept_com = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=2 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$nt_concept_com2 = $sonuc->fetch(PDO::FETCH_ASSOC);

	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=3 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$N11 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=3 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$N112 = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=4 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$trendyol = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=4 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$trendyol2 = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=5 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$gittigidiyor = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=5 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$gittigidiyor2 = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=6 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$hepsiburada = $sonuc->fetch(PDO::FETCH_ASSOC);


$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=6 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$hepsiburada2 = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=7 and rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$amazon = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis WHERE sip_yeri_no=7 and rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$amazon2 = $sonuc->fetch(PDO::FETCH_ASSOC);
	
$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis  WHERE rutbe_no=6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$GENEL = $sonuc->fetch(PDO::FETCH_ASSOC);

$sonuc=$db->prepare("SELECT count(sip_yeri_no) as 'satis_sayisi', sum(fiyat) as 'toplam_fiyat' FROM siparis  WHERE rutbe_no<6 and sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2'");
$sonuc->execute();
$GENEL2 = $sonuc->fetch(PDO::FETCH_ASSOC);
}


?>
	
	

	<div class="container">
  
  
  
  
  
  
  
    <center><form action="muhasebe.php" method="post"  style="display:inline-block;">
  <table >
  <tr class="m-3">
  
  
  <td><b>Tarih: </b></td>
  <td>
  <?php
  
  
    if(isset($_POST['tarih_1'])){
	  
	  
	  
	   echo "<input type='date' class='form-control' value=".$tarih_1." ''  name='tarih_1'>";
	  
  }else{
	  
	   echo "<input type='date' class='form-control'  ''  name='tarih_1'>";
	  
	  
  }
  
  
  ?>
  </td>
  <td>
  <?php
      if(isset($_POST['tarih_2'])){
	  
	  
	  
	   echo "<input type='date' class='form-control' value=".$tarih_2." ''  name='tarih_2'>";
	  
  }
  else{
	  
	    echo "<input type='date' class='form-control'  ''  name='tarih_2'>";
	  
  }
  ?>
  
  </td>
  <td><button type="submit"  class="btn btn-primary">ARA</button></td>
  
  <td></td>
  <tr>
  </table>
  </form>
  </center>
  
  
  
  
  
  
  
  
  
	 <br><br>
	 
	  <div class="well well-sm"><h3><center>ÜRETİM VE SATIŞI TAMAMLANAN ÜRÜNLER</center></h3></div>
	 <table class="table table-bordered">
    <thead>
	
	 
       <tr    style='background-color: #5DADE2;'>
      <th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "Elden-Whatsapp"; ?></td>
    <td><?php echo $elden_whatsapp['satis_sayisi']; ?></td>
    <td><?php echo $elden_whatsapp['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
 <!-->  </!-->
 
  <table class="table table-bordered">
    <thead>
        <tr    style='background-color: #F5B041;'>
      <th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "Ntconcept.com"; ?></td>
    <td><?php echo $nt_concept_com['satis_sayisi']; ?></td>
    <td><?php echo $nt_concept_com['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
 <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
       <tr    style='background-color: #52BE80;'>
        
		
		<th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "N11"; ?></td>
    <td><?php echo $N11['satis_sayisi']; ?></td>
    <td><?php echo $N11['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
 


 <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color: #E74C3C;'>
        
		
		<th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Trendyol"; ?></td>
    <td><?php echo $trendyol['satis_sayisi']; ?></td>
    <td><?php echo $trendyol['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
  
  
  <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color: #8E44AD;'>
        
		
		<th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Gittigidiyor"; ?></td>
    <td><?php echo $gittigidiyor['satis_sayisi']; ?></td>
    <td><?php echo $gittigidiyor['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
  
    <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color:#F7DC6F;'>
        
		
		<th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Hepsiburada"; ?></td>
    <td><?php echo $hepsiburada['satis_sayisi']; ?></td>
    <td><?php echo $hepsiburada['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
     <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color:#B2BABB;'>
        
		
		<th>Satiş Yeri</th>
        <th>Satış Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Amazon"; ?></td>
    <td><?php echo $amazon['satis_sayisi']; ?></td>
    <td><?php echo $amazon['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
  
     <hr style=' border-top: 5px solid red;'>
     <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr  >
        
		
		<th style='width: 150px;'></th>
        <th style='width: 532px;'>Toplam Satış Sayısı</th>
        <th style='background-color:#ffffff;'>Toplam Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'></td>
    <td><?php echo $GENEL['satis_sayisi']; ?></td>
    <td><?php echo $GENEL['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
    <hr style=' border-top: 10px solid black;'>
     <div class="well well-sm"><h3><center>ÜRETİM HALİNDE OLAN ÜRÜNLER</center></h3></div>
  
  
  

  
  
	 <br><br>
	 <table class="table table-bordered">
    <thead>
	
	 
       <tr    style='background-color: #5DADE2;'>
      <th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "Elden-Whatsapp"; ?></td>
    <td><?php echo $elden_whatsapp2['satis_sayisi']; ?></td>
    <td><?php echo $elden_whatsapp2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
 <!-->  </!-->
  
  <table class="table table-bordered">
    <thead>
        <tr    style='background-color: #F5B041;'>
      <th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "Ntconcept.com"; ?></td>
    <td><?php echo $nt_concept_com2['satis_sayisi']; ?></td>
    <td><?php echo $nt_concept_com2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
 <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
       <tr    style='background-color: #52BE80;'>
        
		
		<th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr  class="active">
	<td style='width: 150px;'><?php echo "N11"; ?></td>
    <td><?php echo $N112['satis_sayisi']; ?></td>
    <td><?php echo $N112['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
 


 <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color: #E74C3C;'>
        
		
		<th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Trendyol"; ?></td>
    <td><?php echo $trendyol2['satis_sayisi']; ?></td>
    <td><?php echo $trendyol2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
  
  
  <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color: #8E44AD;'>
        
		
		<th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Gittigidiyor"; ?></td>
    <td><?php echo $gittigidiyor2['satis_sayisi']; ?></td>
    <td><?php echo $gittigidiyor2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
  
    <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color:#F7DC6F;'>
        
		
		<th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Hepsiburada"; ?></td>
    <td><?php echo $hepsiburada2['satis_sayisi']; ?></td>
    <td><?php echo $hepsiburada2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
  
     <!-->  </!-->
  
    <table class="table table-bordered">
    <thead>
      <tr    style='background-color:#B2BABB;'>
        
		
		<th>Satiş Yeri</th>
        <th>Ürün Sayısı</th>
        <th>Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'><?php echo "Amazon"; ?></td>
    <td><?php echo $amazon2['satis_sayisi']; ?></td>
    <td><?php echo $amazon2['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  

  </div>
  
  <hr style=' border-top: 5px solid red;'>
     <!-->  </!-->
  <div class="container">
    <table class="table table-bordered">
    <thead>
      <tr  >
        
		
		<th style='width: 150px;'></th>
        <th style='width: 532px;'>Toplam Üretim Halindeki Ürün Sayısı</th>
        <th style='background-color:#ffffff;'>Toplam Brüt Gelir</th>
		
        
</tr>
    </thead>
	<tbody>
<tr class="active">
	<td style='width: 150px;'></td>
    <td><?php echo $GENEL['satis_sayisi']; ?></td>
    <td><?php echo $GENEL['toplam_fiyat']; echo " TL";?></td>
</tr>


    </tbody>
  </table>
  
	</div>