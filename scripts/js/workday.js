import { filterTable } from './workday_functions.js';
$(document).ready(function() {
    // Get workdays to table

    // Target element
    var table = $("#workdays > tbody:last-child");

    // Populate table with list of workdays
    $.get("api/jsonApi.php?mode=workday&action=view&id=all", function(data) {
        $.each(data, function(key, value) {
            // Weekday array
            let weekdayShortFormat = ["Su", "Ma", "Ti", "Ke", "To", "Pe", "La", "Su"];
            let weekdayLongFormat = ["Sunnuntai", "Maanantai", "Tiistai", "Keskiviikko", "Torstai", "Perjantai", "Lauantai", "Sunnuntai"];
            let d = new Date(value.date);

            let timeString = ("<b>Aloitus: </b>" + value.custom_start_time + "<br>" +
                            "<b>Lopetus: </b>" + value.custom_end_time +
                            "<span class='breakTime'><br><b>Tauko: </b><span class='breakTimeValue'>" + value.break + "</span></span><br>" + 
                            "<span class='overTime'><b>Ylityö: </b><span id='overTimeValue' class='overTimeValue'>0:00</span></span>");
            let dateString = ("<b>" + weekdayLongFormat[d.getDay()] + "</b><br>" + value.custom_date);
            table.append($("<tr>")
                // Set id value to original date value for table sorting between dates
                .append($("<td>").attr("id",value.date).prop("innerHTML", dateString))
                .append($("<td style='width:200px'>")
                    .prop("innerHTML", timeString)
                )
                .append($("<td>").html("<span id='totalTime'>" + value.total_time + "</span>"))
                .append($("<td>").text(value.explanation))
                .append($("<td class='align-middle'>")
                    .append($("<a href='modifyworkday.php?id=" + value.id + "' title='Muokkaa työpäivää' data-toggle='tooltip'><i class='fas fa-edit pe-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista työpäivä' data-bs-toggle='modal' data-bs-target='#removeConfirm' data-name='" + value.custom_date + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))    
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
        let name = $(e.relatedTarget).data('name');
        $("#name").text( name );
        let id = $(e.relatedTarget).data('id');

        $("#ahrefremoveworkday").click(function() {
            $.get("api/jsonApi.php?mode=workday&action=remove&id=" + id, function(data) {
                if (data.message) {
                    $("#removeConfirm").modal("hide");
                    $('#doneModal').modal('show');
                } else if (data.error) {
                    $("#removeConfirm").modal("hide");
                    $('#unsuccessfulModal').modal('show');
                }
            }, "json");
            // Return false on <a></a> click to prevent page refresh/forwarding
            return false;
        });
    });

    $("#doneModal").on("hidden.bs.modal", function (e) {
        location.reload();
    });
});