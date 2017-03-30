'use strict'

var chartArea = document.getElementById("dash-transaction-chart");
var lineChart = new Chart(chartArea, {
    type: 'line',
    data: {
        labels: monthlabel,
        datasets: [
            {
                label: "Users",
                fill: false,
                lineTension: 0.1,
                backgroundColor: "rgba(100,8,192,0.4)",
                borderColor: "rgba(100,8,192,1)",
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: "rgba(100,8,192,1)",
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: "rgba(100,8,192,1)",
                pointHoverBorderColor: "rgba(220,220,220,1)",
                pointHoverBorderWidth: 2,
                pointRadius: 1,
                pointHitRadius: 10,
                data: countdata,
                spanGaps: false,
            }
        ]
    },
    options: {
        responsive: true,
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

var data = {
    labels: [
        "Boar",
        "Gilt",
        "Sow",
        "Semen"
    ],
    datasets: [{
            data: [boar, gilt, sow, semen],
            backgroundColor: [
                "#e0fb70",
                "#92caed",
                "#ed92ca",
                "#caed92"
            ],
            hoverBackgroundColor: [
                "#e0fb70",
                "#92caed",
                "#ed92ca",
                "#caed92"
            ]
        }]
};

var productBreakdownChartArea = document.getElementById('admin-product-breakdown-chart');
var productBreakdownChart = new Chart(productBreakdownChartArea,{
    type: 'pie',
    data: data,
    options: {
        animation:{
            animateScale:true
        }
    }
});
