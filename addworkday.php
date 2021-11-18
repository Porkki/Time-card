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
                        <div class="form-floating col-12">
                            <input type="date" class="form-control" name="date" id="date" value="<?php echo date("Y-m-d"); ?>">
                            <label for="date">Päivämäärä</label>
                        </div>
                        <div class="form-floating col-md-4">
                            <input type="datetime-local" class="form-control" name="starttime" id="starttime" value="<?php echo date("Y-m-d") . "T00:00"; ?>">
                            <label for="starttime">Aloitusaika</label>
                            <div id="timeHelp" class="form-text">Voit asettaa automaattisen ajan <a href="usersettings.php" class="link-secondary">asetuksista</a></div>
                        </div>
                        <div class="form-floating col-md-4">
                            <input type="datetime-local" class="form-control" name="endtime" id="endtime" value="<?php echo date("Y-m-d\TH:i"); ?>">
                            <label for="endtime">Lopetusaika</label>
                        </div>
                        <div class="form-floating col-md-4">
                            <input type="time" class="form-control" name="breaktime" id="breaktime">
                            <label for="break">Tauko</label>
                        </div>
                        <div class="form-floating col-12">
                            <textarea class="form-control" name="explanation" rows="3" maxlength="250" aria-describedby="explanationHelpBlock"></textarea>
                            <label for="explanation">Selite</label>
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
        <div class="modal fade" tabindex="-1" id="doneModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ilmoitus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Työpäivä lisätty onnistuneesti!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" tabindex="-1" id="unsuccessfulModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ilmoitus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Työpäivän lisäys epäonnistui.</p>
                        <span id="errormessage"></span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ok</button>
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
