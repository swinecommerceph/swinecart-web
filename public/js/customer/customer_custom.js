$(document).ready(function(){

    // Initialization for select tags
    $('select').material_select();

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

    // Delete item from Swine Cart
    $('body').on('click', '#cart-dropdown .delete-from-swinecart' ,function(e){
        e.preventDefault();
        swinecart.delete($(this).parents('form'), $(this).parents('li').first());
    });

});
