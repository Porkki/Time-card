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