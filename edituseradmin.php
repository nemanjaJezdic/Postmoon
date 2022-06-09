<?php
if(empty($_GET["userid"])){
   header("Location:adminpanel.php?id=1");
   exit();
}

$drzave = $conn->query("SELECT * FROM countries")->fetchAll();

$post = $conn->prepare("SELECT u.*,c.name as country_name,c.id as country_id
                          FROM users as u JOIN countries as c ON u.country_id=c.id  
                          WHERE u.id=:id");

$post->bindParam(":id", $_GET["userid"]);
$post->execute();

$result = $post->fetch();

if(empty($result)){
    header("Location:adminpanel.php?id=1");
    $_SESSION["error"]="user not found";
    exit();
}

?>







<?php include "assets/error/error.php" ?>
<?php include "assets/success/success.php" ?>
<h1 class="text-center">Edit user:</h1>
<form id="edituserform" action="/assets/models/edituser.php" method="POST">
    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" class="form-control" name="name" id="name" value="<?php echo $result->name; ?>">
        <p id="profileName" class="text-danger">Minimum 3 letters with first capital letter</p>
        <input type="hidden" class="form-control" name="userid" id="userid" value="<?php echo $_GET["userid"] ?>">
    </div>
    <div class="form-group">
        <label for="name">Surname</label>
        <input type="text" class="form-control" name="surname" id="surname" value="<?php echo $result->surname; ?>">
        <p id="profileSurname" class="text-danger">Minimum 3 letters with first capital letter</p>
    </div>
    <div class="form-group">
        <label for="name">City</label>
        <input type="text" class="form-control" name="city" id="city" value="<?php echo $result->city; ?>">
        <p id="profileCity" class="text-danger">Minimum 3 letters with first capital letter</p>
    </div>
    <div class="form-group">
        <label for="name">Zip</label>
        <input type="text" class="form-control" name="zip" id="zip" value="<?php echo $result->zip; ?>">
        <p id="profileZip" class="text-danger">Exactly 5 numbers</p>
    </div>
    <div class="form-group">
        <label for="name">Address</label>
        <input type="text" class="form-control" name="address" id="address" value="<?php echo $result->address; ?>">
        <p id="profileAddress" class="text-danger">Can contain numbers and letters</p>
    </div>
    <div class="form-group">
        <label for="country">Country</label>
        <select name="country" class="form-control" id="country">
            <option value="0">Choose...</option>
            <?php foreach ($drzave as $dr) : ?>
                <?php if ($dr->id == $result->country_id) : ?>
                    <option selected value="<?php echo $dr->id; ?>"><?php echo $dr->name; ?></option>
                <?php else : ?>
                    <option value="<?php echo $dr->id; ?>"><?php echo $dr->name; ?></option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <p id="profileCountry" class="text-danger">Please choose country</p>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Confirm changes</button>
</form>