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

        <title>Vaihda salasanaa</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-12 col-sm-12 col-md-10 border bg-white p-2">
                    <h1>Vaihda salasana</h1>
                    <form id="changepw" class="row g-3">
                        <div class="col-12">
                            <label for="oldpassword"><b>Vanha salasana</b></label>
                            <input type="password" class="form-control" name="oldpassword" placeholder="Vanha salasana" required>
                        </div>
                        <div class="col-12">
                            <label for="newpassword"><b>Uusi salasana</b></label>
                            <input type="password" class="form-control" name="newpassword" placeholder="Uusi salasana" required>
                        </div>
                        <div class="col-12">
                            <label for="retypepassword"><b>Varmista uusi salasana</b></label>
                            <input type="password" class="form-control" name="retypepassword" placeholder="Uusi salasana" required>
                        </div>
                        <div class="col-12">
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
                        Salasana päivitetty!
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
        <script src="scripts/js/changepassword.js"></script>
    </body>
</html>