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
        <?php include "scripts/include/head.php"; ?>

        <title>Etusivu</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
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
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-8">
                                    <small class="text-muted"><b><span id="weekinfo"></span></b></small>
                                </div>
                                <div class="col-auto ms-auto">
                                    <button id="prevweek" class="btn btn-primary"><i class="fas fa-angle-double-left"></i></button>
                                    <button id="nextweek" class="btn btn-primary"><i class="fas fa-angle-double-right"></i></button>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <canvas id="currentweek"></canvas>
                                    <p class="text-center text-muted">Tunnit yhteensä: <span id="currentweektotalhours"></span></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted"><b>Kuluvan kuukauden tunnit</b></small>
                            <canvas id="currentmonth" class="mt-3"></canvas>
                            <p class="text-center text-muted">Tunnit yhteensä: <span id="currentmonthtotalhours"></span></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <small class="text-muted"><b>Kuluvan vuoden yhteenveto (<span id="currentyear"></span>)</b></small>
                            <canvas id="currentyeartotal"></canvas>
                        </div>
                        <div class="col-lg-6">
                            <small class="text-muted"><b>Viime vuoden yhteenveto (<span id="previousyear"></span>)</b></small>
                            <canvas id="lastyeartotal"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/moment.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
        <script src="scripts/js/welcome.js"></script>
    </body>
</html>