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

        <title>Muokkaa työpäivää</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
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
        <div class="modal fade" id="doneModal" tabindex="-1"  >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                        <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Työpäivä päivitetty onnistuneesti!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="unsuccessfulModal" tabindex="-1"  >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="unsuccessfulModalLabel">Virhe</h5>
                        <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
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
        <script src="scripts/js/modifyworkday.js"></script>
    </body>
</html>