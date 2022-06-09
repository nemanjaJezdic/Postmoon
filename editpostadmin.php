<?php

$kategorije = $conn->query("SELECT * FROM category")->fetchAll();
$post = $conn->prepare("SELECT *
                        FROM posts
                        WHERE id=:id");
$post->bindParam(":id", $_GET["postid"]);

$post->execute();

$result = $post->fetch();

if (empty($result)) {
    header("Location:adminpanel.php?id=2");
    $_SESSION["error"] = "post not found";
    exit();
}

?>






<div class="container p-3">
    <?php include "assets/error/error.php" ?>
    <?php include "assets/success/success.php" ?>
    <form id="editpostform" action="/assets/models/editpost.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="heading">Heading</label>
            <input type="text" class="form-control" name="heading" id="heading" value="<?php echo $result->heading ?>">
            <p id="newpostheading" class="text-danger">Must contain minimum 5 characters without special characters</p>
            <input type="hidden" class="form-control" name="postid" id="postid" value="<?php echo $_GET["postid"] ?>">
        </div>
        <div class="form-group">
            <label for="categoryid">Category</label>
            <select class="form-control" name="category_id" id="category_id">
                <option value="0">Choose...</option>
                <?php foreach ($kategorije as $kt) : ?>
                    <?php if ($kt->id == $result->category_id) : ?>
                        <option selected value="<?php echo $kt->id; ?>"><?php echo $kt->name; ?></option>
                    <?php else : ?>
                        <option value="<?php echo $kt->id; ?>"><?php echo $kt->name; ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
            <p id="newpostcategory" class="text-danger">Please select category</p>
        </div>
        <div class="form-group">
            <label for="image">Upload post image</label>
            <input type="file" class="form-control-file" name="image" id="image">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Update</button>
    </form>
</div>