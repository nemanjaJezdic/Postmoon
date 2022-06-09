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

    if (empty($_GET["categoryid"])) {
        header("Location:/adminpanel.php?id=3");
        exit();
    }

    include "../config/connection.php";


    try {


        $id = $_GET["categoryid"];


        $regexid = "/^[0-9]+/";


        if (!preg_match($regexid, $id)) {
            $_SESSION["error"] = "Invalid category";
            header("Location:/adminpanel.php?id=3");
            exit();
        }

        $doesexist = $conn->prepare("SELECT *
                                     FROM   category
                                     WHERE  id=:id");
        $doesexist->bindParam(":id", $id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=3");
            exit();
        }
        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "Category not found";
            header("Location:/adminpanel.php?id=3");
            exit();
        }

        $conn->beginTransaction();
        $deletedcomm = $conn->prepare("DELETE c
                                       FROM comments AS c JOIN posts AS p ON c.post_id=p.id
                                       WHERE p.category_id=:id");
        $deletedcomm->bindParam(":id", $id);

        if (!$deletedcomm->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=3");
            exit();
        }

        $deletedlikes= $conn->prepare("DELETE upi
                FROM user_posts_likes AS upi JOIN posts AS p ON upi.post_id=p.id
                WHERE p.category_id=:id");
        $deletedlikes->bindParam(":id", $id);

        if (!$deletedlikes->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=3");
            exit();
        }
        $deletedpost= $conn->prepare("DELETE 
                FROM posts
                WHERE category_id=:id");
        $deletedpost->bindParam(":id", $id);

        if (!$deletedpost->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=3");
            exit();
        }


        $deletecat = $conn->prepare("DELETE 
                                             FROM category
                                             WHERE id=:id");
        $deletecat->bindParam(":id", $id);

        if (!$deletecat->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=3");
            exit();
        } else {
            $conn->commit();
            $_SESSION["success"] = "Successfuly deleted category";
            header("Location:/adminpanel.php?id=3");
            exit();
        }     

    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/adminpanel.php?id=3");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/adminpanel.php?id=3");
    exit();
}
