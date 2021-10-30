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

    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("api/jsonApi.php?mode=user&action=view&id=" + id, function(data) {
        if (!data.error) {
            $("#firstname").val(data.firstname);
            $("#lastname").val(data.lastname);
            $("#username").val(data.username);
            $("#class").val(data.class);
            $("#user_company_id").val(data.user_company_id);
            $("#id").val(data.id);
        } else {
            $("#errormessage").text(data.error);
            $('#unsuccessfulModal').modal('show');
            $("#unsuccessfulModal").on("hidden.bs.modal", function (e) {
                // Send user back to front page if id is wrong
                window.location.href= "modifyuser.php";
            });
        }
        
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
                var username = data.username;
                var error = data.error;
                if (username) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "modifyuser.php";
                    }, 2000);
                } 
                if (error) {
                    $("#errormessage").text(error);
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});