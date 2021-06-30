<?php
session_start();
ob_start();



if(isset($_SESSION['yetki'])){
	
	
	
}



?>


<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

<!-- no additional media querie or css is required -->
<div style="background-image: url('https://i.pinimg.com/originals/c2/d6/db/c2d6dbee2864ad9dc506f41b30d911f0.jpg');">

<div class="container" >
	
        <div class="row justify-content-center align-items-center" style="height:100vh">
	
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <form  autocomplete="off" action="bru.php" method="post">
							<center><div class="well">NT CONCEPT İŞ TAKİP</div></center>
							<br>
                            <div class="form-group">
                                <input type="text" class="form-control" name="username" placeholder="Kullanıcı Adı">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Şifre">
                            </div>
                            <center><button type="submit" id="sendlogin" class="btn btn-primary">GİRİŞ YAP</button></center>
                            
                        </form>
						<?php
						if (isset($_GET['sonuc'])) {
	if($_GET['sonuc']=="basarisiz"){
							echo '
						 <div class="alert alert-danger">
  <center><strong>Başarısız !</strong></center><br> Kullanici adiniz yada şifreniz yanliş...
</div>
						';}
	
	
}
						
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>
	   </div>
	<?php ob_end_flush();?>