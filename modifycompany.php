<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
// If user is employee he/she does not have rights to modify users
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] != "admin"){
    header("location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
    <head>
        <?php include "scripts/include/head.php"; ?>

        <title>Muokkaa/Poista yrityksiä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white pl-3">
                    <h1>Muokkaa tai poista yrityksiä</h1>
                    <div class="table-responsive">
                        <table id="companies" class="table table-striped table-hover w-100">
                            <thead>
                                <tr>
                                    <th scope="col">Yrityksen nimi</th>
                                    <th scope="col">Y-tunnus</th>
                                    <th scope="col">Osoite</th>
                                    <th scope="col">Postinumero</th>
                                    <th scope="col">Paikkakunta</th>
                                    <th scope="col">Työntekijät</th>
                                    <th scope="col">Voim. ol. asiakas</th>
                                    <th scope="col">Luonut käyttäjä</th>
                                    <th scope="col">Toiminto</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="removeConfirm" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="removeConfirmLabel">Varoitus</h5>
                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Oletko varma että haluat poistaa yrityksen <span id=name>e</span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Peruuta</button>
                            <a href="" id="ahrefremoveuser" class="btn btn-danger">Poista</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Start of user action modals -->
            <div class="modal fade" id="doneModal" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Yritys poistettu onnistuneesti!
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="unsuccessfulModal" tabindex="-1">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="unsuccessfulModalLabel">Ilmoitus</h5>
                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Yrityksen poisto epäonnistui jostain syystä, ota yhteys asiakaspalveluun.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of user action modals -->
        </div>

        <!-- Page function scripts -->
        <?php include "scripts/include/scripts.php"; ?>
        <script src="scripts/js/modifycompany.js"></script>
    </body>
</html>