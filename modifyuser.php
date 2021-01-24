<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
// If user is employee he/she does not have rights to modify users
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] == "employee"){
    header("location: index.php");
    exit;
}
?>

<!doctype html>
<html lang="en">
    <head>
        <?php include "scripts/include/head.php"; ?>

        <title>Muokkaa/Poista käyttäjiä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row g-0">
                <?php include "scripts/include/nav.php"; ?>
                <div class="box-shadow col-md-10 border bg-white p-2">
                    <h1>Muokkaa tai poista käyttäjiä</h1>
                    <div class="table-responsive">
                        <table id="users" class="table table-striped table-hover w-100">
                            <thead>
                                <tr>
                                    <th data-column-id="firstname" scope="col">Etunimi</th>
                                    <th data-column-id="lastname" scope="col">Sukunimi</th>
                                    <th data-column-id="username" scope="col">Käyttäjänimi</th>
                                    <th data-column-id="user_company_id" scope="col">Yritys</th>
                                    <th scope="col">Toiminto</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <p>Löydetty <span id="numberofusers">0</span> käyttäjää.</p>
                </div>
            </div>
        </div>
        <!-- Start of user action modals -->
        <div class="modal fade" id="removeConfirm" tabindex="-1"  >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="removeConfirmLabel">Varoitus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Oletko varma että haluat poistaa käyttäjän <span id=username>e</span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Peruuta</button>
                        <a href="#" id="ahrefremoveuser" class="btn btn-danger">Poista</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="doneModal" tabindex="-1"  >
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="doneModalLabel">Ilmoitus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Käyttäjä poistettu onnistuneesti!
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </div>
                    <div class="modal-body">
                        Käyttäjän poisto epäonnistui jostain syystä, ota yhteys asiakaspalveluun.
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
        <script src="scripts/js/modifyuser.js"></script>
    </body>
</html>