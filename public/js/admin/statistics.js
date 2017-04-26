// For statistics related logic functions
"use strict"
var statistics = {
    get_deleted_data: function(){
        $.ajax({
            url: 'deleted',
            type: 'GET',
            cache: false,
            success: function(data){
                var deletedChartArea = document.getElementById("deleted_chart_area");
                var deletedLineChart = new Chart(deletedChartArea, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [
                            {
                                label: "Users",
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(75,192,192,0.4)",
                                borderColor: "rgba(75,192,192,1)",
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: "rgba(75,192,192,1)",
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7],data[8], data[9], data[10], data[11]],
                                spanGaps: false,
                            }
                        ]
                    }
                })
            }
        });
    },

    get_blocked_data: function(){
        $.ajax({
            url: 'blocked',
            type: 'GET',
            cache: false,
            success: function(data){
                var blockedChartArea = document.getElementById("blocked_chart_area");
                var blockedLineChart = new Chart(blockedChartArea, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [
                            {
                                label: "Users",
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(75,192,192,0.4)",
                                borderColor: "rgba(75,192,192,1)",
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: "rgba(75,192,192,1)",
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7],data[8], data[9], data[10], data[11]],
                                spanGaps: false,
                            }
                        ]
                    }
                })
            }
        });
    },

    get_accepted_data: function(){
        $.ajax({
            url: 'accepted',
            type: 'GET',
            cache: false,
            success: function(data){
                var acceptedChartArea = document.getElementById("accepted_chart_area");
                var acceptedLineChart = new Chart(acceptedChartArea, {
                    type: 'line',
                    data: {
                        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                        datasets: [
                            {
                                label: "Users",
                                fill: false,
                                lineTension: 0.1,
                                backgroundColor: "rgba(75,192,192,0.4)",
                                borderColor: "rgba(75,192,192,1)",
                                borderCapStyle: 'butt',
                                borderDash: [],
                                borderDashOffset: 0.0,
                                borderJoinStyle: 'miter',
                                pointBorderColor: "rgba(75,192,192,1)",
                                pointBackgroundColor: "#fff",
                                pointBorderWidth: 1,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                pointHoverBorderColor: "rgba(220,220,220,1)",
                                pointHoverBorderWidth: 2,
                                pointRadius: 1,
                                pointHitRadius: 10,
                                data: [data[0], data[1], data[2], data[3], data[4], data[5], data[6], data[7],data[8], data[9], data[10], data[11]],
                                spanGaps: false,
                            }
                        ]
                    }
                })
            }
        });
    },
}
