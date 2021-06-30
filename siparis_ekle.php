<?php
include("conn.php");
include("bar.php");


if($_SESSION['yetki']==0){
	


$sonuc1=$db->prepare("SELECT * FROM yetki");
$sonuc1->execute();
$yetki_tablo = $sonuc1->fetchAll(PDO::FETCH_ASSOC);	

$sonuc1=$db->prepare("SELECT * FROM siparis_yeri_kopru");
$sonuc1->execute();
$sip_yeri_tablo = $sonuc1->fetchAll(PDO::FETCH_ASSOC);



$sonuc1=$db->prepare("SELECT * FROM siparis_acilyet_kopru");
$sonuc1->execute();
$sip_aciliyet_tablo = $sonuc1->fetchAll(PDO::FETCH_ASSOC);




}

?>




<div class="container">
  <center><h2>Sipariş Ekle</h2></center>

  
  <form class="form-horizontal" action="/siparis_ekle_kaydet.php" method="post" enctype="multipart/form-data">
  
   
<div class="form-group">
      <label class="control-label col-sm-2">Sipariş Türü:</label>
      <div class="col-sm-10">
        <select name="sip_yeri_no" class="form-control">
       <?php foreach($sip_yeri_tablo as $rows) : ?>
	   
	   <option value=<?php echo $rows['sip_yeri_no']; 
	   echo " ";
	 
	   
	   ?>><?php echo $rows['sip_yeri_adi'];?></option>
	   
	   
    <?php endforeach;?>
      </select> 
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Şipariş Yeri Kodu:</label>
      <div class="col-sm-10">
        <input type="text"  class="form-control"  placeholder="Şipariş Yeri Kodu:" name="sip_yeri_no_kod">
      </div>
	  
    </div>
	
	<div class="form-group">
      <label class="control-label col-sm-2">Müşteri Adi Soyadi:</label>
      <div class="col-sm-10">
        <input type="text"  class="form-control"  placeholder="Müşteri Adi Soyadi:" name="mus_ad_soyad">
      </div>
	  
    </div>
	
	
		<div class="form-group">
      <label class="control-label col-sm-2">Müşteri TEL:</label>
      <div class="col-sm-10">
        <input type="number"  class="form-control"  placeholder="Müşteri Telefon:" name="mus_tel">
      </div>
	  
    </div>
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Sipariş Geliş Tarihi:</label>
      <div class="col-sm-2">
        <input type="date"  class="form-control"   name="sip_gel_tarih">
      </div>
	  
    </div>
      
	  
	  <div class="form-group">
      <label class="control-label col-sm-2">Sipariş Teslim Tarihi:</label>
      <div class="col-sm-2">
        <input type="date"  class="form-control"  name="sip_tes_tarih">
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Ürün:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;" class="form-control" rows="5"  name="urun"></textarea>
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Resim:</label>
      <div class="col-sm-4">
        <input type="file"  class="form-control"  name="resim" id="resim">
      </div>
	  
    </div>
	
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Not:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;" class="form-control" rows="5"  name="notu"></textarea>
      </div>
	  
    </div>
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Kargo Adresi:</label>
      <div class="col-sm-10">
        <textarea style="resize:none;" class="form-control" rows="5"  name="adres"></textarea>
      </div>
	  
    </div>
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2" style="color: #ff3333;">Acil:</label>
      <div class="col-sm-10">
        <select name="aciliyet_no" class="form-control">
       <?php foreach($sip_aciliyet_tablo as $rows) : ?>
	   
	   <option value=<?php echo $rows['aciliyet_no']; 
	   echo " ";
	 
	   
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
		 
			  echo  '<option value='; echo $i;      echo  '>'; echo $i;'</option>';
			  
			  
		  }
		  
	  
     

	  ?>
	  
	 
    
      </select> 
      </div>
	  
    </div>
	
	
	 <div class="form-group">
      <label class="control-label col-sm-2">FİYATI:</label>
      <div class="col-sm-2">
        <input type="number"  class="form-control"  name="fiyat">
      </div>
	   <div>
       <label><h4>TL</h4></label>
      </div>
    </div>
	
	
	
	
    <div class="form-group">        
      <div class="col-sm-offset-10 pull-right">
        <a href="siparis.php" class="btn btn-danger">İptal</a>
		<button type="submit" class="btn btn-success">Kaydet</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>