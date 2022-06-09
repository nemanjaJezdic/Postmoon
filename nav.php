<?php
$navigation = $conn->query("SELECT * FROM navigation")->fetchAll();
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="/posts.php">Postmoon</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto" id="navigation">
            <?php foreach ($navigation as $nav) : ?>
                <?php
                if (!empty($_SESSION["user"]) && $_SESSION["user"]->role_id == "1" && $nav->admin == 1) :
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/<?php echo $nav->location?>"><?php echo $nav->name?></a>
                    </li>
                <?php
                endif;
                ?>
                <?php
                if ($nav->admin == 0) :
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/<?php echo $nav->location?>"><?php echo $nav->name?></a>
                    </li>
                <?php
                endif;
                ?>
            <?php endforeach; ?>
        </ul>
        <?php
          if($_SERVER["PHP_SELF"]=="/posts.php" || $_SERVER["PHP_SELF"]=="/category.php"):
        ?>      
        <form action="" method="GET" class="form-inline my-2 my-lg-0">
            <?php if(!empty($kategorijaid)):?>
             <input type="hidden" id="id" name="id" value="<?php echo $kategorijaid ?>"/>
            <?php endif;?>
            <input class="form-control mr-sm-2" type="search" id="search" name="search" placeholder="Search" aria-label="Search" value="<?php
                                                                                                                                        echo isset($_GET["search"]) ? $_GET["search"] : "";
                                                                                                                                        ?>">
            <button type="submit" class="btn btn-primary fas fa-search p-2"></button>
        </form>
        <?php
          endif;
        ?>
    </div>
</nav>