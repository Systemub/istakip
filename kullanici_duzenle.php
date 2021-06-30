<?php
include("conn.php");
session_start();
$id= $_POST['id'];

if($_SESSION['yetki']==0){
	
$sonuc=$db->prepare("SELECT * FROM kullanicilar inner join yetki on kullanicilar.rutbe_no=yetki.rutbe_no where id='$id'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);	

}else{
	header("Location: logout.php");
	
	
}

$sonuc1=$db->prepare("SELECT * FROM yetki");
$sonuc1->execute();
$inner_ham = $sonuc1->fetchAll(PDO::FETCH_ASSOC);	


?>


<?php include("bar.php");?>

<div class="container">
  <h2>Kullanıcı Düzenleme</h2>

  
  <form class="form-horizontal" action="/kullanici_duzenle_kaydet.php" method="post">
   <input type="label" hidden="on" value=<?php echo $inner_gelen['id']; ?> name="id">
   
   <div class="form-group">
      <label class="control-label col-sm-2" >Kullanici Adi:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['kullanici_adi']; ?>"class="form-control" placeholder="Kullanici Adi:" name="kullanici_adi">
      </div>
	
    </div>
	<div class="form-group">
      <label class="control-label col-sm-2">Kullanici Sifre:</label>
      <div class="col-sm-10">
        <input type="text"  value="<?php echo $inner_gelen['kullanici_sifre'];?>" class="form-control"  placeholder="Kullanici Sifre:" name="kullanici_sifre">
      </div>
	  
    </div>
	
	
	
	
	<div class="form-group">
      <label class="control-label col-sm-2">Adi:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['adi'];?>" class="form-control"  placeholder="Adi:" name="adi">
      </div>
	  
    </div>
      
	  
	  <div class="form-group">
      <label class="control-label col-sm-2">Soyadi:</label>
      <div class="col-sm-10">
        <input type="text" value="<?php echo $inner_gelen['soyadi'];?>" class="form-control"  placeholder="Soyadi:" name="soyadi">
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
      <label class="control-label col-sm-2">Görev:</label>
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
      <div class="col-sm-offset-10 pull-right">
        <a href="kullanicilar.php" class="btn btn-danger">İptal</a>
		<button type="submit" class="btn btn-success">Kaydet</button>
      </div>
    </div>
  </form>
</div>

</body>
</html>