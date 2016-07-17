$(document).ready(function(){

    // Delete item from Swine Cart
    $('body').on('click', '.delete-from-swinecart' ,function(e){
      e.preventDefault();
      swinecart.delete($(this).parents('form'), $(this).parents('li').first());
    });

    $('body').on('click', '.request-product' ,function(e){
      e.preventDefault();
      swinecart.request($(this).parents('form'), $(this).parents('li').first());
    });

    //Rating system
    $('.delivery').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-delivery',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).prevAll().andSelf().children('i').removeClass('grey-text text-darken-2').addClass('yellow-text text-darken-1');
      $(this).nextAll().children('i').html('star_border');
      $(this).nextAll().children('i').removeClass('yellow-text text-darken-1').addClass('grey-text text-darken-2');
    });

    // $('.delivery').hover(function(){
    //   var state = document.getElementById("delivery");
    //   $(this).prevAll().andSelf().children('i').html('star');
    //   $(this).nextAll().children('i').html('star_border');
    // }, function(e){
    //   // $(this).parents().replaceWith(state);
    // });

    $('.transaction').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-transaction',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).prevAll().andSelf().children('i').removeClass('grey-text text-darken-2').addClass('yellow-text text-darken-1');
      $(this).nextAll().children('i').html('star_border');
      $(this).nextAll().children('i').removeClass('yellow-text text-darken-1').addClass('grey-text text-darken-2');
    });

    $('.productQuality').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-productQuality',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).prevAll().andSelf().children('i').removeClass('grey-text text-darken-2').addClass('yellow-text text-darken-1');
      $(this).nextAll().children('i').html('star_border');
      $(this).nextAll().children('i').removeClass('yellow-text text-darken-1').addClass('grey-text text-darken-2');
    });

    $('.rate-button').click(function(e){
      $('#submit-rate').parents('form').attr('data-customer-id',$(this).attr('data-customer-id'));
      $('#submit-rate').parents('form').attr('data-breeder-id',$(this).attr('data-breeder-id'));
      $('#submit-rate').parents('form').attr('data-product-id',$(this).attr('data-product-id'));
      $('#submit-rate').parents('form').attr('data-status',$(this).attr('data-status'));
      $('#submit-rate').siblings('input').val($(this).attr('data-token'));
      $('#rate').openModal({});
    });

    $('.anchor-title').click(function(e){
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.collection-header').children('.row').children('.product-name').html($(this).children('span').html());
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.collection-header').children('.row').children('.product-farm').html($(this).attr('data-breeder'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-type').children('span.type').html($(this).attr('data-type'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-type').children('span.breed').html($(this).attr('data-breed'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-age').children('span').html($(this).attr('data-age'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-adg').children('span').html($(this).attr('data-adg'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-fcr').children('span').html($(this).attr('data-fcr'));
      $('#info-modal').children('.modal-content').children('.row').children('.cart-details').children('.collection').children('.product-backfat_thickness').children('span').html($(this).attr('data-backfat-thickness'));
      $('#info-modal').children('.modal-content').children('.row').children('.image').children('.row').children('.other-details').children('.card').children('.card-content').children('p').html($(this).attr('data-other-details'));
      $('#modal-img').attr('src',$(this).attr('data-imgpath'));
      $('#info-modal').openModal();
    });

    $('#submit-rate').click(function(e){
      swinecart.rate($(this).parents('form'), $('#comment').val());
      swinecart.record($(this).parents('form'));
    });

    // $('.swine-cart-item').hover(function(){
    //   $(this).addClass('teal');
    // })
});
