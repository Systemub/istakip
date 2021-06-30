<?php
include("conn.php");
include("bar.php");
$sip_id= $_POST['sip_id'];

if($_SESSION['yetki']==0){
	
$sonuc=$db->prepare("select * from siparis s inner join siparis_yeri_kopru syk on s.sip_yeri_no=syk.sip_yeri_no inner join siparis_acilyet_kopru sak on s.aciliyet_no=sak.aciliyet_no inner join yetki y on s.rutbe_no=y.rutbe_no where sip_id=$sip_id");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);





 //

}else{
	
	header("Location: logout.php");
	
	
}

$sonuc1=$db->prepare("SELECT * FROM yetki");
$sonuc1->execute();
$inner_ham = $sonuc1->fetchAll(PDO::FETCH_ASSOC);	



$sonuc1=$db->prepare("SELECT * FROM siparis_yeri_kopru");
$sonuc1->execute();
$sip_yeri_tablo = $sonuc1->fetchAll(PDO::FETCH_ASSOC);



$sonuc1=$db->prepare("SELECT * FROM siparis_acilyet_kopru");
$sonuc1->execute();
$sip_aciliyet_tablo = $sonuc1->fetchAll(PDO::FETCH_ASSOC);
?>

<script>

function d(){

document.getElementById("kargo").value = "";
}

</script>


<div class="container" onload="d()">
  <center><h2>Sipariş Düzenleme </h2></center><br><br>
  
  


  
  <form class="form-horizontal" action="/siparis_duzenle_kaydet.php" method="post" enctype="multipart/form-data">
 
   <input type="text" hidden="on" value="<?php echo $inner_gelen['sip_id'];?>"    name="sip_id">
   <div class="form-group">
      <label class="control-label col-sm-2" >Sipariş ID:</label>
      <div class="col-sm-10">
        <label  class="form-control"><?php echo $sip_id;?></label>
      </div>
	
    </div>
	
	
	
<div class="form-group">
      <label class="control-label col-sm-2">Sipariş Türü:</label>
      <div class="col-sm-10">
        <select name="sip_yeri_no" class="form-control">
       <?php foreach($sip_yeri_tablo as $rows) : ?>
	   
	   
	   
	   <option value=<?php echo $rows['sip_yeri_no']; 
	   echo " ";
	  
	   if($rows['sip_yeri_no']==$inner_gelen['sip_yeri_no']){ echo "selected";}
	   ?> >
	   
	   <?php echo $rows['sip_yeri_adi'];?></option>
	   
	   
    <?php endforeach;?>
      </select> 
      </div>
	  
    </div>
	
	
		<div class="form-group">
      <label class="control-label col-sm-2">Sipariş Yeri Kodu:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['sip_yeri_no_kod'];?>" class="form-control"   name="sip_yeri_no_kod">
      </div>
	  
    </div>
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Müşteri Ad Soyad:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['mus_ad_soyad'];?>" class="form-control"   name="mus_ad_soyad">
      </div>
	  
    </div>
      
	  
	  <div class="form-group">
      <label class="control-label col-sm-2">Müşteri Tel:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['mus_tel']; ?>" class="form-control"  name="mus_tel">
      </div>
	  
    </div>
	
	
	
	  <div class="form-group">
      <label class="control-label col-sm-2">Sipariş Geliş Tarihi:</label>
        <div class="col-sm-2">
        <input type="date" value="<?php echo $inner_gelen['sip_gel_tarih']; ?>" class="form-control"   name="sip_gel_tarih">
      </div>
	  
    </div>
	
	
	
	  <div class="form-group">
      <label class="control-label col-sm-2">Sipariş Teslim Tarihi:</label>
        <div class="col-sm-2">
        <input type="date" value="<?php echo $inner_gelen['sip_tes_tarih']; ?>" class="form-control"  name="sip_tes_tarih">
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Kargo Tarihi:</label>
        <div class="col-sm-2">
        <input type="date" value="<?php echo $inner_gelen['kargo_tarih']; ?>" class="form-control"  name="kargo_tarih" id="kargo"><input type="button"  class="btn btn-warning" onclick="d()" value="Sifirla-Geri Al">
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Ürün:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;"  class="form-control" rows="5"  name="urun"><?php echo $inner_gelen['urun']; ?></textarea>
      </div>
      </div>
	
	
	<?php if ( file_exists( "resimler/".$sip_id.".jpg" ) ){
echo "<div class='form-group'>";
echo "<label class='control-label col-sm-2'>Var Olan Resim:</label>";
echo "<div class='col-sm-5'>";
       echo "<img src='resimler/{$sip_id}.jpg' width='100' height='100'>";
echo "</div>";
echo "</div>";

	}
	
	
	
	
	?>
	
	
		<div class="form-group">
      <label class="control-label col-sm-2">Resim:</label>
      <div class="col-sm-4">
        <input type="file"  class="form-control"  name="resim" id="resim">
      </div>
	 </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Not:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;"  class="form-control" rows="5"  name="notu"><?php echo $inner_gelen['notu']; ?></textarea>
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Kargo Adresi:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;"  class="form-control" rows="5"  name="adres"><?php echo $inner_gelen['adres']; ?></textarea>
      </div>
	  
    </div>
	
	<div class="form-group">
      <label class="control-label col-sm-2" style="color: #ff3333;">Acil:</label>
      <div class="col-sm-10">
        <select name="aciliyet_no" class="form-control">
       <?php foreach($sip_aciliyet_tablo as $rows) : ?>
	   
	   <option value=<?php echo $rows['aciliyet_no']; 
	   echo " ";
	  if($rows['aciliyet_no']==$inner_gelen['aciliyet_no']){ echo "selected";}
	   
	   ?>><?php echo $rows['aciliyet_adi'];?></option>
	   
	   
    <?php endforeach;?>
      </select> 
      </div>
	  
    </div>
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Şube:</label>
      <div class="col-sm-10">
      <select name="SUBE" class="form-control">
      <?php
	  
	  
	  
	  
	  for($i=1;$i<3;$i++){
		  if($inner_gelen['SUBE']==$i){
			  
			 echo  '<option value='; echo $i.' ';      echo 'selected>'; echo $i;'</option>';
			  
		  }
		    
		  else{
			  echo  '<option value='; echo $i;      echo  '>'; echo $i;'</option>';
			  
			  
		  }
		  
	  }
     

	  ?>
	  
	 
    
      </select> 
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Üretim Adımı:</label>
      <div class="col-sm-10">
        <select name="rutbe_no" class="form-control">
       <?php foreach($inner_ham as $rows) : ?>
	   
	   <option value=<?php echo $rows['rutbe_no']; 
	   echo " ";
	   if($rows['rutbe_no']==$inner_gelen['rutbe_no']){
		   echo "selected";
		   
	   }
	   
	   ?>><?php echo $rows['rutbe_adi'];?></option>
	   
	   
    <?php endforeach;?>
      </select> 
      </div>
	  
    </div>
	
	 <div class="form-group">
      <label class="control-label col-sm-2">FİYATI:</label>
      <div class="col-sm-2">
        <input type="number"  value="<?php echo $inner_gelen['fiyat']; ?>" class="form-control"  name="fiyat">
      </div>
	   <div>
       <label><h4>TL</h4></label>
      </div>
	  
    <div class="form-group">        
      <div class="col-sm-offset-10 pull-right">
        <a href="siparis.php" class="btn btn-danger">İptal</a>
		<button type="submit" class="btn btn-success">Kaydet</button>
      </div>
    </div>
	
		
    </div>
	
  </form>
</div>

</body>
</html>