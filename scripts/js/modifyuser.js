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