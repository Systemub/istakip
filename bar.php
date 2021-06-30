<?php
    if(!isset($_SESSION)) 
    { 

        session_start(); 
    }
   
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <title>İS TAKİP</title>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Language" content="TR"/>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>


  
  </script>
  <style>

</style>
</head>
<body>

<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand">NTCONCEPT</a>
    </div>
	
    <ul class="nav navbar-nav">
     <?php 
	 if(isset($_SESSION['rutbe'])){
		 if($_SESSION['rutbe']<1){
	 echo "
	 
	 <li><a href='/siparis.php'><b>SiPARiŞ</b></a></li>
     <li><a href='/kullanicilar.php'><b>KULLANICILAR</b></a></li>
	 <li><a href='/muhasebe.php'><b>MUHASEBE</b></a></li>
		 ";
	 }else{
		 
		  echo "<li><a href='/durum.php'><b>İŞLERİM</b></a></li>";
		 
		 
	 }
	 
	 
	 
	 }
		?>
    </ul>
	

    <ul class="nav navbar-nav navbar-right">                              
      <li><a><span class="glyphicon glyphicon-user"></span><?php echo " ".$_SESSION['kullanici_adi']." - ".$_SESSION['yetki'];?></a></li>
     <li><a href="logout.php"></span> ÇIKIŞ YAP</a></li>
    </ul>
  </div>
</nav>