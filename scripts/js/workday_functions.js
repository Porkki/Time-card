/**
 * Filters table to date between and hides empty breaks
 * 
 * Expects break to be in .breakTime class and break value in .breakTimeValue class
 * Table should have id #workdays and date inputs have ids #startdate and #enddate
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
    var hours = 0;
    var mins = 0;
    var seconds = 0;
    $("#workdays > tbody tr:visible").each(function() {
        var totalTimeContent = $(this).find("#totalTime").text();

        var totalTimeArray = totalTimeContent.split(":")
        seconds += parseInt(totalTimeArray[0])*60*60;
        seconds += parseInt(totalTimeArray[1])*60;
    });
    $("#hours").text(secondsToHms(seconds));
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