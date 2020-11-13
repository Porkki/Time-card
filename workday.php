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
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <link rel="stylesheet" href="css/style.css">
        <!-- FontAwesome -->
        <link href="css/all.css" rel="stylesheet">

        <title>Työpäivät</title>
    </head>
    <body>
        <div class="container-fluid my-2">
            <div class="row no-gutters">
                <?php include "nav.php"; ?>
                <div class="box-shadow col-md w-100 border bg-white p-2">
                    <h1>Työpäivät</h1>
                    <hr>
                    <p>Hae työpäiviä väliltä</p>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" name="startdate" id="startdate" value="<?php echo date("Y-m") . "-01"; ?>">
                            <small id="startdateHelp" class="form-text text-muted">Alkaen</small>
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="enddate" id="enddate" value="<?php echo date("Y-m") . "-15"; ?>">
                            <small id="startdateHelp" class="form-text text-muted">Päättyen</small>
                        </div>
                    </div>
                    
                    <table id="workdays" class="table table-striped table-hover w-100">
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
                        <a href="" id="ahrefremoveuser" class="btn btn-danger">Poista</a>
                    </div>
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
    // Get companies to table
    // Target element
    var table = $("#workdays > tbody:last-child");
    // Populate table with list of companies
    $.get("scripts/workday_manager.php", function(data) {
        $.each(data, function(key, value) {
            table.append($("<tr>")
                // Set id value to original date value for table sorting between dates
                .append($("<td>").attr("id",value.date).text(value.custom_dateformat))
                .append($("<td style='width:200px'>")
                    .html($("<b>Aloitus: </b>" + value.custom_start_time + "<br>" +
                    "<b>Lopetus: </b>" + value.custom_end_time + "<br>" +
                    "<b>Tauko: </b> " + value.custom_break_time))
                )
                .append($("<td>").attr("id","total_time").html("<span>" + value.total_time + "</span>"))
                .append($("<td>").text(value.explanation))
                //TODO: Align to center
                .append($("<td>")
                    .append($("<a href='modifyworkday.php?id=" + value.id + "' title='Muokkaa työpäivää' data-toggle='tooltip'><i class='fas fa-edit pr-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista työpäivä' data-toggle='modal' data-target='#removeConfirm' data-name='" + value.custom_dateformat + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))
                )
            );
        })
    }, "json")
        .always(function() {
            filterTable();
        });

    $("#startdate").on("input", function() {
        filterTable();
    });
    $("#enddate").on("input", function() {
        filterTable();
    });

    // https://stackoverflow.com/a/25060114
    // Send name&userid to show in modal
    $('#removeConfirm').on('show.bs.modal', function (e) {
        var name = $(e.relatedTarget).data('name');
        $("#name").text( name );
        var id = $(e.relatedTarget).data('id');
        $("#ahrefremoveuser").attr("href", "scripts/workday_manager.php?remove=" + id);
    })
});

function filterTable() {
    var from = document.getElementById("startdate").value;
    var to = document.getElementById("enddate").value;

    var fromdate = new Date(from).getTime();
    var todate = new Date(to).getTime();

    // Hide every tr which is not between startdate and enddate
    $("#workdays tr").show().filter(function() {
        // Get original date value from date section id attribution and not text, because JS Date object cant parse dd-mm-yyyy format.
        var curdate = new Date($(this).find("td").first().attr("id")).getTime();
        return curdate < fromdate || curdate > todate;
    }).hide();

    // Calculate total hours
    var hours = 0;
    var mins = 0;
    var seconds = 0;
    $("#workdays > tbody tr:visible").each(function() {
        var content = $(this).find("span").text();

        var array = content.split(":")
        seconds += parseInt(array[0])*60*60;
        seconds += parseInt(array[1])*60;
    });
    $("#hours").text(secondsToHms(seconds));
}

// https://stackoverflow.com/questions/37096367/how-to-convert-seconds-to-minutes-and-hours-in-javascript
function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " tunti " : " tuntia ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minuutti" : " minuuttia") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay; 
}
</script>