<?php if (session_status() === PHP_SESSION_NONE) {
            session_start();
}
?>
<?php if(!empty($_SESSION["error"])):?>

<div class="alert alert-danger">
     <p><?php
           echo $_SESSION["error"];
           unset($_SESSION["error"]);
       ?></p>
</div>


<?php endif;?>
