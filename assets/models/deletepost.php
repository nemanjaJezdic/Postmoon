<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (empty($_SESSION["user"])) {
        header("Location:/index.php");
        exit();
    }

    include "../config/connection.php";


    try {


        $id = $_GET["id"];


        $regexid = "/^[0-9]+/";


        if (!preg_match($regexid, $id)) {
            $_SESSION["error"] = "Invalid post";
            header("Location:/posts.php");
            exit();
        }

        $doesexist = $conn->prepare("SELECT *
                                           FROM   posts
                                           WHERE  id=:id");
        $doesexist->bindParam(":id", $id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/posts.php");
            exit();
        }
        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "Post not found";
            header("Location:/posts.php");
            exit();
        }

        if ($_SESSION["user"]->id != $result->user_id) {
            $_SESSION["error"] = "Premission denied";
            header("Location:/posts.php");
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
            header("Location:/posts.php");
            exit();
        }

        $deletedlikes= $conn->prepare("DELETE 
                FROM user_posts_likes
                WHERE post_id=:id");
        $deletedlikes->bindParam(":id", $id);

        if (!$deletedlikes->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/posts.php");
            exit();
        }


        $deletedpost = $conn->prepare("DELETE 
                                             FROM posts
                                             WHERE id=:id");
        $deletedpost->bindParam(":id", $id);

        if (!$deletedpost->execute()) {
            $conn->rollBack();
            $_SESSION["error"] = "Server internal error";
            header("Location:/posts.php");
            exit();
        } else {
            $conn->commit();
            $_SESSION["success"] = "Successfuly deleted post";
            header("Location:/posts.php");
            exit();
        }
    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/posts.php");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/posts.php");
    exit();
}
