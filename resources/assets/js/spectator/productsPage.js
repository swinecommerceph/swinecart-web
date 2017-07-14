'use strict';


var vm = new Vue({
    el: '#app-products',
    data: {
        toggled: false,
    },

    // call the function to get the product data using ajax
    methods: {
        displayProductModal: function(id){
            products.fetchProductDetails(id);
        }
    }
});
