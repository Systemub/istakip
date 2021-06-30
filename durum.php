<?php

include("conn.php");
include("bar.php");
if(isset($_SESSION['rutbe'])){
$yetkim=$_SESSION['rutbe'];
$sube=$_SESSION['sube'];
}
else{
	header("location: logout.php");
	
}


	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no where s.rutbe_no>0 and s.rutbe_no<6 and SUBE=$sube ORDER BY s.sip_id desc");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);




?>
 
  	<?php header("Refresh: 60"); ?>
<html> 
 <head>
<style>
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
	 <head>
	 <body>
    
	<div id='myModal' class='modal'>
  <span class='close'>&times;</span>
  <img class='modal-content' id='img01'>
  <div id='caption'></div>
</div>
	 
	  
	    <br><br>
	 <form action="durum_kaydet.php" method="post" >
	 
	
	 <table class="table table-bordered" >
    <thead>
      <tr  class="info">
	 
       
        <th><center>Siparis No</center></th>
       
        <th><center>Müşteri Adı Soyadı</center></th>
        <th><center>Müşteri Adresi</center></th>
		<th><center>Sipariş Geliş Tarihi</center></th>
		<th><center>Sipariş Teslim Tarihi</center></th>
		<th><center>Ürün</center></th>
		<th><center>Ürün Resmi</center></th>
		<th><center>Not</center></th>
		
		<th><center>Aciliyet</center></th>
		<th><center>Durumu</center></th>
		
        <th><center></center></th>
		</center>
	  </tr>
    </thead>
	<tbody>
	
	<?php foreach($inner_gelen as $row) : ?>
	
<?php 
$aralik=0;
if($row['kargo_tarih']!=null){
		

        $zaman1= new DateTime();
	    $zaman2= new DateTime($row['kargo_tarih']);
	    $aralik=$zaman1->diff($zaman2);
        $aralik=$aralik->format('%r%m%d');}


		if($aralik<(-29)&&$row['rutbe_no']>5){
		
		
}else{
	

	
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
<?php
	echo "<td><center><b>{$row['sip_id']}</b></center></td>";
	?>
    
    <?php echo "<td><b>{$row['mus_ad_soyad']}</b></td>";?>
    <?php echo "<td><b>{$row['adres']}</b></td>";?>
    <?php echo"<td><b>";?><?php echo date('d-m-Y',strtotime($row['sip_gel_tarih'])); ?> <?php echo "</b></td>";?>
    <?php 
	
	
	$time1 = new DateTime();
    $time2 = new DateTime($row['sip_tes_tarih']);
    $interval = $time1->diff($time2);
	

    
	if((3>($interval->format('%r%d')))&&($interval->format('%r%d'))>1&&$row['rutbe_no']<6){
	    
		echo "<td style='background-color: #ffff00;'>";
		
		echo "<b>".date('d-m-Y',strtotime($row['sip_tes_tarih']))."</b>";
	
	}else if((3>($interval->format('%r%d')))&&$row['rutbe_no']<6){
	echo "<td style='background-color: #ff0000; color: #ffffff;'>";
	
		echo "<b>".date('d-m-Y',strtotime($row['sip_tes_tarih']))."</b>";
	}
	else{
		echo "<td>";
		echo "<b>".date('d-m-Y',strtotime($row['sip_tes_tarih']))."</b>";
		
	}
	?>
	
	
	
	<?php echo "</td>";?>
	<?php echo "<td style='max-width: 25ch; word-wrap:break-word;'><b>{$row['urun']}</b></td>";?>
	<td style="max-width: 25ch; word-wrap:break-word;">
	<?php if ( file_exists( "resimler/".$row['sip_id'].".jpg" ) ){ 
	
	
	
	echo "<center><img src='resimler/{$row['sip_id']}.jpg' width='25' height='25' id='{$row['sip_id']}' onclick=calis('{$row['sip_id']}')></center>";}else {echo "<center>---</center>";}?>
	
	
	
	</td>
	<?php echo "<td style='max-width: 25ch; word-wrap:break-word;'><b>{$row['notu']}</b></td>";?>
<?php 
	if($row['aciliyet_no']==2){
		echo "<td style='background-color: #ffff00; color: #000000;'>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
	}
	else if($row['aciliyet_no']==3){
		echo "<td style='background-color: #ff0000; color: #ffffff;'>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
	}
	else{
		echo "<td>";
		echo "<center><b>".$row['aciliyet_adi']."</b></center>"; 
		
	}
	
	
	?>
	
	<?php echo "</td>"?>
	<?php 
	
	if($row['rutbe_no']==6){
		echo "<td>";
		echo "<center><b>".$row['rutbe_adi']."</b></center>";
		if($row['kargo_tarih']!=null){
			echo "<center><b>".$row['kargo_tarih']."</b></center>";
			
		}
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
	
		
	}
	echo "</td>";
	 ?>
	<?php 
	if($row['rutbe_no']<5){
	$sip_no=$row['sip_id'];
	echo "<td><center><button name='sip_id' value='$sip_no' type='submit' class='btn btn-warning'>İŞİ TAMAMLA</button></center></td>";
	}else{
		echo "<td></td>";
	}
	
	?>
<?php echo "</tr>"; }?>
	

	
	
	
	
<?php endforeach;?>
</tbody>
    </table>
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
<body>
</html>
