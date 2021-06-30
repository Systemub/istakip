<?php 
include("conn.php");

include("bar.php");


if(!isset($_SESSION['yetki'])){
	header("location: logout.php");
	
}
if(isset($_SESSION['rutbe'])){
    if($_SESSION['rutbe']>0){
	header("location: logout.php");
	}
}




$post_durum=0;


if(isset($_POST['ad_soyad'])){
$ad_soyad=$_POST['ad_soyad'];
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where s.mus_ad_soyad LIKE '%$ad_soyad%' ORDER BY s.sip_id desc  ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
$sayac=count($inner_gelen);

$elde_1=(int)($sayac%100);
$elde_2=(int)($sayac/100);
if($elde_1>0){
	
	$elde_2++;
}

$sayfa_sayisi=$elde_2;

$post_durum=1;
}else if(isset($_POST['sip_no'])){
	 $sip_no=$_POST['sip_no'];
	
	$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where s.sip_id=$sip_no ORDER BY s.sip_id desc  ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
$sayac=count($inner_gelen);
$elde_1=(int)($sayac%100);
$elde_2=(int)($sayac/100);
if($elde_1>0){
	
	$elde_2++;
}

$sayfa_sayisi=$elde_2;
	$post_durum=2;
}else if(isset($_POST['tel_no'])){
	$tel_no=$_POST['tel_no'];
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where s.mus_tel LIKE '%$tel_no%' ORDER BY s.sip_id desc  ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
$sayac=count($inner_gelen);
$elde_1=(int)($sayac%100);
$elde_2=(int)($sayac/100);
if($elde_1>0){
	
	$elde_2++;
}

$sayfa_sayisi=$elde_2;
$post_durum=3;
}else if(isset($_POST['tarih_1'])&&isset($_POST['tarih_2'])){
	$tarih_1=$_POST['tarih_1'];
	$tarih_2=$_POST['tarih_2'];
	
	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where  sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2' ORDER BY s.sip_id desc  ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
$sayac=count($inner_gelen);
$elde_1=(int)($sayac%100);
$elde_2=(int)($sayac/100);
if($elde_1>0){
	
	$elde_2++;
}

$sayfa_sayisi=$elde_2;
$post_durum=4;
}

else{

$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no ORDER BY s.sip_id desc");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
$sayac=count($inner_gelen);
$elde_1=(int)($sayac%100);
$elde_2=(int)($sayac/100);
if($elde_1>0){
	
	$elde_2++;
}

$sayfa_sayisi=$elde_2;


}





?>
<?php

$sayfam=1;
if(!isset($_GET['page'])){
	$sayfa=0;

	
}
if(isset($_GET['page'])){
	$gelen=$_GET['page'];
	
	if($gelen=null || $gelen==1){
		
		
		$sayfam=1;
		$sayfa=0;
		
	}else{
		
		$sayfam=$_GET['page'];
		$sayfa=100*($sayfam-1);
		
}}
	
	
	
	


if($post_durum==0){	

$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no ORDER BY s.sip_id desc limit $sayfa,100");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);

}


if($post_durum==1){
	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where s.mus_ad_soyad LIKE '%$ad_soyad%' ORDER BY s.sip_id desc  limit $sayfa,100");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
	
	
}





if($post_durum==2){

$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where  s.sip_id=$sip_no ORDER BY s.sip_id desc limit $sayfa,100 ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
	
	
}


if($post_durum==3){
	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where s.mus_tel LIKE '%$tel_no%' ORDER BY s.sip_id desc limit $sayfa,100 ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
	
	
}

if($post_durum==4){
	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no  where  sip_gel_tarih >= '$tarih_1' and sip_gel_tarih  <= '$tarih_2' ORDER BY s.sip_id desc limit $sayfa,100 ");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);
	
	
}	








?>

<style>

 th {
    position: sticky;
    top: 0;
    box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
  }



body {font-family: Arial, Helvetica, sans-serif;}

#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}

/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.9); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>

 <script>

function f(gelen){

if(gelen==0){document.getElementById("alan1").innerHTML = ""; document.getElementById("alan2").innerHTML = "";}
if(gelen==1){document.getElementById("alan1").innerHTML = "<input type='text' class='form-control' placeholder='Ad Soyad Giriniz.' name='ad_soyad'>"; document.getElementById("alan2").innerHTML = "";}
if(gelen==2){document.getElementById("alan1").innerHTML = "<input type='text' class='form-control' placeholder='Sipariş No Giriniz.' name='sip_no'>"; document.getElementById("alan2").innerHTML = "";}
if(gelen==3){
	document.getElementById("alan1").innerHTML = "<input type='number' class='form-control' placeholder='Telefon No Giriniz.' name='tel_no'>";
	document.getElementById("alan2").innerHTML = "";
	
	}
if(gelen==4){
	document.getElementById("alan1").innerHTML = "<input type='date' class='form-control'  name='tarih_1'>";
    document.getElementById("alan2").innerHTML = "<input type='date' class='form-control'  name='tarih_2'>";
}
}

</script>
 
 
 

 
 
	
<div id='myModal' class='modal'>
  <span class='close'>&times;</span>
  <img class='modal-content' id='img01'>
  <div id='caption'></div>
</div>
       
	
  <div class="container"><form action="siparis_ekle.php" method="post"><button name="sip_id" type="submit"  class="btn btn-success ">Siparis Ekle +</button></form></div>
  <center><form action="siparis.php" method="post"  style="display:inline-block;">
  <table >
  <tr class="m-3">
  
   <td><b>Arama Türü:</b>&nbsp</td>
  <td >
 
   <select   onchange="f(this.value)" class="form-control">
    <option value="0">---</option>
	<option value="1"
	<?php
  if(isset($_POST['ad_soyad'])){
	 echo " "; echo "selected";
  }
	 ?>
	
	>Ad Soyad</option>
    <option value="2"
	
		<?php
  if(isset($_POST['sip_no'])){
	 echo " "; echo "selected";
  }
	
	?>
	>Sipariş No</option>
	<option value="3"
	
			<?php
  if(isset($_POST['tel_no'])){
	 echo " "; echo "selected";
  }
	
	?>
	
	
	
	
	>Telefon No</option>
	<option value="4"
	
	
				<?php
  if(isset($_POST['tarih_1'])||isset($_POST['tarih_2'])){
	 echo " "; echo "selected";
  }
	
	?>
	
	
	>Tarih</option>

  </select>
  </td>
  <td id="alan1">
  <?php
  if(isset($_POST['ad_soyad'])){
	  
	  echo "<input type='text' class='form-control' value=".$ad_soyad." '' name='ad_soyad'>";
	  
  }
    if(isset($_POST['sip_no'])){
	  
	  echo "<input type='text' class='form-control' value=".$sip_no."  '' name='sip_no'></input>";
	  
  }
  if(isset($_POST['tel_no'])){
	  
	  
	  
	   echo "<input type='text' class='form-control' value=".$tel_no."  '' name='tel_no'></input>";
	  
  }
  
  
    if(isset($_POST['tarih_1'])){
	  
	  
	  
	   echo "<input type='date' class='form-control' value=".$tarih_1." ''  name='tarih_1'>";
	  
  }
  
  
  ?>
  </td>
  <td id="alan2">
  <?php
      if(isset($_POST['tarih_2'])){
	  
	  
	  
	   echo "<input type='date' class='form-control' value=".$tarih_2." ''  name='tarih_2'>";
	  
  }
  ?>
  
  </td>
  <td><button type="submit"  class="btn btn-primary">ARA</button></td>
  
  <td></td>
  <tr>
  </table>
  </form>
  </center>
 <br><br><br>
 
 
	 <form action="siparis_duzenle.php" method="post" >
	 
	
	 <table class="table table-bordered  " >
    <thead>
      <tr  class="info" >
	 

        <th><center>Siparis No</center></th>
        <th><center>Sipariş Yeri</center></th>
		<th><center>Sipariş Yeri Kodu</center></th>
        <th style="max-width: 30em; max-width: 15ch;"><center>Müşteri Adı Soyadı</center></th>
        <th><center>Müşteri Tel</center></th>
		<th><center>Sipariş Geliş Tarihi</center></th>
		<th><center>Sipariş Teslim Tarihi</center></th>
		<th style="max-width: 40em; max-width: 25ch;"><center>Ürün</center></th>
		<th><center>Ürün Resmi</center></th>
		<th style="max-width: 40em; max-width: 25ch;"><center>Not</center></th>
		<th style="max-width: 40em; max-width: 25ch;"><center>Adres</center></th>
		<th><center>Aciliyet</center></th>
		<th><center>Şube</center></th>
		<th><center>Durumu</center></th>
		<th><center>Fiyat</center></th>
		
        <th colspan="2"><center>Düzenle</center></th>
	  </tr>
    </thead>
	<tbody>
	
	<?php foreach($inner_gelen as $row) : ?>
	
<?php 

if($row['rutbe_no']==6){
		echo "<tr  style='background-color: #5cd65c;'>";
	
	}
	else if($row['rutbe_no']==5){
				echo "<tr style='border:1px solid;   box-shadow: 0 0 0 5px #000000 inset; '>";
	
	}
		else if($row['rutbe_no']==4){
				echo "<tr style='border:1px solid;  box-shadow: 0 0 0 5px #FF6347 inset;'>";
	
	}
		else if($row['rutbe_no']==3){
				echo "<tr style='border:1px solid; box-shadow: 0 0 0 5px #DA70D6 inset;'>";
	
	}
		else if($row['rutbe_no']==2){
				echo "<tr style='border:1px solid;  box-shadow: 0 0 0 5px #4682B4 inset;'>";
	
	}
		else if($row['rutbe_no']==1){
				echo "<tr style='border:1px solid;  box-shadow: 0 0 0 5px #FFD700 inset;'>";
	
	}
	else{
	echo '<tr>';
	
}

?>
	<td><center><b><?php echo $row['sip_id']; ?></b></center></td>
    <td><?php echo $row['sip_yeri_adi']; ?></td>
	<td style="max-width: 15ch; word-wrap:break-word;"><?php echo $row['sip_yeri_no_kod']; ?></td>
    <td style="max-width: 15ch; word-wrap:break-word;"><?php echo $row['mus_ad_soyad']; ?></td>
    <td><?php echo $row['mus_tel']; ?></td>
    <td><?php echo date('d-m-Y',strtotime($row['sip_gel_tarih'])); ?></td>
    
	<?php 
	
	
	$time1 = new DateTime();
    $time2 = new DateTime($row['sip_tes_tarih']);
    $interval = $time1->diff($time2);
	

    
	if((3>($interval->format('%r%m%d')))&&($interval->format('%r%m%d'))>1&&$row['rutbe_no']<6){
	    
		echo "<td style='background-color: #ffff00;'>";
		
		echo "<b>".date('d-m-Y',strtotime($row['sip_tes_tarih']))."</b>";
	
	}else if((3>($interval->format('%r%d')))&&$row['rutbe_no']<6){
	echo "<td style='background-color: #ff0000; color: #ffffff;'>";
	
		echo "<b>".date('d-m-Y',strtotime($row['sip_tes_tarih']))."</b>";
	}
	else{
		echo "<td>";
		echo date('d-m-Y',strtotime($row['sip_tes_tarih'])); 
		
	}
	?>
	
	
	
	</td>
	<td style="max-width: 25ch; word-wrap:break-word;"><?php echo $row['urun']; ?></td>
	<td style="max-width: 25ch; word-wrap:break-word;">
	<?php if ( file_exists( "resimler/".$row['sip_id'].".jpg" ) ){ 
	
	
	
	echo "<center><img src='resimler/{$row['sip_id']}.jpg' width='50' height='50' id='{$row['sip_id']}' onclick=calis('{$row['sip_id']}')></center>";}else {echo "<center>---</center>";}?>
	
	
	
	</td>
	<td style="max-width: 25ch; word-wrap:break-word;"><?php echo $row['notu']; ?></td>
	<td style="max-width: 25ch; word-wrap:break-word;"><?php echo $row['adres']; ?></td>
	
	
	
	<?php 
	if($row['aciliyet_no']==2){
		if($row['rutbe_no']>5){
			echo "<td>";
			echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
			
		}else{
		echo "<td style='background-color: #ffff00; color: #000000;'>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
		}
	}
	else if($row['aciliyet_no']==3){
		if($row['rutbe_no']>5){
			echo "<td>";
			echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
			
		}else{
		
		echo "<td style='background-color: #ff0000; color: #ffffff;'>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
		}
	}
	else{
		echo "<td>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
		
	}
	
	
	?>
	
	</td>
	<td><?php echo $row['SUBE']; ?></td>
	<?php 
	
	if($row['rutbe_no']==6){
		echo "<td style='background-color: #5cd65c;'>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
		
		if($row['kargo_tarih']!=null){
		echo '<b>'.date('d-m-Y',strtotime($row['kargo_tarih'])).'</b>'; }
	}
	else if($row['rutbe_no']==5){
		echo "<td style='background-color: #000000; color: #ffffff;'>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
	}
	else if($row['rutbe_no']==4){
		echo "<td style='background-color: #FF6347;'>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
	}
	else if($row['rutbe_no']==3){
		echo "<td style='background-color: #DA70D6';>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
	}
	else if($row['rutbe_no']==2){
		echo "<td style='background-color: #4682B4;color: #ffffff;'>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
	}
	else if($row['rutbe_no']==1){
		echo "<td style='background-color:  #FFD700;'>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
	}
	
	
	
	
	
	else{
		
		echo "<td>";
		echo $row['rutbe_adi'];
		
	}
	
	 ?>
	
	
	</td>
	<td><?php echo $row['fiyat']." "; ?>TL</td>
	
	<td><center><form action="siparis_duzenle.php" method="post"><button name="sip_id" value="<?php echo $row['sip_id'];?>" type="submit" class="btn btn-warning">Düzenle</button></form></center></td>
	<td><center><form action="siparis_sil_kaydet.php" method="post"><button name="sip_id" value="<?php echo $row['sip_id'];?>" type="submit" class="btn btn-danger">Sil</button></form></center></td>
	
	
	</tr>
	
<?php endforeach;?>
</tbody>
</table>
</form>



<center><ul class="pagination">
	 
	 <?php if($sayfam>1){
         $azalt=$sayfam-1;
		   if(isset($_POST['ad_soyad'])){
			   
			   echo "<form  action='/siparis.php?page=$azalt' method='post'>";
			   echo "<input type='text' hidden='on' value='$ad_soyad' name='ad_soyad'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&larr; ÖNCEKİ SAYFA</button>";
			   echo "</form>";
		   }else if(isset($_POST['tel_no'])){
			   
			   echo "<form  action='/siparis.php?page=$azalt' method='post'>";
			   echo "<input type='text' hidden='on' value='$tel_no' name='tel_no'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&larr; ÖNCEKİ SAYFA</button>";
			   echo "</form>";
		   }else if(isset($_POST['sip_no'])){
			   
			   echo "<form  action='/siparis.php?page=$azalt' method='post'>";
			   echo "<input type='text' hidden='on' value='$sip_no' name='sip_no'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&larr; ÖNCEKİ SAYFA</button>";
			   echo "</form>";
		   }
		   else if(isset($_POST['tarih_1'])&&isset($_POST['tarih_2'])){
			   
			   echo "<form  action='/siparis.php?page=$azalt' method='post'>";
			   echo "<input type='text' hidden='on'  value='$tarih_1'  name='tarih_1'></input>";
			   echo "<input type='text' hidden='on'  value='$tarih_2'  name='tarih_2'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&larr; ÖNCEKİ SAYFA</button>";
			   echo "</form>";
		   }
		  else{
			   
		  echo "<form  action='/siparis.php?page=$azalt' method='post'>";
	      
	      echo "<button type='submit' class='btn btn-primary'>&larr; ÖNCEKİ SAYFA</button>";
			    echo "</form>";
			   
		   }

		
		
	
 
		   
		   
          
	 }
	
	?>
	
	<?php if($sayfam<$sayfa_sayisi){
		$arttir=$sayfam+1;
		
		   if(isset($_POST['ad_soyad'])){
			   
			   echo "<form  action='/siparis.php?page=$arttir' method='post'>";
			   echo "<input type='text' hidden='on' value='$ad_soyad' name='ad_soyad'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&rarr; SONRAKİ SAYFA</button>";
			   echo "</form>";
		   }else if(isset($_POST['tel_no'])){
			   
			   echo "<form  action='/siparis.php?page=$arttir' method='post'>";
			   echo "<input type='text' hidden='on' value='$tel_no' name='tel_no'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&rarr; SONRAKİ SAYFA</button>";
			   echo "</form>";
		   }else if(isset($_POST['sip_no'])){
			   
			   echo "<form  action='/siparis.php?page=$arttir' method='post'>";
			   echo "<input type='text' hidden='on' value='$sip_no' name='sip_no'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&rarr; SONRAKİ SAYFA</button>";
			   echo "</form>";
		   }
		   else if(isset($_POST['tarih_1'])&&isset($_POST['tarih_2'])){
			   
			   echo "<form  action='/siparis.php?page=$arttir' method='post'>";
			   echo "<input type='text' hidden='on'   value='$tarih_1'   name='tarih_1'></input>";
			   echo "<input type='text' hidden='on'   value='$tarih_2'   name='tarih_2'></input>";
			   echo "<button type='submit' class='btn btn-primary'>&rarr; SONRAKİ SAYFA</button>";
			   echo "</form>";
		   }
		  
		   else{
			   
		  echo "<form  action='/siparis.php?page=$arttir' method='post'>";
	      
	      echo "<button type='submit' class='btn btn-primary'>&rarr; SONRAKİ SAYFA</button>";
			   echo "</form>";
			   
		   }
		
	}
	?>
	 
</ul>
</center>

	</div>
	
<script>

function calis(gelen){
	
var modal = document.getElementById('myModal');

// Get the image and insert it inside the modal - use its 'alt' text as a caption
var img = document.getElementById(gelen);
var modalImg = document.getElementById('img01');
var captionText = document.getElementById('caption');
img.onclick = function(){
  modal.style.display = 'block';
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName('close')[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = 'none';
}
}
</script>



	</body>
	</html>
