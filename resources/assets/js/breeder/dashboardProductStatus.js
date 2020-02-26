"use strict";

Vue.component("custom-status-select", {
  template:
    '\
        <div> \
            <select ref="select" :value="value">\
                <option value="">All</option> \
                <option value="requested">Requested</option> \
                <option value="reserved">Reserved</option> \
                <option value="on_delivery">On Delivery</option> \
                <option value="sold">Sold</option> \
            </select> \
            <label>Status</label> \
        </div> \
    ',
  props: ["value"],
  mounted: function() {
    var self = this;
    $("select").on("change", function() {
      self.$emit("status-select", self.$refs.select.value);
    });
  }
});

Vue.component("custom-date-select", {
  template:
    '\
        <div class="col s12" style="padding:0;"> \
            <input type="date" id="delivery-date" name="delivery-date" class="datepicker" ref="select" :value="value"/> \
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

    var self = this;
    $("#delivery-date").on("change", function() {
      self.$emit("date-select", self.$refs.select.value);
    });
  }
});

Vue.component("status-table", {
  template: "#status-table-template",
  props: ["products", "token", "filterQuery", "statusFilter"],
  data: function() {
    return {
      sortKey: "",
      isProductInformationUpActive: "",
      isProductInformationUpActiveFlag: true,
      isStatusUpActive: "",
      isStatusUpActiveFlag: true,
      sortOrders: {
        name: 1,
        status: 1
      },
      productRequest: {
        productId: 0,
        productName: "",
        productIndex: 0,
        type: "",
        breed: "",
        customers: []
      },
      productReserve: {
        customerId: 0,
        customerName: "",
        swineCartId: 0,
        requestQuantity: 0
      },
      productInfoModal: {
        productId: 0,
        reservationId: 0,
        productName: "",
        productIndex: 0,
        customerName: "",
        deliveryDate: ""
      },
      reservationDetails: {
        productName: "",
        customerName: "",
        type: "",
        breed: "",
        dateNeeded: "",
        specialRequest: ""
      },
      customerInfo: {
        name: "",
        addressLine1: "",
        addressLine2: "",
        province: "",
        mobile: ""
      }
    };
  },
  computed: {
    filteredProducts: function() {
      var self = this;
      var sortKey = this.sortKey;
      var statusFilter = this.statusFilter;
      var filterQuery = this.filterQuery.toLowerCase();
      var order = this.sortOrders[sortKey];
      var products = this.products;

      // Check if desired product status exists
      if (statusFilter) {
        products = products.filter(function(product) {
          return product.status === statusFilter;
        });
      }

      // Check if there is a search query
      if (filterQuery) {
        products = products.filter(function(product) {
          return Object.keys(product).some(function(key) {
            return (
              String(product[key])
                .toLowerCase()
                .indexOf(filterQuery) > -1
            );
          });
        });
      }

      // Check if desired sort key exists
      if (sortKey) {
        products = products.sort(function(a, b) {
          a = a[sortKey];
          b = b[sortKey];
          return (a === b ? 0 : a > b ? 1 : -1) * order;
        });
      }

      return products;
    }
  },
  methods: {
    sortBy: function(key) {
      // sort alphabetically according to 'name' of product
      if (key === "name") {
        // reset the color of arrows in Status header if Product Info is clicked
        this.isStatusUpActive = "";

        /* this if condition is for the first visit of user
         * in which the table rows are not yet sorted, and
         * the color of the arrows are still black
         */
        if (this.isProductInformationUpActiveFlag) {
          this.isProductInformationUpActiveFlag = false;
          this.isProductInformationUpActive = false;
        }

        // for the switching color of arrow up and down
        this.isProductInformationUpActive = !this.isProductInformationUpActive;
      }
      // sort alphabetically according to 'status' of product
      else if (key === "status") {
        // reset the color of arrows in Product Info header if Status is clicked
        this.isProductInformationUpActive = "";

        /* this if condition is for the first visit of user
         * in which the table rows are not yet sorted, and
         * the color of the arrows are still black
         */
        if (this.isStatusUpActiveFlag) {
          this.isStatusUpActiveFlag = false;
          this.isStatusUpActive = false;
        }
        // for the switching color of arrow up and down
        this.isStatusUpActive = !this.isStatusUpActive;
      }

      // Sort table column according to what's chosen
      this.sortKey = key;
      this.sortOrders[key] = this.sortOrders[key] * -1;
    },

    searchProduct: function(uuid) {
      // Return index of productId to find
      for (var i = 0; i < this.products.length; i++) {
        if (this.products[i].uuid === uuid) return i;
      }
    },

    dateChange: function(value) {
      // Event listener to reflect data change in date select to vue's data
      this.productInfoModal.deliveryDate = value;
    },

    getProductRequests: function(uuid, event) {
      var index = this.searchProduct(uuid);

      // Set data values for initializing product-requests-modal
      this.productRequest.productId = this.products[index].id;
      this.productRequest.productName = this.products[index].name;
      this.productRequest.productIndex = index;
      this.productRequest.type = this.products[index].type;
      this.productRequest.breed = this.products[index].breed;

      $(event.target)
        .parent()
        .tooltip("remove");

      // Do AJAX
      this.$http
        .get(
          config.dashboard_url + "/product-status/retrieve-product-requests",
          {
            params: { product_id: this.products[index].id }
          }
        )
        .then(
          function(response) {
            // Store fetched data in local component data
            this.productRequest.customers = response.body;
            $("#product-requests-modal").modal("open");

            this.$nextTick(function() {
              // Initialize tooltips
              $(".tooltipped").tooltip({ delay: 50 });
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    confirmReservation: function(index) {
      var requestDetails = this.productRequest.customers[index];

      // Initialize productReserve local data to be
      // used for the confirmation modal
      this.productReserve.customerId = requestDetails.customerId;
      this.productReserve.customerName = requestDetails.customerName;
      this.productReserve.swineCartId = requestDetails.swineCartId;
      this.productReserve.requestQuantity = requestDetails.requestQuantity;
      this.productReserve.dateNeeded = requestDetails.dateNeeded;
      this.productReserve.specialRequest = requestDetails.specialRequest;
      $("#reserve-product-confirmation-modal").modal("open");
    },

    reserveToCustomer: function(event) {
      var reserveButtons = $(".reserve-product-buttons");
      this.disableButtons(reserveButtons, event.target);

      // Do AJAX
      this.$http
        .patch(config.dashboard_url + "/product-status/update-status", {
          _token: this.token,
          product_id: this.productRequest.productId,
          customer_id: this.productReserve.customerId,
          swinecart_id: this.productReserve.swineCartId,
          request_quantity: this.productReserve.requestQuantity,
          date_needed: this.productReserve.dateNeeded,
          special_request: this.productReserve.specialRequest,
          status: "reserved"
        })
        .then(
          function(response) {
            var responseBody = response.body;
            var index = this.productRequest.productIndex;

            $("#reserve-product-confirmation-modal").modal("close");
            $("#product-requests-modal").modal("close");

            // Update product data (root data) based on the response
            // of the AJAX PATCH method
            if (responseBody[0] === "success") {
              if (
                this.products[index].type !== "semen" &&
                this.products[index].is_unique === 1
              ) {
                console.log("product not semen; product is unique");
                var updateDetails = {
                  status: "reserved",
                  statusTime: responseBody[5].date,
                  index: index,
                  type: this.products[index].type,
                  reservationId: responseBody[2],
                  customerId: this.productReserve.customerId,
                  customerName: this.productReserve.customerName
                };

                // Update product list on root data
                this.$emit("update-product", updateDetails);
              } else {
                var updateDetails = {
                  status: "reserved",
                  statusTime: responseBody[5].date,
                  uuid: responseBody[3],
                  index: index,
                  type: this.products[index].type,
                  is_unique: this.products[index].is_unique,
                  reservationId: responseBody[2],
                  quantity: this.productReserve.requestQuantity,
                  customerId: this.productReserve.customerId,
                  customerName: this.productReserve.customerName,
                  dateNeeded: this.productReserve.dateNeeded,
                  specialRequest: this.productReserve.specialRequest,
                  removeParentProductDisplay: responseBody[4]
                };

                // Update product list on root data
                this.$emit("update-product", updateDetails);
              }
            }

            // Initialize/Update some DOM elements
            this.$nextTick(function() {
              if (responseBody[0] === "success")
                Materialize.toast(responseBody[1], 2500, "green lighten-1");
              else Materialize.toast(responseBody[1], 2500, "orange accent-2");
              $(".tooltipped").tooltip({ delay: 50 });
              this.enableButtons(reserveButtons, event.target);
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    setUpConfirmation: function(uuid, status) {
      var index = this.searchProduct(uuid);

      // Initialize productDeliver local data to be
      // used for the confirmation modal
      this.productInfoModal.productId = this.products[index].id;
      this.productInfoModal.reservationId = this.products[index].reservation_id;
      this.productInfoModal.productName = this.products[index].name;
      this.productInfoModal.customerName = this.products[index].customer_name;
      this.productInfoModal.productIndex = index;
      this.productInfoModal.deliveryDate =
        status === "delivery"
          ? moment()
              .add(5, "days")
              .format("MMMM DD, YYYY")
          : "";

      if (status === "delivery")
        $("#product-delivery-confirmation-modal").modal("open");
      else if (status === "cancel_transaction")
        $("#cancel-transaction-confirmation-modal").modal("open");
      else $("#sold-product-confirmation-modal").modal("open");
    },

    productCancelTransaction: function(event) {
      var cancelTransactionButtons = $(".cancel-transaction-buttons");
      this.disableButtons(cancelTransactionButtons, event.target);

      // Do AJAX
      this.$http
        .patch(config.dashboard_url + "/product-status/update-status", {
          _token: this.token,
          product_id: this.productInfoModal.productId,
          reservation_id: this.productInfoModal.reservationId,
          status: "cancel_transaction"
        })
        .then(
          function(response) {
            var responseBody = response.body,
              index = this.productInfoModal.productIndex,
              customerName = this.productInfoModal.customerName,
              productName = this.productInfoModal.productName;

            $("#cancel-transaction-confirmation-modal").modal("close");

            // Set status of the product (root data) to 'on_delivery'
            // after successful product status change
            this.$emit("update-product", {
              status: "cancel_transaction",
              index: index
            });

            // Initialize/Update some DOM elements
            this.$nextTick(function() {
              if (responseBody[0] === "OK")
                Materialize.toast(
                  "Cancelled transaction on " + productName,
                  2500,
                  "green lighten-1"
                );
              else
                Materialize.toast(
                  "Failed status change",
                  2500,
                  "orange accent-2"
                );
              $(".tooltipped").tooltip({ delay: 50 });
              this.enableButtons(cancelTransactionButtons, event.target);
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    productOnDelivery: function(event) {
      var deliveryButtons = $(".delivery-product-buttons");
      this.disableButtons(deliveryButtons, event.target);

      // Do AJAX
      this.$http
        .patch(config.dashboard_url + "/product-status/update-status", {
          _token: this.token,
          product_id: this.productInfoModal.productId,
          reservation_id: this.productInfoModal.reservationId,
          status: "on_delivery",
          delivery_date: this.productInfoModal.deliveryDate
        })
        .then(
          function(response) {
            var responseBody = response.body,
              index = this.productInfoModal.productIndex,
              customerName = this.productInfoModal.customerName,
              productName = this.productInfoModal.productName;

            $("#product-delivery-confirmation-modal").modal("close");

            // Set status of the product (root data) to 'on_delivery'
            // after successful product status change
            this.$emit("update-product", {
              status: "on_delivery",
              statusTime: responseBody[1].date,
              deliveryDate: this.productInfoModal.deliveryDate,
              index: index
            });

            // Initialize/Update some DOM elements
            this.$nextTick(function() {
              if (responseBody[0] === "OK")
                Materialize.toast(
                  productName + " on delivery to " + customerName,
                  2500,
                  "green lighten-1"
                );
              else
                Materialize.toast(
                  "Failed status change",
                  2500,
                  "orange accent-2"
                );
              $(".tooltipped").tooltip({ delay: 50 });
              this.enableButtons(deliveryButtons, event.target);
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    productOnSold: function() {
      var soldButtons = $(".sold-product-buttons");
      this.disableButtons(soldButtons, event.target);

      // Do AJAX
      this.$http
        .patch(config.dashboard_url + "/product-status/update-status", {
          _token: this.token,
          product_id: this.productInfoModal.productId,
          reservation_id: this.productInfoModal.reservationId,
          status: "sold"
        })
        .then(
          function(response) {
            var responseBody = response.body,
              index = this.productInfoModal.productIndex,
              customerName = this.productInfoModal.customerName,
              productName = this.productInfoModal.productName;

            $("#sold-product-confirmation-modal").modal("close");

            // Set status of the product (root data) to 'sold' after
            // successful product status change
            this.$emit("update-product", {
              status: "sold",
              statusTime: responseBody[1].date,
              index: index
            });

            // Initialize/Update some DOM elements
            this.$nextTick(function() {
              if (responseBody[0] === "OK")
                Materialize.toast(
                  productName + " already sold to " + customerName,
                  2500,
                  "green lighten-1"
                );
              else
                Materialize.toast(
                  "Failed status change",
                  2500,
                  "orange accent-2"
                );
              $(".tooltipped").tooltip({ delay: 50 });
              this.enableButtons(soldButtons, event.target);
            });
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    showReservationDetails: function(uuid) {
      var index = this.searchProduct(uuid);

      this.reservationDetails.productName = this.products[index].name;
      this.reservationDetails.customerName = this.products[index].customer_name;
      this.reservationDetails.type = this.products[index].type;
      this.reservationDetails.breed = this.products[index].breed;
      this.reservationDetails.dateNeeded = this.products[index].date_needed;
      this.reservationDetails.specialRequest = this.products[
        index
      ].special_request;

      $("#product-reservation-details-modal").modal("open");
    },

    showCustomerInfo: function(customerId, customerName) {
      // Do AJAX
      this.$http
        .get(config.dashboard_url + "/customer-info", {
          params: { customer_id: customerId }
        })
        .then(
          function(response) {
            // Store fetched data in local component data
            var data = response.body;
            this.customerInfo.name = customerName;
            this.customerInfo.addressLine1 = data.address_addressLine1;
            this.customerInfo.addressLine2 = data.address_addressLine2;
            this.customerInfo.province = data.address_province;
            this.customerInfo.mobile = data.mobile;

            $("#customer-info-modal").modal("open");
          },
          function(response) {
            console.log(response.statusText);
          }
        );
    },

    disableButtons: function(buttons, actionBtnElement) {
      buttons.addClass("disabled");

      actionBtnElement.innerHTML = "...";
    },

    enableButtons: function(buttons, actionBtnElement) {
      buttons.removeClass("disabled");

      actionBtnElement.innerHTML = "Yes";
    }
  },
  filters: {
    capitalize: function(str) {
      if (str) return str[0].toUpperCase() + str.slice(1);
      else return "";
    },

    transformDate: function(value) {
      return moment(value).format("MMM D YYYY (ddd), h:mmA");
    },

    transformToReadableStatus: function(value) {
      return _.startCase(value);
    }
  }
});

var vm = new Vue({
  el: "#product-status-container",
  data: {
    topic: window.pubsubTopic,
    searchQuery: "",
    statusFilter: "",
    products: rawProducts
  },
  methods: {
    searchProduct: function(swineCart_id) {
      // Return index of productId to find
      for (var i = 0; i < this.products.length; i++) {
        if (this.products[i].id === swineCart_id) return i;
      }
    },

    statusChange: function(value) {
      this.statusFilter = value;
    },

    // Update local product data depending on the status
    updateProduct: function(updateDetails) {
      // Listener to 'update-product' on status-table component

      switch (updateDetails.status) {
        case "reserved":
          var index = updateDetails.index;

          // Just update the product if it is not of type 'semen'
          if (updateDetails.type !== "semen" && updateDetails.is_unique == 1) {
            this.products[index].status = "reserved";
            this.products[index].status_time = updateDetails.statusTime;
            this.products[index].quantity = 0;
            this.products[index].reservation_id = updateDetails.reservationId;
            this.products[index].customer_id = updateDetails.customerId;
            this.products[index].customer_name = updateDetails.customerName;
          }

          // Add another entry to the product list if of type 'semen'
          else {
            var baseProduct = this.products[index];

            this.products.push({
              uuid: updateDetails.uuid,
              id: baseProduct.id,
              reservation_id: updateDetails.reservationId,
              img_path: baseProduct.img_path,
              breeder_id: baseProduct.breeder_id,
              farm_province: baseProduct.farm_province,
              name: baseProduct.name,
              type: baseProduct.type,
              age: baseProduct.age,
              breed: baseProduct.breed,
              quantity: updateDetails.quantity,
              adg: baseProduct.adg,
              fcr: baseProduct.fcr,
              bft: baseProduct.bft,
              status: "reserved",
              status_time: updateDetails.statusTime,
              customer_id: updateDetails.customerId,
              customer_name: updateDetails.customerName,
              date_needed: updateDetails.dateNeeded,
              special_request: updateDetails.specialRequest
            });

            // If after reservation, the product has been put to status 'displayed'
            // due to zero customers requesting it the parent product
            // display should be removed in the UI component
            if (updateDetails.removeParentProductDisplay)
              this.products.splice(index, 1);
          }

          break;

        case "on_delivery":
          var index = updateDetails.index;
          this.products[index].status = "on_delivery";
          this.products[index].status_time = updateDetails.statusTime;
          this.products[index].delivery_date = updateDetails.deliveryDate;

          break;

        case "sold":
          var index = updateDetails.index;
          this.products[index].status = "sold";
          this.products[index].status_time = updateDetails.statusTime;

          break;

        case "cancel_transaction":
          // Remove from products
          this.products.splice(updateDetails.index, 1);

          break;

        default:
          break;
      }
    }
  },
  created: function() {
    // If parameters are found parse it for the statusFilter data
    if (location.search) {
      var status = location.search.slice(1).split("=");
      this.statusFilter = status[1];
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
        // Update notificationCount and prompt a toast
        data = JSON.parse(data);
        if (data.type === "db-productRequest") {
          var index = self.searchProduct(data.body.id);

          // Add another entry in the table if no current entry yet for the product
          if (
            self.products[index] === undefined ||
            self.products[index].status !== "requested"
          ) {
            self.products.unshift(data.body);
          }
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
