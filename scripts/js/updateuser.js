$(document).ready(function() {
    // Get companies to list
    // https://www.codebyamir.com/blog/populate-a-select-dropdown-list-with-json

    // Target element
    var dropdown = $("#user_company_id");
    dropdown.empty();
    // Create default option
    dropdown.append('<option selected="true" disabled>Valitse yritys</option>');
    // Populate dropdown with list of companies
    $.get("scripts/company_manager.php", function(data) {
        $.each(data, function(key, value) {
            dropdown.append($("<option></option>").attr("value", value.id).text(value.company_name));
        })
    }, "json");

    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("scripts/user_manager.php?id=" + id, function(data) {
        document.getElementsByName("firstname")[0].value = data[0].firstname;
        document.getElementsByName("lastname")[0].value = data[0].lastname;
        document.getElementsByName("username")[0].value = data[0].username;
        document.getElementsByName("class")[0].value = data[0].class;
        document.getElementsByName("user_company_id")[0].value = data[0].user_company_id;
        document.getElementsByName("id")[0].value = data[0].id;
    }, "json");

    // Form handling
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "scripts/user_manager.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var username = data.username;
                var error = data.error;
                if (username) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "modifyuser.php";
                    }, 2000);
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});