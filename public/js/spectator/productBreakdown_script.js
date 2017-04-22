'use strict'
var productBreakdownChartArea = document.getElementById('productBreakdownChartArea');

var data = {
    labels: labels,
    datasets: [{
            data: products,
            backgroundColor: [
                "#edf26d",
                "#3db0bf",
                "#d38393",
                "#519370"
            ],
            hoverBackgroundColor: [
                "#edf26d",
                "#3db0bf",
                "#d38393",
                "#519370"
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
