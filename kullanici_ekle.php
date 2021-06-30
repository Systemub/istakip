<?php
include("conn.php");
session_start();


if($_SESSION['yetki']==0){
	


$sonuc1=$db->prepare("SELECT * FROM yetki");
$sonuc1->execute();
$inner_ham = $sonuc1->fetchAll(PDO::FETCH_ASSOC);	

}

?>


<?php include("bar.php");?>

<div class="container">
  <center><h2>Kullanıcı Ekle</h2></center>

  
  <form class="form-horizontal" action="/kullanici_ekle_kaydet.php" method="post">
  
   
   <div class="form-group">
      <label class="control-label col-sm-2" >Kullanici Adi:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" placeholder="Kullanici Adi:" name="kullanici_adi">
      </div>
	
    </div>
	<div class="form-group">
      <label class="control-label col-sm-2">Kullanici Sifre:</label>
      <div class="col-sm-10">
        <input type="text"  class="form-control"  placeholder="Kullanici Sifre:" name="kullanici_sifre">
      </div>
	  
    </div>
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Adi:</label>
      <div class="col-sm-10">
        <input type="text"  class="form-control"  placeholder="Adi:" name="adi">
      </div>
	  
    </div>
      
	  
	  <div class="form-group">
      <label class="control-label col-sm-2">Soyadi:</label>
      <div class="col-sm-10">
        <input type="text"  class="form-control"  placeholder="Soyadi:" name="soyadi">
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
      <label class="control-label col-sm-2">Görev:</label>
      <div class="col-sm-10">
        <select name="rutbe_no" class="form-control">
       <?php foreach($inner_ham as $rows) : ?>
	   
	   <option value=<?php echo $rows['rutbe_no']; 
	   echo " ";
	 
	   
	   ?>><?php echo $rows['rutbe_adi'];?></option>
	   
	   
    <?php endforeach;?>
      </select> 
      </div>
	  
    </div>
	
	
    <div class="form-group">        
      <div class="col-sm-offset-10 pull-right">
        <a href="kullanicilar.php" class="btn btn-danger">İptal</a>
		<button type="submit" class="btn btn-success">Kaydet</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>