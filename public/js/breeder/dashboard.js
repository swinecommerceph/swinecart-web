'use strict';

var dashboard = {

    // Functions related to product_status
    product_status : {
        get_product_requests : function(product_id, product_name){

            // Do AJAX
            $.ajax({
                url: config.dashboard_url+'/product-status/retrieve-product-requests',
                type: "GET",
                cache: false,
                data: {
                    "product_id": product_id
                },
                success: function(data){
                    var items = '';

                    // Add customer data to modal
                    data.forEach(function(element){
                        var item = '<li class="collection-item">'+
                                '<div>'+
                                    element.customer_name +' from '+ element.province +
                                    '<a href="#!" class="secondary-content tooltipped reserve-product-button" data-position="top" data-delay="50" data-tooltip="Reserve product to '+ element.customer_name +'" data-product-id="'+ product_id +'" data-product-name="'+ product_name +'" data-customer-id="'+ element.customer_id +'" data-customer-name="'+ element.customer_name +'" data-token="'+ element._token +'"><i class="material-icons">add_to_photos</i></a>'+
                                    '<a href="#!" class="secondary-content tooltipped" data-position="top" data-delay="50" data-tooltip="Message '+ element.customer_name +'"><i class="material-icons">message</i></a>'+
                                '</div>'+
                            '<li>';
                        items += item;
                    });

                    $('#product-requests-modal').find('.modal-content .collection').html(items);
                    $('.tooltipped').tooltip({delay: 50});
                    $('#product-requests-modal').openModal();
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        },

        reserve_product: function(confirmation_button){

            // Do AJAX
            $.ajax({
                url: config.dashboard_url+'/product-status/reserve-product',
                type: "POST",
                cache: false,
                data: {
                    "_token": confirmation_button.attr('data-token'),
                    "product_id": confirmation_button.attr('data-product-id'),
                    "customer_id": confirmation_button.attr('data-customer-id')
                },
                success: function(data){
                    console.log(data[0]+ ': '+data[1]);
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        },

        product_delivery: function(delivery_icon){

            // Do AJAX
            $.ajax({
                url: config.dashboard_url+'/product-status/product-delivery',
                type: "POST",
                cache: false,
                data: {
                    "_token": delivery_icon.attr('data-token'),
                    "product_id": delivery_icon.attr('data-product-id'),
                },
                success: function(data){
                    console.log(data);
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        },

        product_paid: function(paid_icon){

            // Do AJAX
            $.ajax({
                url: config.dashboard_url+'/product-status/product-paid',
                type: "POST",
                cache: false,
                data: {
                    "_token": paid_icon.attr('data-token'),
                    "product_id": paid_icon.attr('data-product-id'),
                },
                success: function(data){
                    console.log(data);
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        }


    }
};
