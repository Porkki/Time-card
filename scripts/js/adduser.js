$(document).ready(function() {
    // Get companies to list
    // https://www.codebyamir.com/blog/populate-a-select-dropdown-list-with-json

    // Target element
    var dropdown = $("#user_company_id");
    dropdown.empty();
    // Create default option
    dropdown.append('<option selected="true" disabled>Valitse yritys</option>');
    // Populate dropdown with list of companies
    $.get("api/jsonApi.php?mode=company&action=view&id=all", function(data) {
        $.each(data, function(key, value) {
            dropdown.append($("<option></option>").attr("value", value.id).text(value.name));
        })
    }, "json");

    // Form handling
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "api/jsonApi.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                if (data.username) {
                    document.getElementsByName("firstname")[0].value = "";
                    document.getElementsByName("lastname")[0].value = "";
                    document.getElementsByName("username")[0].value = "";
                    document.getElementsByName("password")[0].value = "";
                    $('#doneModal').modal('show');
                } else if (data.error) {
                    document.getElementById("errormessage").textContent=data.error;
                    $('#unsuccessfulModal').modal('show');
                }
                
            });
    });
});