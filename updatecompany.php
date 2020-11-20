<?php
    // Initialize the session
    session_start();
    
    // Check if the user is logged in, if not then redirect him to login page
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] == "employee"){
        header("location: login.php");
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
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- FontAwesome -->
        <link href="css/all.css" rel="stylesheet">

        <title>Muokkaa yritystä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Muokkaa yritystä</h1>
                    <hr>
                    <form id="target">
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="company_name"><b>Yrityksen nimi</b></label>
                                <input type="text" class="form-control" name="company_name" placeholder="Esimerkki Oy" required>
                            </div>
                            <div class="form-group col">
                                <label for="ytunnus"><b>Y-tunnus</b></label>
                                <input type="text" class="form-control" name="ytunnus" placeholder="1234567-1" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="company_address"><b>Osoite</b></label>
                            <input type="text" class="form-control" name="company_address" placeholder="Yrityspolku 1" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="company_postcode"><b>Postinumero</b></label>
                                <input type="text" class="form-control" name="company_postcode" placeholder="12345" required>
                            </div>
                            <div class="form-group col">
                                <label for="company_area"><b>Paikkakunta</b></label>
                                <input type="text" class="form-control" name="company_area" placeholder="Helsinki" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="is_client"><b>Voimassa oleva asiakkuus</b></label>
                            <select class=form-control name="is_client">
                                <option value=1>Kyllä</option>
                                <option value=0>Ei</option>
                            </select>
                            <input type="hidden" class="form-control" name="id">
                        </div>
                        <button type="submit" value="submit" name="submit" class="btn btn-success">Päivitä</button>
                        <a href="modifycompany.php" class="btn btn-danger">Peruuta</a>
                    </form>
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
                                    Yritys päivitetty onnistuneesti!
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
                                    Yrityksen päivitys epäonnistui.<br>
                                    <span id="errormessage"></span>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Ok</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of user action modals -->
                </div>
            </div>
        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="scripts/jquery-3.5.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="scripts/js/updatecompany.js"></script>
    </body>
</html>