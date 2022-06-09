<?php session_start();
if (empty($_SESSION["user"])) {
  header("Location:/index.php");
  exit();
}

include "assets/config/connection.php";

$drzave = $conn->query("SELECT * FROM countries")->fetchAll();

$post = $conn->prepare("SELECT u.*,c.name as country_name,c.id as country_id
                          FROM users as u JOIN countries as c ON u.country_id=c.id  
                          WHERE u.id=:id");

$post->bindParam(":id", $_SESSION["user"]->id);
$post->execute();

$result = $post->fetch();

?>

<?php include "header.php"; ?>

<?php include "nav.php"; ?>



<div class="container p-3">
  <div class="row align-items-center">
    <div class="col-12 col-lg-4">
      <div class="card text-left mt-5">
        <img src="/assets/img/user.webp" class="card-img-top" alt="...">
        <div class="card-body">
          <p class="card-text">Name: <?php echo $result->name; ?></p>
          <p class="card-text">Surname: <?php echo $result->surname; ?></p>
          <p class="card-text">Gender: <?php echo $result->gender; ?></p>
          <p class="card-text">Email: <?php echo $result->email; ?></p>
          <p class="card-text">Address: <?php echo $result->address; ?></p>
          <p class="card-text">City: <?php echo $result->city; ?></p>
          <p class="card-text">Zip: <?php echo $result->zip; ?></p>
          <p class="card-text">Country : <?php echo $result->country_name ?></p>
        </div>
      </div>
    </div>
    <div class="col-12 col-lg-8">
      <?php include "assets/error/error.php"?>
      <?php include "assets/success/success.php"?>
      <h1 class="text-center">Edit your profile:</h1>
      <form id="profileform" action="/assets/models/userupdate.php" method="POST">
        <div class="form-group">
          <label for="name">Name</label>
          <input type="text" class="form-control" name="name" id="name" value="<?php echo $result->name; ?>">
          <p id="profileName" class="text-danger">Minimum 3 letters with first capital letter</p>
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
              <?php if ($dr->id==$result->country_id) : ?>
                 <option selected value="<?php echo $dr->id; ?>"><?php echo $dr->name; ?></option>
              <?php else :?>
                  <option value="<?php echo $dr->id; ?>"><?php echo $dr->name; ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
          <p id="profileCountry" class="text-danger">Please choose country</p>
        </div>
        <button type="submit" class="btn btn-primary mb-2">Confirm changes</button>
      </form>
    </div>
  </div>
</div>


<?php include "footer.php"; ?>