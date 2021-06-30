<?php 



require_once("conn.php");
require_once("Mailer/mail.php");



function msg($basarim=0,$message,$result=array()){

$response['basarim']=$basarim;
$response['message']=$message;
$response['response']=$result;


echo json_encode($response,JSON_UNESCAPED_UNICODE);
}

  
function googleGetUserInfo($authKey){
    $context = stream_context_create(array(
        'http' => array(
            'ignore_errors' => true,
            'timeout' => 50,
            'method'  => 'GET',
            'content' => ''
         )
    ));
    $str = $authKey;
    $url = "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=$str";
   
    return file_get_contents($url,false,$context);
}

//Random Key Creator Start//
function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

function getRandomKey($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    //$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";//Request change by mobile developer.
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}


function getRandomKeyOnlyNumber($length)
{
    $token = "";
    //$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    //$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";//Request change by mobile developer.
    $codeAlphabet= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max-1)];
    }

    return $token;
}
//Random Key Creator End//

$decoded=@json_decode($_POST['data']);






if(!isset($decoded->roll)){
    echo "Parametre Gönderilmedi KES SOFT&SEC";
    exit();
}
    



if($decoded->roll=='costumerRegister'){


    
        $fName=$decoded->fName;
        $sName=$decoded->sName;
        $email=$decoded->email;
        $passphrase=md5($decoded->passphrase);
        $phone=$decoded->phone;
        $smsVerificationCode=getRandomKeyOnlyNumber(6);
        $loginAuthKey=md5($passphrase.$email.time());


        $insert_stmt = $conn->prepare("INSERT INTO Consumer(fName, sName, email, passphrase, phone,smsVerificationCode,loginAuthKey,smsVerified) VALUES (?,?,?,?,?,?,?,?)");
      
        $insert_stmt->execute([$fName, $sName, $email, $passphrase,$phone,$smsVerificationCode,$loginAuthKey,0]);
        $arr = $insert_stmt->errorInfo();
    
        if(!isset($arr[2])){
            $stmt = $conn->prepare("SELECT ID,smsVerificationCode,loginAuthKey FROM Consumer WHERE email = '$email' and passphrase='$passphrase' LIMIT 1");
        
            if($stmt->execute()){
            
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if(isset($row['ID'])){
                        return $returnData = msg(1,"Kullanıcı Kaydedildi.",$row);
                       
                    }
                
                
                }
            return $returnData = msg(1,"Veritabanı Yürütme Hatası.");
            
        }
        else{
           
            preg_match('/for.key.\'(.*)\'/', $arr[2], $infos);  
            if($infos[1]=='phone'){$infos[1]='telefon';}
            
            $answer='Bu '.strtolower($infos[1]).' kullanmaktadır!';
            return $returnData = msg(0,$answer);
        }

}



if($decoded->roll=='producerRegister'){
    $fileErr=1;
    $filename = $decoded->email.'.pdf'; 
    $fName=$decoded->fName;
    $sName=$decoded->sName;
    $email=$decoded->email;
    $passphrase=md5($decoded->passphrase);
    $phone=$decoded->phone;
    $smsVerificationCode=getRandomKeyOnlyNumber(6);
    $verificationDocUrl="http://www.****.com/pazarYeriApi/files/".$filename;
    $loginAuthKey=md5($passphrase.$email.time());

    $insert_stmt = $conn->prepare("INSERT INTO Producer (fName, sName, email, passphrase, phone,smsVerificationCode, verificationDocUrl,loginAuthKey,smsVerified) VALUES (?,?,?,?,?,?,?,?,?)");
    $insert_stmt->execute([$fName, $sName, $email, $passphrase,$phone,$smsVerificationCode,$loginAuthKey,$verificationDocUrl,0]);
    $arr = $insert_stmt->errorInfo();
   
    
    if(!isset($arr[2])){
        if(isset($_FILES["file"])){
            $target_dir = "files/"; 
            $savefile = "$target_dir/$filename";
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $savefile)) {
                $fileErr = 0;
            }
            if($fileErr){
                $sql = "DELETE FROM Producer WHERE email = ?";        
                $q = $con->prepare($sql);
                $response = $q->execute([$email]);
            }
        }
        if(!$fileErr){
            $stmt = $conn->prepare("SELECT ID,smsVerificationCode, verificationDocUrl,loginAuthKey FROM Producer WHERE email = '$email' and passphrase='$passphrase' LIMIT 1");
            if($stmt->execute()){
              
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['ID'])){
                return $returnData = msg(1,"Üretici kayıt edildi.",$row);
            }    
        }
        return $returnData = msg(1,"Üretici kayıt edildi.");
        }
    }
    else{
        preg_match('/for.key.\'(.*)\'/', $arr[2], $infos);  
        if($infos[1]=='phone'){$infos[1]='telefon';}
        $answer='Bu '.strtolower($infos[1]).' kullanmaktadır!';
        return $returnData = msg(0,$answer);
    }

}


if($decoded->roll=='login'){
    
    $email=$decoded->email;
    $passphrase=md5($decoded->passphrase);

    //EMAIL CONTROL//
    $stmt = $conn->prepare("SELECT ID,email FROM Producer WHERE email = '$email'
    UNION
    SELECT ID,email FROM Consumer WHERE email = '$email'");
    if($stmt->execute()){ 
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(!isset($row['ID'])){  
        return $returnData = msg(0,"Hiçbir Üye Bulunamadı.",$row);
        exit();
    }
    }
    //EMAILCONTROLEND//




    $loginAuthKey=md5($passphrase.$email.time());
    
    $fileState=0;
    $dir="files/$email.pdf";
    $founder=glob($dir);
    
        if(isset($founder[0])){
            $fileState=1;
        }

 
    //start
    $stmt = $conn->prepare("SELECT ID, fName, sName, email, phone, isVerified, smsVerificationCode, smsVerified, registerDate,loginAuthKey,photoUrl FROM Producer WHERE email = '$email' and passphrase='$passphrase' LIMIT 1");
    if($stmt->execute()){ 
      
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($row['ID'])&&$fileState){
        $userID=$row['ID'];

        $stmt = $conn->prepare("SELECT Farm.ID FROM Farm WHERE Farm.ownerID='$userID' LIMIT 1");
        if($stmt->execute()){
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($_row['ID'])){
                    $row['FarmID']=$_row['ID'];
            }
         
        }
        
        
        if($row['smsVerified']==0){
            $arr=['loginAuthKey'=>$row['loginAuthKey']];
            return $returnData = msg(-1,"Telefon Numarasıni Onaylayınız.",$arr);
            exit();
        }
        
        
        $stmt = $conn->prepare("UPDATE Producer SET loginAuthKey='$loginAuthKey' where ID=$userID");
        if($stmt->execute()){$row['loginAuthKeyUpdate']=1;$row['loginAuthKey']=$loginAuthKey;}else{$row['loginKeyUpdate']=0;}
    $row['isProducer']=1;
    

   
    return $returnData = msg(1,"Üretici girişi başarılı",$row);
    exit();
    }
    } //stop
    $stmt = $conn->prepare("SELECT ID, fName, sName, email,phone, smsVerificationCode,smsVerified,registerDate,loginAuthKey FROM Consumer WHERE email = '$email' and passphrase='$passphrase' LIMIT 1");
        
    if($stmt->execute()){
            
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
        if(isset($row['ID'])){
            $userID=$row['ID'];
            if($row['smsVerified']==0){
                $arr=['loginAuthKey'=>$row['loginAuthKey']];
                return $returnData = msg(-1,"Telefon Numarasıni Onaylayınız.",$arr);
                exit();
            }
        $stmt = $conn->prepare("UPDATE Consumer SET loginAuthKey='$loginAuthKey' where ID=$userID");
        if($stmt->execute()){$row['loginAuthKeyUpdate']=1;$row['loginAuthKey']=$loginAuthKey;}else{$row['loginKeyUpdate']=0;}
        $row['isProducer']=0;
        

        return $returnData = msg(1,"Kullanıcı girişi başarılı.",$row);
    }
    }
        
    return $returnData = msg(0,"Parola Hatalı.");
}
    

if($decoded->roll=='forgotPass'){
    if(isset($decoded->phone)){
        $phone=$decoded->phone;
        $sql="SELECT * FROM Consumer WHERE phone ='$phone' LIMIT 1";
    
        $stmt = $conn->prepare($sql);
            
        if($stmt->execute()){
                
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
            if(isset($row['ID'])){
                $userID=$row['ID'];
                $smsVerificationCode=getRandomKeyOnlyNumber(6);
                $sql="UPDATE Consumer SET smsVerificationCode='$smsVerificationCode' where ID=$userID";
               
               $stmt = $conn->prepare($sql);
               if($stmt->execute()){
                $arr=["smsVerificationCode"=>$smsVerificationCode];
                return $returnData = msg(1,"Sms Gönderildi",$arr);
        
               }
               else{
        
                return $returnData = msg(0,"Telefon Numarası Sisteme Kayıtlı");
        
               }
            }
            else{
                $sql="SELECT * FROM Consumer WHERE email ='$phone' LIMIT 1";

                $stmt = $conn->prepare($sql);
                    
                if($stmt->execute()){
                        
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        
                    if(isset($row['ID'])){
                        $userID=$row['ID'];
                        $smsVerificationCode=getRandomKeyOnlyNumber(6);
                        $sql="UPDATE Consumer SET smsVerificationCode='$smsVerificationCode' where ID=$userID";
                       
                       $stmt = $conn->prepare($sql);
                       if($stmt->execute()){
                        $arr=["smsVerificationCode"=>$smsVerificationCode];
                        return $returnData = msg(1,"Sms Gönderildi",$arr);
                
                       }
                       else{
                
                        return $returnData = msg(0,"Telefon Numarası Sisteme Kayıtlı");
                
                       }
                    }
                    else{
                        return $returnData = msg(0,"Telefon Numarasına Kayıtlı Kullanıcı Yok");
                    }
                
            }


            
        
        }


    }


    

    if(isset($decoded->email)){
        $email=$decoded->email;

 
    
   
    

    $newAuthKey=md5(getRandomKey(10));

    $sql="SELECT * FROM Consumer WHERE email ='$email' LIMIT 1";

    $stmt = $conn->prepare($sql);
        
    if($stmt->execute()){
            
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
        if(isset($row['ID'])){
            $userID=$row['ID'];
           
            Sender($email,"Sifre Sifirlama Talebi","<a href='https://www.****.com/pazarYeriApi/passwordService?key=$newAuthKey'>SIFIRLAMA LINKI</a1>","Register Service By TechTorial");
           
            $stmt = $conn->prepare("UPDATE Consumer SET loginAuthKey='$newAuthKey' where ID=$userID");
            if($stmt->execute()){
                return  $returnData = msg(1,"Sıfırlama Maili Gönderildi.");
            }else{
                return $returnData = msg(0,"Sıfırlama Hatası.");
            }
           
        }else{
            return $returnData = msg(0,"Mail Bulunamadı.");

        }

    }
   

    $sql="SELECT * FROM Producer WHERE email ='$email' LIMIT 1";

    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($row['ID'])){
            $userID=$row['ID'];
    
            Sender($email,utf8_encode("Sifre Sifirlama Talebi"),"<h1>".$newPassphrase."</h1>","Service by KES");
            $newPassphraseMd5=md5($newPassphrase);
            $stmt = $conn->prepare("UPDATE Producer SET passphrase='$newPassphraseMd5' where ID=$userID");
            if($stmt->execute()){
                return $returnData = msg(1,"Sıfırlama Maili Gönderildi.");
            }else{
                return $returnData = msg(0,"Sıfırlama Hatası.");
            }
        }


    }
}

}
}



if($decoded->roll=='loginGoogle'){
  
$googleAuthKey=$decoded->googleAuthKey;
$loginAuthKey=md5($googleAuthKey.time());


$email=@json_decode(googleGetUserInfo($googleAuthKey),true)['email'];


$stmt = $conn->prepare("SELECT * FROM Producer WHERE email = '$email'  LIMIT 1");
if($stmt->execute()){

$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(isset($row['ID'])){
    $userID=$row['ID'];

    $stmt = $conn->prepare("SELECT Farm.ID FROM Farm WHERE Farm.ownerID='$userID' LIMIT 1");
        if($stmt->execute()){
            $_row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($_row['ID'])){
                    $row['FarmID']=$_row['ID'];
            }
         
        }
   
    if($row['phone']==null){
        $arr=["loginAuthKey"=>$row['loginAuthKey']];
        return $returnData = msg(-1,"Telefon Numarası giriniz.",$arr);
        exit();
    }
        
        $stmt = $conn->prepare("UPDATE Producer SET loginAuthKey='$loginAuthKey' where ID=$userID");
        if($stmt->execute()){$row['loginAuthKeyUpdate']=1;}else{$row['loginKeyUpdate']=0;}
$row['isProducer']=1;
unset($row['passphrase']);
unset($row['verificationDocUrl']);
unset($row['smsVerificationCode']);
return $returnData = msg(1,"Üretici girişi başarılı",$row);
exit();
}
}
$stmt = $conn->prepare("SELECT * FROM Consumer WHERE email = '$email'  LIMIT 1");
    
if($stmt->execute()){
        
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row['ID'])){
        $userID=$row['ID'];
        if($row['phone']==null){
            $arr=["loginAuthKey"=>$row['loginAuthKey']];
            return $returnData = msg(-1,"Telefon Numarası giriniz.",$arr);
         
            exit();
        }
        
        $stmt = $conn->prepare("UPDATE Consumer SET loginAuthKey='$loginAuthKey' where ID=$userID");
            if($stmt->execute()){$row['loginAuthKeyUpdate']=1;}else{$row['loginKeyUpdate']=0;}
            $row['isProducer']=0;
            $row["loginAuthKey"]=$loginAuthKey;
            unset($row['passphrase']);
            unset($row['verificationDocUrl']);
            unset($row['smsVerificationCode']);
    return $returnData = msg(1,"Kullanıcı girişi başarılı.",$row);}
}
    
return $returnData = msg(0,"Hiçbir üye bulunamadı.");



}


if($decoded->roll=='registerConsumerGoogle'){
    
    $googleAuthKey=$decoded->googleAuthKey;
    $loginAuthKey=md5($googleAuthKey.time());
    
    
    $email=@json_decode(googleGetUserInfo($googleAuthKey),true)['email'];
    if(!isset($email)){
        return $returnData = msg(0,"Google'dan mail alınamadı.");

    }

    $fName=$decoded->fName;
    $sName=$decoded->sName;
    $passphrase=md5($email.time());
   



    $insert_stmt = $conn->prepare("INSERT INTO Consumer(fName, sName, email, passphrase,loginAuthKey,smsVerified) VALUES (?,?,?,?,?,?)");
  
    $insert_stmt->execute([$fName, $sName, $email, $passphrase,$loginAuthKey,0]);
    $arr = $insert_stmt->errorInfo();
    $arr2=["loginAuthKey"=>$loginAuthKey];
    if(!isset($arr[2])){
        return $returnData = msg(1,"Kullanıcı kaydedildi.",$arr);
        
    }
    else{
    
        preg_match('/for.key.\'(.*)\'/', $arr[2], $infos);  
        if($infos[1]=='phone'){$infos[1]='telefon';}
        
        $answer='Bu '.strtolower($infos[1]).' kullanmaktadır!';
        return $returnData = msg(0,$answer);
    }

    
    
    
    }

if($decoded->roll=='getProductCategories'){
    

        
        $stmt = $conn->prepare("SELECT * FROM Product_Categories order by id asc");
        if($stmt->execute()){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Kategori verileri getirildi.",$row);
        }else{return $returnData = msg(0,"Veritabanı yürütme hatası.");}
}


if($decoded->roll=='deleteMyTheProduct'){

    if(isset($decoded->productID)){
        $productID=$decoded->productID;
    }

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;

        $stmt = $conn->prepare("SELECT Farm.ID FROM Producer 
        inner join Farm on Farm.ownerID=Producer.ID
        where Producer.loginAuthKey='$loginAuthKey' LIMIT 1");
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['ID'])){
                $farmID=$row['ID'];
               
                
            }
            else{

                return $returnData = msg(0,"Oturum bulunamadı");
                exit();

            }
        }
       
    }else{
        return $returnData = msg(0,"Oturum bulunamadı");
        exit();


    }


    $stmt = $conn->prepare("DELETE FROM Product WHERE Product.ID=$productID AND Product.FarmID=$farmID");
    if($stmt->execute()){
        $stmt = $conn->prepare("DELETE FROM Basket WHERE Product.ID=$productID AND Product.FarmID=$farmID");
        if($stmt->execute()){
            //yazilacak
        }
        return $returnData = msg(1,"Ürün Silindi",$row);
    }
    else{
        return $returnData = msg(0,"Ürün Silme Başarısız");
    }


}




if($decoded->roll=='getTheFarmProduct'){

    if(isset($decoded->producerID)){
        $producerID=$decoded->producerID;
    }


  
       
  


    $stmt = $conn->prepare("SELECT Product.ID,Farm.ID as farmID,Product.title,Product.categoryID,Product_Categories.title as categoryName,Product.isOrganic,Product.description,Product.photoUrl,Product.saleTypeID,Product.isHarvesting,Product.inventorySize,Product.maxRange,Product.minSale,Product.packSize,Product.price,Product.shipmentPrice,Product.shipmentTime 
    FROM Producer 
    INNER JOIN Farm ON Farm.ownerID=Producer.ID 
    INNER JOIN Product ON Farm.ID=Product.farmID 
    INNER JOIN Product_Categories ON Product_Categories.ID=Product.categoryID 
    WHERE Producer.ID=$producerID");
    if($stmt->execute()){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Ürün verileri getirildi",$row);
    }
    else{
        return $returnData = msg(0,"Ürün bulunamadı");
    }


}



if($decoded->roll=='getOwnTheProducts'){

 

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;

        $stmt = $conn->prepare("SELECT Farm.ID FROM Producer 
        inner join Farm on Farm.ownerID=Producer.ID
        where Producer.loginAuthKey='$loginAuthKey' LIMIT 1");
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['ID'])){
                $farmID=$row['ID'];
               
                
            }
            else{

                return $returnData = msg(0,"Oturum bulunamadı");
                exit();

            }
        }
       
    }else{
        return $returnData = msg(0,"Oturum bulunamadı");
        exit();


    }


    $stmt = $conn->prepare("SELECT ID,(SELECT Product_Categories.title FROM Product_Categories WHERE Product_Categories.ID=Product.categoryID) as categoryName,categoryID,farmID, saleTypeID,isHarvesting,title,photoUrl,minSale,price,packSize,shipmentTime,inventorySize,maxRange, description,createdDate, shipmentPrice, isOrganic FROM Product WHERE  Product.FarmID=$farmID");
    if($stmt->execute()){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Ürün verileri getirildi",$row);
    }
    else{
        return $returnData = msg(0,"Ürün bulunamadı");
    }


}




if($decoded->roll=='getMyTheProduct'){

    if(isset($decoded->productID)){
        $productID=$decoded->productID;
    }

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;

        $stmt = $conn->prepare("SELECT Farm.ID FROM Producer 
        inner join Farm on Farm.ownerID=Producer.ID
        where Producer.loginAuthKey='$loginAuthKey' LIMIT 1");
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['ID'])){
                $farmID=$row['ID'];
               
                
            }
            else{

                return $returnData = msg(0,"Oturum bulunamadı");
                exit();

            }
        }
       
    }else{
        return $returnData = msg(0,"Oturum bulunamadı");
        exit();


    }


    $stmt = $conn->prepare("SELECT ID,(SELECT Product_Categories.title FROM Product_Categories WHERE Product_Categories.ID=Product.categoryID) as categoryName,categoryID,farmID, saleTypeID,isHarvesting,title,photoUrl,minSale,price,packSize,shipmentTime,inventorySize,maxRange, description,createdDate, shipmentPrice, isOrganic FROM Product WHERE Product.ID=$productID AND Product.FarmID=$farmID");
    if($stmt->execute()){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Ürün verileri getirildi",$row);
    }
    else{
        return $returnData = msg(0,"Ürün bulunamadı");
    }


}




if($decoded->roll=='getLastFarm'){
    $stmt = $conn->prepare("SELECT Farm.ID,Farm.ownerID,Farm.name,(SELECT Farm_Photos.photoUrl FROM Farm_Photos WHERE Farm_Photos.farmID=Farm.ID LIMIT 1) as farmPhoto,
    (SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate  FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate,
    City.name as cityName,Town.name as townName,townID FROM Farm
    INNER JOIN Farm_Address ON Farm_Address.farmID=Farm.ID 
    INNER JOIN Town ON Town.ID=Farm_Address.townID
    iNNER JOIN City ON City.ID=Town.cityID
    ORDER BY Farm.createdDate");
    if($stmt->execute()){
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Çiftlik verileri getirildi.",$row);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }
}



if($decoded->roll=='getLastFarmLastTen'){
    /*SELECT farmID,SUM(Farm_Comments.point)/COUNT(*) as Rate FROM Farm_Comments where farmID=1*/
    $stmt = $conn->prepare("SELECT Farm.ID,Farm.ownerID,Farm.name,(SELECT Farm_Photos.photoUrl FROM Farm_Photos WHERE Farm_Photos.farmID=Farm.ID LIMIT 1) as farmPhoto,
    (SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate  FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate,
    City.name as cityName,Town.name as townName,townID FROM Farm
    INNER JOIN Farm_Address ON Farm_Address.farmID=Farm.ID 
    INNER JOIN Town ON Town.ID=Farm_Address.townID
    iNNER JOIN City ON City.ID=Town.cityID
    ORDER BY Farm.createdDate LIMIT 10");
    if($stmt->execute()){
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Çiftlik verileri getirildi.",$row);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }
}




if($decoded->roll=='getProductProducer'){
    
    $productCategoryID=$decoded->productCategoryID;
        
    $stmt = $conn->prepare("SELECT Farm.ID,Farm.name,Farm_Photos.photoUrl,City.name as location,
    (SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate  FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate 
    FROM Product 
    INNER JOIN Product_Categories ON Product_Categories.ID=Product.categoryID 
    INNER JOIN Farm ON Farm.ID=Product.farmID 
    INNER JOIN Producer ON Producer.ID=Farm.ownerID
	INNER JOIN Farm_Address ON Farm_Address.farmID=Farm.ID
    INNER JOIN Town ON Town.ID=Farm_Address.townID
    INNER JOIN Farm_Photos ON Farm_Photos.farmID=Farm.ID
    INNER JOIN City ON City.ID=Town.cityID
    WHERE Product.categoryID='$productCategoryID'
    GROUP BY Farm.name");
    if($stmt->execute()){
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Kategori verileri getirildi.",$row);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }
}


if($decoded->roll=='getProductProducerSearch'){
   
    $productCategoryID=$decoded->productCategoryID;
    $title=$decoded->title;
   
    

    $title=explode(",",$title);
    $title=str_replace(array('[',']',""),"", $title);
    for($i=0;$i<count($title);$i++){trim($title[$i]);}
    
    $titleseparate = str_repeat("?,", count($title)-1) . "?";


    $sql="SELECT DISTINCT(Product.farmID),Farm.name as farmName,(SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate  FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate
    FROM Product 
    INNER JOIN Product_Categories ON Product_Categories.ID=Product.categoryID 
    INNER JOIN Farm ON Farm.ID=Product.farmID INNER JOIN Producer ON Producer.ID=Farm.ownerID 
    WHERE Product.title IN ($titleseparate) AND Product.categoryID='$productCategoryID' 
    ORDER BY Product.farmID";
    $stmt = $conn->prepare($sql);
    $farms=array();
    $lastArray=array();
    if($stmt->execute($title)){
        $farms=$stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    $sql="SELECT  Product.ID,Product.farmID,Product.saleTypeID,Product.photoUrl,Product.title,Product.price,Product.minSale,Product.maxRange,Farm.name as farmName,(SELECT Farm_Photos.photoUrl FROM Farm_Photos WHERE Farm_Photos.farmID=Farm.ID) as farmPhoto
    FROM Product 
    INNER JOIN Product_Categories ON Product_Categories.ID=Product.categoryID
    INNER JOIN Farm ON Farm.ID=Product.farmID 
    INNER JOIN Producer ON Producer.ID=Farm.ownerID 
    WHERE Product.title IN ($titleseparate) AND Product.categoryID='$productCategoryID' 
    ORDER BY Product.farmID";

    $stmt = $conn->prepare($sql);
    $masterState=array();
    
    if($stmt->execute($title)){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($farms as $farm){
              
        
                $masterState[]=array("farmName"=>$farm['farmName'],"farmID"=>$farm["farmID"],"rate"=>$farm["Rate"],"products"=>array());
      
        }

        foreach($row as $subRow){
            for($a=0;$a<count($masterState);$a++){
                if($masterState[$a]['farmID']==$subRow['farmID']){
                    $masterState[$a]["products"][]=$subRow;
                }
            }
        }
 
    
    return $returnData = msg(1,"Kategori verileri getirildi.",$masterState);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }

}

if($decoded->roll=='isHarvesting'){
    $isLimited=$decoded->isLimited;
    if($isLimited){
        $stmt = $conn->prepare("SELECT Product.ID,Product.title,Product.minSale,Product.price,Product.photoUrl
        FROM Product 
        WHERE Product.isHarvesting=1 LIMIT 10");
    }else{
        $stmt = $conn->prepare("SELECT Product.ID,Product.title,Product.minSale,Product.price,Product.photoUrl
        FROM Product 
        WHERE Product.isHarvesting=1");
    }
    if($stmt->execute()){
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Ürün verileri getirildi.",$row);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }
}


if($decoded->roll=='isOrganic'){
    $isLimited=$decoded->isLimited;
    if($isLimited){
        $stmt = $conn->prepare("SELECT Product.ID,Product.title,Product.photoUrl,Product.minSale,Product.price 
        FROM Product 
        WHERE Product.isOrganic=1 LIMIT 10");
    }else{
        $stmt = $conn->prepare("SELECT Product.ID,Product.title,Product.photoUrl,Product.minSale,Product.price 
        FROM Product 
        WHERE Product.isOrganic=1");
    }
   
    if($stmt->execute()){
    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $returnData = msg(1,"Ürün verileri getirildi.",$row);
    }else{
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
    }
}






if($decoded->roll=='getCategoryHaveFarm'){
    
    $categoryID=$decoded->categoryID;
   
        $stmt = $conn->prepare("SELECT DISTINCT(Farm.ID) as farmID,Farm.name as farmName,(SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate  FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate
        FROM Farm
        INNER JOIN Product 
        ON Product.farmID=Farm.ID
        WHERE Product.categoryID='$categoryID'
        GROUP BY Farm.ID");
        if($stmt->execute()){
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $returnData = msg(1,"Tezgahlar Getirildi.",$row);
        }
        else{
        return $returnData = msg(0,"Veriler getirilemedi.",$row);
        }
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
       
}


if($decoded->roll=='getCategoryTag'){
    
    $categoryID=$decoded->categoryID;
   
        $stmt = $conn->prepare("SELECT  Product.ID,Product.title
        FROM Farm
        INNER JOIN Product 
        ON Product.farmID=Farm.ID
        WHERE Product.categoryID='$categoryID'
        GROUP BY Product.title");
        if($stmt->execute()){
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $returnData = msg(1,"Başlıklar Getirildi.",$row);
        }
        else{
        return $returnData = msg(0,"Veriler getirilemedi.",$row);
        }
        return $returnData = msg(0,"Veritabanı yürütme hatası.");
       
}


if($decoded->roll=='getCity'){


    
    $stmt = $conn->prepare("SELECT ID,name FROM City");
    if($stmt->execute()){
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $returnData = msg(1,"Başlıklar Getirildi.",$row);
    }
    else{
    return $returnData = msg(0,"Veriler getirilemedi.",$row);
    }
    return $returnData = msg(0,"Veritabanı yürütme hatası.");



}

if($decoded->roll=='getTown'){


    $cityID=$decoded->cityID;
    $stmt = $conn->prepare("SELECT Town.ID,Town.name FROM Town where Town.cityID='$cityID'");
    if($stmt->execute()){
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $returnData = msg(1,"Başlıklar Getirildi.",$row);
    }
    else{
    return $returnData = msg(0,"Veriler getirilemedi.",$row);
    }
    return $returnData = msg(0,"Veritabanı yürütme hatası.");



}


// if($decoded->roll=='getTownTheCity'){

//     $townID=$decoded->townID;
//     $stmt = $conn->prepare("SELECT Town.ID,Town.name FROM Town where Town.ID='$townID'");
//     if($stmt->execute()){
//             $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
//             return $returnData = msg(1,"Başlıklar Getirildi.",$row);
//     }
//     else{
//     return $returnData = msg(0,"Veriler getirilemedi.",$row);
//     }
//     return $returnData = msg(0,"Veritabanı yürütme hatası.");

// }


if($decoded->roll=='getTownTheCity'){

    $townID=$decoded->townID;
    $stmt = $conn->prepare("SELECT Town.ID,Town.name as townName,Town.cityID,City.name as cityName FROM Town 
    INNER JOIN City ON City.ID=Town.cityID WHERE Town.ID=$townID");
    if($stmt->execute()){
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $returnData = msg(1,"Başlıklar Getirildi.",$row);
    }
    else{
    return $returnData = msg(0,"Veriler getirilemedi.",$row);
    }
    return $returnData = msg(0,"Veritabanı yürütme hatası.");
}

if($decoded->roll=='editFarmAddress'){



}


if($decoded->roll=='setAdvertisements'){



    $insert_stmt = $conn->prepare("INSERT INTO Advertisements (linkedFarmID, priority, bannerUrl) VALUES (?,?,?)");
      
    $insert_stmt->execute([$fName, $sName, $email, $passphrase,$phone,0]);
    $arr = $insert_stmt->errorInfo();

    if(!isset($arr[2])){
        return $returnData = msg(1,"Kullanıcı kaydedildi.");
        
    }

}



if($decoded->roll=='profileEdit'){
   
    if(isset($decoded->loginAuthKey)){
     $loginAuthKey=$decoded->loginAuthKey;
    }else{
     return $returnData = msg(0,"Parametre Eksik");
    }
 
 
     $result=array();
     $sql="SELECT * FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
    
     $stmt = $conn->prepare($sql);
     if($stmt->execute()){
       
     $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
   
 
  
     if(isset($row['ID'])){
         
        $userID=$row['ID'];
        
        $line="";

        if(isset($decoded->fName)){
            $fName=$decoded->fName;
            $line.=",fName='$fName'";
        }

        if(isset($decoded->sName)){
            $sName=$decoded->sName;
            $line.=",sName='$sName'";
        }

        if(isset($decoded->passphrase)){
            $passphrase=md5($decoded->passphrase);
            $sql="SELECT * FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
    
            $stmt = $conn->prepare($sql);
            if($stmt->execute()){
              
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if(md5($row['passphrase'])!=$passphrase){
               
                    return $returnData = msg(0,"Hatalı Şifre Girildi");
                }
            }
            
        }

        if(isset($decoded->newPassphrase)){
            $line.=",passphrase='$decoded->newPassphrase'";
            if(!isset($passphrase)){
                return $returnData = msg(0,"Şifre Gönderilmedi");
            }
        }



        
            /*
        if(isset($decoded->phone)){
            $phone=$decoded->phone;
            $line.=",phone='$phone'";
        }*/
        $line= ltrim($line, ',');
        
       
             
         $sql="UPDATE Consumer SET $line where ID=$userID";
       
         $stmt = $conn->prepare($sql);
         if($stmt->execute()){
         
            return $returnData = msg(1,"Güncelleme Başarılı");
            exit();
         }else{

            return $returnData = msg(0,"Veritabanı Yürütme Hatası");
            exit();
         }
         
     
   
     return $returnData = msg(1,"Güncelleme Başarılı");
     exit();
     }
     //
     else{

 
    
        $sql="SELECT * FROM Producer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
       
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
       
    
    
        if(isset($row['ID'])){
            $userID=$row['ID'];
            $line="";
         
            if(isset($decoded->fName)){
                $fName=$decoded->fName;
                $line.=",fName='$fName'";
            }
    
            if(isset($decoded->sName)){
                $sName=$decoded->sName;
                $line.=",sName='$sName'";
            }
    
             
            if(isset($decoded->passphrase)){
                $passphrase=md5($decoded->passphrase);
                $sql="SELECT * FROM Producer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
        
                $stmt = $conn->prepare($sql);
                if($stmt->execute()){
                  
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if($row['passphrase']!=$passphrase){
                     
                        return $returnData = msg(0,"Hatalı Şifre Girildi");
                    }
                }
                
            }
             

            if(isset($decoded->newPassphrase)){
                $newPassphrase=md5($decoded->newPassphrase);
                $line.=",passphrase='$newPassphrase'";
                if(!isset($passphrase)){
                    return $returnData = msg(0,"Varolan Şifre Gönderilmedi");
                }
            }

   
            /*
            if(isset($decoded->phone)){
                $phone=$decoded->phone;
                $line.=",phone='$phone'";
            }
            */
            $line= ltrim($line, ',');
            
            
                
            $sql="UPDATE Producer SET $line where ID=$userID";
          
            $stmt = $conn->prepare($sql);
            if($stmt->execute()){
                
                 return $returnData = msg(1,"Güncelleme Başarılı");
                 exit();
            }
            
        }
    
     }
    }
    //
    }
 



	
     return $returnData = msg(0,"Oturum Başarısız");
 }




 if($decoded->roll=='emptyBasketInsert'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
    if(isset($decoded->productID)){
        $productID=$decoded->productID;
       }else{
        return $returnData = msg(0,"productID Verisi Gönderilmedi");
    }
    if(isset($decoded->packCount)){
        $packCount=$decoded->packCount;
       }else{
        return $returnData = msg(0,"packCount Verisi Gönderilmedi");
    }
    if(isset($decoded->packSize)){
        $packSize=$decoded->packSize;
       }else{
        return $returnData = msg(0,"packSize Verisi Gönderilmedi");
    }



    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];
      
       $sql="INSERT INTO Basket (userID,productID,packCount,packSize) VALUES (?,?,?,?)";
       
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID,$productID,$packCount,$packSize])){

        return $returnData = msg(1,"Sepet Guncellendi");

       }
       else{

        return $returnData = msg(0,"Sepet Guncellenemedi");

       }
    
    }
    }

    return $returnData = msg(0,"Veritabanı Yürütme Hatası.");

}



if($decoded->roll=='basketAdd'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
    if(isset($decoded->productID)){
        $productID=$decoded->productID;
       }else{
        return $returnData = msg(0,"productID Verisi Gönderilmedi");
    }
    if(isset($decoded->packCount)){
        $packCount=$decoded->packCount;
       }else{
        return $returnData = msg(0,"packCount Verisi Gönderilmedi");
    }
   



    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="SELECT productID FROM Basket WHERE userID=? AND productID=?";
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID,$productID])){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['productID'])){
                $productID=$row['productID'];
                $sql="UPDATE Basket SET packCount=packCount+$packCount WHERE userID=? AND productID=?";
                $stmt = $conn->prepare($sql);
                if($stmt->execute([$userID,$productID])){
                    return $returnData = msg(1,"Sepet Guncellendi");
                }else{
                    return $returnData = msg(0,"Sepet Guncellenemedi");
                }

         
            }
       
        }
      
       $sql="INSERT INTO Basket (userID,productID,packCount) VALUES (?,?,?)";
       
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID,$productID,$packCount])){

        return $returnData = msg(1,"Sepete Eklendi");

       }
       else{

        return $returnData = msg(0,"Sepet Guncellenemedi2");

       }
    
    }
    }

    return $returnData = msg(0,"Veritabanı Yürütme Hatası.");

}




if($decoded->roll=='basketFetch'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
   



    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="SELECT Basket.productID,Basket.packCount,Product.title as productName,Product.packSize,Product.price FROM Basket INNER JOIN Product ON Product.ID=Basket.productID WHERE Basket.userID=?";
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID])){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
          
    
            return $returnData = msg(1,"Sepet Getirildi.",$row);
   
       
        }
        else{
            return $returnData = msg(1,"Sepet Bos.");
        }
    }

    }
      
    return $returnData = msg(0,"Oturum Bilgileri Hatali.");

}

if($decoded->roll=='basketInfo'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
   



    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="SELECT SUM((SELECT Product.price FROM Product 
       WHERE Product.ID=Basket.productID)*Basket.packCount) as totalPrice,MAX(Product.shipmentTime) AS shipmentTime 
       FROM Basket 
       INNER JOIN Product ON Product.ID=Basket.productID WHERE Basket.userID=?";
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID])){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    
            return $returnData = msg(1,"Toplam Fiyat Getirildi.",$row);
   
       
        }
        else{
            return $returnData = msg(0,"Fiyat Getirilemedi.");
        }
    }

    }
      
    return $returnData = msg(0,"Oturum Bilgileri Hatali.");

}


if($decoded->roll=='basketDecrease'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
   
    if(isset($decoded->productID)){
        $productID=$decoded->productID;
       }else{
        return $returnData = msg(0,"productID Verisi Gönderilmedi");
    }


    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="UPDATE Basket SET packCount=packCount-1 WHERE userID=? and productID =? and (select packCount from Basket WHERE productID=? AND packCount>0 AND  userID=?);
       DELETE FROM Basket WHERE userID=? and productID =? AND packCount=0;";

       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID,$productID,$productID,$userID,$userID,$productID])){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    
            return $returnData = msg(1,"Urun Eksiltildi.",$row);
   
       
        }
        else{
            return $returnData = msg(1,"Sepet Bos.");
        }
    }

    }
      
    return $returnData = msg(0,"Oturum Bilgileri Hatali.");

}



if($decoded->roll=='basketDelete'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
   
    if(isset($decoded->productID)){
        $productID=$decoded->productID;
       }else{
        return $returnData = msg(0,"productID Verisi Gönderilmedi");
    }


    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="DELETE FROM Basket WHERE userID=? and productID =?";
       $stmt = $conn->prepare($sql);
       if($stmt->execute([$userID,$productID])){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    
            return $returnData = msg(1,"Urun Silindi.",$row);
   
       
        }
        else{
            return $returnData = msg(1,"Sepet Bos.");
        }
    }

    }
      
    return $returnData = msg(0,"Oturum Bilgileri Hatali.");

}


if($decoded->roll=='randomProduct'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum parametresi eksik");
    }
   
  


    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
    if(isset($row['ID'])){
        $userID=$row['ID'];


       $sql="SELECT * FROM Product ORDER BY RAND() LIMIT 5";
       $stmt = $conn->prepare($sql);
       if($stmt->execute()){
        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
     
    
            return $returnData = msg(1,"Urunler Getirildi.",$row);
   
       
        }
        else{
            return $returnData = msg(0,"Urunler Getirilemedi.");
        }
    }

    }
      
    return $returnData = msg(0,"Oturum Bilgileri Hatali.");

}
 


 
if($decoded->roll=='getSmsVerificationCode'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Oturum Bulunamadı");
    }
    if(isset($decoded->phone)){
        $phone=$decoded->phone;
       }else{
        return $returnData = msg(0,"Telefon Verisi Gönderilmedi");
    }



    $sql="SELECT ID,email FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
       $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
       if(isset($row['ID'])){
        $userID=$row['ID'];
        $smsVerificationCode=getRandomKeyOnlyNumber(6);
        $sql="UPDATE Consumer SET smsVerificationCode='$smsVerificationCode' where ID=$userID";
       
       $stmt = $conn->prepare($sql);
       if($stmt->execute()){
        $arr=["smsVerificationCode"=>$smsVerificationCode];
        return $returnData = msg(1,"Sms Gönderildi",$arr);

       }
       else{

        return $returnData = msg(0,"Telefon Numarası Sisteme Kayıtlı");

       }
        }
        else{}
    }

    
        $sql="SELECT ID,email FROM Producer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
        
       
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
      
       if(isset($row['ID'])){
       
        $userID=$row['ID'];
        $smsVerificationCode=getRandomKeyOnlyNumber(6);
        $sql="UPDATE Producer SET smsVerificationCode='$smsVerificationCode' where ID=$userID";
       
       $stmt = $conn->prepare($sql);
       if($stmt->execute()){
        $arr=["smsVerificationCode"=>$smsVerificationCode];
        return $returnData = msg(1,"Sms Gönderildi",$arr);

       }
       else{

        return $returnData = msg(0,"Telefon Numarası Sisteme Kayıtlı");

       }
        
       }else{

        return $returnData = msg(0,"Oturum Bulunamadı.");

       }

       }

       

    
    return $returnData = msg(0,"Veritabanı Yürütme Hatası.");

}







if($decoded->roll=='updatePhone'){

    if(isset($decoded->loginAuthKey)){
        $loginAuthKey=$decoded->loginAuthKey;
       }else{
        return $returnData = msg(0,"Parametre  Eksik");
    }
    if(isset($decoded->phone)){
        $phone=$decoded->phone;
       }else{
        return $returnData = msg(0,"Parametre  Eksik");
    }

    if(isset($decoded->smsCode)){
        $smsCode=$decoded->smsCode;
       }else{
        return $returnData = msg(0,"Parametre  Eksik");
    }
   

    if(isset($smsCode)){
    
        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' and smsVerificationCode='$smsCode' LIMIT 1";
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);


      
      
        if(isset($row['ID'])){
            $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
   
            $stmt = $conn->prepare($sql);
            if($stmt->execute()){
               $row = $stmt->fetch(PDO::FETCH_ASSOC);
               if($row['ID']){
                $userID=$row['ID'];
                $sql="UPDATE Consumer SET phone='$phone',smsVerified=1 where ID=$userID";
               
               $stmt = $conn->prepare($sql);
                    if($stmt->execute()){
                        
                        return $returnData = msg(1,"Güncelleme Başarılı");
                    }
                    else{

                        return $returnData = msg(0,"Telefon Numarası1 Sisteme Kayıtlı");
                    }
                }
            }else{
                return $returnData = msg(1,"Oturum Bulunamadı");
        
            }
        }else{

            $sql="SELECT ID FROM Producer WHERE loginAuthKey='$loginAuthKey' and smsVerificationCode='$smsCode' LIMIT 1";
            $stmt = $conn->prepare($sql);
            if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row['ID']){
                    $userID=$row['ID'];
                    $sql="UPDATE Producer SET phone='$phone',smsVerified=1 where ID=$userID";
                
                $stmt = $conn->prepare($sql);
                    if($stmt->execute()){
                        
                        return $returnData = msg(1,"Güncelleme Başarılı");
                    }
                    else{

                        return $returnData = msg(0,"Telefon Numarası1 Sisteme Kayıtlı");
                    }
                }
            }
        }

    }

}
   
    return $returnData = msg(0,"Telefon Numarası Sisteme Kayıtlı");
}


if($decoded->roll=='getConsumerAddress'){

$loginAuthKey=$decoded->loginAuthKey;

    $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(isset($row['ID'])){
            $userID=$row['ID'];
            
            $sql="SELECT ID,townID, title, details FROM Addresses WHERE userID='$userID'";
           
            $stmt = $conn->prepare($sql);
            if($stmt->execute()){
           
                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $returnData = msg(1,"Kullanıcı verileri getirildi.",$row);
            }
        }
        return $returnData = msg(0,"Veri getirilemedi.",$row);
    }

    else{
            return $returnData = msg(0,"Veritabanı yürütme hatası.");
        }

    }


    if($decoded->roll=='addConsumerAddress'){
        
        $loginAuthKey=$decoded->loginAuthKey;

        $line="";
        $rowArray=array();
        $counter=0; 

        if(isset($decoded->townID)){
            $townID=$decoded->townID;
            $line.=",townID";
            array_push($rowArray,$townID);
            $counter++;
        }
    

        if(isset($decoded->title)){
            $title=$decoded->title;
            $line.=",title";
            array_push($rowArray,$title);
            $counter++;
        }

      
        if(isset($decoded->details)){
            $details=$decoded->details;
            $line.=",details";
            array_push($rowArray,$details);
            $counter++;
        }

        $line= ltrim($line, ',');
        


        $askLine="";

 
       

        
        $line= ltrim($line, ',');

        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(isset($row['ID'])){
                $userID=$row['ID'];
                array_push($rowArray,$userID);
                $line.=",userID";
                $counter++;

          for($i=0;$i<$counter;$i++){

            if($i==0){
                $askLine.='?';
            }
            else{

                $askLine.=',?';
            }

        }
           

        $sql="INSERT INTO Addresses 
        ($line)
        VALUES ($askLine)";
            
        $insert_stmt = $conn->prepare($sql);
    
        if($insert_stmt->execute($rowArray)){
            return $returnData = msg(1,"Ürün Yükleme Başarılı");

        }
        else
        {
            //return $returnData = msg(0,"Ürün Yükleme Başarısız");
            $arr = $insert_stmt->errorInfo();
            print_r($arr);
        }
    }
    }
        
    }


    if($decoded->roll=='deleteConsumerAddress'){
        $loginAuthKey=$decoded->loginAuthKey;
        $addressID=$decoded->addressID;

        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(isset($row['ID'])){
                $userID=$row['ID'];
                
                $sql="DELETE FROM Addresses  WHERE userID='$userID' AND ID='$addressID'";
               
                $stmt = $conn->prepare($sql);
                if($stmt->execute()){
               
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $returnData = msg(1,"Kullanıcı adresi silindi.",$row);
                }
            }
            return $returnData = msg(0,"Adres silinemedi.");
        }

    }

   

    if($decoded->roll=='updateConsumerAddress'){
    
        $loginAuthKey=$decoded->loginAuthKey;
        $addressID=$decoded->addressID;

        $line="";
        $counter=0; 

        if(isset($decoded->townID)){
            $townID=$decoded->townID;
            $line.=",townID='$townID'";
            $counter++;
        }


        if(isset($decoded->title)){
            $title=$decoded->title;
            $line.=",title='$title'";
            $counter++;
        }


        if(isset($decoded->details)){
            $details=$decoded->details;
            $line.=",details='$details'";
            $counter++;
        }

        $line= ltrim($line, ',');


        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
        
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(isset($row['ID'])){
                $userID=$row['ID'];
                
                $sql="UPDATE Addresses  SET $line WHERE userID='$userID' AND ID='$addressID'";
                
                $stmt = $conn->prepare($sql);
                if($stmt->execute()){
               
                    $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $returnData = msg(1,"Kullanıcı verileri güncellendi.",$row);
                }
            }
            return $returnData = msg(0,"Veriler güncellenmedi.");
        }
    
        else{
                return $returnData = msg(0,"Veritabanı yürütme hatası.");
            }
    
    }

    if($decoded->roll=='addProduct'){

        $rowArray=array();
        $counter=0;


        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;

            $stmt = $conn->prepare("SELECT Farm.ID FROM Producer 
            inner join Farm on Farm.ownerID=Producer.ID
            where Producer.loginAuthKey='$loginAuthKey' LIMIT 1");
            if($stmt->execute()){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($row['ID'])){
                    $farmID=$row['ID'];
                   
                    
                }
                else{

                    return $returnData = msg(0,"Oturum bulunamadı");
                    exit();

                }
            }
           
        }

     



        $line="";

        if(isset($decoded->photoBase64)){
            $output_file="productImages/".time().".jpeg";
            $ifp = fopen( $output_file, "wb" ); 
            if(fwrite($ifp, base64_decode($decoded->photoBase64))){
            $photoUrl="https://imageurl/".$output_file;
            
                array_push($rowArray,$photoUrl);
                $line=",photoUrl";
                $counter++;
            }else{
                return $returnData = msg(0,"Resim Yükleme Başarısız");

            }
            
       
        }

        if(isset($decoded->categoryID)){
            $categoryID=$decoded->categoryID;
            array_push($rowArray,$categoryID);
            $line.=",categoryID";
            $counter++;
        }
       
        if(isset($farmID)){
         
            $line.=",farmID";
            array_push($rowArray,$farmID);
            $counter++;
        }

        if(isset($decoded->saleTypeID)){
            $saleTypeID=$decoded->saleTypeID;
            array_push($rowArray,$saleTypeID);
            $line.=",saleTypeID";
            $counter++;
        }
       

        if(isset($decoded->title)){
            $title=$decoded->title;
            array_push($rowArray,$title);
            $line.=",title";
            $counter++;
        }

   
     
        if(isset($decoded->minSale)){
            $minSale=$decoded->minSale;
            array_push($rowArray,$minSale);
            $line.=",minSale";
            $counter++;
        }
  
        if(isset($decoded->price)){
            $price=$decoded->price;
            array_push($rowArray,$price);
            $line.=",price";
            $counter++;
        }

        if(isset($decoded->packSize)){
            $packSize=$decoded->packSize;
            array_push($rowArray,$packSize);
            $line.=",packSize";
            $counter++;
        }
        if(isset($decoded->shipmentTime)){
            $shipmentTime=$decoded->shipmentTime;
            array_push($rowArray,$shipmentTime);
            $line.=",shipmentTime";
            $counter++;
        }
   

        if(isset($decoded->inventorySize)){
            $inventorySize=$decoded->inventorySize;
            array_push($rowArray,$inventorySize);
            $line.=",inventorySize";
            $counter++;
        }

        if(isset($decoded->maxRange)){
            $maxRange=$decoded->maxRange;
            array_push($rowArray,$maxRange);
            $line.=",maxRange";
            $counter++;

        }

        if(isset($decoded->description)){
            $description=$decoded->description;
            array_push($rowArray,$description);
            $line.=",description";
            $counter++;
        }

      
       

        if(isset($decoded->shipmentPrice)){
            $shipmentPrice=$decoded->shipmentPrice;
            array_push($rowArray,$shipmentPrice);
            $line.=",shipmentPrice";
            $counter++;
         
        }

        if(isset($decoded->isOrganic)){
            $isOrganic=$decoded->isOrganic;
            array_push($rowArray,$isOrganic);
            $line.=",isOrganic";
            $counter++;
        }

        $askLine="";

        for($i=0;$i<$counter;$i++){

            if($i==0){
                $askLine.='?';
            }
            else{

                $askLine.=',?';
            }

        }
        
       

        
        $line= ltrim($line, ',');
        $sql="INSERT INTO Product 
        ($line)
        VALUES ($askLine)";

        $insert_stmt = $conn->prepare($sql);

        if($insert_stmt->execute($rowArray)){
            return $returnData = msg(1,"Ürün Yükleme Başarılı");

        }
        else{

            return $returnData = msg(1,"Ürün Yükleme Başarısız");

        }
        $arr = $insert_stmt->errorInfo();
        


    }


    if($decoded->roll=='editProduct'){

        $rowArray=array();
        $counter=0;
    
    
        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;
    
            $stmt = $conn->prepare("SELECT Farm.ID FROM Producer 
            inner join Farm on Farm.ownerID=Producer.ID
            where Producer.loginAuthKey='$loginAuthKey' LIMIT 1");
            if($stmt->execute()){
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(isset($row['ID'])){
                    $farmID=$row['ID'];
                   
                    
                }
                else{
    
                    return $returnData = msg(0,"Oturum bulunamadı");
                    exit();
    
                }
            }
           
        }
    
     
    
    
    
        $line="";
    
    
        if(isset($decoded->ID)&&$decoded->ID!=''){
            $ID=$decoded->ID;
            array_push($rowArray,$ID);
            $line.=",ID='$ID'";
            $counter++;
        }
    
        if(isset($decoded->photoBase64)&&$decoded->photoBase64!=''){
            $output_file="productImages/".time().".jpeg";
            $ifp = fopen( $output_file, "wb" ); 
            if(fwrite($ifp, base64_decode($decoded->photoBase64))){
            $photoUrl=$output_file;
            
                array_push($rowArray,$photoUrl);
                $line=",photoUrl='$photoUrl'";
                $counter++;
            }else{
                return $returnData = msg(0,"Resim Yükleme Başarısız");
    
            }
        }
    
        if(isset($decoded->categoryID)&&$decoded->categoryID!=''){
            $categoryID=$decoded->categoryID;
            array_push($rowArray,$categoryID);
            $line.=",categoryID='$categoryID'";
            $counter++;
        }
       
        if(isset($farmID)&&$farmID!=''){
         
            $line.=",farmID='$farmID'";
            array_push($rowArray,$farmID);
            $counter++;
        }
    
        if(isset($decoded->saleTypeID)&&$decoded->saleTypeID!=''){
            $saleTypeID=$decoded->saleTypeID;
            array_push($rowArray,$saleTypeID);
            $line.=",saleTypeID='$saleTypeID'";
            $counter++;
        }
       
    
        if(isset($decoded->title)&&$decoded->title!=''){
            $title=$decoded->title;
            array_push($rowArray,$title);
            $line.=",title='$title'";
            $counter++;
        }
    
    
     
        if(isset($decoded->minSale)&&$decoded->minSale!=''){
            $minSale=$decoded->minSale;
            array_push($rowArray,$minSale);
       
            $line.=",minSale='$minSale'";
            $counter++;
        }
    
        if(isset($decoded->price)&&$decoded->price!=''){
            $price=$decoded->price;
            array_push($rowArray,$price);
            $line.=",price='$price'";
            $counter++;
        }
    
        if(isset($decoded->packSize)&&$decoded->packSize!=''){
            $packSize=$decoded->packSize;
            array_push($rowArray,$packSize);
            $line.=",packSize='$packSize'";
            $counter++;
        }
        if(isset($decoded->shipmentTime)&&$decoded->shipmentTime!=''){
            $shipmentTime=$decoded->shipmentTime;
            array_push($rowArray,$shipmentTime);
            $line.=",shipmentTime='$shipmentTime'";
            $counter++;
        }
    
    
        if(isset($decoded->inventorySize)&&$decoded->inventorySize!=''){
            $inventorySize=$decoded->inventorySize;
            array_push($rowArray,$inventorySize);
            $line.=",inventorySize='$inventorySize'";
            $counter++;
        }
    
        if(isset($decoded->maxRange)&&$decoded->maxRange!=''){
            $maxRange=$decoded->maxRange;
            array_push($rowArray,$maxRange);
            $line.=",maxRange='$maxRange'";
            $counter++;
    
        }
    
        if(isset($decoded->description)&&$decoded->description!=''){
            $description=$decoded->description;
            array_push($rowArray,$description);
            $line.=",description='$description'";
            $counter++;
        }
    
      
       
    
        if(isset($decoded->shipmentPrice)&&$decoded->shipmentPrice!=''){
            $shipmentPrice=$decoded->shipmentPrice;
            array_push($rowArray,$shipmentPrice);
            $line.=",shipmentPrice='$shipmentPrice'";
            $counter++;
         
        }
    
        if(isset($decoded->isOrganic)&&$decoded->isOrganic!=''){
            $isOrganic=$decoded->isOrganic;
            array_push($rowArray,$isOrganic);
            $line.=",isOrganic='$isOrganic'";
            $counter++;
        }
    
        $askLine="";
    
        for($i=0;$i<$counter;$i++){
    
            if($i==0){
                $askLine.='?';
            }
            else{
    
                $askLine.=',?';
            }
    
        }
        
       
    
        
        $line= ltrim($line, ',');
    
        $sql="UPDATE Product SET $line WHERE Product.farmID=$farmID AND Product.ID=$ID";
     
        $insert_stmt = $conn->prepare($sql);
    
        if($insert_stmt->execute($rowArray)){
            return $returnData = msg(1,"Ürün Düzenleme Başarılı");
    
        }else{
    
            return $returnData = msg(0,"Ürün Düzenleme Başarısız");
        }
      
        
    
    
    }


    if($decoded->roll=='getSaleTypes'){

        $sql="SELECT * FROM Sale_Types";
               
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
       
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $returnData = msg(1,"Satış Tipleri Getirildi.",$row);
        }
    
    }

    if($decoded->roll=='farmDetails'){

        $ownerID=$decoded->ownerID;



        $sql="SELECT Farm.ID,Farm.ownerID,Farm.name as farmName,
        Farm.details as farmDetails, Town.name as townName,
        Town.cityID,City.name as cityName,
        Farm_Address.details as addressDetails,
      
        (SELECT COUNT(*) FROM Product WHERE Product.farmID=Farm.ID) as productCounter,
        (SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate,
        (SELECT COUNT(*) FROM Farm_Comments WHERE Farm_Comments.farmID=Farm.ID) as farmCommentCounter 
        FROM Farm 
        INNER JOIN Farm_Address ON Farm_Address.farmID=Farm.ID 
        INNER JOIN Town ON Town.ID=Farm_Address.townID 
        INNER JOIN City ON City.ID=Town.cityID
        WHERE ownerID=$ownerID";
               
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
       
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $farmID=$row['ID'];
            $sql=  "SELECT photoUrl FROM Farm_Photos WHERE Farm_Photos.farmID=$farmID";
            $stmt=$conn->prepare($sql);
            if($stmt->execute()){
                $_row = $stmt->fetchAll(PDO::FETCH_COLUMN);
                $row['photoUrl']=$_row;
            }else{
                
                $row['photoUrl']=null;
            }

            return $returnData = msg(1,"Tezgah detayı getirildi.",$row);
        }else{

            return $returnData = msg(0,"Veritabanı yürütme hatası.");
        }



    }

    


    if($decoded->roll=='getFarmComments'){
        $ownerID=$decoded->ownerID;

        $sql="SELECT Farm.ownerID,Farm.name,Farm_Comments.userID,
        CONCAT(UPPER(substring(Consumer.fName,1,1)),LOWER(substring(Consumer.fName,2))) as fName,
        UPPER(SUBSTRING(Consumer.sName, 1,1)) as sName, Farm_Comments.farmID,Farm_Comments.userID,
        Farm_Comments.shopID,Farm_Comments.details, Farm_Comments.point,Farm_Comments.farmerComment,
        Farm_Comments.farmerCommentDate, Comment_Subjects.subjectName, Comment_Types.typeName 
        FROM Farm_Comments INNER JOIN Consumer ON Consumer.ID=Farm_Comments.userID 
        INNER JOIN Comment_Types ON Comment_Types.ID=Farm_Comments.commentTypeID 
        INNER JOIN Comment_Subjects ON Comment_Subjects.ID=Farm_Comments.commentSubjectID 
        INNER JOIN Farm ON Farm.ID=Farm_Comments.farmID
        WHERE ownerID=$ownerID";
               
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
       
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $returnData = msg(1,"Yorumlar getirildi.",$row);
        }else{

            return $returnData = msg(0,"Veritabanı yürütme hatası.");
        }

    }

    if($decoded->roll=='getOrders'){

        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;
           }else{
            return $returnData = msg(0,"Oturum parametresi eksik");
        }
       
    
    
    
        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
       
        $stmt = $conn->prepare($sql);
        if($stmt->execute()){
           $row = $stmt->fetch(PDO::FETCH_ASSOC);
          
        if(isset($row['ID'])){
            $userID=$row['ID'];
    
    
           $sql="SELECT Shop.*,Farm.name as farmName,Product.title as productName,Product.price
           FROM Shop 
           INNER JOIN Product ON Shop.productID=Product.ID 
           INNER JOIN Farm ON Farm.ID=Product.farmID WHERE Shop.userID=? ORDER BY Shop.createdDate";
           $stmt = $conn->prepare($sql);
           if($stmt->execute([$userID])){
            $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if(count($row)==0){
                $arr=array();
                return $returnData = msg(1,"Siparis Gecmisi Getirildi.",$arr);
            }
            

            $counter=0;
                foreach ($row as $subRow) {
               
                    $_row[$subRow['createdDate']][]=$subRow;
                    

               

                }
                    $arr=[];
                    $counter=0;
                foreach($_row as $value){
                    array_push($arr,$value);
                    $counter++;
                }
     
                return $returnData = msg(1,"Siparis Gecmisi Getirildi.",$arr);
       
           
            }
            else{
                return $returnData = msg(1,"Siparis Yok.");
            }
        }
    
        }
          
        return $returnData = msg(0,"Oturum Bilgileri Hatali.");

    }



    if($decoded->roll=='likeFarm'){
        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;
           }else{
            return $returnData = msg(0,"Oturum parametresi eksik");
        }

        if(isset($decoded->farmID)){
            $farmID=$decoded->farmID;
           }else{
            return $returnData = msg(0,"FarmID parametresi eksik");
        }


        

        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";


        
       
        $stmt = $conn->prepare($sql);
            if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if(isset($row['ID'])){
                    $userID=$row['ID'];

                    $sql="SELECT ID FROM Follow WHERE Follow.userID=? and Follow.farmID=?";
       
                    $stmt = $conn->prepare($sql);
                        if($stmt->execute([$userID,$farmID])){
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(isset($row['ID'])){
                            $insert_stmt = $conn->prepare("DELETE FROM Follow WHERE userID=? AND farmID=?");
  
                            $insert_stmt->execute([$userID, $farmID]);
                            $arr['isLike']=0;
                            return $returnData = msg(0,"Begeni Kaldirildi.",$arr);

                        }
                    }
                    


                    $insert_stmt = $conn->prepare("INSERT INTO Follow (userID,farmID) VALUES (?,?)");
  
                    $insert_stmt->execute([$userID, $farmID]);
                    $arr['isLike']=1;
                    return $returnData = msg(1,"Begeniler Kaydedildi.",$arr);
                
                }else{
                    return $returnData = msg(0,"Begeniler Kaydedilemedi.");
                }

            }




    }


    if($decoded->roll=='getLikeFarm'){
        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;
           }else{
            return $returnData = msg(0,"Oturum parametresi eksik");
        }
     

        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
       
        $stmt = $conn->prepare($sql);
            if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if(isset($row['ID'])){
                    $userID=$row['ID'];


                    $sql="SELECT farmID FROM Follow WHERE Follow.userID=?";
       

                    
                    $stmt = $conn->prepare($sql);
                    if($stmt->execute([$userID])){

                        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
               

                        $_arr=array();    
                        $counter=0;
                        $qstr="";
                        foreach($row as $sub){
                            if($counter==0){
                                $qstr.=$sub['farmID'];
                            }else{
                                $qstr.=','.$sub['farmID'];

                            }
                            $counter++;
                            }

                           $arr=array();

                           if($counter==0){
                            return $returnData = msg(1,"Begenilen Detay.",$arr);

                           }

                          
                        $sql="SELECT Farm.ID,Farm.ownerID,Farm.name as farmName, Farm.details as farmDetails, Town.name as townName, Town.cityID,City.name as cityName, Farm_Address.details as addressDetails, (SELECT COUNT(*) FROM Product WHERE Product.farmID=Farm.ID) as productCounter, (SELECT SUM(Farm_Comments.point)/COUNT(*) AS rate FROM Farm_Comments where Farm_Comments.farmID=Farm.ID) as Rate,(SELECT Farm_Photos.photoUrl FROM Farm_Photos WHERE Farm_Photos.ID=Farm.ID) as photoUrl,(SELECT COUNT(*) FROM Farm_Comments WHERE Farm_Comments.farmID=Farm.ID) as farmCommentCounter FROM Farm INNER JOIN Farm_Address ON Farm_Address.farmID=Farm.ID INNER JOIN Town ON Town.ID=Farm_Address.townID INNER JOIN City ON City.ID=Town.cityID INNER JOIN Follow ON Follow.farmID=Farm.ID WHERE Farm.ID IN ($qstr)";
                        
                        $stmt = $conn->prepare($sql);
                            if($stmt->execute()){
                                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                             
                                if($stmt->rowCount()){
                                  
                                    foreach($row as $sub){
                                        array_push($arr,$sub);
                                    }
                                    return $returnData = msg(1,"Begenilen Detay.",$arr);
                                }else{

                                    return $returnData = msg(1,"Begenilen Detay.",$arr);
                                }
                             
                            }
                    }
                }
            }

        }


    if($decoded->roll=='isLikeFarm'){
        if(isset($decoded->loginAuthKey)){
            $loginAuthKey=$decoded->loginAuthKey;
           }else{
            return $returnData = msg(0,"Oturum parametresi eksik");
        }

        if(isset($decoded->farmID)){
            $farmID=$decoded->farmID;
           }else{
            return $returnData = msg(0,"FarmID parametresi eksik");
        }

     

        $sql="SELECT ID FROM Consumer WHERE loginAuthKey='$loginAuthKey' LIMIT 1";
       
        $stmt = $conn->prepare($sql);
            if($stmt->execute()){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if(isset($row['ID'])){
                    $userID=$row['ID'];


                    $sql="SELECT ID FROM Follow WHERE Follow.userID=? and Follow.farmID=?";
       
                    $stmt = $conn->prepare($sql);
                        if($stmt->execute([$userID,$farmID])){
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        if(isset($row['ID'])){
                            $result=true;
                            return $returnData = msg(1,"1",$result);
                        }
                        else{
                            $result=false;
                            return $returnData = msg(1,"0.",$result);
                        }
                        
            }
        }
    }




    }


    
return $returnData = msg(0,"Parametre Bekleniyor Hostinger.");

?>


