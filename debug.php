<?php

?>
<html>
    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
    </head>
    <body>
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
        <table id="workdays" class="table table-striped table-hover w-100">
            <thead>
                <tr>
                    <th scope="col">Päivämäärä</th>
                    <th scope="col">Aika</th>
                    <th scope="col">Lopetus</th>
                    <th scope="col">Yhteensä (hh:mm)</th>
                    <th scope="col">Selite</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
        <p>Tunnit valitulla aikavälillä yhteensä: <span id="hours">0</span>.</p>
    </body>
</html>
<script src="scripts/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script>
    var seconds = 0;
    $.fn.dataTable.ext.search.push(
    function( settings, data, dataIndex ) {
        var from = document.getElementById("startdate").value;
        var to = document.getElementById("enddate").value;
        var fromdate = new Date(from).getTime();
        var todate = new Date(to).getTime();
        //var split = data[0].split(".")
        var curdate = new Date(data[0]).getTime();

        if (curdate >= fromdate && curdate <= todate) {
            var totalTimeContent = data[3];
            var totalTimeArray = totalTimeContent.split(":")
            seconds += parseInt(totalTimeArray[0])*60*60;
            seconds += parseInt(totalTimeArray[1])*60;
            return true;
        } else {
            return false;
        }
    }
    );
$(document).ready(function() {
    $("#workdays").DataTable( {
        "ajax": {
            "url": "http://localhost:8080/worktime/api/jsonApi.php?mode=workday&action=view&id=all&userid=1",
            "dataSrc": ""
        },
        "columns": [
            {"data": "date"},
            {"data": "start_time"},
            {"data": "end_time"},
            {"data": "total_time"},
            {"data": "explanation"}
        ],
        "columnDefs": [
            {
                "targets": 0,
                "render": function(data, type, row) {
                    let d = new Date(data);
                    if (type == "sort" || type == "type") {
                        return data;
                    }     
                    return d.getDate() + "." + (d.getMonth()+1) + "." + d.getFullYear();
                }
            },
            {
                "targets": 1,
                "render": function(data, type, row) {
                    let start = new Date(data);
                    let end = new Date(row["end_time"]);
                    if (type == "sort" || type == "type" || type == "filter") {
                        return data;
                    }

                    let returnString = ("Aloitus: " + start.getDate() + "." + (start.getMonth()+1) + "." + start.getFullYear() + " " + start.getHours() + "." + start.getMinutes() + "<br>" +
                                        "Lopetus: " + end.getDate() + "." + (end.getMonth()+1) + "." + end.getFullYear() + " " + end.getHours() + "." + end.getMinutes());

                    return returnString;
                } 
            },
            {
                "targets": 2,
                "visible": false
            },
            {
                "targets": 3,
                "render": function(data, type, row) {
                    let d = data.split(":");
                    if (type == "sort" || type == "type") {
                        return data;
                    }     
                    return d[0] + ":" + d[1];
                }
            }
        ]
    });

    $("#startdate").on("input", function() {
        seconds = 0;
        $("#workdays").DataTable().draw();
        $("#hours").text(secondsToHms(seconds));
    });
    $("#enddate").on("input", function() {
        seconds = 0;
        $("#workdays").DataTable().draw();
        $("#hours").text(secondsToHms(seconds));
    });
});

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