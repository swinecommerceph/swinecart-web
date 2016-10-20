'use strict';

Vue.component('status-table',{
    template: '#status-table-template',
    props: ['products', 'token', 'filterQuery', 'statusFilter'],
    data: function(){
        return {
            searchKey: '',
            sortKey: '',
            sortOrders:{
                name: 1,
                status: 1
            },
            productRequest:{
                productId: 0,
                productName: '',
                productIndex: 0,
                customers: []
            },
            productReserve:{
                productName: '',
                customerId: 0,
                customerName: '',
                requestQuantity: 0,
            },
            productInfoModal:{
                productId: 0,
                reservationId: 0,
                productName: '',
                productIndex: 0,
                customerName: ''
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

        getProductRequests: function(uuid){
            var index = this.searchProduct(uuid);

            // Set data values for initializing product-requests-modal
            this.productRequest.productId = this.products[index].id;
            this.productRequest.productName = this.products[index].name;
            this.productRequest.productIndex = index;

            // Do AJAX
            this.$http.get(
                config.dashboard_url+'/product-status/retrieve-product-requests',
                {
                    params: { product_id: this.products[index].id }
                }
            ).then(
                function(response){

                    // Store fetched data in local component data
                    this.productRequest.customers = response.json();
                    $('#product-requests-modal').openModal();

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

        confirmReservation: function(customerId, customerName, requestQuantity){
            // Initialize productReserve local data to be
            // used for the confirmation modal
            this.productReserve.customerId = customerId;
            this.productReserve.customerName = customerName;
            this.productReserve.requestQuantity = requestQuantity;
            $('#reserve-product-confirmation-modal').openModal();
        },

        reserveToCustomer: function(){
            // Do AJAX
            this.$http.patch(
                config.dashboard_url+'/product-status/update-status',
                {
                    _token: this.token,
                    product_id: this.productRequest.productId,
                    customer_id: this.productReserve.customerId,
                    request_quantity: this.productReserve.requestQuantity,
                    status: 'reserved'
                }
            ).then(
                function(response){
                    var responseBody = response.json();
                    var index = this.productRequest.productIndex;

                    $('#reserve-product-confirmation-modal').closeModal();
                    $('#product-requests-modal').closeModal();

                    // Set local data to new data from the response
                    // of the AJAX PATCH method
                    if(responseBody[0] === "success"){
                        this.products[index].status = "reserved";
                        this.products[index].customer_name = this.productReserve.customerName;
                        this.products[index].quantity = this.products[index].quantity - this.productReserve.requestQuantity;
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

            if(status === 'delivery') $('#product-delivery-confirmation-modal').openModal();
            else if(status === 'paid') $('#paid-product-confirmation-modal').openModal();
            else $('#sold-product-confirmation-modal').openModal();
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

                    $('#product-delivery-confirmation-modal').closeModal();

                    // Set status of the product to 'on_delivery'
                    // after successful product status change
                    this.products[index].status = "on_delivery";

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody === "OK") Materialize.toast(productName + ' on delivery to ' + customerName , 2500, 'green lighten-1');
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

                    $('#paid-product-confirmation-modal').closeModal();

                    // Set status of the product to 'paid' after
                    // successful product status change
                    this.products[index].status = "paid";

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody === "OK") Materialize.toast(productName + ' already paid by ' + customerName , 2500, 'green lighten-1');
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

                    $('#sold-product-confirmation-modal').closeModal();

                    // Set status of the product to 'sold' after
                    // successful product status change
                    this.products[index].status = "sold";

                    // Initialize/Update some DOM elements
                    this.$nextTick(function(){
                        if(responseBody === "OK") Materialize.toast(productName + ' already sold to ' + customerName , 2500, 'green lighten-1');
                        else Materialize.toast('Failed status change', 2500, 'orange accent-2');
                        $('.tooltipped').tooltip({delay:50});
                    });
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        }

    },
    filters: {
        capitalize: function(str){
            return str[0].toUpperCase() + str.slice(1);
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
        $('select').material_select();
        var self = this;
        $('select').on('change', function(){
            self.$emit('status-select',self.$refs.select.value);
        });
    }

});

new Vue({
    el: '#product-status-container',
    data:{
        searchQuery: '',
        statusFilter: ''
    },
    methods:{
        statusChange: function(value){
            this.statusFilter = value;
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
