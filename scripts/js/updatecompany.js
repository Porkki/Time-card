$(document).ready(function() {
    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("api/jsonApi.php?mode=company&action=view&id=" + id, function(data) {
        document.getElementsByName("company_name")[0].value = data.name;
        document.getElementsByName("ytunnus")[0].value = data.ytunnus;
        document.getElementsByName("company_address")[0].value = data.address;
        document.getElementsByName("company_postcode")[0].value = data.postcode;
        document.getElementsByName("company_area")[0].value = data.area;
        document.getElementsByName("is_client")[0].value = data.is_client;
        document.getElementsByName("id")[0].value = data.id;
    }, "json");

    // Form handling
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = new FormData(this);
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: "api/jsonApi.php",
            dataType: "json",
            data: formdata,
            cache: false,
            contentType: false,
            processData: false
        })
            .done(function( data ) {
                var companyname = data.name;
                var error = data.error;
                if (companyname) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "modifycompany.php";
                    }, 2000);
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });

    // Company logo removal
    $("#remove_logo").click(function() {
        $.ajax({
            type: "POST",
            url: "api/jsonApi.php",
            dataType: "json",
            data: {
                postfrom: "removelogo",
                id: document.getElementsByName("id")[0].value
            }
        })
            .done(function(data) {
                let error = data.error;
                if (error) {
                    $('#unsuccessfulModal').modal('show');
                } else {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            })
    });
});