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

?>
<?php
	
$sonuc=$db->prepare("SELECT * FROM kullanicilar inner join yetki on kullanicilar.rutbe_no=yetki.rutbe_no ORDER BY yetki.rutbe_no asc");
$sonuc->execute();
$inner_gelen = $sonuc->fetchAll(PDO::FETCH_ASSOC);

?>
	

	<div class="container">
     <form action="kullanici_ekle.php" method="post"><button name="id" type="submit"  class="btn btn-success ">Kullanıcı Ekle +</button></form>
	 <br><br>
	 <table class="table table-bordered">
    <thead>
      <tr  class="info">
	  
        <th>Kullanici Adi</th>
        <th>Kullanici Sifre</th>
        <th>Adi</th>
		<th>Soyadi</th>
		<th>Sube</th>
		<th>Gorev</th>
        <th colspan="2"><center>Düzenle</center></th>
	  </tr>
    </thead>
	<tbody>
	
	
	
	<?php foreach($inner_gelen as $row) : ?>
<?php if((int)$row['id']%2==0){
	echo '<tr class="active">';
	
}else{
	echo '<tr>';
	
}

?>

    <td><?php echo $row['kullanici_adi']; ?></td>
    <td><?php echo $row['kullanici_sifre']; ?></td>
    <td><?php echo $row['adi']; ?></td>
    <td><?php echo $row['soyadi']; ?></td>
    <td><?php echo $row['SUBE']; ?></td>
	<td><?php echo $row['rutbe_adi']; ?></td>
	<td><center><form action="kullanici_duzenle.php" method="post"><button name="id" value="<?php echo $row['id'];?>" type="submit" class="btn btn-warning">Düzenle</button></form></center></td>
	<td><center><form action="kullanici_sil_kaydet.php" method="post"><button name="id" value="<?php echo $row['id'];?>" type="submit" class="btn btn-danger">Sil</button></form></center></td>
	
</tr>
<?php endforeach;?>

    </tbody>
  </table>

  </div>
	