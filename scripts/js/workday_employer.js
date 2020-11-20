import { filterTable } from './workday_functions.js';
$(document).ready(function() {
    // Get users to select list
    var dropdown = $("#user");
    $.get("scripts/user_manager.php", function(data) {
        $.each(data, function(key, value) {
            dropdown.append($("<option value='" + value.id + "'>" + value.firstname + " " + value.lastname + "</option>"));
        })
    }, "json");

    // Get companies to table
    // Target element
    var table = $("#workdays > tbody:last-child");
    // Populate table with list of companies after change in dropdown selection
    dropdown.change(function() {
        // Clear table before populating it with the new data
        $("#workdays tbody").empty();
        // Get selected userid from dropdown
        var userid = $(this).val();
        $.get("scripts/workday_manager.php?userid=" + userid, function(data) {
            $.each(data, function(key, value) {
                // Weekday array
                let weekdayShortFormat = ["Su", "Ma", "Ti", "Ke", "To", "Pe", "La", "Su"];
                let weekdayLongFormat = ["Sunnuntai", "Maanantai", "Tiistai", "Keskiviikko", "Torstai", "Perjantai", "Lauantai", "Sunnuntai"];
                let d = new Date(value.date);
                // There was bug with .html attribute which did not show complete string but left parts out so we changed it to .prop
                var timestring = ("<b>Aloitus: </b>" + value.custom_start_time + "<br>" +
                                "<b>Lopetus: </b>" + value.custom_end_time +
                                "<span class='breakTime'><br><b>Tauko: </b><span class='breakTimeValue'>" + value.custom_break_time + "</span></span>");
                let dateString = ("<b>" + weekdayLongFormat[d.getDay()] + "</b><br>" + value.custom_dateformat);
                table.append($("<tr>")
                    // Set id value to original date value for table sorting between dates
                    .append($("<td>").attr("id",value.date).prop("innerHTML", dateString))
                    .append($("<td style='width:200px'>")
                        .prop("innerHTML", timestring)
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