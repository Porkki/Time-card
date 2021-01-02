// Form handling
$( "#login" ).submit(function( event ) {
    // Put form data to variable
    var formdata = $( this ).serialize();
    // Disable form page refresh
    event.preventDefault();

    $.ajax({
        method: "POST",
        url: "login.php",
        dataType: "json",
        data: formdata
    })
        .done(function( data ) {
            if (data.error) {
                $("#errormessage").html(data.error);
                $('#unsuccessfulModal').modal('show');
            } else if (data.username) {
                window.location.href= "welcome.php";
            }
        });
});