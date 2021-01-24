/*
* TODO: Table sorting system implemention
*/
$(document).ready(function() {
    // Get users to table
    // Target element
    var table = $("#users > tbody:last-child");

    var counter = 0;
    // Populate table with list of users
    $.get("api/jsonApi.php?mode=user&action=view&id=all", function(data) {
        $.each(data, function(key, value) {
            table.append($("<tr>")
                .append($("<td>").text(value.firstname))
                .append($("<td>").text(value.lastname))
                .append($("<td>").text(value.username))
                .append($("<td>").attr("name","user_company_id").text(value.user_company_id))
                .append($("<td>")
                    .append($("<a href='updateuser.php?id=" + value.id + "' title='Muokkaa käyttäjää' data-toggle='tooltip'><i class='fas fa-edit pe-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista käyttäjä' data-bs-toggle='modal' data-bs-target='#removeConfirm' data-name='" + value.username + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))
                )
            );
        })
    }, "json")
        // Count how many employees there are in the company
        .always(function() {
            $('td[name = "user_company_id"]').each(function(index, element){
                counter++;
                document.getElementById("numberofusers").innerHTML=counter;
            });
        });
    // https://stackoverflow.com/a/25060114
    // Send name&userid to show in modal
    $('#removeConfirm').on('show.bs.modal', function (e) {
        let name = $(e.relatedTarget).data('name');
        $("#username").text( name );
        let id = $(e.relatedTarget).data('id');

        $("#ahrefremoveuser").click(function() {
            $.get("api/jsonApi.php?mode=user&action=remove&id=" + id, function(data) {
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