
<?php session_start();
    header("Content-type: application/json");
    $response=["success" => false,"message" =>"Server internal error","data" =>[]];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include "../config/connection.php";

        try{
            $ime= $_POST['imep'];
            $prezime= $_POST['prezimep'];
            $email = $_POST['emailp'];
            $lozinka = $_POST['lozinkap'];
            $adresa = $_POST['adresap'];
            $grad = $_POST['gradp'];
            $zip = $_POST['zipp'];
            $drzava = $_POST['drzavap'];
            $pol=$_POST['polp'];
            $check=$_POST['checkp'];
            
            
            $regName="/^[A-Z][a-z]{3,}$/";
            $regSurname="/^[A-Z][a-z]{3,}$/";
            $regEmail="/^[a-z]((\.|-)?[a-z0-9]){2,}@[a-z]((\.|-)?[a-z0-9]+){2,}\.[a-z]{2,6}$/";
            $regPassword="/^[a-z A-Z 0-9]{2,}$/";
            $regAdresa="/^[0-9 a-z A-Z \s,]+$/";
            $regCity="/^[A-Z][a-z]{3,}$/";
            $regZip="/^([0-9]{5})$/";

           
            $greske=[];

            if(!preg_match($regName,$ime)){
                $greske["ime"]="ime nije validno";
            }
            if(!preg_match($regSurname,$prezime)){
                $greske["prezime"]="Prezime nije validno";
            }
            if(!preg_match($regEmail,$email)){
                $greske["email"]="Email nije validan";
            }
            if(!preg_match($regPassword,$lozinka)){
                $greske["lozinka"]="Lozinka nije validna";
            }
            if(!preg_match($regAdresa,$adresa)){
                $greske["adresa"]="Adresa nije validna";
            }
            if(!preg_match($regCity,$grad)){
                $greske["grad"]="Grad nije validan";
            }
            if(!preg_match($regZip,$zip)){
                $greske["zip"]="zip nije validan";
            }

            if($drzava==0){
                $greske["drzava"]="Izaberite drzavu";
            }
            if($check!="accept"){
                $greske["check"]="Prihvati uslove";             
            }

            if($pol!="Male" && $pol!="Female"){
                $greske["pol"]="Prihvati uslove";   
            }

            $sifralozinka=md5($lozinka);
            $idrole=2;
            $active=0;

            if(count($greske)){
                $response["message"]="Validation error";
                $response["data"]=$greske;
                echo json_encode($response);
                http_response_code(400);
            }else{

               $upit1="SELECT id FROM users WHERE email=:email";
               $prepare=$conn->prepare($upit1);
               $prepare->bindParam(":email",$email);
               $prepare->execute();

               $getemail=$prepare->fetch();

               if($getemail!=false){
                $response["message"]="Email must be unique";
                echo json_encode($response);
                http_response_code(400);
                exit();
               }


               
               $upit = "INSERT INTO users(name,surname,email,password,address,city,country_id,zip,gender,role_id,active)
               VALUES (:ime,:prezime,:email,:lozinka,:adresa,:grad,:drzava,:zip,:pol,:idrole,:active)";

               $prep = $conn->prepare($upit);
               $prep->bindParam(':ime', $ime);
               $prep->bindParam(':prezime', $prezime);
               $prep->bindParam(':email', $email);
               $prep->bindParam(':lozinka', $sifralozinka);
               $prep->bindParam(':adresa', $adresa);
               $prep->bindParam(':grad', $grad);
               $prep->bindParam(':drzava', $drzava);
               $prep->bindParam(':zip', $zip);
               $prep->bindParam(':pol', $pol);
               $prep->bindParam(':idrole', $idrole);
               $prep->bindParam(':active', $active);

               $result = $prep->execute();

               if($result){
                   
                
                  
                $lastid=$conn->lastInsertId();
                $upit3="SELECT * FROM users WHERE id=:lastid";

                $pr=$conn->prepare($upit3);
                $pr->bindParam(":lastid",$lastid);
                $pr->execute();

                $emailsend=$pr->fetch();

                 $emailresult=mail(
                    $emailsend->email,
                    "Email activation",
                    "Hello".$emailsend->name."To activeate this mail pleas click next link <a href='http://dev.php1-sajt.com/activate_account.php?id=".$emailsend->id."'>Link</a>"                    
                 );
                  
                

                
                 
                   $response["success"]=true;
                   $response["message"]="You successfully registered";
                   http_response_code(201);
               }
               else{
                   $respone["message"]="Failed to register";
                   http_response_code(500);
               }

               echo json_encode($response);
               die();
            }        

        }catch(PDOException $exception){
            echo json_encode($response);
            http_response_code(500);
        }     
 } 
 else{
     echo json_encode($response);
     http_response_code(404);
 }
  
?>
