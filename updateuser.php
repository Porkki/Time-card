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

        <title>Muokkaa käyttäjää</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Muokkaa käyttäjää</h1>
                    <hr>
                    <form id="target" method="POST">
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="class"><b>Käyttäjäluokka</b></label>
                                <select class=form-control name="class" required>
                                    <?php if ($_SESSION["class"] == "admin") { ?>
                                    <option value="admin">Ylläpitäjä</option>
                                    <?php } ?>
                                    <option value="employer">Työnantaja</option>
                                    <option value="employee">Työntekijä</option>
                                </select>
                            </div>
                            <div class="form-group col">
                                <label for="user_company_id"><b>Yritys</b></label>
                                <select class="form-control" id="user_company_id" name="user_company_id" required>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="firstname"><b>Etunimi</b></label>
                                <input type="text" class="form-control" name="firstname" required>
                            </div>
                            <div class="form-group col">
                                <label for="lastname"><b>Sukunimi</b></label>
                                <input type="text" class="form-control" name="lastname" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col">
                                <label for="username"><b>Käyttäjätunnus</b></label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            <div class="form-group col">
                                <label for="password"><b>Salasana</b></label>
                                <input type="password" class="form-control" name="password" placeholder="Salasana">
                                <input type="hidden" class="form-control" name="id">
                            </div>
                        </div>
                        <hr>
                        <button type="submit" value="submit" name="submit" class="btn btn-success">Päivitä</button>
                        <a href="modifyuser.php" class="btn btn-danger">Peruuta</a>
                    </form>
                    <hr>
                    <p><b>Huom!</b> jos muutat salasana kenttää niin tällöin myös muuttuu käyttäjän salasana. Jätä kenttä tyhjäksi jos et halua muuttaa käyttäjän salasanaa.</p>
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
                                    Käyttäjä päivitetty onnistuneesti!
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
                                    Käyttäjän päivitys epäonnistui.<br>
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
    // Get companies to list
    // https://www.codebyamir.com/blog/populate-a-select-dropdown-list-with-json

    // Target element
    var dropdown = $("#user_company_id");
    dropdown.empty();
    // Create default option
    dropdown.append('<option selected="true" disabled>Valitse yritys</option>');
    // Populate dropdown with list of companies
    $.get("scripts/company_manager.php", function(data) {
        $.each(data, function(key, value) {
            dropdown.append($("<option></option>").attr("value", value.id).text(value.company_name));
        })
    }, "json");

    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("scripts/user_manager.php?id=" + id, function(data) {
        document.getElementsByName("firstname")[0].value = data[0].firstname;
        document.getElementsByName("lastname")[0].value = data[0].lastname;
        document.getElementsByName("username")[0].value = data[0].username;
        document.getElementsByName("class")[0].value = data[0].class;
        document.getElementsByName("user_company_id")[0].value = data[0].user_company_id;
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
            url: "scripts/user_manager.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var username = data.username;
                var error = data.error;
                if (username) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "modifyuser.php";
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
