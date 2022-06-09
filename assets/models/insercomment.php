<?php
    session_start();
    
    
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(empty($_SESSION["user"])){
            header("Location:/index.php");
            exit();
         }

        include "../config/connection.php";


        try{ 

                $komentar=$_POST["textarea"];
                $postid=$_POST["postid"];

                $regex="/^[a-z A-Z 0-9 \s . ? !]{5,200}$/";
                $regexid="/^[0-9]+/";

                if(!preg_match($regex,$komentar)){
                    $_SESSION["error"]="Form is invalid";
                    header("Location:/post.php?id=$postid");
                    exit();
                }
                


                if(!preg_match($regexid,$postid)){
                    $_SESSION["error"]="Invalid post";
                    header("Location:/posts.php");  
                    exit();      
                }
                $doesexist=$conn->prepare("SELECT *
                                           FROM  posts
                                           WHERE  id=:id");
                $doesexist->bindParam(":id",$postid);

                if(!$doesexist->execute()){
                    $_SESSION["error"]="Server internal error";
                    header("Location:/post.php?id=$postid");
                    exit();
                }

                $result=$doesexist->fetch();

                if(empty($result)){
                    $_SESSION["error"]="Post not found";
                    header("Location:/posts.php");
                    exit();
                }

                $insertcomm=$conn->prepare("INSERT INTO comments (comment,post_id,user_id,created)
                                           VALUES(:komentar,:postid,:userid,:date)");
                $insertcomm->bindParam(":komentar",$komentar);
                $insertcomm->bindParam(":postid",$postid);
                $insertcomm->bindParam(":userid",$_SESSION["user"]->id);
                $insertcomm->bindParam(":date",date('Y-m-d H:i:s'));

                if(!$insertcomm->execute()){
                    $_SESSION["error"]="Server internal error";
                    header("Location:/post.php?id=$postid");
                    exit();
                }else{
                    $_SESSION["success"]="Successfuly commented";
                    header("Location:/post.php?id=$postid");
                    exit();
                }
                
         
                
    
           
        }
        catch(PDOException $exception){
            $_SESSION["error"]="Server internal error";
            header("Location:/post.php?id=$postid");
            exit();
        }
    }
    else{
      $_SESSION["error"]="Invalid request";
      header("Location:/post.php?id=$postid");
      exit();
    }
?>
