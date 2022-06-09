<?php

$cat = $conn->prepare("SELECT *
                        FROM category
                        WHERE id=:id");
$cat->bindParam(":id", $_GET["categoryid"]);

$cat->execute();

$result = $cat->fetch();

if (empty($result)) {
    header("Location:adminpanel.php?id=3");
    $_SESSION["error"] = "category not found";
    exit();
}

?>






<div class="container p-3">
    <?php include "assets/error/error.php" ?>
    <?php include "assets/success/success.php" ?>
    <form id="editcategoryform" action="/assets/models/editcategory.php" method="POST">
         <h1>Change category name</h1>
        <div class="form-group">
            <label for="oldcategoryname">Old category name</label>
            <input type="text" class="form-control" name="oldcategoryname" id="oldcategoryname" value="<?php echo $result->name?>" disabled>
        </div>
        <div class="form-group">
            <label for="categoryname">New category name</label>
            <input type="text" class="form-control" name="categoryname" id="categoryname"/>
            <p id="editcategoryerr" class="text-danger">Minimum 3 letters first capital letter</p>
            <input type="hidden" class="form-control" name="categoryid" id="categoryid" value="<?php echo $_GET["categoryid"] ?>">
        </div>
        <button type="submit" class="btn btn-primary mb-2">Update</button>
    </form>
</div>