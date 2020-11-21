$(document).ready(function() {
    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form

    var modified = false;
    var modifiedString = "";
    $.get("scripts/workday_manager.php?viewid=" + id, function(data) {
        document.getElementsByName("date")[0].value = data[0].date;
        document.getElementsByName("starttime")[0].value = data[0].custom_start_time;
        document.getElementsByName("endtime")[0].value = data[0].custom_end_time;
        document.getElementsByName("breaktime")[0].value = data[0].break_time;
        document.getElementsByName("id")[0].value = data[0].id;
        document.getElementsByName("explanation")[0].value = data[0].explanation;

        if (data[0].created_time == data[0].modified_time) {
            modifiedString = ("Luotu: " + data[0].custom_created_time);
            $("#created").html(modifiedString);
            modified = false;
        } else {
            modified = true;
        }
    }, "json")
        .done(function(data) {
            if (modified) {
                $.get("scripts/user_manager.php?id=" + data[0].modified_user_id, function(userdata) {
                    modifiedString = ("Luotu: " + data[0].custom_created_time + "<br>" + 
                                    "Muokattu: " + data[0].custom_modified_time + " käyttäjän " + userdata[0].username + " toimesta.");
                    $("#created").html(modifiedString);
                }, "json");
            }
        });

    // Form handling
    $( "#modifyworkday" ).submit(function( event ) {
        // Put form data to variable
        var formdata = $( this ).serialize();
        // Disable form page refresh
        event.preventDefault();

        $.ajax({
            method: "POST",
            url: "scripts/workday_manager.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var workday = data.workday;
                var error = data.error;
                if (workday) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "workday.php";
                    }, 2000);
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
    // Cancel button
    $("#cancel").click(function() {
        window.history.back();
    });
});