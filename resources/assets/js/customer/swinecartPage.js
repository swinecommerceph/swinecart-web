"use strict";

// This is the event hub we'll use in every
// component to communicate between them.
var eventHub = new Vue();

Vue.component("average-star-rating", {
  template: "#average-star-rating",
  props: ["rating"],
  computed: {
    ratingToPercentage: function() {
      return (100 * this.rating) / 5;
    }
  }
});

Vue.component("star-rating", {
  template: "#star-rating-template",
  props: ["type"],
  data: function() {
    return {
      starValues: [
        {
          class: "",
          icon: ""
        },
        {
          class: "",
          icon: ""
        },
        {
          class: "",
          icon: ""
        },
        {
          class: "",
          icon: ""
        },
        {
          class: "",
          icon: ""
        }
      ],
      normalClass: "grey-text text-darken-2",
      toggledClass: "yellow-text text-darken-1",
      normalIcon: "star_border",
      toggledIcon: "star",
      clicked: 0
    };
  },
  methods: {
    animateHover: function(order) {
      if (!this.clicked) {
        // Designate appropriate classes to the stars
        this.toggleClasses(order);
        this.normalizeClasses(order);
      }
    },

    deanimateHover: function() {
      if (!this.clicked) this.normalizeClasses(-1);
    },

    getValue: function(value) {
      this.clicked = 1;

      // Designate appropriate classes to the stars
      this.toggleClasses(value);
      this.normalizeClasses(value);

      // Notify Order Details component of rating value set
      switch (this.type) {
        case "delivery":
          this.$emit("set-delivery-rating", value + 1);
          break;
        case "transaction":
          this.$emit("set-transaction-rating", value + 1);
          break;
        case "productQuality":
          this.$emit("set-product-rating", value + 1);
          break;
        default:
          break;
      }
    },

    normalizeClasses: function(order) {
      for (var i = order + 1; i < 5; i++) {
        this.starValues[i].class = this.normalClass;
        this.starValues[i].icon = this.normalIcon;
      }
    },

    toggleClasses: function(order) {
      for (var i = 0; i <= order; i++) {
        this.starValues[i].class = this.toggledClass;
        this.starValues[i].icon = this.toggledIcon;
      }
    }
  },
  created: function() {
    // Normalize classes of stars
    this.normalizeClasses(-1);
  }
});

Vue.component("quantity-input", {
  template:
    '\
        <span class="col s12" style="padding:0;"> \
            <input type="text" \
                ref="input" \
                class="center-align remove-borders" \
                style="margin:0;" \
                :value="value" \
                @input="updateValue($event.target.value)" \
                @focus="selectAll" \
                @blur="formatValue" \
            > \
        </span> \
    ',
  props: {
    value: {
      type: Number
    }
  },
  methods: {
    updateValue: function(value) {
      var resultValue = this.validateQuantity(value);
      this.$refs.input.value = resultValue;
      this.$emit("input", resultValue);
    },

    selectAll: function(event) {
      setTimeout(function() {
        event.target.select();
      }, 0);
    },

    formatValue: function() {
      this.$refs.input.value = this.validateQuantity(this.value);
    },

    validateQuantity: function(value) {
      var parsedValue = _.toNumber(value);
      if (_.isFinite(parsedValue) && parsedValue > 0) {
        // If it is a number check if it is divisible by 2
        if (parsedValue % 2 !== 0) return parsedValue + 1;
        else return parsedValue;
      } else return 2;
    }
  }
});
Vue.component("multiplier-quantity-input", {
  template: `
        <span class="col s12" style="padding:0;">
          <input type="text"
              ref="input"
              class="center-align remove-borders"
              :value="value"
              style="margin:0;"
              @focus="selectAll"
              @input="updateValue($event.target.value)"
              placeholder="Enter request quantity"
          >
        </span>
      `,
  props: {
    value: {
      type: Number
    }
  },
  methods: {
    updateValue: function(value) {
      var resultValue = this.validateQuantity(value);
      this.$refs.input.value = resultValue;
      this.$emit("input", resultValue);
    },

    selectAll: function(event) {
      setTimeout(function() {
        event.target.select();
      }, 0);
    },

    formatValue: function() {
      this.$refs.input.value = this.validateQuantity(this.value);
    },

    validateQuantity: function(value) {
      var parsedValue = _.toNumber(value);
      if (_.isFinite(parsedValue) && parsedValue > 0) {
        // If it is a number check if it is divisible by 2
        if (parsedValue % 2 !== 0) return parsedValue + 1;
        else return parsedValue;
      } else return 2;
    }
  }
});

Vue.component("custom-date-select", {
  template:
    '\
        <div> \
            <input type="date" id="date-needed" name="date-needed" class="datepicker remove-borders" ref="select" :value="value"/> \
            <label for="date-needed" class="grey-text text-darken-3">Date Needed</label> \
        </div> \
    ',
  props: ["value"],
  mounted: function() {
    $(".datepicker").pickadate({
      min: true,
      selectMonths: true,
      selectYears: 2,
      format: "mmmm d, yyyy"
    });

    // prevent the date picker from instatly closing upon clicking
    // Materialize bug
    $(".datepicker").on("mousedown", function(event) {
      event.preventDefault();
    });

    var self = this;
    $("#date-needed").on("change", function() {
      self.$emit("date-select", self.$refs.select.value);
    });
  }
});

Vue.component("order-details", {
  template: "#order-details-template",
  props: ["products", "token"],
  data: function() {
    return {
      productRemove: {
        index: 0,
        name: "",
        id: 0
      },
      productRequest: {
        index: 0,
        name: "",
        id: 0,
        type: "",
        dateNeeded: "",
        specialRequest: ""
      },
      requestDetails: {
        name: "",
        type: "",
        dateNeeded: "",
        specialRequest: ""
      },
      breederRate: {
        index: 0,
        breederName: "",
        deliveryValue: 0,
        transactionValue: 0,
        productQualityValue: 0,
        isDeliveryValueFilled: false,
        isTransactionValueFilled: false,
        isProductQualityValueFilled: false,
        isValidToSubmitRate: false,
        commentField: ""
      }
    };
  },
  filters: {
    capitalize: function(value) {
      // Capitalize first letter of word
      return value[0].toUpperCase() + value.slice(1);
    },

    transformToDetailedDate: function(value, prepend) {
      return "[" + prepend + "] " + moment(value).format("MMM D YYYY, h:mmA");
    }
  },
  computed: {
    sortedProducts: function() {
      // Sort Swine Cart items according to its request status
      return _.sortBy(this.products, ["request_status"]);
    }
  },
  methods: {
    searchProduct: function(itemId) {
      // Return index of Swine Cart itemId to find
      for (var i = 0; i < this.products.length; i++) {
        if (this.products[i].item_id === itemId) return i;
      }
    },

    subtractQuantity: function(itemId) {
      // Update (subtract) product's quantity on root data
      this.$emit("subtract-quantity", this.searchProduct(itemId));
    },

    addQuantity: function(itemId) {
      // Update (add) product's quantity on root data
      this.$emit("add-quantity", this.searchProduct(itemId));
    },

    dateChange: function(value) {
      // Event listener to reflect data change in date select to vue's data
      this.productRequest.dateNeeded = value;

      // Enable confirm-request-product button if date needed exist
      if (this.productRequest.dateNeeded) {
        $("#confirm-request-product").removeAttr("disabled");
      } else {
        $("#confirm-request-product").attr("disabled", true);
      }
    },

    viewProductModalFromCart: function(itemId) {
      var index = this.searchProduct(itemId);
      vm.productInfoModal.imgPath = this.products[index].img_path;
      vm.productInfoModal.name = this.products[index].product_name;
      vm.productInfoModal.breeder = this.products[index].breeder;
      vm.productInfoModal.province = this.products[index].product_province;
      vm.productInfoModal.type = this.products[index].product_type;
      vm.productInfoModal.breed = this.products[index].product_breed;
      vm.productInfoModal.birthdate = this.products[index].product_birthdate;
      vm.productInfoModal.age = this.products[index].product_age;
      vm.productInfoModal.adg = this.products[index].product_adg;
      vm.productInfoModal.fcr = this.products[index].product_fcr;
      vm.productInfoModal.bft = this.products[index].product_backfat_thickness;
      vm.productInfoModal.avgDelivery = this.products[index].avg_delivery;
      vm.productInfoModal.avgTransaction = this.products[index].avg_transaction;
      vm.productInfoModal.avgProductQuality = this.products[
        index
      ].avg_productQuality;
      vm.productInfoModal.otherDetails = this.products[
        index
      ].other_details.split(",");

      $("#info-modal").modal({
        opacity: 0
      });
      $("#info-modal").modal("open");
    },

    viewRequestDetails: function(itemId) {
      var index = this.searchProduct(itemId);
      this.requestDetails.name = this.products[index].product_name;
      this.requestDetails.type = this.products[index].product_type;
      this.requestDetails.dateNeeded = this.products[index].date_needed;
      this.requestDetails.specialRequest = this.products[index].special_request;

      $("#product-request-details-modal").modal("open");
    },

    confirmRemoval: function(itemId) {
      var index = this.searchProduct(itemId);
      this.productRemove.index = index;
      this.productRemove.name = this.products[index].product_name;
      this.productRemove.id = this.products[index].product_id;
      $("#remove-product-confirmation-modal").modal("open");
    },

    removeProduct: function() {
      var index = this.productRemove.index;

      $("#remove-product-confirmation-modal").modal("close");

      // Do AJAX
      this.$http
        .delete(config.swineCart_url + "/delete", {
          body: {
            _token: this.token,
            itemId: this.products[index].item_id
          }
        })
        .then(
          function(response) {
            var data = response.body;

            // If deletion of item is successful
            if (data[0] === "success") {
              var span = $("#cart-icon span");

              // Put quantity of Swine Cart to sessionStorage
              sessionStorage.setItem("swine_cart_quantity", data[2]);

              if (data[2] === 0) {
                span.html("");
                span.removeClass("badge");
                $("#cart-icon .material-icons").removeClass("left");
                $("#cart-dropdown #item-container").html(
                  '<li> <span class="center-align black-text"> No items in your Swine Cart </span> </li>'
                );
              } else span.html(sessionStorage.getItem("swine_cart_quantity"));

              // Update (remove product) list of products on root data
              this.$emit("remove-product", index);

              Materialize.toast(
                data[1] + " removed from Swine Cart",
                2000,
                "green lighten-1"
              );
            } else
              Materialize.toast(
                data[1] + " is " + data[0],
                2000,
                "orange accent-2"
              );
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    confirmRequest: function(itemId) {
      var index = this.searchProduct(itemId);
      this.productRequest.index = index;
      this.productRequest.name = this.products[index].product_name;
      this.productRequest.id = this.products[index].product_id;
      this.productRequest.type = this.products[index].product_type;
      this.productRequest.dateNeeded = "";
      this.productRequest.specialRequest = this.products[index].special_request;
      $("#request-product-confirmation-modal").modal("open");
    },

    requestProduct: function(event) {
      var index = this.productRequest.index;
      var requestButtons = $(".request-product-buttons");

      this.disableButtons("request", requestButtons, event.target);

      // Do AJAX
      this.$http
        .patch(config.swineCart_url + "/request", {
          _token: this.token,
          itemId: this.products[index].item_id,
          productId: this.products[index].product_id,
          productType: this.products[index].product_type,
          requestQuantity: this.products[index].request_quantity,
          dateNeeded: this.productRequest.dateNeeded,
          specialRequest: this.productRequest.specialRequest
        })
        .then(
          function(response) {
            var data = response.body;
            var span = $("#cart-icon span");

            // Update necessary product attributes on root data
            var updateDetails = {
              index: index,
              status: "requested",
              requestStatus: 1,
              dateNeeded: this.productRequest.dateNeeded,
              specialRequest: this.productRequest.specialRequest,
              statusTransaction: data[1].date
            };

            $("#request-product-confirmation-modal").modal("close");
            this.$emit("product-requested", updateDetails);

            // Put quantity of Swine Cart to sessionStorage
            sessionStorage.setItem("swine_cart_quantity", data[0]);

            if (data[0] == 0) {
              span.html("");
              span.removeClass("badge");
              $("#cart-icon .material-icons").removeClass("left");
              $("#cart-dropdown #item-container").html(
                '<li> <span class="center-align black-text"> No items in your Swine Cart </span> </li>'
              );
            } else span.html(sessionStorage.getItem("swine_cart_quantity"));

            Materialize.toast(
              this.products[index].product_name + " requested",
              2000,
              "green lighten-1"
            );

            // Update some DOM elements
            this.$nextTick(function() {
              $(".tooltipped").tooltip({ delay: 50 });
              $('label[for="special-request"]').removeClass("active");
              $('label[for="date-needed"]').removeClass("active");
              this.enableButtons("request", requestButtons, event.target);
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    showRateModal: function(itemId) {
      $("#rate-modal").modal("open");

      var index = this.searchProduct(itemId);
      this.breederRate.index = index;
      this.breederRate.breederName = this.products[index].breeder;
    },

    rateAndRecord: function(event) {
      var index = this.breederRate.index;
      var rateButtons = $(".rate-breeder-buttons");

      this.disableButtons("rate", rateButtons, event.target);

      // Do AJAX
      this.$http
        .post(config.swineCart_url + "/rate", {
          _token: this.token,
          breederId: this.products[index].breeder_id,
          customerId: this.products[index].customer_id,
          productId: this.products[index].product_id,
          delivery: this.breederRate.deliveryValue,
          transaction: this.breederRate.transactionValue,
          productQuality: this.breederRate.productQualityValue,
          comment: this.breederRate.commentField
        })
        .then(
          function(response) {
            $("#rate-modal").modal("close");
            Materialize.toast(
              this.products[index].breeder + " rated",
              2000,
              "green lighten-1"
            );

            // Update local storage of the ratings modal
            this.breederRate.commentField = "";
            this.breederRate.deliveryValue = 0;
            this.breederRate.transactionValue = 0;
            this.breederRate.productQualityValue = 0;

            // Put the rated product to transaction-history component
            this.$emit("update-history", { index: index });

            this.enableButtons("rate", rateButtons, event.target);
          },
          function(response) {
            console.log(response.statusText);
          }
        );

      // Normalize classes of rating modal
      this.$refs.delivery.normalizeClasses(-1);
      this.$refs.transaction.normalizeClasses(-1);
      this.$refs.productQuality.normalizeClasses(-1);
    },

    disableButtons: function(operation, buttons, actionBtnElement) {
      buttons.addClass("disabled");

      switch (operation) {
        case "request":
          actionBtnElement.innerHTML = "Requesting ...";

          break;
        case "rate":
          actionBtnElement.innerHTML = "Rating ...";

          break;
        default:
          break;
      }
    },

    enableButtons: function(operation, buttons, actionBtnElement) {
      buttons.removeClass("disabled");

      switch (operation) {
        case "request":
          actionBtnElement.innerHTML = "Yes";

          break;
        case "rate":
          actionBtnElement.innerHTML = "Rate";

          break;
        default:
          break;
      }
    },

    setDeliveryRating: function(value) {
      // Listener to 'set-delivery-rating' from 'star-rating' component
      this.breederRate.deliveryValue = value;
      this.breederRate.isDeliveryValueFilled = true;
    },

    setTransactionRating: function(value) {
      // Listener to 'set-transaction-rating' from 'star-rating' component
      this.breederRate.transactionValue = value;
      this.breederRate.isTransactionValueFilled = true;
    },

    setProductRating: function(value) {
      // Listener to 'set-product-rating' from 'star-rating' component
      this.breederRate.productQualityValue = value;
      this.breederRate.isProductQualityValueFilled = true;
    }
  }
});

Vue.component("transaction-history", {
  template: "#transaction-history-template",
  props: ["history"],
  data: function() {
    return {};
  },
  filters: {
    capitalize: function(value) {
      // Capitalize first letter of word
      if (value) {
        var str = value;
        return str[0].toUpperCase() + str.slice(1);
      }
      return "";
    },

    transformToDetailedDate: function(value) {
      return moment(value).format("MMM D YYYY, h:mmA");
    },

    transformToReadableStatus: function(value) {
      return _.startCase(value);
    }
  },
  methods: {
    viewProductModalFromHistory: function(index) {
      vm.productInfoModal.imgPath = this.history[
        index
      ].product_details.l_img_path;
      vm.productInfoModal.name = this.history[index].product_details.name;
      vm.productInfoModal.breeder = this.history[
        index
      ].product_details.breeder_name;
      vm.productInfoModal.province = this.history[
        index
      ].product_details.farm_from;
      vm.productInfoModal.type = this.history[index].product_details.type;
      vm.productInfoModal.breed = this.history[index].product_details.breed;
      vm.productInfoModal.birthdate = moment(
        this.history[index].product_details.birthdate
      ).format("MMMM D, YYYY");
      vm.productInfoModal.age = moment().diff(
        moment(this.history[index].product_details.birthdate),
        "days"
      );
      vm.productInfoModal.adg = this.history[index].product_details.adg;
      vm.productInfoModal.fcr = this.history[index].product_details.fcr;
      vm.productInfoModal.bft = this.history[index].product_details.bft;
      vm.productInfoModal.avgDelivery = this.history[
        index
      ].product_details.avg_delivery;
      vm.productInfoModal.avgTransaction = this.history[
        index
      ].product_details.avg_transaction;
      vm.productInfoModal.avgProductQuality = this.history[
        index
      ].product_details.avg_productQuality;
      vm.productInfoModal.otherDetails = this.history[
        index
      ].product_details.other_details.split(",");

      $("#info-modal").modal({
        opacity: 0
      });
      $("#info-modal").modal("open");
    },

    reverseArray: function(value) {
      var tempArray = _.takeRight(value, 3);
      return _.reverse(tempArray);
    },

    trimmedArray: function(value) {
      var tempArray = _.take(value, value.length);
      return _.slice(_.reverse(tempArray), 3);
    },

    toggleShowFullLogs: function(key) {
      this.history[key].showFullLogs = !this.history[key].showFullLogs;
    }
  }
});

// Root component
var vm = new Vue({
  el: "#swine-cart-container",
  data: {
    topic: window.pubsubTopic,
    products: rawProducts,
    history: [],
    selectedOrdersTab: true,
    productInfoModal: {
      imgPath: "",
      name: "",
      breeder: "",
      farm_province: "",
      type: "",
      breed: "",
      birthdate: "",
      age: 0,
      adg: 0,
      fcr: 0,
      bft: 0,
      avgDelivery: 0,
      avgTransaction: 0,
      avgProductQuality: 0,
      otherDetails: []
    }
  },
  computed: {
    capitalizedProductType: function() {
      // Capitalize first letter of the word
      if (this.productInfoModal.type) {
        var str = this.productInfoModal.type;
        return str[0].toUpperCase() + str.slice(1);
      }
      return "";
    }
  },
  methods: {
    searchProduct: function(swineCart_id) {
      // Return index of Swine Cart itemId to find
      for (var i = 0; i < this.products.length; i++) {
        if (this.products[i].item_id === swineCart_id) return i;
      }
    },

    subtractProductQuantity: function(index) {
      // Listener to 'subtract-quantity' from order-details component
      if (this.products[index].request_quantity > 2)
        this.products[index].request_quantity -= 2;
    },

    addProductQuantity: function(index) {
      // Listener to 'add-quantity' from order-details component
      this.products[index].request_quantity += 2;
    },

    updateHistory: function(updateDetails) {
      // Listener to 'update-history' from order-details component
      this.products.splice(updateDetails.index, 1);
    },

    removeProduct: function(index) {
      // Listener to 'remove-product' from order-details component
      this.products.splice(index, 1);
    },

    productRequested: function(updateDetails) {
      var index = updateDetails.index;
      this.products[index].status = "requested";
      this.products[index].request_status = 1;
      this.products[index].date_needed = updateDetails.dateNeeded;
      this.products[index].special_request = updateDetails.specialRequest;
      this.products[index].status_transactions["requested"] =
        updateDetails.statusTransaction;
    },

    getTransactionHistory: function(customerId) {
      // Do AJAX
      this.$http
        .get(config.swineCart_url + "/transaction-history", {
          params: { customerId: customerId }
        })
        .then(
          function(response) {
            // Store fetched data in local component data
            this.history = JSON.parse(response.body);
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    changeBreadCrumbs: function() {
      // Change the value of final breadcrumbs
      const bc = document.querySelector("#swinecart-breadcrumb #active-tab");
      if (this.selectedOrdersTab) {
        bc.innerHTML = "Transaction History";
        this.selectedOrdersTab = false;
      } else {
        bc.innerHTML = "Orders";
        this.selectedOrdersTab = true;
      }
    }
  },
  filters: {
    round: function(value) {
      // Round number according to precision
      var precision = 2;
      var factor = Math.pow(10, precision);
      var tempNumber = value * factor;
      var roundedTempNumber = Math.round(tempNumber);
      return roundedTempNumber / factor;
    }
  },
  mounted: function() {
    var self = this;

    // Determine if connection to websocket server must
    // be secure depending on the protocol
    var pubsubServer =
      location.protocol === "https:"
        ? config.pubsubWSSServer
        : config.pubsubWSServer;

    // Set-up configuration and subscribe to a topic in the pubsub server
    var onConnectCallback = function(session) {
      session.subscribe(self.topic, function(topic, data) {
        // Update products
        data = JSON.parse(data);
        switch (data.type) {
          case "sc-reserved":
            // Update status
            var index = self.searchProduct(data.item_id);

            self.products[index].status = "reserved";
            self.products[index].status_transactions.reserved = data.reserved;

            break;
          case "sc-onDelivery":
            // Update status
            var index = self.searchProduct(data.item_id);

            self.products[index].status = "on_delivery";
            self.products[index].status_transactions.on_delivery =
              data.on_delivery;

            /*
                            format the date to an abbreviated month so
                            the product cards will not overflow when the
                            size increase
                        */
            self.products[index].delivery_date = moment(
              data.delivery_date
            ).format("MMM D, YYYY");

            break;
          case "sc-sold":
            // Update status
            var index = self.searchProduct(data.item_id);

            self.products[index].status = "sold";
            self.products[index].status_transactions.sold = data.sold;

            break;
          case "sc-cancelTransaction":
            // Reset product as just once added to Swine Cart
            var index = self.searchProduct(data.item_id);

            self.products[index].reservation_id = 0;
            self.products[index].quantity =
              self.products[index].type === "semen" ? 2 : 1;
            self.products[index].request_status = 0;
            self.products[index].date_needed = "";
            self.products[index].special_request = "";
            self.products[index].status = "displayed";
            self.products[index].status_transactions.requested = "";
            self.products[index].status_transactions.reserved = "";
            self.products[index].status_transactions.on_delivery = "";
            self.products[index].status_transactions.sold = "";

            break;
          case "sc-reservedToOthers":
            // Remove product from Swine Cart
            var index = self.searchProduct(data.item_id);

            self.products.splice(index, 1);
            Materialize.toast("Product is already reserved to others", 4000);

            break;
          default:
            break;
        }

        // Update some DOM elements
        self.$nextTick(function() {
          $(".tooltipped").tooltip({ delay: 50 });
        });
      });
    };

    var onHangupCallback = function(code, reason, detail) {
      console.warn("WebSocket connection closed");
      console.warn(code + ": " + reason);
    };

    var conn = new ab.connect(
      pubsubServer,
      onConnectCallback,
      onHangupCallback,
      {
        maxRetries: 30,
        retryDelay: 2000,
        skipSubprotocolCheck: true
      }
    );
  }
});
