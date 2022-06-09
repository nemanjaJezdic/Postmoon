<?php
    session_start();
    
    
    if($_SERVER['REQUEST_METHOD'] == 'GET'){

        if(empty($_SESSION["user"])){
            header("Location:/index.php");
            exit();
         }

        include "../config/connection.php";


        try{ 

               
                $id=$_GET["id"];

                
                $regexid="/^[0-9]+/";

              
                if(!preg_match($regexid,$id)){
                    $_SESSION["error"]="Invalid post";
                    header("Location:/posts.php");  
                    exit();      
                }
                
                $doesexist=$conn->prepare("SELECT com.*,post.id as idpost
                                           FROM  comments as com
                                           JOIN posts as post ON com.post_id=post.id
                                           WHERE  com.id=:id");
                $doesexist->bindParam(":id",$id);

                if(!$doesexist->execute()){
                    $_SESSION["error"]="Server internal error";
                    header("Location:/posts.php");
                    exit();
                }
                $result=$doesexist->fetch();

                if(empty($result)){
                    $_SESSION["error"]="Post not found";
                    header("Location:/posts.php");
                    exit();
                }

                if($_SESSION["user"]->id!=$result->user_id){
                    $_SESSION["error"]="Premission denied";
                    header("Location:/post.php?id={$result->idpost}");
                    exit();
                }
                $deletedcomm=$conn->prepare("DELETE 
                                             FROM comments
                                             WHERE id=:id");
                $deletedcomm->bindParam(":id",$id);
    
                if(!$deletedcomm->execute()){
                    $_SESSION["error"]="Server internal error";
                    header("Location:/post.php?id={$result->idpost}");
                    exit();
                }else{
                    $_SESSION["success"]="Successfuly deleted comment";
                    header("Location:/post.php?id={$result->idpost}");
                    exit();
                }
                
         
                
    
           
        }
        catch(PDOException $exception){
            $_SESSION["error"]="Server internal error";
            header("Location:/posts.php");
            exit();
        }
    }
    else{
      $_SESSION["error"]="Invalid request";
      header("Location:/posts.php");
      exit();
    }
