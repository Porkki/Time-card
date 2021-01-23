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
        <?php include "scripts/include/head.php"; ?>

        <title>Lisää työpäivä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
                    <h1>Lisää uusi työpäivä</h1>
                    <hr>
                    <form id="createnewworkday" class="row g-3">
                        <div class="col-12">
                            <label for="date">Päivämäärä</label>
                            <input type="date" class="form-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="starttime">Aloitusaika</label>
                            <input type="datetime-local" class="form-control" name="starttime" id="starttime" value="<?php echo date("Y-m-d") . "T00:00"; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="endtime">Lopetusaika</label>
                            <input type="datetime-local" class="form-control" name="endtime" id="endtime" value="<?php echo date("Y-m-d\TH:i"); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="break">Tauko</label>
                            <input type="time" class="form-control" name="breaktime">
                        </div>
                        <div class="col-12">
                            <label for="explanation">Selite</label>
                            <textarea class="form-control" name="explanation" rows="3" maxlength="250" aria-describedby="explanationHelpBlock"></textarea>
                            <small id="explanationHelpBlock" class="form-text text-muted">
                            Max 250 kirjainta.
                            </small>
                            <input type="hidden" class="form-control" name="postfrom" value="createworkday">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">Lisää työpäivä</button>
                        </div>
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
                        Työpäivä lisätty onnistuneesti!
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
                        <h5 class="modal-title" id="unsuccessfulModalLabel">Ilmoitus</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Työpäivän lisäys epäonnistui.<br>
                        <span id="errormessage"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of user action modals -->

        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/addworkday.js"></script>
    </body>
</html>
