<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
// If user is employee he/she does not have rights to modify users
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

        <title>Muokkaa/Poista käyttäjiä</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white pl-3">
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
                            Oletko varma että haluat poistaa käyttäjän <span id=username>e</span>?
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
                            Käyttäjä poistettu onnistuneesti!
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
                            Käyttäjän poisto epäonnistui jostain syystä, ota yhteys asiakaspalveluun.
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>

<script>
/*
* TODO: Table sorting system implemention
*/
$(document).ready(function() {
    // Get users to table
    // Target element
    var table = $("#users > tbody:last-child");

    var counter = 0;
    // Populate table with list of users
    $.get("scripts/user_manager.php", function(data) {
        $.each(data, function(key, value) {
            table.append($("<tr>")
                .append($("<td>").text(value.firstname))
                .append($("<td>").text(value.lastname))
                .append($("<td>").text(value.username))
                .append($("<td>").attr("name","user_company_id").text(value.user_company_id))
                .append($("<td>")
                    .append($("<a href='updateuser.php?id=" + value.id + "' title='Muokkaa käyttäjää' data-toggle='tooltip'><i class='fas fa-edit pr-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista käyttäjä' data-toggle='modal' data-target='#removeConfirm' data-name='" + value.username + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))
                )
            );
        })
    }, "json")
        // Get company name from company id and count how many users are in it
        .always(function() {
            $('td[name = "user_company_id"]').each(function(index, element){
                $.get("scripts/company_manager.php?id=" + element.innerHTML, function(company) {
                    element.innerHTML = company[0].company_name;
                }, "json");
                counter++;
                document.getElementById("numberofusers").innerHTML=counter;
            });
            
        });
    // https://stackoverflow.com/a/25060114
    // Send name&userid to show in modal
    $('#removeConfirm').on('show.bs.modal', function (e) {
        var name = $(e.relatedTarget).data('name');
        $("#username").text( name );
        var id = $(e.relatedTarget).data('id');
        $("#ahrefremoveuser").attr("href", "scripts/user_manager.php?remove=" + id);
    })

    // Show report message of user removal action
    const urlParams = new URLSearchParams(window.location.search);
    const myParam = urlParams.has('result');

    if (myParam) {
        if (urlParams.get("result") === "done") {
            $('#doneModal').modal('show');
        } else if (urlParams.get("result") === "unsuccessful") {
            $('#unsuccessfulModal').modal('show');
        } else {
            $('#unsuccessfulModal').modal('show');
        }
    }
});

</script>