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

    if (empty($_GET["postid"])) {
        header("Location:/adminpanel.php?id=2");
        exit();
    }

    include "../config/connection.php";


    try {


        $id = $_GET["postid"];


        $regexid = "/^[0-9]+/";


        if (!preg_match($regexid, $id)) {
            $_SESSION["error"] = "Invalid post";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        $doesexist = $conn->prepare("SELECT *
                                           FROM   posts
                                           WHERE  id=:id");
        $doesexist->bindParam(":id", $id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        }
        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "Post not found";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        $conn->beginTransaction();
        $deletedcomm = $conn->prepare("DELETE 
                                             FROM comments
                                             WHERE post_id=:id");
        $deletedcomm->bindParam(":id", $id);

        if (!$deletedcomm->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        $deletedlikes= $conn->prepare("DELETE 
                FROM user_posts_likes
                WHERE post_id=:id");
        $deletedlikes->bindParam(":id", $id);

        if (!$deletedlikes->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        }


        $deletedpost = $conn->prepare("DELETE 
                                             FROM posts
                                             WHERE id=:id");
        $deletedpost->bindParam(":id", $id);

        if (!$deletedpost->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        } else {
            $conn->commit();
            $_SESSION["success"] = "Successfuly deleted post";
            header("Location:/adminpanel.php?id=2");
            exit();
        }
    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/adminpanel.php?id=2");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/adminpanel.php?id=2");
    exit();
}
