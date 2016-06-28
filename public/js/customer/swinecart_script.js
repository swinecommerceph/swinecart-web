$(document).ready(function(){

    // Delete item from Swine Cart
    $('body').on('click', '.delete-from-swinecart' ,function(e){
      e.preventDefault();
      swinecart.delete($(this).parents('form'), $(this).parents('li').first());
    });

    $('body').on('click', '.request-product' ,function(e){
      e.preventDefault();
      swinecart.request($(this).parents('form'), $(this).parents('div').siblings('status'));
    });

    $('.delivery').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-delivery',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).nextAll().children('i').html('star_border');
    });

    $('.delivery').hover(function(){
      var state = document.getElementById("delivery");
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).nextAll().children('i').html('star_border');
    }, function(e){
      $(this).parents().replaceWith(state);
    });

    $('.transaction').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-transaction',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).nextAll().children('i').html('star_border');
    });

    $('.productQuality').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-productQuality',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).nextAll().children('i').html('star_border');
    });

    $('.afterSales').click(function(e){
      e.preventDefault();
      $('#submit-rate').parents('form').attr('data-afterSales',$(this).attr('data-value'));
      $(this).prevAll().andSelf().children('i').html('star');
      $(this).nextAll().children('i').html('star_border');
    });

    $('.rate-button').click(function(e){
      $('#submit-rate').parents('form').attr('data-customer-id',$(this).attr('data-customer-id'));
      $('#submit-rate').parents('form').attr('data-breeder-id',$(this).attr('data-breeder-id'));
      $('#submit-rate').siblings('input').val($(this).attr('data-token'));
      $('#rate').openModal();
    });

    $('#submit-rate').click(function(e){
      swinecart.rate($(this).parents('form'), $('#comment').val());
    });
});
