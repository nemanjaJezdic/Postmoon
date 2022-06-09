<?php
    session_start();
    header("Content-type: application/json");
    $response=["success" => false,"message" =>"Server internal error","data" =>[]];
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(empty($_SESSION["user"])){
            header("Location:/index.php");
         }

        include "../config/connection.php";


        try{
                if(!empty($_POST["id"])){
                   
                    $id=$_POST["id"];

                    $post=$conn->prepare("SELECT *
                                          FROM posts
                                          WHERE id=:id
                                                 ");
                    $post->bindParam(":id",$id);
                    $post->execute();

                    $result=$post->fetch();

                    if(empty($result)){
                        $response["message"]="Post not found";
                        echo json_encode($response);
                        http_response_code(400);
                        exit();
                    }

                    $iduserlike=$conn->prepare("SELECT *
                                               FROM user_posts_likes
                                               WHERE post_id=:postid AND user_id=:userid");
                    $iduserlike->bindParam(":postid",$id);
                    $iduserlike->bindParam(":userid",$_SESSION["user"]->id);
                    $iduserlike->execute();

                    $iduserlikeresult=$iduserlike->fetch();

                    if(!empty($iduserlikeresult)){
                        $response["message"]="User already liked";
                        echo json_encode($response);
                        http_response_code(400);
                        exit();
                    }

                    $likes=$result->likes+1;
 
                    $upd=$conn->prepare("UPDATE posts
                                         SET likes=:likes
                                         WHERE id=:id
                                      ");
                    $upd->bindParam(":likes",$likes);
                    $upd->bindParam(":id",$id);
                    $update_result=$upd->execute();

                    if($update_result){
                        $rememberlikes=$conn->prepare("INSERT INTO user_posts_likes(post_id,user_id)
                                                       VALUES(:postid,:userid)");
                        $rememberlikes->bindParam(":postid",$id);
                        $rememberlikes->bindParam(":userid",$_SESSION["user"]->id);
                     

                        if(!$rememberlikes->execute()){
                            $response["message"]="SERVER INTERNAL ERROR";
                            echo json_encode($response);
                            http_response_code(500);
                            exit();
                        }

                        $response["data"]=$likes;
                        $response["success"]=true;
                        $response["message"]="Successfuly liked";
                        echo json_encode($response);
                        http_response_code(200);
                    }else{
                        $response["message"]="Could not save like";
                        echo json_encode($response);
                        http_response_code(500);
                    }

                 
                }else{
                    $response["message"]="Id has not been send";
                    echo json_encode($response);
                    http_response_code(400);
                }         
        }
        catch(PDOException $exception){
            echo json_encode($response);
            http_response_code(500);
        }
    }
    else{
        echo json_encode($response);
        http_response_code(404);
    }
?>