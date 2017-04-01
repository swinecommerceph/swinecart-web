'use strict'
var productBreakdownChartArea = document.getElementById('productBreakdownChartArea');

var data = {
    labels: labels,
    datasets: [{
            data: products,
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
}
var productBreakdownChart = new Chart(productBreakdownChartArea,{
    type: 'pie',
    data: data,
    options: {
        animation:{
            animateScale:true
        }
    }
});
