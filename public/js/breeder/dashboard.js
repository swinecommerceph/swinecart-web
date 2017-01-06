'use strict';

Vue.component('custom-date-from-select', {
    template: '\
        <div> \
            <input type="date" id="date-from" name="date-from" class="datepicker" ref="selectFrom" :value="value"/> \
            <label for="date-from">Date From</label> \
        </div> \
    ',
    props:['value', 'dateAccreditation'],
    mounted: function(){
        var self = this;

        $('#date-from').pickadate({
            min: new Date(self.dateAccreditation),
            max: true,
            selectMonths: true,
            selectYears: true,
            format: 'mmmm yyyy',
            formatSubmit: 'yyyy-mm-dd'
        });

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
            selectMonths: true,
            selectYears: 2,
            format: 'mmmm yyyy',
            formatSubmit: 'yyyy-mm-dd'
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
        dateFromInput: '',
        dateToInput: '',
        dateFromObject: {},
        dateToObject: {},
        latestAccreditation: '',
        serverDateNow: ''
    },
    methods: {
        valueChange: function(){
            // Clear date objects
            this.dateFromObject.clear();
            this.dateToObject.clear();

            // Set input date labels to normal
            $("label[for='date-from']").removeClass('active');
            $("label[for='date-to']").removeClass('active');

            // Set "Date To" input to disabled
            $('#date-to').prop('disabled', true);

            // Change format of Date inputs depending on the frequency
            switch (this.chosenFrequency) {
                case 'monthly':
                    this.dateFromObject.component.settings.format = 'mmmm yyyy';
                    this.dateToObject.component.settings.format = 'mmmm yyyy';
                    break;

                case 'weekly':
                    this.dateFromObject.component.settings.format = 'mmmm yyyy';
                    break;

                case 'daily':
                    this.dateFromObject.component.settings.format = 'mmmm d, yyyy';
                    this.dateToObject.component.settings.format = 'mmmm d, yyyy';
                    break;

                default:
                    break;
            }

        },

        dateFromChange: function(value){

            var minDate = new Date(this.dateFromObject.get('select','yyyy-mm-dd'));
            var now = moment(this.serverDateNow);
            var constrictedDate, maxDate;

            this.dateFromInput = value;
            this.dateToObject.clear();

            switch (this.chosenFrequency) {
                case 'monthly':
                    $("label[for='date-to']").removeClass('active');
                    $('#date-to').prop('disabled', false);

                    // Make sure to get the correct month interval
                    var plusFiveMonths = moment(minDate).add(5, 'months');

                    constrictedDate = (plusFiveMonths.isSameOrAfter(now)) ? now : plusFiveMonths;
                    maxDate = new Date(constrictedDate.format('YYYY-MM-D'));

                    break;

                case 'weekly':

                    return;

                case 'daily':
                    $("label[for='date-to']").removeClass('active');
                    $('#date-to').prop('disabled', false);

                    // Make sure to get the correct month interval
                    var plusSixDays = moment(minDate).add(6, 'days');

                    constrictedDate = (plusSixDays.isSameOrAfter(now)) ? now : plusSixDays;
                    maxDate = new Date(constrictedDate.format('YYYY-MM-D'));

                    break;

                default: break;
            }

            // Set min and max of "Date To" Input based on "Date From" Input
            this.dateToObject.set('min', minDate);
            this.dateToObject.set('max', maxDate);

        },

        dateToChange: function(value){

            this.dateToInput = value;
        },

        retrieveSoldProducts: function(){

            // Get Sold Products data from server according to chosen frequency
            if((this.dateFromObject.get() && this.dateToObject.get()) || this.chosenFrequency === 'weekly'){

                // Do AJAX
                this.$http.get(
                    config.dashboard_url+'/sold-products',
                    {
                        params: {
                            dateFrom: this.dateFromObject.get('select','yyyy-mm-dd'),
                            dateTo: this.dateToObject.get('select','yyyy-mm-dd'),
                            frequency: this.chosenFrequency
                        }
                    }
                ).then(
                    function(response){

                        // Store fetched data in local component data
                        var soldData = response.body;
                        this.barChartData.labels = soldData.labels;
                        this.barChartData.datasets.forEach(function(dataset, i){
                            dataset.data = soldData.dataSets[i];
                        });
                        this.barChart.options.title.text = soldData.title;

                        // Update Bar Chart
                        this.barChart.update();
                    },
                    function(response){
                        console.log(response.statusText);
                    }
                );
            }
            else console.log('Nope!');
        }
    },
    created: function(){

        // Initialize local data
        this.barChartData = rawBarChartData;
        this.latestAccreditation = rawLatestAccreditation;
        this.serverDateNow = rawServerDateNow;
    },
    mounted: function(){

        // Declaring global defaults
        Chart.defaults.global.defaultFontFamily = 'Poppins';
        Chart.defaults.global.defaultFontSize = 14;
        Chart.defaults.global.title.fontSize = 18;

        // Instantiating the Bar Chart
        var barChartCanvas = document.getElementById("barChart");

        this.barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: this.barChartData,
            options: {
                defaultFontFamily: 'Poppins',
                title:{
                    display: true,
                    text: rawChartTitle
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    titleSpacing: 10
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });

        // Store Date Picker object to root component
        this.dateFromObject = $('#date-from').pickadate('picker');
        this.dateToObject = $('#date-to').pickadate('picker');
    }
});
