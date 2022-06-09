
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
         $ime= $_POST['categoryname'];
         $categoryid=$_POST['categoryid'];
       
         
         $regName="/^[A-Z][a-z]{3,}$/";
        

        
        

         if(!preg_match($regName,$ime)){
             $_SESSION["error"] = "Invalid category name";
             header("Location:/adminpanel.php?id=3");
             exit();
         }
         if(!is_numeric($categoryid)){
            $_SESSION["error"] = "Invalid category";
            header("Location:/adminpanel.php?id=3");
            exit();
         }

         $checkcategory=$conn->prepare("SELECT *
                                       FROM category
                                       WHERE id=:id");
         $checkcategory->bindParam(":id",$categoryid);

         if (!$checkcategory->execute()) {
             $_SESSION["error"] = "Server internal error";
             header("Location:/adminpanel.php?id=3");
             exit();
         }
 
         $result = $checkcategory->fetch();
 
         if (empty($result)) {
             $_SESSION["error"] = "Category not found";
             header("Location:/adminpanel.php?id=3");
             exit();
         }

         $categoryupdate=$conn->prepare("UPDATE category
                                     SET name=:name
                                     WHERE id=:id");
         $categoryupdate->bindParam(":name",$ime);
         $categoryupdate->bindParam(":id",$categoryid);

         if (!$categoryupdate->execute()) {
             $_SESSION["error"] = "Server internal error";
             header("Location:/adminpanel.php?id=3");
             exit();
         } else {
             $_SESSION["success"] = "Successfuly edited category";
             header("Location:/adminpanel.php?id=3");
             exit();
         }
         
          

             

     }catch(PDOException $exception){
         $_SESSION["error"] = "Server internal error";
         header("Location:/adminpanel.php?id=3");      
         exit();
     }     
} 
else{
 $_SESSION["error"] = "Invalid request";
 header("Location:/adminpanel.php?id=3");
 exit();
}

?>
