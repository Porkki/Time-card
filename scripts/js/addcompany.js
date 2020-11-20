$(document).ready(function() {
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "addcompany_script.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var companyname = data.companyname;
                var error = data.error;
                if (companyname) {
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