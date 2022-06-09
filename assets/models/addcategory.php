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

    if (empty($_POST["catname"])) {
        header("Location:/adminpanel.php?id=7");
        exit();
    }

    include "../config/connection.php";


    try {


        $name = $_POST["catname"];

        $regex = "/^[A-Z][a-z]{3,}$/";


        if (!preg_match($regex, $name)) {
            $_SESSION["error"] = "Name is invalid";
            header("Location:/adminpanel.php?id=7");
            exit();
        }

        $doesexist = $conn->prepare("SELECT *
        FROM  category
        WHERE  name=:name");
        $doesexist->bindParam(":name", $name);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=7");
            exit();
        }

        $result = $doesexist->fetch();

        if (!empty($result)) {
            $_SESSION["error"] = "Category already exists";
            header("Location:/adminpanel.php?id=7");
            exit();
        }

        $insertcat = $conn->prepare("INSERT INTO category (name)
        VALUES(:name)");
        $insertcat->bindParam(":name", $name);

        if (!$insertcat->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/adminpanel.php?id=7");
            exit();
        } else {
            $_SESSION["success"] = "Successfuly added category";
            header("Location:/adminpanel.php?id=7");
            exit();
        }
    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/adminpanel.php?id=7");
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/adminpanel.php?id=7");
    exit();
}
