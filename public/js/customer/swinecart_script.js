$(document).ready(function(){

    // Delete item from Swine Cart
    $('body').on('click', '.delete-from-swinecart' ,function(e){
        e.preventDefault();
        swinecart.delete($(this).parents('form'), $(this).parents('li').first());
    });

    $('body').on('click', '.request-product' ,function(e){
        e.preventDefault();
        swinecart.request($(this).parents('form'));
    });

});
