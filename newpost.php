<?php session_start();
    if(empty($_SESSION["user"])){
       header("Location:/index.php");
       exit();
    }

    include "assets/config/connection.php";

    $kategorije=$conn->query("SELECT * FROM category")->fetchAll();
    

?>
<?php include "header.php";?>

<?php include "nav.php";?>



<div class="container p-3">
<?php include "assets/error/error.php"?>
<?php include "assets/success/success.php"?>
<form id="newpostform" action="/assets/models/insertpost.php" method="POST" enctype="multipart/form-data">
  <div class="form-group">
    <label for="heading">Heading</label>
    <input type="text" class="form-control" name="heading" id="heading">
    <p id="newpostheading" class="text-danger">Must contain minimum 5 characters without special characters</p>
  </div>
  <div class="form-group">
    <label for="categoryid">Category</label>
    <select class="form-control" name="category_id" id="category_id">
      <option value="0">Choose...</option>
      <?php foreach($kategorije as $kt):?>
           <option value="<?php echo $kt->id?>"><?php echo $kt->name ?></option>
       <?php endforeach;?>
    </select>
    <p id="newpostcategory" class="text-danger">Please select category</p>
  </div>
  <div class="form-group">
    <label for="image">Upload post image</label>
    <input type="file" class="form-control-file" name="image" id="image">
  </div>
  <button type="submit" class="btn btn-primary mb-2">Create new post</button>
</form>
</div>

<?php include "footer.php";?>