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
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- FontAwesome -->
        <link href="css/all.css" rel="stylesheet">

        <title>Muokkaa/Poista yrityksiä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white pl-3">
                    <h1>Muokkaa tai poista yrityksiä</h1>
                    <div class="table-responsive-lg">
                        <table id="companies" class="table table-striped table-hover">
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
                            Oletko varma että haluat poistaa yrityksen <span id=name>e</span>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Peruuta</button>
                            <a href="" id="ahrefremoveuser" class="btn btn-danger">Poista</a>
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
                            Yritys poistettu onnistuneesti!
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
                            Yrityksen poisto epäonnistui jostain syystä, ota yhteys asiakaspalveluun.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End of user action modals -->
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
        <!-- Page function scripts -->
        <script src="scripts/js/modifycompany.js"></script>
    </body>
</html>