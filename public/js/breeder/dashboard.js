'use strict';

Vue.component('custom-date-from-select', {
    template: '\
        <div> \
            <input type="date" id="date-from" name="date-from" class="datepicker" ref="selectFrom" :value="value"/> \
            <label for="date-from">Date From</label> \
        </div> \
    ',
    props:['value'],
    mounted: function(){
        var now = new Date();
        $('#date-from').pickadate({
            min: new Date(now.getFullYear(), now.getMonth()-3, now.getDay()),
            max: true,
            selectMonths: true,
            format: 'mmmm yyyy'
        });

        var self = this;
        $('#date-from').on('change', function(){
            self.$emit('date-from-select',self.$refs.selectFrom.value);
        });
    }

});

Vue.component('custom-date-to-select', {
    template: '\
        <div> \
            <input type="date" id="date-to" name="date-to" class="datepicker" ref="selectTo" :value="value" disabled/> \
            <label for="date-to">Date To</label> \
        </div> \
    ',
    props:['value'],
    mounted: function(){
        $('#date-to').pickadate({
            // min: true,
            selectMonths: true,
            selectYears: 2,
            format: 'mmmm yyyy'
        });

        var self = this;
        $('#date-to').on('change', function(){
            self.$emit('date-to-select',self.$refs.selectTo.value);
        });
    }

});

var vm = new Vue({
    el: '#charts-container',
    data: {
        barChartData: '',
        barChart: '',
        chosenFrequency: 'monthly',
        dateFromInputs: {
            monthly: '',
            weekly: '',
            daily: ''
        },
        dateToInputs: {
            monthly: '',
            weekly: '',
            daily: ''
        },
        dateFromInput: '',
        dateToInput: '',
        dateFromObject: {},
        dateToObject: {}
    },
    methods: {
        valueChange: function(){
            // Clear date objects
            this.dateFromObject.clear();
            this.dateToObject.clear();

            // Set input date labels to normal
            $("label[for='date-to']").removeClass('active');
            $("label[for='date-from']").removeClass('active');

            // Set Date To input to disabled
            $('#date-to').prop('disabled', true);

            // / switch (this.chosenFrequency) {
            //     case 'monthly':
            //
            //         break;
            //
            //     case 'weekly':
            //
            //         break;
            //
            //     case 'daily':
            //
            //         break;
            //
            //     default:
            //         break;
            // }
        },

        dateFromChange: function(value){

            $('#date-to').prop('disabled', false);

            console.log(this.dateFromObject.get());
            // this.dateToObject.set('min', new Date());
            // this.dateToObject.set('max',);

            // switch (this.chosenFrequency) {
            //     case 'monthly':
            //
            //         break;
            //
            //     case 'weekly':
            //
            //         break;
            //
            //     case 'daily':
            //
            //         break;
            //
            //     default:
            //         break;
            // }

        },

        dateToChange: function(value){

        },

        fetchSoldData: function(){
            // Get Data from server:
            // Breeder->Products->Reservations->where('order_status','sold')->with('transactionLog')
            // Use Carbon and use it to compare dates
        }
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
                    text:"No. of Products Sold"
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

        this.dateFromObject = $('#date-from').pickadate('picker');
        this.dateToObject = $('#date-to').pickadate('picker');

    }
});
