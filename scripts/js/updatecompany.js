$(document).ready(function() {
    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form
    $.get("scripts/company_manager.php?id=" + id, function(data) {
        document.getElementsByName("company_name")[0].value = data[0].company_name;
        document.getElementsByName("ytunnus")[0].value = data[0].ytunnus;
        document.getElementsByName("company_address")[0].value = data[0].company_address;
        document.getElementsByName("company_postcode")[0].value = data[0].company_postcode;
        document.getElementsByName("company_area")[0].value = data[0].company_area;
        document.getElementsByName("is_client")[0].value = data[0].is_client;
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
            url: "scripts/company_manager.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var companyname = data.companyname;
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
});