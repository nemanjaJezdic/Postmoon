<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_SESSION["user"])) {
        header("Location:/index.php");
        exit();
    }

    include "../config/connection.php";


    try {

        $heading = $_POST["heading"];
        $category_id = $_POST["category_id"];
        $image = $_FILES["image"];
        $supportedfiletypes=["image/jpeg" =>".jpg","image/jpg" => ".jpg","image/png" =>".png","image/gif" =>".gif"];

      
      
        if(!(in_array($image["type"],array_keys($supportedfiletypes)))){
            $_SESSION["error"] = "Image type is invalid(supported:jpg,jpeg,png,gif)";
            header("Location:/newpost.php");
            exit();
        }
        if($image["error"]!=0){
            $_SESSION["error"] = "Image error";
            header("Location:/newpost.php");
            exit();
        }

        if($image["size"]>5000000){
            $_SESSION["error"] = "Image size is over 5mb";
            header("Location:/newpost.php");
            exit();
        }

        $regex = "/^[a-z A-Z 0-9 \s . ? !]{5,200}$/";
        $regexid = "/^[0-9]+/";

        if (!preg_match($regex, $heading)) {
            $_SESSION["error"] = "Heading is invalid";
            header("Location:/newpost.php");
            exit();
        }

        if (!preg_match($regexid, $category_id)) {
            $_SESSION["error"] = "Category is invalid";
            header("Location:/newpost.php");
            exit();
        };


        $doesexist = $conn->prepare("SELECT *
                                           FROM   category
                                           WHERE  id=:id");
        $doesexist->bindParam(":id", $category_id);

        if (!$doesexist->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/newpost.php");
            exit();
        }

        $result = $doesexist->fetch();

        if (empty($result)) {
            $_SESSION["error"] = "Category not found";
            header("Location:/newpost.php");
            exit();
        }
         
        $imagename=time().$supportedfiletypes[$image["type"]];
        $imagepath=dirname(__FILE__)."\..\img\\".$imagename;
        move_uploaded_file($image["tmp_name"],$imagepath);
        
         
        $date=date('Y-m-d H:i:s');

    
        $insertpost = $conn->prepare("INSERT INTO posts (heading,image,likes,user_id,category_id,created)
                                           VALUES(:heading,:image,0,:userid,:categoryid,:date)");
        $insertpost->bindParam(":heading", $heading);
        $insertpost->bindParam(":image", $imagename);
        $insertpost->bindParam(":userid", $_SESSION["user"]->id);
        $insertpost->bindParam(":categoryid", $category_id);
        $insertpost->bindParam(":date",$date);

        if (!$insertpost->execute()) {
            $_SESSION["error"] = "Server internal error";
            header("Location:/newpost.php");
            exit();
        } else {
            $_SESSION["success"] = "Successfuly created post";
            header("Location:/posts.php");
            exit();
        }
    } catch (PDOException $exception) {
        $_SESSION["error"] = "Server internal error";
        header("Location:/newpost.php");      
        exit();
    }
} else {
    $_SESSION["error"] = "Invalid request";
    header("Location:/newpost.php");
    exit();
}
