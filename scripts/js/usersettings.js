$(document).ready(function() {

    // Fill form
    $.get("api/jsonApi.php?mode=settings&action=viewall", function(data) {
        $.each(data, function(key, value) {
            if (value.name == "showdailyovertime") {
                document.getElementsByName(value.name)[0].value = value.value_bool;
                if (value.value_bool == 0) {
                    $("#dailyovertimelimit").prop("disabled", true);
                } else if (value.value_bool == 1) {
                    $("#dailyovertimelimit").prop("disabled", false);
                }
            } else {
                document.getElementsByName(value.name)[0].value = value.value_str;
            }
        })
    }, "json")

    // Form handling
    $("#settingsform").submit(function( event ) {
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
                if (data.error) {
                    document.getElementById("errormessage").textContent=data.error;
                    $('#unsuccessfulModal').modal('show');
                } else if (data.message) {
                    $('#doneModal').modal('show');
                }
            })
    });

    $("#cancel").click(function() {
        history.back();
    });

    $("#showdailyovertime").change(function() {
        if ($(this).val() == 0) {
            $("#dailyovertimelimit").prop("disabled", true);
        } else if ($(this).val() == 1) {
            $("#dailyovertimelimit").prop("disabled", false);
        }
    });
});