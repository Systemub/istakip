<?php 


	$host="localhost";
	$dbname="ntconcep_is_takip";

	try{
	    $db= new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
	
		// türkçe karakter için utf8
		//$db->exec("SET CHARSET UTF8");		//$db->exec("SET CHARACTER SET utf8_general_ci");                $db->query(“SET NAMES utf8”);
		//eğer hata olursa pdo nun exception komutu ile ekrana yazdırıyoruz
	}catch(PDOException $e){
         die("Could not connect to the database $dbname :" . $e->getMessage());

	
	}
?>