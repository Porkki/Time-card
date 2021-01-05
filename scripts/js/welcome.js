$(document).ready(function() {
    function formatTime(secs)
    {
        var hours = Math.floor(secs / (60 * 60));
    
        var divisor_for_minutes = secs % (60 * 60);
        var minutes = Math.floor(divisor_for_minutes / 60);
    
        if (minutes == 0) {
            minutes = "00";
        }

        var divisor_for_seconds = divisor_for_minutes % 60;
        var seconds = Math.ceil(divisor_for_seconds);
    
        return hours + ":" + minutes;
    }
    moment.locale("fi");
    var start = moment().startOf("week");
    var end = moment().endOf("week");

    var startstr = start.format("YYYY-MM-DD");
    var endstr = end.format("YYYY-MM-DD");
    var curyearstr = start.format("YYYY");
    var previousyearstr = start.subtract(1, "year").format("YYYY");

    $("#currentyear").html(curyearstr);
    $("#previousyear").html(previousyearstr);

    $.get("api/jsonApi.php?mode=workday&action=view&id=between&start="+startstr+"&end="+endstr, function(data) {
        let label = [];
        let dataset = [];
        let totalsecs = 0;

        data.forEach(function(item) {
            let date = moment(item.date);
            let timestring = item.total_time;
            let splitstring = timestring.split(":");

            let hoursecs = splitstring[0]*60*60;
            let minutesec = splitstring[1]*60;
            let total = hoursecs+minutesec;
            totalsecs += total;

            let datapoint = {
                x: date,
                y: total
            }
            dataset.push(datapoint);

            label.push(item.date);
        });

        let totalhours = Math.floor(totalsecs / 3600);
        let totalminutes = Math.floor(totalsecs / 60)%60;
        $("#currentweektotalhours").html(totalhours + "h " + totalminutes + "m");

        var chartdata = {
            labels: [
                ["Maanantai", start.format("DD.MM")], 
                ["Tiistai", start.add(1, "days").format("DD.MM")], 
                ["Keskiviikko", start.add(1, "days").format("DD.MM")], 
                ["Torstai", start.add(1, "days").format("DD.MM")], 
                ["Perjantai", start.add(1, "days").format("DD.MM")], 
                ["Lauantai", start.add(1, "days").format("DD.MM")], 
                ["Sunnuntai", start.add(1, "days").format("DD.MM")]
            ],
            datasets: [
                {
                    label: 'Tunnit',
                    backgroundColor: 'rgba(89, 159, 217, 0.5)',
                    borderColor: 'rgba(89, 159, 217, 1)',
                    hoverBackgroundColor: 'rgba(204, 204, 204, 0.5)',
                    hoverBorderColor: 'rgba(204, 204, 204, 1)',
                    data: dataset
                }
            ]
        };

        let graph = $("#currentweek");
        let heightRatio = 1.5;
        graph.height = graph.width * heightRatio;

        let barGraph = new Chart(graph, {
            type: "bar",
            data: chartdata,
            options: {
                tooltips: {
                    mode: "index",
                    callbacks: {
                        title: function(tooltipItem, data) {
                            let date = data.datasets[0].data[tooltipItem[0].index].x;
                            date.locale("fi");
                            return date.format("dddd DD. MMMM gggg");
                        },
                        label: function(tooltipItem, data) {
                            let totalSeconds = tooltipItem.value;
                            let hours = Math.floor(totalSeconds / 3600);
                            let minutes = Math.floor(totalSeconds / 60)%60;
                            if (minutes == 0) {
                                minutes = "00";
                            }

                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            return label + ": " + hours + ":" + minutes;
                        }
                    },
                    titleFontSize: 16
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            stepSize: 1800,
                            beginAtZero: true,
                            callback: function(label, index, labels) {
                                return formatTime(label);
                            }
                        },
                        scaleLabel: {
							display: true,
							labelString: 'Tunnit'
						}
                    }]
                }
            }
        });
    }, "json");

    $.get("api/jsonApi.php?mode=workday&action=view&id=between&start="+curyearstr+"-01-01&end="+curyearstr+"-12-31", function(data) {
        let dataset = [];
        let monthtotal = 0; // Keep total seconds of the current month 
        var curmonth = 1;  // Keep track at which month we are in currently
        let datalength = 0; // Keep track how many items we have gone through in GET data
        let firstcheck = false;
        // Note: This script expects that incoming data from api is ordered by date, otherwise this would not work in current design
        data.forEach(function(item) {
            datalength++;
            // Get month number to compare for where dataset should be adding data
            let date = moment(item.date);
            let monthnumber = date.format("M");

            // If workdays does not start at January we need to push 0 seconds to all months before first workday of the year
            if (!firstcheck) {
                while (curmonth < monthnumber) {
                    dataset.push(0);
                    curmonth++;
                }
                curmonth = monthnumber;
                firstcheck = true;
            }

            // When GET data month switches to next we know that we are finished with calculatin that month and we can push it in to the dataset
            if (curmonth < parseInt(monthnumber)) {
                dataset.push(monthtotal);
                monthtotal = 0; // Reset monthtotal
                curmonth++;     // Add one to curmonth to keep track where we are
            }

            // If we have empty months in between data, eg Jan and Mar has hours but Feb doesnt we need to push 0 seconds so many times that there are empty months in data
            while (dataset.length < monthnumber-1) {
                dataset.push(0);
                curmonth++;
            }
            
            let timestring = item.total_time;
            let splitstring = timestring.split(":");

            let hoursecs = splitstring[0]*60*60;
            let minutesec = splitstring[1]*60;
            let total = hoursecs+minutesec;
            monthtotal+=total;

            // When we hit to the end of GET data, we need to push final monthtotal in
            if (datalength == data.length) {
                dataset.push(monthtotal);
            }
        });
        
        let chartdata = {
            labels: ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"],
            datasets: [
                {
                    label: 'Tunnit',
                    backgroundColor: 'rgba(89, 159, 217, 0.5)',
                    borderColor: 'rgba(89, 159, 217, 1)',
                    hoverBackgroundColor: 'rgba(204, 204, 204, 0.5)',
                    hoverBorderColor: 'rgba(204, 204, 204, 1)',
                    data: dataset
                }
            ]
        };

        let graph = $("#currentyeartotal");
        let heightRatio = 1.5;
        graph.height = graph.width * heightRatio;

        let barGraph = new Chart(graph, {
            type: "bar",
            data: chartdata,
            options: {
                tooltips: {
                    mode: "index",
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let totalSeconds = tooltipItem.value;
                            let hours = Math.floor(totalSeconds / 3600);
                            let minutes = Math.floor(totalSeconds / 60)%60;
                            if (minutes == 0) {
                                minutes = "00";
                            }

                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            return label + ": " + hours + ":" + minutes;
                        }
                    },
                    titleFontSize: 16
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            stepSize: 36000,
                            beginAtZero: true,
                            callback: function(label, index, labels) {
                                return formatTime(label);
                            }
                        },
                        scaleLabel: {
							display: true,
							labelString: 'Tunnit'
						}
                    }]
                }
            }
        });
    }, "json");

    $.get("api/jsonApi.php?mode=workday&action=view&id=between&start="+previousyearstr+"-01-01&end="+previousyearstr+"-12-31", function(data) {
        let dataset = [];
        let monthtotal = 0; // Keep total seconds of the current month 
        var curmonth = 1;  // Keep track at which month we are in currently
        let datalength = 0; // Keep track how many items we have gone through in GET data
        let firstcheck = false;
        // Note: This script expects that incoming data from api is ordered by date, otherwise this would not work in current design
        data.forEach(function(item) {
            datalength++;
            // Get month number to compare for where dataset should be adding data
            let date = moment(item.date);
            let monthnumber = date.format("M");

            // If workdays does not start at January we need to push 0 seconds to all months before first workday of the year
            if (!firstcheck) {
                while (curmonth < monthnumber) {
                    dataset.push(0);
                    curmonth++;
                }
                curmonth = monthnumber;
                firstcheck = true;
            }

            // When GET data month switches to next we know that we are finished with calculatin that month and we can push it in to the dataset
            if (curmonth < parseInt(monthnumber)) {
                dataset.push(monthtotal);
                monthtotal = 0; // Reset monthtotal
                curmonth++;     // Add one to curmonth to keep track where we are
            }

            // If we have empty months in between data, eg Jan and Mar has hours but Feb doesnt we need to push 0 seconds so many times that there are empty months in data
            while (dataset.length < monthnumber-1) {
                dataset.push(0);
                curmonth++;
            }
            
            let timestring = item.total_time;
            let splitstring = timestring.split(":");

            let hoursecs = splitstring[0]*60*60;
            let minutesec = splitstring[1]*60;
            let total = hoursecs+minutesec;
            monthtotal+=total;

            // When we hit to the end of GET data, we need to push final monthtotal in
            if (datalength == data.length) {
                dataset.push(monthtotal);
            }
        });

        let chartdata = {
            labels: ["Tammikuu", "Helmikuu", "Maaliskuu", "Huhtikuu", "Toukokuu", "Kesäkuu", "Heinäkuu", "Elokuu", "Syyskuu", "Lokakuu", "Marraskuu", "Joulukuu"],
            datasets: [
                {
                    label: 'Tunnit',
                    backgroundColor: 'rgba(89, 159, 217, 0.5)',
                    borderColor: 'rgba(89, 159, 217, 1)',
                    hoverBackgroundColor: 'rgba(204, 204, 204, 0.5)',
                    hoverBorderColor: 'rgba(204, 204, 204, 1)',
                    data: dataset
                }
            ]
        };

        let graph = $("#lastyeartotal");
        let heightRatio = 1.5;
        graph.height = graph.width * heightRatio;

        let barGraph = new Chart(graph, {
            type: "bar",
            data: chartdata,
            options: {
                tooltips: {
                    mode: "index",
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let totalSeconds = tooltipItem.value;
                            let hours = Math.floor(totalSeconds / 3600);
                            let minutes = Math.floor(totalSeconds / 60)%60;
                            if (minutes == 0) {
                                minutes = "00";
                            }

                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            return label + ": " + hours + ":" + minutes;
                        }
                    },
                    titleFontSize: 16
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            stepSize: 36000,
                            beginAtZero: true,
                            callback: function(label, index, labels) {
                                return formatTime(label);
                            }
                        },
                        scaleLabel: {
							display: true,
							labelString: 'Tunnit'
						}
                    }]
                }
            }
        });
    }, "json");

    
});