<?php
include("conn.php");


session_start();

	
if(isset($_POST['username'])&&isset($_POST['password']))


{


$kullanici_adi=$_POST['username'];
$kullanici_sifre=$_POST['password'];


}




  
$sonuc=$db->prepare("SELECT * FROM kullanicilar inner join yetki on kullanicilar.rutbe_no=yetki.rutbe_no where kullanici_adi='$kullanici_adi' and kullanici_sifre='$kullanici_sifre'");
$sonuc->execute();
$inner_gelen = $sonuc->fetch(PDO::FETCH_ASSOC);


if($inner_gelen['id']!=null){
 

$_SESSION['oturum']=true;
$_SESSION['kullanici_adi']=$kullanici_adi;
$_SESSION['yetki']=$inner_gelen['rutbe_adi'];
$_SESSION['rutbe']=$inner_gelen['rutbe_no'];
$_SESSION['sube']=$inner_gelen['SUBE'];			  
if($inner_gelen['rutbe_no']<1){
	
	header("Location: siparis.php");
	
}
else{
	
	header("Location: durum.php");
}	  
				 
		  
} 
else{
	
header("Location: login.php?sonuc=basarisiz");
	
}


	
		  
	



?>

