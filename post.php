<?php session_start();
if (empty($_SESSION["user"])) {
  header("Location:/index.php");
  exit();
}

include "assets/config/connection.php";

$kategorije = $conn->query("SELECT * FROM category")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

  $postid = $_GET["id"];

  if (!empty($postid)) {
    $post = $conn->prepare("SELECT p.*,u.name as user_name,u.surname as user_surname,c.name as category_name,upl.id as user_post_like_id
                                  FROM posts as p 
                                       JOIN users as u ON p.user_id=u.id 
                                       JOIN category as c ON p.category_id=c.id
                                       LEFT JOIN user_posts_likes as upl ON upl.post_id=p.id AND upl.user_id=:userid
                                  WHERE p.id=:id");

    $post->bindParam(":id", $postid);
    $post->bindParam(":userid", $_SESSION["user"]->id);
    $post->execute();

    $res = $post->fetch();

    $komnt=$conn->prepare("SELECT c.*,u.id as user_id,u.name as user_name,u.surname as user_surname
                           FROM comments as c 
                           JOIN users as u ON c.user_id=u.id
                           WHERE post_id=:postid
                           ORDER BY c.created DESC");
    $komnt->bindParam(":postid",$postid);
    $komnt->execute();

    $reskomnt=$komnt->fetchAll();
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
      <?php include "assets/error/error.php"?>
      <?php include "assets/success/success.php"?>
      <div class="card shadow-lg  bg-white rounded mt-5">
        <img class="card-img-top" src="/assets/img/<?php echo $res->image; ?>" alt="Card image cap">
        <div class="card-body">
          <h5 class="card-title"><?php echo $res->heading; ?></h5>
          <p class="card-text"><?php echo  $res->category_name; ?></p>
          <p class="card-text text-muted">(Created by: <?php echo $res->user_name . " " . $res->user_surname; ?>)</p>
        </div>
        <div class="card-footer">
          <span id="like_<?php echo $res->id; ?>" class="text-muted fas <?php echo empty($res->user_post_like_id) ? "fa-arrow-up" : "fa-arrow-down"; ?>  like-button" data-post-id="<?php echo $res->id; ?>"> Likes: <?php echo $res->likes; ?></span>
        </div>
      </div>
      <div class="">
        <form id="comment-form" action="/assets/models/insercomment.php" method="POST" class="needs-validation">
          <div class="form-group">
            <label for="exampleFormControlTextarea1">Comments:</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" name="textarea"  placeholder="Comment here..." cols="150" rows="3"></textarea>
            <p class="commenterr text-danger">Must be minimum 5 characters without special characters</p>
            <input type="hidden" name="postid" value="<?php echo $res->id?>"/>
          </div>
          <button type="submit" class="btn btn-primary">Send</button>
        </form>
      </div>
      <div class="mt-3">
        <div class="list-group">
          <?php foreach($reskomnt as $kom):?>
          <div  class="list-group-item list-group-item-action" aria-current="true">
            <div class="d-flex w-100 justify-content-between">
              <small><?php echo date("d M Y H:i:s",strtotime($kom->created))?></small>
              <small><?php echo ($kom->user_id==$_SESSION["user"]->id ? "<a class='text-secondary' href='/assets/models/deletecomment.php?id=$kom->id'><span class='fas fa-times'></span></a>":"");?></small>
            </div>
            <p class="mb-1"><?php echo $kom->comment?></p>
            <small><?php echo ($kom->user_name . " " . $kom->user_surname);?></small>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>