<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Set default timezone to helsinki
date_default_timezone_set('Europe/Helsinki');

/*
* TODO:
* Datetimen min ja max valuet työnanatajan säätämien asetuksien mukaan
* esim jos työnantaja ei halua että työntekijä voi lisätä työpäiviä jälkikäteen/etukäteen
*/
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

        <title>Muokkaa työpäivää</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Muokkaa työpäivää</h1>
                    <hr>
                    <form id="modifyworkday">
                        <div class="form-group">
                            <label for="date">Päivämäärä</label>
                            <input type="date" class="form-control" name="date" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="starttime">Aloitusaika</label>
                                <input type="datetime-local" class="form-control" name="starttime" value="<?php echo date("Y-m-d") . "T00:00"; ?>">
                            </div>
                            <div class="form-group col">
                                <label for="endtime">Lopetusaika</label>
                                <input type="datetime-local" class="form-control" name="endtime" value="<?php echo date("Y-m-d\TH:i"); ?>">
                            </div>
                            <div class="form-group col">
                                <label for="breaktime">Tauko</label>
                                <input type="time" class="form-control" name="breaktime" aria-describedby="breaktimeHelpBlock">
                                <small id="breaktimeHelpBlock" class="form-text text-muted">
                                hh:mm
                                </small>
                                <input type="hidden" class="form-control" name="id">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="explanation">Selite</label>
                            <textarea class="form-control" name="explanation" rows="3" maxlength="250" aria-describedby="explanationHelpBlock"></textarea>
                            <small id="explanationHelpBlock" class="form-text text-muted">
                            Max 250 kirjainta.
                            </small>
                            <input type="hidden" class="form-control" name="postfrom" value="updateworkday">
                        </div>
                        <div class="form-group" id="created">
                            <p>Luotu: <span id="created"></span></p>
                        </div>
                        <button type="button" id="cancel" class="btn btn-secondary">Peruuta</button>
                        <button type="submit" class="btn btn-success">Muokkaa työpäivää</button>
                    </form>
                </div>
            </div>
        </div>
        <!-- Start of user action modals -->
        <div class="modal fade" id="doneModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Työpäivä päivitetty onnistuneesti!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="unsuccessfulModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unsuccessfulModalLabel">Virhe</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span id="errormessage"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of user action modals -->
       
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <!-- Page function scripts -->
        <script src="scripts/js/modifyworkday.js"></script>
    </body>
</html>