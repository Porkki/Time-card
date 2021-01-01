$(document).ready(function() {
    // Get id parameter from url to send to ajax request
    var url = new URL(document.URL);
    var search_params = url.searchParams;
    var id = search_params.get("id");
    // Send get request to script and then assign values to the form

    var modified = false;
    var modifiedString = "";
    $.get("api/jsonApi.php?mode=workday&action=view&id=" + id, function(data) {
        if (data.error) {
            document.getElementById("errormessage").textContent=data.error;
            $('#unsuccessfulModal').modal('show');
            $("#unsuccessfulModal").on("hidden.bs.modal", function (e) {
                // Send user back to front page if id is wrong
                window.location.href= "welcome.php";
            });
            return true;
        }
        document.getElementsByName("date")[0].value = data.date;
        document.getElementsByName("starttime")[0].value = data.html_start_time;
        document.getElementsByName("endtime")[0].value = data.html_end_time;
        document.getElementsByName("breaktime")[0].value = data.html_break;
        document.getElementsByName("id")[0].value = data.id;
        document.getElementsByName("explanation")[0].value = data.explanation;

        if (data.created_time == data.modified_time) {
            modifiedString = ("Luotu: " + data.custom_created_time);
            $("#created").html(modifiedString);
            modified = false;
        } else {
            modified = true;
        }
    }, "json")
        .done(function(data) {
            if (modified) {
                $.get("api/jsonApi.php?mode=user&action=view&id=" + data.modified_user_id, function(userdata) {
                    modifiedString = ("Luotu: " + data.custom_created_time + "<br>" + 
                                    "Muokattu: " + data.custom_modified_time + " käyttäjän " + userdata.username + " toimesta.");
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
            url: "api/jsonApi.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var workday = data.date;
                var error = data.error;
                if (workday) {
                    $('#doneModal').modal('show');
                    setTimeout(function() {
                        window.location.href= "workday.php";
                    }, 2000);
                } else if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
    // Cancel button
    $("#cancel").click(function() {
        window.history.back();
    });

    // Send user back if id is 
});