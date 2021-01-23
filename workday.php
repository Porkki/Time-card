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

        <title>Työpäivät</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
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
                    <div class="row">
                        <div class="col ml-auto">
                            <a href="#">Tulosta pdf</a>
                        </div>
                    </div>
                    
                    <table id="workdays" class="table table-striped table-hover w-100 table-responsive">
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
                        <a href="#" id="ahrefremoveworkday" class="btn btn-danger">Poista</a>
                    </div>
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
                        Työpäivä poistettu onnistuneesti!
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
                        Työpäivän poisto epäonnistui jostain syystä, ota yhteys asiakaspalveluun.
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
        <script src="scripts/js/workday.js" type="module"></script>
    </body>
</html>