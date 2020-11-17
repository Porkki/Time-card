<?php
// Set path to filename so we can see which page is active for navigation highlight
$path = explode("/", $_SERVER["PHP_SELF"]);

// List navigation according which class user is
if ($_SESSION["class"] == "employee") {

}
?>

<div class="box-shadow col-md-2 border h-100">
    <div class="navbar navbar-expand-md navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "welcome.php") {echo('active');} else {echo('');}?>" href="welcome.php">Etusivu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "workday.php") {echo('active');} else {echo('');}?>" href="workday.php">Työpäivät</a>
                </li>
                <?php if ($_SESSION["class"] == "admin" || $_SESSION["class"] == "employer") { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "workday_employer.php") {echo('active');} else {echo('');}?>" href="workday_employer.php">Työntekijän työpäivät</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "addworkday.php") {echo('active');} else {echo('');}?>" href="addworkday.php">Lisää työpäivä</a>
                </li>
                <?php if ($_SESSION["class"] == "admin" || $_SESSION["class"] == "employer") { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "adduser.php") {echo('active');} else {echo('');}?>" href="adduser.php">Lisää käyttäjiä</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "modifyuser.php") {echo('active');} else {echo('');}?>" href="modifyuser.php">Muokkaa/Poista käyttäjiä</a>
                </li>
                <?php } ?>
                <?php if ($_SESSION["class"] == "admin") { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "addcompany.php") {echo('active');} else {echo('');}?>" href="addcompany.php">Lisää yritys</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php if ($path[2] == "modifycompany.php") {echo('active');} else {echo('');}?>" href="modifycompany.php">Muokkaa/Poista yrityksiä</a>
                </li>
                <?php } ?>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Kirjaudu ulos</a>
                </li>
            </ul>
        </div>
    </div>
</div>