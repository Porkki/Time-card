$(document).ready(function() {
    $( "#target" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "adduser_script.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var username = data.username;
                var error = data.error;
                if (username) {
                    document.getElementsByName("firstname")[0].value = "";
                    document.getElementsByName("lastname")[0].value = "";
                    document.getElementsByName("username")[0].value = "";
                    document.getElementsByName("password")[0].value = "";
                    $('#doneModal').modal('show');
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});