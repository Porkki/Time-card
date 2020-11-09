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
    </body>
</html>

<script>
$(document).ready(function() {
    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("scripts/company_manager.php?id=" + id, function(data) {
        document.getElementsByName("company_name")[0].value = data[0].company_name;
        document.getElementsByName("ytunnus")[0].value = data[0].ytunnus;
        document.getElementsByName("company_address")[0].value = data[0].company_address;
        document.getElementsByName("company_postcode")[0].value = data[0].company_postcode;
        document.getElementsByName("company_area")[0].value = data[0].company_area;
        document.getElementsByName("is_client")[0].value = data[0].is_client;
        document.getElementsByName("id")[0].value = data[0].id;
    }, "json");

    // Form handling
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "scripts/company_manager.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var companyname = data.companyname;
                var error = data.error;
                if (companyname) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "modifycompany.php";
                    }, 2000);
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});
</script>