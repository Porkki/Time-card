$(document).ready(function() {
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = new FormData(this);
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "api/jsonApi.php",
            dataType: "json",
            data: formdata,
            cache: false,
            contentType: false,
            processData: false
        })
            .done(function( data ) {
                var name = data.name;
                var error = data.error;
                if (name) {
                    document.getElementsByName("company_name")[0].value = "";
                    document.getElementsByName("ytunnus")[0].value = "";
                    document.getElementsByName("company_address")[0].value = "";
                    document.getElementsByName("company_postcode")[0].value = "";
                    document.getElementsByName("company_area")[0].value = "";
                    $('#doneModal').modal('show');
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});