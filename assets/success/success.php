<?php if (session_status() === PHP_SESSION_NONE) {
            session_start();
}
?>
<?php if(!empty($_SESSION["success"])):?>

<div class="alert alert-success">
     <p><?php
           echo $_SESSION["success"];
           unset($_SESSION["success"]);
       ?></p>
</div>


<?php endif;?>