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
    $.get("api/jsonApi.php?mode=workday&action=view&id=between&start="+startstr+"&end="+endstr, function(data) {
        var label = [];
        var dataset = [];

        data.forEach(function(item) {
            date = moment(item.date);
            timestring = item.total_time;
            splitstring = timestring.split(":");

            hoursecs = splitstring[0]*60*60;
            minutesec = splitstring[1]*60;
            total = hoursecs+minutesec;

            let datapoint = {
                x: date,
                y: total
            }
            dataset.push(datapoint);

            label.push(item.date);
        });
        var chartdata = {
            labels: ["Maanantai", "Tiistai", "Keskiviikko", "Torstai", "Perjantai", "Lauantai", "Sunnuntai"],
            datasets: [
                {
                    label: 'Tunnit',
                    backgroundColor: '#49e2ff',
                    borderColor: '#46d5f1',
                    hoverBackgroundColor: '#CCCCCC',
                    hoverBorderColor: '#666666',
                    data: dataset
                }
            ]
        };

        var graph = $("#myChart");
        var heightRatio = 1.5;
        graph.height = graph.width * heightRatio;

        var barGraph = new Chart(graph, {
            type: "bar",
            data: chartdata,
            options: {
                tooltips: {
                    mode: "index",
                    callbacks: {
                        title: function(tooltipItem, data) {
                            let date = data.datasets[0].data[tooltipItem[0].index].x;
                            date.locale("fi");
                            return date.format("dddd E. MMMM gggg");
                        },
                        label: function(tooltipItem, data) {
                            let totalSeconds = tooltipItem.value;
                            let hours = Math.floor(totalSeconds / 3600);
                            let minutes = Math.floor(totalSeconds / 60)%60
                            if (minutes == 0) {
                                minutes = "00";
                            }

                            let date = moment(tooltipItem.label);
                            date.locale("fi");

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

    /*
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: label,
            datasets: [{
                label: 'Kuluvan viikon tunnit',
                data: dataset,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                xAxes: [{
                    type: 'time',
                    time: {
                        unit: 'month'
                    }
                }]
            }
        }
    });
    */
});