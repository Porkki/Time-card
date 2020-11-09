<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
// If user is employee he/she does not have rights to modify users
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["class"] != "admin"){
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>

<script>
$(document).ready(function() {
    // Get companies to table
    // Target element
    var table = $("#companies > tbody:last-child");
    // Populate table with list of companies
    $.get("scripts/company_manager.php", function(data) {
        $.each(data, function(key, value) {
            table.append($("<tr>")
                .append($("<td>").text(value.company_name))
                .append($("<td>").text(value.ytunnus))
                .append($("<td>").text(value.company_address))
                .append($("<td>").text(value.company_postcode))
                .append($("<td>").text(value.company_area))
                .append($("<td>").text(value.is_client))
                .append($("<td>").attr("name","created_user_id").text(value.created_user_id))
                .append($("<td>")
                    .append($("<a href='updatecompany.php?id=" + value.id + "' title='Muokkaa yritystä' data-toggle='tooltip'><i class='fas fa-edit pr-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista yritys' data-toggle='modal' data-target='#removeConfirm' data-name='" + value.company_name + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))
                )
            );
        })


    }, "json")
        // Get username who created the company to database
        .always(function() {
            $('td[name = "created_user_id"]').each(function(index, element){
                $.get("scripts/user_manager.php?id=" + element.innerHTML, function(user) {
                    element.innerHTML = user[0].username;
                }, "json");
            });
        });


    // https://stackoverflow.com/a/25060114
    // Send name&userid to show in modal
    $('#removeConfirm').on('show.bs.modal', function (e) {
        var name = $(e.relatedTarget).data('name');
        $("#name").text( name );
        var id = $(e.relatedTarget).data('id');
        $("#ahrefremoveuser").attr("href", "scripts/workday_manager.php?remove=" + id);
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