'use strict'

var totalTransactionPageChartArea = document.getElementById('admin-totaltransaction-page-chart');
var totaltransactionPageChart = new Chart(totalTransactionPageChartArea, {
    type: 'line',
    data: {
        labels: yearLabel.reverse(),
        datasets: [{
            labels: "Transactions",
            data: counts.reverse(),
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
            spanGaps: false
        }]
    },
    options: {
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value) {if (value % 1 === 0) {return value;}}
                }
            }]
        }
    }
});
