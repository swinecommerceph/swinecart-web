'use strict';

var dashboard = {

    // Functions related to product_status
    product_status : {

    }
};

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
                customerName: ''
            },
            productInfoModal:{
                productId: 0,
                productName: '',
                productIndex: 0,
                customerName: ''
            }
        };
    },
    methods:{
        sortBy: function(key){
            this.sortKey = key;
            this.sortOrders[key] = this.sortOrders[key] * -1;
        },

        searchProduct : function(productId){
            // Return index of productId to find
            for(var i = 0; i < this.products.length; i++) {
                if(this.products[i].id === productId) return i;
            }
        },

        getProductRequests: function(productId){
            var index = this.searchProduct(productId);

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

        confirmReservation: function(customerId, customerName){
            // Initialize productReserve local data to be
            // used for the confirmation modal
            this.productReserve.customerId = customerId;
            this.productReserve.customerName = customerName;
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

        setUpConfirmation: function(productId, status){
            var index = this.searchProduct(productId);

            // Initialize productDeliver local data to be
            // used for the confirmation modal
            this.productInfoModal.productId = this.products[index].id;
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

    }
});

// Connect Materialize's select to data binding in VueJS
Vue.directive("select", {
    "twoWay": true,

    "bind": function () {
        $(this.el).material_select();

        var self = this;

        $(this.el).on('change', function() {
            self.set($(self.el).val());
        });
    },

    update: function (newValue, oldValue) {
        $(this.el).val(newValue);
    },

    "unbind": function () {
        $(this.el).material_select('destroy');
    }
});

new Vue({
    el: '#product-status-container',
    data:{
        searchQuery: '',
        statusFilter: ''
    },
    created: function(){
        // If parameters are found parse it for the statusFilter data
        if(location.search){
            var status = location.search.slice(1).split('=');
            this.statusFilter = status[1];
        }
    }
});
