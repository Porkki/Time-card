<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include_once __DIR__ . "./models/user.php";
include_once __DIR__ . "./models/company.php";
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- FontAwesome -->
        <link href="css/all.css" rel="stylesheet">

        <title>Etusivu</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <div class="row">
                        <div class="col">
                            <?php
                            $user = User::withID(trim($_SESSION["id"]));
                            $userCompany = Company::withID($user->user_company_id);
                            $companylogoPath = "img/company_logos/$user->user_company_id.png";

                            if (file_exists($companylogoPath)) {
                                echo "<img class='mx-auto img-fluid d-block' src='$companylogoPath'>";
                            } else {
                                echo "<h1 class='text-center'>$userCompany->name</h1>";
                            }
                            ?>
                            <hr>
                            <h1>Tervetuloa <?php echo $user->firstname . " " . $user->lastname . "!"; ?></h1>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-lg">
                            <small class="text-muted"><b>Kuluvan viikon tunnit</b></small>
                            <canvas id="currentweek" class="col-md"></canvas>
                            <p class="text-center text-muted">Tunnit yhteensä: <span id="currentweektotalhours"></span></p>
                        </div>
                        <div class="w-100"></div>
                        <div class="col-lg">
                            <small class="text-muted"><b>Kuluvan kuukauden tunnit</b></small>
                            <canvas id="currentmonth" class="col-md"></canvas>
                            <p class="text-center text-muted">Tunnit yhteensä: <span id="currentmonthtotalhours"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg">
                            <small class="text-muted"><b>Kuluvan vuoden yhteenveto (<span id="currentyear"></span>)</b></small>
                            <canvas id="currentyeartotal" class="col-md"></canvas>
                        </div>
                        <div class="col-lg">
                            <small class="text-muted"><b>Viime vuoden yhteenveto (<span id="previousyear"></span>)</b></small>
                            <canvas id="lastyeartotal" class="col-md"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <!-- Page function scripts -->
        <script src="scripts/js/moment.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script src="scripts/js/welcome.js"></script>
    </body>
</html>