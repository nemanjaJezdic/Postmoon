
<div class="container p-3">
    <?php include "assets/error/error.php" ?>
    <?php include "assets/success/success.php" ?>
    <form id="addcatform" action="/assets/models/addcategory.php" method="POST">
         <h1>Add new Category</h1>
        <div class="form-group">
            <label for="catname">Name new Category</label>
            <input type="text" class="form-control" name="catname" id="catname">
            <p id="addcategoryerr" class="text-danger">Minimum 3 letters first capital letter</p>
        </div>
        <button type="submit" class="btn btn-primary mb-2">Add</button>
    </form>
</div>