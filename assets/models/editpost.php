<?php
session_start();

if (empty($_SESSION["user"])) {
    header("Location:/index.php");
    exit();
}
if ($_SESSION["user"]->role_id != 1) {
    header("Location:/posts.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST["postid"])) {
        header("Location:/adminpanel.php?id=2");
        exit();
    }

    include "../config/connection.php";


    try {


        $heading = $_POST["heading"];
        $category_id = $_POST["category_id"];
        $image = $_FILES["image"];
        $supportedfiletypes = ["image/jpeg" => ".jpg", "image/jpg" => ".jpg", "image/png" => ".png", "image/gif" => ".gif"];

      

        if (!empty($image) && $image["error"] != 4) {
            if (!(in_array($image["type"], array_keys($supportedfiletypes)))) {
                $_SESSION["error"] = "Image type is invalid(supported:jpg,jpeg,png,gif)";
                header("Location:/adminpanel.php?id=2");
                exit();
            }
            if ($image["error"] != 0) {
                $_SESSION["error"] = "Image error";
                header("Location:/adminpanel.php?id=2");
                exit();
            }

            if ($image["size"] > 5000000) {
                $_SESSION["error"] = "Image size is over 5mb";
                header("Location:/adminpanel.php?id=2");
                exit();
            }

            $imagename = time() . $supportedfiletypes[$image["type"]];
            $imagepath = dirname(__FILE__) . "\..\img\\" . $imagename;
            move_uploaded_file($image["tmp_name"], $imagepath);
        }



        $regex = "/^[a-z A-Z 0-9 \s . ? !]{5,200}$/";
        $regexid = "/^[0-9]+/";

        if (!preg_match($regex, $heading)) {
            $_SESSION["error"] = "Heading is invalid";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        if (!preg_match($regexid, $category_id)) {
            $_SESSION["error"] = "Category is invalid";
            header("Location:/adminpanel.php?id=2");
            exit();
        };


        $doesexist = $conn->prepare("SELECT *
                                           FROM   category
                                           WHERE  id=:id");
        $doesexist->bindParam(":id", $category_id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "Category not found";
            header("Location:/adminpanel.php?id=2");
            exit();
        }

        $updatepost = null;

        if (!empty($image) && $image["error"] != 4) {

            $updatepost = $conn->prepare("UPDATE posts
                                          SET heading=:heading,image=:image,category_id=:categoryid
                                          WHERE id=:id");
            $updatepost->bindParam(":heading", $heading);
            $updatepost->bindParam(":image", $imagename);
            $updatepost->bindParam(":categoryid", $category_id);
            $updatepost->bindParam(":id", $_POST["postid"]);
        } else {
            $updatepost = $conn->prepare("UPDATE posts
                                          SET heading=:heading,category_id=:categoryid
                                          WHERE id=:id");
            $updatepost->bindParam(":heading", $heading);
            $updatepost->bindParam(":categoryid", $category_id);
            $updatepost->bindParam(":id", $_POST["postid"]);
        }




        if (!$updatepost->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=2");
            exit();
        } else {
            $_SESSION["success"] = "Successfuly updated post";
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
