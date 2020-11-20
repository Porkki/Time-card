$(document).ready(function() {
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
            url: "scripts/addworkday_script.php",
            dataType: "json",
            data: formdata
        })
            .done(function( data ) {
                var workday = data.workday;
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