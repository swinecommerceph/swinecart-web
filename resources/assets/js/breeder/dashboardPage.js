'use strict';

Vue.component('custom-date-from-select', {
  template: '\
        <div> \
            <input type="date" id="date-from" name="date-from" class="datepicker" ref="selectFrom" :value="value"/> \
            <label for="date-from">Date From</label> \
        </div> \
    ',
  props: ['value', 'dateAccreditation'],
  mounted: function () {
    var self = this;

    // Initialize datepicker
    $('#date-from').pickadate({
      min: new Date(self.dateAccreditation),
      max: moment().format(),
      selectMonths: true,
      selectYears: true,
      format: 'mmmm yyyy',
      formatSubmit: 'yyyy-mm-dd',
      today: ''
    });

    $('#date-from').on('change', function () {
      self.$emit('date-from-select', self.$refs.selectFrom.value);
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
  props: ['value'],
  mounted: function () {
    var self = this;

    // Initialize datepicker
    $('#date-to').pickadate({
      selectMonths: true,
      selectYears: 2,
      format: 'mmmm yyyy',
      formatSubmit: 'yyyy-mm-dd'
    });

    $('#date-to').on('change', function () {
      self.$emit('date-to-select', self.$refs.selectTo.value);
    });
  }

});

var vm = new Vue({
  el: '#card-status',
  data: {
    barChart: '',
    barChartConfig: {},
    chosenFrequency: 'monthly',
    dateFromInput: '',
    dateToInput: '',
    dateFromObject: {},
    dateToObject: {},
    dashboardStats: {},
    farms: [],
    latestAccreditation: '',
    serverDateNow: '',
    pubsubTopic: window.pubsubTopic
  },  
  computed: {
    overallOnDelivery: function () {
      var sum = this.dashboardStats.on_delivery.boar + this.dashboardStats.on_delivery.gilt + this.dashboardStats.on_delivery.semen + this.dashboardStats.on_delivery.sow;
      return sum;
    },

    overallReserved: function () {
      var sum = this.dashboardStats.reserved.boar + this.dashboardStats.reserved.gilt + this.dashboardStats.reserved.semen + this.dashboardStats.reserved.sow;
      return sum;
    },

    overallHidden: function () {
      var sum = this.dashboardStats.hidden.boar + this.dashboardStats.hidden.gilt + this.dashboardStats.hidden.semen + this.dashboardStats.hidden.sow;
      return sum;
    },

    overallDisplayed: function () {
      var displayedSum = this.dashboardStats.displayed.boar + this.dashboardStats.displayed.gilt + this.dashboardStats.displayed.semen + this.dashboardStats.displayed.sow;
      var requestedSum = this.dashboardStats.requested.boar + this.dashboardStats.requested.gilt + this.dashboardStats.requested.semen + this.dashboardStats.requested.sow;
      return displayedSum + requestedSum;
    },

    overallRequested: function () {
      var sum = this.dashboardStats.requested.boar + this.dashboardStats.requested.gilt + this.dashboardStats.requested.semen + this.dashboardStats.requested.sow;
      return sum;
    },

    overallProductsAvailable: function () {
      // var displayedSum = this.dashboardStats.displayed.boar + this.dashboardStats.displayed.gilt + this.dashboardStats.displayed.semen + this.dashboardStats.displayed.sow;
      // var hiddenSum = this.dashboardStats.hidden.boar + this.dashboardStats.hidden.gilt + this.dashboardStats.hidden.semen + this.dashboardStats.hidden.sow;
      return this.overallDisplayed + this.overallHidden;
    },

    overallRatings: function () {
      var overallAvgRating = (this.dashboardStats.ratings.delivery + this.dashboardStats.ratings.transaction + this.dashboardStats.ratings.productQuality) / 3;
      return this.round(overallAvgRating, 2);
    }
  },
  methods: {

    valueChange: function () {
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

    dateFromChange: function (value) {
      // Trigger if dateFrom component's value changes

      // value = Month Year picked

      var minDate = new Date(this.dateFromObject.get('select', 'yyyy-mm-dd'));
      var now = moment(this.serverDateNow);
      var constrictedDate, maxDate;

      this.dateFromInput = value;
      this.dateToObject.clear();

      switch (this.chosenFrequency) {
        case 'monthly':
          $("label[for='date-to']").removeClass('active');
          $('#date-to').prop('disabled', false);

          // Make sure to get the correct month interval
          var plusElevenMonths = moment(minDate).add(11, 'months');

          constrictedDate = (plusElevenMonths.isSameOrAfter(now)) ? now : plusElevenMonths;
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

    dateToChange: function (value) {
      // Trigger if dateTo component's value changes

      this.dateToInput = value;
    },

    getPattern: function (shape, color) {
      // Function from https://github.com/chartjs/Chart.js/issues/4279
      // Used to resolve the bug for using patterns from patternomaly.js

      let rgb = Chart.helpers.color(color)
      let bgPattern = pattern.draw(shape, color)
      return Chart.helpers.extend(bgPattern, { r: rgb.red(), g: rgb.green(), b: rgb.blue(), alpha: rgb.alpha() })
    },

    retrieveSoldProducts: function () {

      // Get Sold Products data from server according to chosen frequency
      if ((this.dateFromObject.get() && this.dateToObject.get()) || this.chosenFrequency === 'weekly') {

        

        // Do AJAX
        this.$http.get(
          config.dashboard_url + '/sold-products',
          {
            params: {
              dateFrom: this.dateFromObject.get('select', 'yyyy-mm-dd'),
              dateTo: this.dateToObject.get('select', 'yyyy-mm-dd'),
              frequency: this.chosenFrequency
            }
          }
        ).then(
          function (response) {

            // Store fetched data in local component data
            var soldData = response.body;
            this.barChartConfig.labels = soldData.labels;
            console.table(soldData.datasets);
            this.barChartConfig.datasets.forEach(function (dataset, i) {
              dataset.data = soldData.dataSets[i];
            });
            this.barChart.options.title.text = soldData.title;

            // Update Bar Chart
            this.barChart.update();
          },
          function (response) {
            console.log(response.statusText);
          }
        );
      }
      else console.log('Nope!');
    },

    computeAverageRating: function (currentAverage, newValue) {
      var size = this.dashboardStats.ratings.reviewsSize;
      var continuousAverage = ((size * currentAverage) + newValue) / (size + 1);
      return this.round(continuousAverage, 1);
    },

    round: function (number, precision) {
      // Round number according to precision
      var factor = Math.pow(10, precision);
      var tempNumber = number * factor;
      var roundedTempNumber = Math.round(tempNumber);
      return roundedTempNumber / factor;
    },
  },
  created: function () {

    // Initialize local data
    this.farms = farmAddresses;

    // object used for bar chart
    this.barChartConfig = {
      labels: rawLabels,
      datasets: [{
        label: 'Boar',
        backgroundColor: this.getPattern('zigzag-vertical', '#ff6384'),
        //backgroundColor: 'green',
        data: rawDataBoar
      }, {
        label: 'Sow',
        backgroundColor: this.getPattern('diagonal', '#36a2eb'),
        //backgroundColor: 'rgb(54, 162, 235)',
        data: rawDataSow
      }, {
        label: 'Gilt',
        //backgroundColor: 'rgb(75, 192, 192)',
        backgroundColor: this.getPattern('line-vertical', '#cc65fe'),
        data: rawDataGilt
      }, {
        label: 'Semen',
        //backgroundColor: 'rgb(153, 102, 255)',
        backgroundColor: this.getPattern('cross', '#ffce56'),
        data: rawDataSemen
      }]
    };

    this.latestAccreditation = rawLatestAccreditation;
    this.serverDateNow = rawServerDateNow;
    this.dashboardStats = rawDashboardStats;
  },
  mounted: function () {
    var self = this;

    // Determine if connection to websocket server must
    // be secure depending on the protocol
    var pubsubServer = (location.protocol === 'https:') ? config.pubsubWSSServer : config.pubsubWSServer;

    // Declaring global defaults
    Chart.defaults.global.defaultFontFamily = 'Poppins';
    Chart.defaults.global.defaultFontSize = 14;
    Chart.defaults.global.title.fontSize = 18;

    // Instantiating the Bar Chart
    var barChartCanvas = document.getElementById("barChart");

    this.barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: this.barChartConfig,
      options: {
        defaultFontFamily: 'Poppins',
        title: {
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
            stacked: true,
            ticks: {
              beginAtZero: true,
              userCallback: function (label, index, labels) {
                if (Math.floor(label) === label) {
                  return label;
                }
              }
            }
          }]
        }
      }
    });

    // Store Date Picker object to root component
    this.dateFromObject = $('#date-from').pickadate('picker');
    this.dateToObject = $('#date-to').pickadate('picker');

    // Set-up configuration and subscribe to a topic in the pubsub server
    var onConnectCallback = function (session) {

      session.subscribe(self.pubsubTopic, function (topic, data) {
        // Update product status numbers
        data = JSON.parse(data);
        switch (data.type) {
          case 'db-requested':
            self.dashboardStats.displayed[data.product_type]--;
            self.dashboardStats.requested[data.product_type]++;

            break;
          case 'db-reserved':
            self.dashboardStats.requested[data.product_type]--;
            self.dashboardStats.reserved[data.product_type]++;

            break;
          case 'db-cancelTransaction':
            self.dashboardStats[data.previous_status][data.product_type]--;
            self.dashboardStats.displayed[data.product_type]++;

            break;
          case 'db-onDelivery':
            self.dashboardStats.reserved[data.product_type]--;
            self.dashboardStats.on_delivery[data.product_type]++;

            break;
          case 'db-sold':
            self.dashboardStats.on_delivery[data.product_type]--;

            break;
          case 'db-rated':
            var currentDeliveryRating = self.dashboardStats.ratings.delivery,
              currentTransactionRating = self.dashboardStats.ratings.transaction,
              currentProductQualityRating = self.dashboardStats.ratings.productQuality;

            // Compute new averages
            self.dashboardStats.ratings.delivery = self.computeAverageRating(currentDeliveryRating, data.rating_delivery);
            self.dashboardStats.ratings.transaction = self.computeAverageRating(currentTransactionRating, data.rating_transaction);
            self.dashboardStats.ratings.productQuality = self.computeAverageRating(currentProductQualityRating, data.rating_productQuality);

            // Update reviews
            if (self.dashboardStats.ratings.reviews.length >= 3) self.dashboardStats.ratings.reviews.pop();
            self.dashboardStats.ratings.reviewsSize++;
            self.dashboardStats.ratings.reviews.unshift(
              {
                comment: data.review_comment,
                customerName: data.review_customerName
              }
            );

            break;
          default:
            break;
        }

      });
    };

    var onHangupCallback = function (code, reason, detail) {
      console.warn('WebSocket connection closed');
      console.warn(code + ': ' + reason);
    };

    var conn = new ab.connect(
      pubsubServer,
      onConnectCallback,
      onHangupCallback,
      {
        'maxRetries': 30,
        'retryDelay': 2000,
        'skipSubprotocolCheck': true
      }
    );
  }
});
