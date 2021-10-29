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
        <?php include "scripts/include/head.php"; ?>

        <title>Asetukset</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-12 col-sm-12 col-md-10 border bg-white p-2">
                    <h1>Asetukset</h1>
                    <hr>
                    <form id="settingsform" class="row g-3">
                        <div class="col-12">
                            <h2>Automaattiset ajat uudelle työpäivälle</h2>
                        </div>
                        <div class="col-md-4">
                            <label for="autostarttime">Aloitusaika</label>
                            <input type="time" class="form-control" name="autostarttime">
                        </div>
                        <div class="col-md-4">
                            <label for="autoendtime">Lopetusaika</label>
                            <input type="time" class="form-control" name="autoendtime">
                        </div>
                        <div class="col-md-4">
                            <label for="autobreak">Tauko</label>
                            <input type="time" class="form-control" name="autobreaktime">
                        </div>
                        <div class="col-12">
                            <input type="hidden" class="form-control" name="postfrom" value="usersettings">
                            <button id="cancel" class="btn btn-secondary">Peruuta</button>
                            <button type="submit" value="submit" name="submit" class="btn btn-success">Tallenna</button>
                        </div>
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
                        Asetukset päivitetty!
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
                        <h5 class="modal-title" id="unsuccessfulModalLabel">Ilmoitus</h5>
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
        <script src="scripts/js/usersettings.js"></script>
    </body>
</html>