'use strict';

var vm = new Vue({
    el: '#charts-container',
    data: {
        barChartData: '',
        barChart: ''
    },
    methods: {

    },
    mounted: function(){
        this.barChartData = rawBarChartData;

        // Instantiating the Bar Chart
        var bctx = document.getElementById("barChart");
        this.barChart = new Chart(bctx, {
            type: 'bar',
            data: this.barChartData,
            options: {
                title:{
                    display:true,
                    text:"Chart.js Bar Chart - Stacked"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });


    }
});
