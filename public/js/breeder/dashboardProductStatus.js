'use strict';

Vue.component('day-expiration-input',{
    template: '\
        <div class="col s12" style="padding:0;"> \
            <input type="text" \
                ref="input" \
                class="center-align" \
                :value="value" \
                @input="updateValue($event.target.value)" \
                @focus="selectAll" \
                @blur="formatValue" \
            > \
        </div> \
    ',
    props: {
        value: {
            type: Number
        }
    },
    methods: {
        updateValue: function(value){
            var resultValue = this.validateQuantity(value);
            this.$refs.input.value = resultValue;
            this.$emit('input', resultValue);
        },

        selectAll: function(event){
            setTimeout(function () {
                event.target.select()
            }, 0);
        },

        formatValue: function(){
            this.$refs.input.value = this.validateQuantity(this.value);
        },

        validateQuantity: function(value){
            var parsedValue = _.toNumber(value);
            if(_.isFinite(parsedValue) && parsedValue > 0){
                return parsedValue;
            }
            else return 1;
        }
    }

});

Vue.component('custom-status-select', {
    template: '\
        <div> \
            <select ref="select" :value="value">\
                <option value="">All</option> \
                <option value="requested">Requested</option> \
                <option value="reserved">Reserved</option> \
                <option value="on_delivery">On Delivery</option> \
                <option value="paid">Paid</option> \
                <option value="sold">Sold</option> \
            </select> \
            <label>Status</label> \
        </div> \
    ',
    props:['value'],
    mounted: function(){

        var self = this;
        $('select').on('change', function(){
            self.$emit('status-select',self.$refs.select.value);
        });
    }

});

Vue.component('status-table',{
    template: '#status-table-template',
    props: ['products', 'token', 'filterQuery', 'statusFilter'],
    data: function(){
        return {
            sortKey: '',
            sortOrders:{
                name: 1,
                status: 1
            },
            productRequest:{
                productId: 0,
                productName: '',
                productIndex: 0,
                type: '',
                breed: '',
                customers: []
            },
            productReserve:{
                customerId: 0,
                customerName: '',
                swineCartId: 0,
                requestQuantity: 0,
                daysAfterExpiration: 3
            },
            productInfoModal:{
                productId: 0,
                reservationId: 0,
                productName: '',
                productIndex: 0,
                customerName: ''
            },
            reservationDetails:{
                productName: '',
                customerName: '',
                type: '',
                breed: '',
                dateNeeded: '',
                specialRequest: ''
            }
        };
    },
    computed: {
        filteredProducts: function(){
            var self = this;
            var sortKey = this.sortKey;
            var statusFilter = this.statusFilter;
            var filterQuery = this.filterQuery.toLowerCase();
            var order = this.sortOrders[sortKey];
            var products = this.products;

            // Check if desired product status exists
            if(statusFilter){
                products = products.filter(function(product){
                    return product.status === statusFilter;
                });
            }

            // Check if there is a search query
            if(filterQuery){
                products = products.filter(function(product){
                    return Object.keys(product).some(function (key) {
                        return String(product[key]).toLowerCase().indexOf(filterQuery) > -1;
                    });
                });
            }

            // Check if desired sort key exists
            if(sortKey){
                products = products.sort(function(a,b){
                    a = a[sortKey];
                    b = b[sortKey];
                    return (a === b ? 0 : a > b ? 1 : -1) * order;
                });
            }

            return products;
        }
    },
    methods:{

        sortBy: function(key){
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },

        searchProduct : function(uuid){
            // Return index of productId to find
            for(var i = 0; i < this.products.length; i++) {
                if(this.products[i].uuid === uuid) return i;
            }
        },

        getProductRequests: function(uuid, event){
            var index = this.searchProduct(uuid);

            // Set data values for initializing product-requests-modal
            this.productRequest.productId = this.products[index].id;
            this.productRequest.productName = this.products[index].name;
            this.productRequest.productIndex = index;
            this.productRequest.type = this.products[index].type;
            this.productRequest.breed = this.products[index].breed;

            $(event.target).parent().tooltip('remove');

            // Do AJAX
            this.$http.get(
                config.dashboard_url+'/product-status/retrieve-product-requests',
                {
                    params: { product_id: this.products[index].id }
                }
            ).then(
                function(response){

                    // Store fetched data in local component data
                    this.productRequest.customers = response.body;
                    $('#product-requests-modal').modal('open');

                    this.$nextTick(function(){
                        // Initialize tooltips
                        $('.tooltipped').tooltip({delay: 50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );

        },

        confirmReservation: function(index){
            var requestDetails = this.productRequest.customers[index];

            // Initialize productReserve local data to be
            // used for the confirmation modal
            this.productReserve.customerId = requestDetails.customerId;
            this.productReserve.customerName = requestDetails.customerName;
            this.productReserve.swineCartId = requestDetails.swineCartId;
            this.productReserve.requestQuantity = requestDetails.requestQuantity;
            this.productReserve.dateNeeded = requestDetails.dateNeeded;
            this.productReserve.specialRequest = requestDetails.specialRequest;
            $('#reserve-product-confirmation-modal').modal('open');
        },

        reserveToCustomer: function(){
            // Do AJAX
            this.$http.patch(
                config.dashboard_url+'/product-status/update-status',
                {
                    _token: this.token,
                    product_id: this.productRequest.productId,
                    customer_id: this.productReserve.customerId,
                    swinecart_id: this.productReserve.swineCartId,
                    request_quantity: this.productReserve.requestQuantity,
                    date_needed: this.productReserve.dateNeeded,
                    special_request: this.productReserve.specialRequest,
                    days_after_expiration: this.productReserve.daysAfterExpiration,
                    status: 'reserved'
                }
            ).then(
                function(response){
                    var responseBody = response.body;
                    var index = this.productRequest.productIndex;

                    $('#reserve-product-confirmation-modal').modal('close');
                    $('#product-requests-modal').modal('close');

                    // Update product data (root data) based on the response
                    // of the AJAX PATCH method
                    if(responseBody[0] === "success"){
                        if(this.products[index].type !== 'semen'){
                            var updateDetails = {
                                'status': 'reserved',
                                'statusTime': responseBody[6].date,
                                'index': index,
                                'type': this.products[index].type,
                                'reservationId': responseBody[2],
                                'customerId': this.productReserve.customerId,
                                'customerName': this.productReserve.customerName,
                                'expirationDate': responseBody[5].date
                            };

                            // Update product list on root data
                            this.$emit('update-product', updateDetails);
                        }
                        else{
                            var updateDetails = {
                                'status': 'reserved',
                                'statusTime': responseBody[6].date,
                                'uuid': responseBody[3],
                                'index': index,
                                'type': this.products[index].type,
                                'reservationId': responseBody[2],
                                'quantity': this.productReserve.requestQuantity,
                                'customerId': this.productReserve.customerId,
                                'customerName': this.productReserve.customerName,
                                'dateNeeded': this.productReserve.dateNeeded,
                                'specialRequest': this.productReserve.specialRequest,
                                'removeParentProductDisplay': responseBody[4],
                                'expirationDate': responseBody[5].date
                            };

                            // Update product list on root data
                            this.$emit('update-product', updateDetails);
                        }

                    }

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody[0] === "success") Materialize.toast(responseBody[1], 2500, 'green lighten-1');
                        else Materialize.toast(responseBody[1], 2500, 'orange accent-2');
                        $('.tooltipped').tooltip({delay:50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        setUpConfirmation: function(uuid, status){
            var index = this.searchProduct(uuid);

            // Initialize productDeliver local data to be
            // used for the confirmation modal
            this.productInfoModal.productId = this.products[index].id;
            this.productInfoModal.reservationId = this.products[index].reservation_id;
            this.productInfoModal.productName = this.products[index].name;
            this.productInfoModal.customerName = this.products[index].customer_name;
            this.productInfoModal.productIndex = index;

            if(status === 'delivery') $('#product-delivery-confirmation-modal').modal('open');
            else if(status === 'paid') $('#paid-product-confirmation-modal').modal('open');
            else $('#sold-product-confirmation-modal').modal('open');
        },

        productOnDelivery: function(){
            // Do AJAX
            this.$http.patch(
                config.dashboard_url+'/product-status/update-status',
                {
                    _token: this.token,
                    product_id: this.productInfoModal.productId,
                    reservation_id: this.productInfoModal.reservationId,
                    status: 'on_delivery'
                }
            ).then(
                function(response){
                    var responseBody = response.body,
                        index = this.productInfoModal.productIndex,
                        customerName = this.productInfoModal.customerName,
                        productName = this.productInfoModal.productName;

                    $('#product-delivery-confirmation-modal').modal('close');

                    // Set status of the product (root data) to 'on_delivery'
                    // after successful product status change
                    this.$emit('update-product',
                        {
                            'status': 'on_delivery',
                            'statusTime': responseBody[1].date,
                            'index': index
                        }
                    );

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody[0] === "OK") Materialize.toast(productName + ' on delivery to ' + customerName , 2500, 'green lighten-1');
                        else Materialize.toast('Failed status change', 2500, 'orange accent-2');
                        $('.tooltipped').tooltip({delay:50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        productPaid: function(){
            // Do AJAX
            this.$http.patch(
                config.dashboard_url+'/product-status/update-status',
                {
                    _token: this.token,
                    product_id: this.productInfoModal.productId,
                    reservation_id: this.productInfoModal.reservationId,
                    status: 'paid'
                }
            ).then(
                function(response){
                    var responseBody = response.body,
                        index = this.productInfoModal.productIndex,
                        customerName = this.productInfoModal.customerName,
                        productName = this.productInfoModal.productName;

                    $('#paid-product-confirmation-modal').modal('close');

                    // Set status of the product (root data) to 'paid' after
                    // successful product status change
                    this.$emit('update-product',
                        {
                            'status': 'paid',
                            'statusTime': responseBody[1].date,
                            'index': index
                        }
                    );

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody[0] === "OK") Materialize.toast(productName + ' already paid by ' + customerName , 2500, 'green lighten-1');
                        else Materialize.toast('Failed status change', 2500, 'orange accent-2');
                        $('.tooltipped').tooltip({delay:50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        productOnSold: function(){
            // Do AJAX
            this.$http.patch(
                config.dashboard_url+'/product-status/update-status',
                {
                    _token: this.token,
                    product_id: this.productInfoModal.productId,
                    reservation_id: this.productInfoModal.reservationId,
                    status: 'sold'
                }
            ).then(
                function(response){
                    var responseBody = response.body,
                        index = this.productInfoModal.productIndex,
                        customerName = this.productInfoModal.customerName,
                        productName = this.productInfoModal.productName;

                    $('#sold-product-confirmation-modal').modal('close');

                    // Set status of the product (root data) to 'sold' after
                    // successful product status change
                    this.$emit('update-product',
                        {
                            'status': 'sold',
                            'statusTime': responseBody[1].date,
                            'index': index
                        }
                    );

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody[0] === "OK") Materialize.toast(productName + ' already sold to ' + customerName , 2500, 'green lighten-1');
                        else Materialize.toast('Failed status change', 2500, 'orange accent-2');
                        $('.tooltipped').tooltip({delay:50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        showReservationDetails: function(uuid){
            var index = this.searchProduct(uuid);

            this.reservationDetails.productName = this.products[index].name;
            this.reservationDetails.customerName = this.products[index].customer_name;
            this.reservationDetails.type = this.products[index].type;
            this.reservationDetails.breed = this.products[index].breed;
            this.reservationDetails.dateNeeded = this.products[index].date_needed;
            this.reservationDetails.specialRequest = this.products[index].special_request;

            $('#product-reservation-details-modal').modal('open');
        }

    },
    filters: {
        capitalize: function(str){
            if (str) return str[0].toUpperCase() + str.slice(1);
            else return '';
        },

        transformDate: function(value){
            return moment(value).format("MMM D YYYY (ddd), h:mmA");
        },

        transformToReadableStatus: function(value){
            return _.startCase(value);
        }
    }
});

var vm = new Vue({
    el: '#product-status-container',
    data:{
        searchQuery: '',
        statusFilter: '',
        products: rawProducts,
    },
    methods:{
        statusChange: function(value){
            this.statusFilter = value;
        },

        // Update local product data depending on the status
        updateProduct: function(updateDetails){
            // Listener to 'update-product' on status-table component

            switch (updateDetails.status) {
                case 'reserved':
                    var index = updateDetails.index;

                    // Just update the product if it is not of type 'semen'
                    if(updateDetails.type !== 'semen'){
                        this.products[index].status = 'reserved';
                        this.products[index].status_time = updateDetails.statusTime;
                        this.products[index].quantity = 0;
                        this.products[index].reservation_id = updateDetails.reservationId;
                        this.products[index].customer_id = updateDetails.customerId;
                        this.products[index].customer_name = updateDetails.customerName;
                        this.products[index].expiration_date = updateDetails.expirationDate;
                    }

                    // Add another entry to the product list if of type 'semen'
                    else{
                        var baseProduct = this.products[index];

                        this.products.push(
                            {
                                'uuid': updateDetails.uuid,
                                'id': baseProduct.id,
                                'reservation_id': updateDetails.reservationId,
                                'img_path': baseProduct.img_path,
                                'breeder_id': baseProduct.breeder_id,
                                'farm_province': baseProduct.farm_province,
                                'name': baseProduct.name,
                                'type': baseProduct.type,
                                'age': baseProduct.age,
                                'breed': baseProduct.breed,
                                'img_path': baseProduct.img_path,
                                'quantity': updateDetails.quantity,
                                'adg': baseProduct.adg,
                                'fcr': baseProduct.fcr,
                                'bft': baseProduct.bft,
                                'status': 'reserved',
                                'status_time': updateDetails.statusTime,
                                'customer_id': updateDetails.customerId,
                                'customer_name': updateDetails.customerName,
                                'date_needed': updateDetails.dateNeeded,
                                'special_request': updateDetails.specialRequest,
                                'expiration_date': updateDetails.expirationDate
                            }
                        );

                        // If after reservation, the product has been put to status 'displayed'
                        // due to zero customers requesting it the parent product
                        // display should be removed in the UI component
                        if(updateDetails.removeParentProductDisplay) this.products.splice(index,1);
                    }

                    break;

                case 'on_delivery':
                    var index = updateDetails.index;
                    this.products[index].status = 'on_delivery';
                    this.products[index].status_time = updateDetails.statusTime;

                    break;

                case 'paid':
                    var index = updateDetails.index;
                    this.products[index].status = 'paid';
                    this.products[index].status_time = updateDetails.statusTime;

                    break;

                case 'sold':
                    var index = updateDetails.index;
                    this.products[index].status = 'sold';
                    this.products[index].status_time = updateDetails.statusTime;

                    break;

                default: break;
            }
        }
    },
    created: function(){
        // If parameters are found parse it for the statusFilter data
        if(location.search){
            var status = location.search.slice(1).split('=');
            this.statusFilter = status[1];
        }
    }
});
