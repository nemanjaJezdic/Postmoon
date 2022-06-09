<?php session_start();
if (empty($_SESSION["user"])) {
    header("Location:/index.php");
    exit();
}

include "assets/config/connection.php";
$number_of_posts = 0;
$posts_per_page = 2;

if (empty($_GET["page"])) {
    $page = 1;
} else {
    $page = $_GET["page"];
}

$kategorije = $conn->query("SELECT * FROM category")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $kategorijaid = $_GET["id"];
    if (!empty($kategorijaid)) {
        if (!empty($_GET["search"])) {
            $number_of_post_search = $conn->prepare("SELECT COUNT(*) FROM posts
                                                   WHERE heading LIKE :search AND category_id=:id");
            $search = '%' . $_GET["search"] . '%';
            $number_of_post_search->bindParam(":search", $search);
            $number_of_post_search->bindParam(":id", $kategorijaid);
            $number_of_post_search->execute();
            $number_of_posts = $number_of_post_search->fetchColumn();

            $this_page = ($page - 1) * $posts_per_page;
            $number_of_pages = ceil($number_of_posts / $posts_per_page);
            if (!empty($_GET["page"]) && ($_GET["page"] > $number_of_pages || $_GET["page"] < 0)) {
                header("Location:/posts.php?page=1");
                exit();
            }

            $post = $conn->prepare("SELECT p.*,u.id as user_id,u.name as user_name,u.surname as user_surname,c.name as category_name,upl.id as user_post_like_id,COUNT(com.id) as commentnumber
            FROM posts as p 
                 JOIN users as u ON p.user_id=u.id 
                 JOIN category as c ON p.category_id=c.id
                 LEFT JOIN user_posts_likes as upl ON upl.post_id=p.id AND upl.user_id=:userid
                 LEFT JOIN comments as com ON com.post_id=p.id
            WHERE p.category_id=:id AND p.heading LIKE :search
            GROUP BY p.id
            ORDER BY p.created DESC
            LIMIT $this_page,$posts_per_page");

            $post->bindParam(":id", $kategorijaid);
            $post->bindParam(":userid", $_SESSION["user"]->id);
            $post->bindParam(":search", $search);
            $post->execute();
        } else {
            $number_of_post_search = $conn->prepare("SELECT COUNT(*) FROM posts
                                               WHERE category_id=:id");
            $number_of_post_search->bindParam(":id", $kategorijaid);
            $number_of_post_search->execute();
            $number_of_posts = $number_of_post_search->fetchColumn();

            $this_page = ($page - 1) * $posts_per_page;
            $number_of_pages = ceil($number_of_posts / $posts_per_page);
            if (!empty($_GET["page"]) && ($_GET["page"] > $number_of_pages || $_GET["page"] < 0)) {
                header("Location:/posts.php?page=1");
                exit();
            }

            $post = $conn->prepare("SELECT p.*,u.id as user_id,u.name as user_name,u.surname as user_surname,c.name as category_name,upl.id as user_post_like_id,COUNT(com.id) as commentnumber
            FROM posts as p 
                 JOIN users as u ON p.user_id=u.id 
                 JOIN category as c ON p.category_id=c.id
                 LEFT JOIN user_posts_likes as upl ON upl.post_id=p.id AND upl.user_id=:userid
                 LEFT JOIN comments as com ON com.post_id=p.id
            WHERE p.category_id=:id
            GROUP BY p.id
            ORDER BY p.created DESC
            LIMIT $this_page,$posts_per_page");

            $post->bindParam(":id", $kategorijaid);
            $post->bindParam(":userid", $_SESSION["user"]->id);
            $post->execute();
        }

        $result = $post->fetchAll();
        
    } else {
        header("Location:/posts.php");
        exit();
    }
}


?>

<?php include "header.php"; ?>

<?php include "nav.php"; ?>



<div class="container p-3">
    <div class="row">
        <div class="col-3 border-right">
            <?php foreach ($kategorije as $kt) : ?>
                <p><a class="text-secondary" href="/category.php?id=<?php echo $kt->id ?>"><?php echo $kt->name; ?></a></p>
            <?php endforeach; ?>
        </div>
        <div class="col-9 col-lg-7 text-center border-right">
            <?php include "assets/error/error.php" ?>
            <?php include "assets/success/success.php" ?>
            <?php foreach ($result as $res) : ?>
                <div class="card shadow-lg  bg-white rounded mt-5">
                    <img class="card-img-top" src="/assets/img/<?php echo $res->image; ?>" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $res->heading; ?></h5>
                        <p class="card-text"><?php echo  $res->category_name; ?></p>
                        <p class="card-text text-muted">(Created by: <?php echo $res->user_name . " " . $res->user_surname; ?>)</p>
                        <small><?php echo ($res->user_id == $_SESSION["user"]->id ? "<a class='text-secondary' href='/assets/models/deletepost.php?id=$res->id'>Delete your post</a>" : ""); ?></small>
                    </div>
                    <div class="card-footer">
                        <span id="like_<?php echo $res->id; ?>" class="text-muted fas <?php echo empty($res->user_post_like_id) ? "fa-arrow-up" : "fa-arrow-down"; ?>  like-button" data-post-id="<?php echo $res->id; ?>"> Likes: <?php echo $res->likes; ?></span>
                        <span><a class="text-secondary" href="/post.php?id=<?php echo $res->id ?>">Comments:(<?php echo $res->commentnumber ?>)</a></span>
                    </div>
                </div>
            <?php endforeach; ?>
            <ul class="pagination pagination-lg justify-content-center mt-3">
                <?php
                for ($page = 1; $page <= $number_of_pages; $page++) {
                    if (!empty($_GET["search"])) {
                        echo '<li class="page-item"><a class="page-link" href="category.php?id='.$_GET["id"].'&page=' . $page . '&search=' . $_GET["search"] . '">' . $page . '</a></li>';
                    } else {
                        echo '<li class="page-item"><a class="page-link" href="category.php?id='.$_GET["id"].'&page=' . $page . '">' . $page . '</a></li>';
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>


<?php include "footer.php"; ?>