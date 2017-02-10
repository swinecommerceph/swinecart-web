// For statistics view functions
"use strict"

Vue.component('blocked-chart-area', {
    template: '<canvas id="blocked_chart_area" width="400" height="250"></canvas>'
});

Vue.component('accepted-chart-area', {
    template: '<canvas id="accepted_chart_area" width="400" height="250"></canvas>'
});

Vue.component('deleted-chart-area', {
    template: '<canvas id="deleted_chart_area" width="400" height="250"></canvas>'
});

var vm = new Vue({
    el: '#app-statistics',
    data: {
    },
    methods: {
        get_deleted_data: function(){
            statistics.get_deleted_data();
        },
        get_blocked_data: function(){
            statistics.get_blocked_data();
        },
        get_accepted_data: function(){
            statistics.get_accepted_data();
        },
        get_weekly_created: function(){
            alert("weekly");
        },
        get_monthly_created: function(){
            alert("monthly");
        },
        get_yearly_created: function(){
            alert("yearly");
        }
    }
});

var createdChartArea = document.getElementById("created_chart_area");
var createdLineChart = new Chart(createdChartArea, {
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
                data: [jan, feb, mar, apr, may, jun, jul, aug,sep, oct, nov, dec],
                spanGaps: false,
            }
        ]
    }
})
