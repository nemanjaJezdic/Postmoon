<?php
session_start();

if(empty($_SESSION["user"])){
    header("Location:/index.php");
    exit();
 }
 if($_SESSION["user"]->role_id!=1){
     header("Location:/posts.php");
     exit();
  }

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (empty($_GET["userid"])) {
        header("Location:/adminpanel.php");
        exit();
    }

    include "../config/connection.php";


    try {


        $id = $_GET["userid"];


        $regexid = "/^[0-9]+/";


        if (!preg_match($regexid, $id)) {
            $_SESSION["error"] = "Invalid user";
            header("Location:/adminpanel.php");
            exit();
        }

        $doesexist = $conn->prepare("SELECT *
                                           FROM   users
                                           WHERE  id=:id");
        $doesexist->bindParam(":id", $id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php");
            exit();
        }
        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "User not found";
            header("Location:/adminpanel.php");
            exit();
        }

        $conn->beginTransaction();
        $deletedcomm = $conn->prepare("DELETE 
                                       FROM comments
                                       WHERE user_id=:id");
        $deletedcomm->bindParam(":id", $id);

        if (!$deletedcomm->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php");
            exit();
        }

        $deletedlikes= $conn->prepare("DELETE 
                FROM user_posts_likes
                WHERE user_id=:id");
        $deletedlikes->bindParam(":id", $id);

        if (!$deletedlikes->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php");
            exit();
        }


        $deletedpost = $conn->prepare("DELETE 
                                       FROM posts
                                       WHERE user_id=:id");
        $deletedpost->bindParam(":id", $id);

        if (!$deletedpost->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php");
            exit();
        } 
        
        $deleteduser = $conn->prepare("DELETE 
                                       FROM users
                                       WHERE id=:id");
        $deleteduser->bindParam(":id", $id);

        if (!$deleteduser->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php");
            exit();
        } else {
            $conn->commit();
            $_SESSION["success"] = "Successfuly deleted user";
            header("Location:/adminpanel.php");
            exit();
        }


     

    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/adminpanel.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/adminpanel.php");
    exit();
}
