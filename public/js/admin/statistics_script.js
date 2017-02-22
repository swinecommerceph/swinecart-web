// For statistics view functions
"use strict"

// @TODO edit javascipt, not used in the application for now due to change in view behavior
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
var createdBarChart = new Chart(createdChartArea, {
    type: 'bar',
    data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        datasets: [
            {
                label: "Users",
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(119, 158, 203, 0.2)',
                    'rgba(130, 105, 83, 0.2)',
                    'rgba(222, 165, 164, 0.2)',
                    'rgba(225, 209, 220, 0.2)',
                    'rgba(225, 105, 97, 0.2)',
                    'rgba(207, 207, 196, 0.2)'

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(119, 158, 203, 1)',
                    'rgba(130, 105, 83, 1)',
                    'rgba(222, 165, 164, 1)',
                    'rgba(225, 209, 220, 1)',
                    'rgba(225, 105, 97, 1)',
                    'rgba(207, 207, 196, 1)'
                ],
                borderWidth: 1,
                data: [jan, feb, mar, apr, may, jun, jul, aug,sep, oct, nov, dec],

            }
        ]
    }
})
