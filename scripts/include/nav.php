<?php
// Set path to filename so we can see which page is active for navigation highlight
$path = explode("/", $_SERVER["PHP_SELF"]);

// List navigation according which class user is
if ($_SESSION["class"] == "employee") {

}
?>

<div class="box-shadow col-md-auto border h-100">
    <div class="navbar navbar-expand-md navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav flex-column w-100">
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "welcome.php") {echo('active');} else {echo('');}?>" href="welcome.php"><i class="fas fa-home fa-fw"></i> Etusivu</a>
                </li>
                <li class="nav-item dropdown <?php if ($path[2] == "workday.php" || $path[2] == "addworkday.php" || $path[2] == "workday_employer.php" || $path[2] == "modifyworkday.php") {echo('active');} else {echo('');}?>">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><i class="fas fa-clock fa-fw"></i> Työpäivät</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if ($path[2] == "workday.php") {echo('active');} else {echo('');}?>" href="workday.php"><i class="fas fa-history fa-fw"></i> Katsele työpäiviä</a></li>
                        <li><a class="dropdown-item <?php if ($path[2] == "addworkday.php") {echo('active');} else {echo('');}?>" href="addworkday.php"><i class="fas fa-calendar-plus fa-fw"></i> Lisää työpäivä</a></li>

                        <?php if ($_SESSION["class"] == "admin" || $_SESSION["class"] == "employer") { ?>
                            <li><a class="dropdown-item <?php if ($path[2] == "workday_employer.php") {echo('active');} else {echo('');}?>" href="workday_employer.php"><i class="fas fa-user-clock fa-fw"></i> Työntekijän työpäivät</a></li>
                        <?php } ?>

                    </ul>
                </li>
                <?php if ($_SESSION["class"] == "admin" || $_SESSION["class"] == "employer") { ?>
                <li class="nav-item dropdown <?php if ($path[2] == "adduser.php" || $path[2] == "modifyuser.php" || $path[2] == "updateuser.php") {echo('active');} else {echo('');}?>">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><i class="fas fa-users fa-fw"></i> Käyttäjät</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if ($path[2] == "modifyuser.php") {echo('active');} else {echo('');}?>" href="modifyuser.php"><i class="fas fa-users-cog fa-fw"></i> Katsele työntekijöitä</a></li>
                        <li><a class="dropdown-item <?php if ($path[2] == "adduser.php") {echo('active');} else {echo('');}?>" href="adduser.php"><i class="fas fa-user-plus fa-fw"></i> Lisää työntekijöitä</a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if ($_SESSION["class"] == "admin") { ?>
                <li class="nav-item dropdown <?php if ($path[2] == "addcompany.php" || $path[2] == "modifycompany.php" || $path[2] == "updatecompany.php") {echo('active');} else {echo('');}?>">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><i class="fas fa-briefcase fa-fw"></i> Yritykset</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item <?php if ($path[2] == "addcompany.php") {echo('active');} else {echo('');}?>" href="addcompany.php">Lisää yritys</a></li>
                        <li><a class="dropdown-item <?php if ($path[2] == "modifycompany.php") {echo('active');} else {echo('');}?>" href="modifycompany.php">Muokkaa/Poista yrityksiä</a></li>
                    </ul>
                </li>
                <?php } ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><i class="fas fa-cog fa-fw"></i> Asetukset</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-wrench fa-fw"></i> Vaihda salasana</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt fa-fw"></i> Kirjaudu ulos</a>
                </li>
            </ul>
        </div>
    </div>
</div>