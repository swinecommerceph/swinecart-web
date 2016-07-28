$(document).ready(function(){

    // Initialization for number of items in Swine Cart
    swinecart.get_quantity();

    // Add product to Swine Cart
    $(".add-to-cart").click(function(e){
        e.preventDefault();
        swinecart.add($(this).parents('form'));
    })

    // Get items from Swine Cart
    $('#cart-icon').hover(function(e){
        e.preventDefault();
        if($(this).hasClass('active')) swinecart.get_items();
    });

});
