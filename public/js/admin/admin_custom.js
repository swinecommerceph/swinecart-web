// Admin page custom javascripts
$(document).ready(function(){
    var carousel_interval = 5000;


    // Modal trigger for admin
    $('.modal-trigger').leanModal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        in_duration: 300, // Transition in duration
        out_duration: 200, // Transition out duration
    });

    $('body').on('mouseenter', ".collection-item", function() {
        $(this).css('background-color', '#b3e6ca');
    }).on('mouseleave', ".collection-item", function() {
        $(this).css('background-color', 'white');
    });

    $('body').on('mouseenter', ".collapsible-header", function() {
        $(this).css('background-color', '#b3e6ca');
    }).on('mouseleave', ".collapsible-header", function() {
        $(this).css('background-color', 'white');
    });

    $('body').on('mouseenter', ".collapsible-body", function() {
        $(this).css('background-color', '#b3e6ca');
    }).on('mouseleave', ".collapsible-body", function() {
        $(this).css('background-color', 'white');
    });

    $('.carousel.carousel-slider').carousel({
        full_width: true
    });

    setInterval(function(){
     		$('.carousel').carousel('next');
      }, carousel_interval);


     $('#menu').pushpin({
         top:55, offset: 60
     });


  $('select').material_select();


});
