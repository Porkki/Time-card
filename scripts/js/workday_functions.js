/**
 * Filters table to date between and hides empty breaks
 * 
 * Expects break to be in .breakTime class and break value in .breakTimeValue class
 * Same with overtime but in .overTime and .overTimeValue
 * Table should have id #workdays and date inputs have ids #startdate and #enddate
 * 
 * TODO:
 * Muuta ylityön lisääminen siten, että lisäys tapahtuu tätä kautta kokonaan eikä alkutietoja lisätä workday.js n kautta
 * nopeuttaa sivun lataamista...
 */
export function filterTable() {
    // Hide every empty break
    $(".breakTime").show().filter(function() {
        var allBreakTimeValues = $(".breakTimeValue");
        var breakTimeContent = $(this).find(allBreakTimeValues).first().text();
        var breakTimeArray = breakTimeContent.split(":");
        if (breakTimeArray[0] == "00" && breakTimeArray[1] == "00") {
            return true;
        } else {
            return false;
        }
    }).hide();

    // Overtime stuff
    var otlimit = "";
    var otlimitinsecs = 0;
    var otenabled = 0;
    $.get("api/jsonApi.php?mode=settings&action=viewall", function(data) {
        $.each(data, function(key, value) {
            if (value.name == "showdailyovertime") {
                otenabled = value.value_bool;
            }
            if (value.name == "dailyovertimelimit") {
                if (value.value_str == "" || value.value_str == null) {
                    otlimit = "0:00";
                } else {
                    otlimit = value.value_str;
                }
            }
        })
    }, "json")
        .done(function( data ) {
            let otlimitArray = otlimit.split(":");
            otlimitinsecs += parseInt(otlimitArray[0])*60*60;
            otlimitinsecs += parseInt(otlimitArray[1])*60;
            $("#workdays > tbody tr:visible").each(function() {
                let workdayseconds = 0;
                let totalTimeContent = $(this).find("#totalTime").text();
        
                let totalTimeArray = totalTimeContent.split(":")
                workdayseconds += parseInt(totalTimeArray[0])*60*60;
                workdayseconds += parseInt(totalTimeArray[1])*60;
                // If overtime is 0:00 then overtime settings is not set or there is no overtime and we can hide all overtimes
                if (otlimitinsecs != 0) {
                    if (workdayseconds > otlimitinsecs) {
                        $(this).find("#overTimeValue").text(convertHMS(workdayseconds-otlimitinsecs));
                    } else {
                        $(this).find(".overTime").hide();
                    }
                } else {
                    $(this).find(".overTime").hide();
                }
                
            });
        });

    // Hide every tr which is not between startdate and enddate
    var from = document.getElementById("startdate").value;
    var to = document.getElementById("enddate").value;

    var fromdate = new Date(from).getTime();
    var todate = new Date(to).getTime();
    $("#workdays tr").show().filter(function() {
        // Get original date value from date section id attribution and not text, because JS Date object cant parse dd-mm-yyyy format.
        var curdate = new Date($(this).find("td").first().attr("id")).getTime();
        return curdate < fromdate || curdate > todate;
    }).hide();

    // Calculate total hours
    var seconds = 0;
    $("#workdays > tbody tr:visible").each(function() {
        let totalTimeContent = $(this).find("#totalTime").text();

        let totalTimeArray = totalTimeContent.split(":")
        seconds += parseInt(totalTimeArray[0])*60*60;
        seconds += parseInt(totalTimeArray[1])*60;
    });
    $("#hours").text(secondsToHms(seconds));

    // Calculate saturday hours
    var satseconds = 0;
    $("#workdays > tbody tr:visible td:first-child:contains('Lauantai')").parent().each(function() {
        let totalTimeContent = $(this).find("#totalTime").text();

        let totalTimeArray = totalTimeContent.split(":")
        satseconds += parseInt(totalTimeArray[0])*60*60;
        satseconds += parseInt(totalTimeArray[1])*60;
    });
    $("#sathours").text(secondsToHms(satseconds));

    // Calculate sunday hours
    var sunseconds = 0;
    $("#workdays > tbody tr:visible td:first-child:contains('Sunnuntai')").parent().each(function() {
        let totalTimeContent = $(this).find("#totalTime").text();

        let totalTimeArray = totalTimeContent.split(":")
        sunseconds += parseInt(totalTimeArray[0])*60*60;
        sunseconds += parseInt(totalTimeArray[1])*60;
    });
    $("#sunhours").text(secondsToHms(sunseconds));
}
/**
 * Returns Hours Minutes and Seconds
 * 
 * Converts seconds to time string eg. 4 tuntia 35 minuuttia.
 * See more: https://stackoverflow.com/questions/37096367/how-to-convert-seconds-to-minutes-and-hours-in-javascript
 *
 * @param {Number} d
 *   Seconds
 * @return
 *   Time string
 */
function secondsToHms(d) {
    d = Number(d);
    var h = Math.floor(d / 3600);
    var m = Math.floor(d % 3600 / 60);
    var s = Math.floor(d % 3600 % 60);

    var hDisplay = h > 0 ? h + (h == 1 ? " tunti " : " tuntia ") : "";
    var mDisplay = m > 0 ? m + (m == 1 ? " minuutti" : " minuuttia") : "";
    var sDisplay = s > 0 ? s + (s == 1 ? " second" : " seconds") : "";
    return hDisplay + mDisplay + sDisplay; 
}

function convertHMS(value) {
    const sec = parseInt(value, 10); // convert value to number if it's string
    let hours   = Math.floor(sec / 3600); // get hours
    let minutes = Math.floor((sec - (hours * 3600)) / 60); // get minutes
    let seconds = sec - (hours * 3600) - (minutes * 60); //  get seconds
    // add 0 if value < 10; Example: 2 => 02
    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}
    return hours+':'+minutes; // Return is HH : MM
}