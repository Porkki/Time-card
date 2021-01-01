$(document).ready(function() {
    // Get companies to table
    // Target element
    var table = $("#companies > tbody:last-child");
    // Populate table with list of companies
    $.get("api/jsonApi.php?mode=company&action=view&id=all", function(data) {
        $.each(data, function(key, value) {
            let is_client;
            if (value.is_client) {
                is_client = "Kyllä";
            } else {
                is_client = "Ei";
            }
            table.append($("<tr>")
                .append($("<td>").text(value.name))
                .append($("<td>").text(value.ytunnus))
                .append($("<td>").text(value.address))
                .append($("<td>").text(value.postcode))
                .append($("<td>").text(value.area))
                .append($("<td>").attr("name","user_count").text(value.worker_count))
                .append($("<td>").text(is_client))
                .append($("<td>").attr("name","created_user_id").text(value.created_user_id))
                .append($("<td>")
                    .append($("<a href='updatecompany.php?id=" + value.id + "' title='Muokkaa yritystä' data-toggle='tooltip'><i class='fas fa-edit pr-2 text-success'></i></a>"))
                    .append($("<a href='#' class='open-removeconfirm' title='Poista yritys' data-toggle='modal' data-target='#removeConfirm' data-name='" + value.name + "' data-id='" + value.id + "'><i class='fas fa-trash text-danger'></i></a>"))
                )
            );
        })


    }, "json");


    // https://stackoverflow.com/a/25060114
    // Send name&userid to show in modal
    $('#removeConfirm').on('show.bs.modal', function (e) {
        let name = $(e.relatedTarget).data('name');
        $("#name").text( name );
        let id = $(e.relatedTarget).data('id');

        $("#ahrefremoveuser").click(function() {
            $.get("api/jsonApi.php?mode=company&action=remove&id=" + id, function(data) {
                if (data.message) {
                    $("#removeConfirm").modal("hide");
                    $('#doneModal').modal('show');
                } else if (data.error) {
                    $("#removeConfirm").modal("hide");
                    $('#unsuccessfulModal').modal('show');
                }
            }, "json");
            return false;
        });
    });

    $("#doneModal").on("hidden.bs.modal", function (e) {
        location.reload();
    });
});