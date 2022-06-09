<?php session_start();   
  if(!empty($_SESSION["user"])){
    header("Location:/posts.php");
    exit();
  }

  include "assets/config/connection.php";

  $drzave=$conn->query("SELECT * FROM countries")->fetchAll();

?>
<?php include "header.php";?>


   <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="/index.php">Postmoon</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto" id="navigation">
    </ul>
    <form action="" method="POST" class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="text" id="logemail" placeholder="Email" aria-label="Search">
      <input class="form-control mr-sm-2" type="password" id="logpass" placeholder="Password" aria-label="Search">
      <button class="btn btn-outline-primary my-2 my-sm-0"  type="submit" id="btnLogin">Log in</button>
    </form>
  </div>
</nav>

<div class="backgroundPhoto">
<div class="container p-5 rounded regholder">
   <form action="" method="POST" class="needs-validation">
   <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputName">Name</label>
      <input type="text" class="form-control" id="inputName">
      <p id="registerName" class="text-danger">Minimum 3 letters with first capital letter</p>
    </div>
    <div class="form-group col-md-6">
      <label for="inputSurname">Surname</label>
      <input type="text" class="form-control" id="inputSurname">
      <p id="registerSurname" class="text-danger">Minimum 3 letters with first capital letter</p>
    </div>
</div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputEmail4">Email</label>
      <input type="email" class="form-control" id="inputEmail4">
      <p id="registerEmail" class="text-danger">Example:pera@gmail.com</p>
    </div>
    <div class="form-group col-md-6">
      <label for="inputPassword4">Password</label>
      <input type="password" class="form-control" id="inputPassword4">
      <p id="registerPassword" class="text-danger">Minimum 2 characters without specail characters</p>
    </div>
  </div>
  <div class="form-group">
    <label for="inputAddress">Address</label>
    <input type="text" class="form-control" id="inputAddress">
    <p id="registerAddress" class="text-danger">Can contain numbers and letters</p>
  </div>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for="inputCity">City</label>
      <input type="text" class="form-control" id="inputCity">
      <p id="registerCity" class="text-danger">Minimum 3 letters with first capital letter</p>
    </div>
    <div class="form-group col-md-4">
      <label for="inputCountry">Country</label>
      <select id="inputCountry" class="form-control">
        <option selected value="0">Choose...</option>
        <?php foreach($drzave as $dr):?>
          <option value="<?php echo $dr->id;?>"><?php echo $dr->name;?></option>
        <?php endforeach;?>
      </select>
      <p id="registerCountry" class="text-danger">Please choose country</p>
    </div>
    <div class="form-group col-md-2">
      <label for="inputZip">Zip</label>
      <input type="text" class="form-control" id="inputZip">
      <p id="registerZip" class="text-danger">Exactly 5 numbers</p>
    </div>
  </div>
  <div class="custom-control custom-radio">
  <input type="radio" id="customRadio1" name="customRadio" class="custom-control-input" value="Male">
  <label class="custom-control-label" for="customRadio1">Male</label>
</div>
<div class="custom-control custom-radio">
  <input type="radio" id="customRadio2" name="customRadio" class="custom-control-input" value="Female">
  <label class="custom-control-label" for="customRadio2">Female</label>
</div>
  <div class="alert alert-danger valradio">Please pick your gender </div>
  <div class="form-group mt-3">
    <div class="form-check">
      <input class="form-check-input" name="customCheck" type="checkbox" id="gridCheck" value="accept">
      <label class="form-check-label" for="gridCheck">
       Accept Terms and Conditions
      </label>
    </div>
  </div>
  <div class="alert alert-danger valcheck">Please agree with our terms</div>
  <button type="submit" id="btnRegister" class="btn btn-primary">Register</button>
  <div id="odgovor">
  
  </div>
</form>
   </div>
</div>

<?php include "footer.php";?>
