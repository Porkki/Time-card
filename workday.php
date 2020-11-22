<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
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

        <title>Työpäivät</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Työpäivät</h1>
                    <hr>
                    <p>Hae työpäiviä väliltä</p>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" name="startdate" id="startdate" value="<?php echo date("Y-m") . "-01"; ?>">
                            <small id="startdateHelp" class="form-text text-muted">Alkaen</small>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="enddate" id="enddate" value="<?php echo date("Y-m-d"); ?>">
                            <small id="startdateHelp" class="form-text text-muted">Päättyen</small>
                        </div>
                    </div>
                    
                    <table id="workdays" class="table table-striped table-hover w-100">
                        <thead>
                            <tr>
                                <th scope="col">Päivämäärä</th>
                                <th scope="col">Aika</th>
                                <th scope="col">Yhteensä</th>
                                <th scope="col">Selite</th>
                                <th scope="col">Toiminto</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    <p>Tunnit valitulla aikavälillä yhteensä: <span id="hours">0</span>.</p>
                </div>
            </div>
        </div>
        <div class="modal fade" id="removeConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeConfirmLabel">Varoitus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Oletko varma että haluat poistaa työpäivän <span id=name>e</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Peruuta</button>
                        <a href="" id="ahrefremoveuser" class="btn btn-danger">Poista</a>
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
        <script src="scripts/js/workday.js" type="module"></script>
    </body>
</html>