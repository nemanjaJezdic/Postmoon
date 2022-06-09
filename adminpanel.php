<?php session_start();
    if(empty($_SESSION["user"])){
       header("Location:/index.php");
       exit();
    }
    if($_SESSION["user"]->role_id!=1){
        header("Location:/posts.php");
        exit();
     }

    include "assets/config/connection.php";
    
?>
<?php include "header.php";?>

<?php include "nav.php";?>



  <div class="container p-3">
  <div class="row">
    <div class="col-3">     
           <p><a class="text-secondary" href="/adminpanel.php?id=1">Users</a></p>
           <p><a class="text-secondary" href="/adminpanel.php?id=2">Posts</a></p>
           <p><a class="text-secondary" href="/adminpanel.php?id=3">Category</a></p>
    </div>
    <div class="col-9 text-center">
        <?php include "assets/error/error.php"?>
        <?php include "assets/success/success.php"?>
         <?php
             if(empty($_GET["id"])){
                 $id=1;
             }else{
                 $id=$_GET["id"];
             }
             switch($id){
                 case 1: {include "adminusers.php";break;}
                 case 2: {include "adminposts.php";break;}
                 case 3: {include "admincategory.php";break;}
                 case 4: {include "edituseradmin.php";break;}
                 case 5: {include "editpostadmin.php";break;}
                 case 6: {include "editcategoryadmin.php";break;}
                 case 7: {include "addcategoryadmin.php";break;}
                 default: {include "adminusers.php";break;}
             };
         ?>
    </div>
  </div>
  </div>

<?php include "footer.php";?>