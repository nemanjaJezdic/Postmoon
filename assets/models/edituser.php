
<?php session_start();
 
 if(empty($_SESSION["user"])){
    header("Location:/index.php");
    exit();
 }
 if($_SESSION["user"]->role_id!=1){
     header("Location:/posts.php");
     exit();
  }
    
 if($_SERVER['REQUEST_METHOD'] == 'POST'){
     include "../config/connection.php";

     try{
         $ime= $_POST['name'];
         $prezime= $_POST['surname'];
         $adresa = $_POST['address'];
         $grad = $_POST['city'];
         $zip = $_POST['zip'];
         $drzava = $_POST['country'];
         $userid=$_POST['userid'];
       
         
         $regName="/^[A-Z][a-z]{3,}$/";
         $regSurname="/^[A-Z][a-z]{3,}$/";   
         $regAdresa="/^[0-9 a-z A-Z \s,]+$/";
         $regCity="/^[A-Z][a-z]{3,}$/";
         $regZip="/^([0-9]{5})$/";

        
        

         if(!preg_match($regName,$ime)){
             $_SESSION["error"] = "Invalid name";
             header("Location:/adminpanel.php");
             exit();
         }
         if(!preg_match($regSurname,$prezime)){
             $_SESSION["error"] = "Invalid surname";
             header("Location:/adminpanel.php");
             exit();
         }
         if(!preg_match($regAdresa,$adresa)){
             $_SESSION["error"] = "Invalid address";
             header("Location:/adminpanel.php");
             exit();
         }
         if(!preg_match($regCity,$grad)){
             $_SESSION["error"] = "Invalid city";
             header("Location:/adminpanel.php");
             exit();
         }
         if(!preg_match($regZip,$zip)){
             $_SESSION["error"] = "Invalid zip";
             header("Location:/adminpanel.php");
             exit();
         }

         if($drzava==0){
             $_SESSION["error"] = "Invalid country";
             header("Location:/adminpanel.php");
             exit();
         }

         if(!is_numeric($userid)){
            $_SESSION["error"] = "Invalid user";
            header("Location:/adminpanel.php");
            exit();
         }

         $checkcountry=$conn->prepare("SELECT *
                                       FROM countries
                                       WHERE id=:id");
         $checkcountry->bindParam(":id",$drzava);

         if (!$checkcountry->execute()) {
             $_SESSION["error"] = "Server internal error";
             header("Location:/adminpanel.php");
             exit();
         }
 
         $result = $checkcountry->fetch();
 
         if (empty($result)) {
             $_SESSION["error"] = "Country not found";
             header("Location:/adminpanel.php");
             exit();
         }

         $userupdate=$conn->prepare("UPDATE users
                                     SET name=:name,surname=:surname,address=:address,city=:city,country_id=:drzava,zip=:zip
                                     WHERE id=:id");
         $userupdate->bindParam(":name",$ime);
         $userupdate->bindParam(":surname",$prezime);
         $userupdate->bindParam(":address",$adresa);
         $userupdate->bindParam(":city",$grad);
         $userupdate->bindParam(":drzava",$drzava);
         $userupdate->bindParam(":zip",$zip);
         $userupdate->bindParam(":id",$userid);

         if (!$userupdate->execute()) {
             $_SESSION["error"] = "Server internal error";
             header("Location:/adminpanel.php");
             exit();
         } else {
             $_SESSION["success"] = "Successfuly edited profile";
             header("Location:/adminpanel.php");
             exit();
         }
         
          

             

     }catch(PDOException $exception){
         $_SESSION["error"] = "Server internal error";
         header("Location:/adminpanel.php");      
         exit();
     }     
} 
else{
 $_SESSION["error"] = "Invalid request";
 header("Location:/adminpanel.php");
 exit();
}

?>
