$(document).ready(function() {
    
    // Fill form from settings
    $.get("api/jsonApi.php?mode=settings&action=viewall", function(data) {
        let date = $("#date").val();
        $.each(data, function(key, value) {
            if (value.value_str != null) {
                switch (value.name) {
                    case "autostarttime":
                        $("#starttime").val(date + "T" + value.value_str);
                        break;
                    case "autoendtime":
                        $("#endtime").val(date + "T" + value.value_str);
                        break;
                    case "autobreaktime":
                        $("#breaktime").val(value.value_str)
                        break;
                }
            }

            //document.getElementsByName(value.name)[0].value = value.value_str;
        })
    }, "json")

    // Modify startdate & enddate according to date input so it automatically enter same date on both fields but keeps time
    $("#date").change(function() {
        var date = $("#date").val();
        // If date is empty then do nothing because #date value is invalid
        if (date == "") {
            return;
        }
        var starttime = new Date($("#starttime").val());
        var endtime = new Date($("#endtime").val());
        // https://stackoverflow.com/questions/18889548/javascript-change-gethours-to-2-digit
        $("#starttime").val(date + "T" + ("0" + starttime.getHours()).slice(-2) + ":" + ("0" + starttime.getMinutes()).slice(-2));
        $("#endtime").val(date + "T" + ("0" + endtime.getHours()).slice(-2) + ":" + ("0" + endtime.getMinutes()).slice(-2));
    });
    $( "#createnewworkday" ).submit(function( event ) {
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
                } 
                if (error) {
                    document.getElementById("errormessage").textContent=error;
                    $('#unsuccessfulModal').modal('show');
                }
            })
    });
});