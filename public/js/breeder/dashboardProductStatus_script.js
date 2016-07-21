$(document).ready(function(){

    // Initialize DataTable
    $('#product-status-table').DataTable({
        'autoWidth' : false
    });

    // Initialize dynamic select provdided by the DataTable
    $('select').material_select();

    // ------------- Request-related functions -------------
    // Get Customer requests of a certain Product
    $('.product-request-icon').click(function(e){
        e.preventDefault();

        // Change Title of the Modal
        $('#product-requests-modal .modal-content h4').html($(this).attr('data-product-name')+' Product Requests');
        dashboard.product_status.get_product_requests($(this).attr('data-product-id'), $(this).attr('data-product-name'));

    });

    // ------------- Reserve-related functions -------------
    // Reserve a Product to a Customer
    $('body').on('click', '.reserve-product-button' ,function(e){
        e.preventDefault();

        var reserve_button = $(this);
        // Change confirmation message of Reserve Product Confirmation Modal
        $('#reserve-product-confirmation-modal .modal-content p').html('Are you sure you want to reserve '+ $(this).attr('data-product-name') +' to '+ $(this).attr('data-customer-name'));
        $('#reserve-product-confirmation-modal').openModal();

        $('.confirm-reserve-button').click(function(){
            dashboard.product_status.reserve_product(reserve_button);
            $('#reserve-product-confirmation-modal').closeModal();
        });

    });

    // ------------- Delivery-related functions -------------
    $('.product-delivery-icon').click(function(e){
        e.preventDefault();

        var delivery_icon = $(this);
        // Change Title of the Modal
        $('#product-delivery-confirmation-modal .modal-content h4').html($(this).attr('data-product-name')+' Delivery Confirmation');
        $('#product-delivery-confirmation-modal').openModal();

        $('.confirm-delivery-button').click(function(){
            dashboard.product_status.product_delivery(delivery_icon);
            $('#product-delivery-confirmation-modal').closeModal();
        });

    });

    // ------------- Paying-related functions -------------
    $('.product-paid-icon').click(function(e){
        e.preventDefault();

        var paid_icon = $(this);
        // Change Title of the Modal
        $('#paid-product-confirmation-modal .modal-content h4').html($(this).attr('data-product-name')+' Pay Confirmation');
        $('#paid-product-confirmation-modal').openModal();

        $('.confirm-paid-button').click(function(){
            dashboard.product_status.product_paid(paid_icon);
            $('#paid-product-confirmation-modal').closeModal();
        });
    });
});
