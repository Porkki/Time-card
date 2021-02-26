$(document).ready(function() {
    // Form handling
    $("#changepw").submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "scripts/php/changepassword.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                if (data.error) {
                    document.getElementById("errormessage").textContent=data.error;
                    $('#unsuccessfulModal').modal('show');
                } else if (data.message) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "welcome.php";
                    }, 2000);
                }
            })
    });

    $("#cancel").click(function() {
        history.back();
    });
});