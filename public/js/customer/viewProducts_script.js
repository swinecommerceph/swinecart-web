$(document).ready(function(){

    // For Search bar Pushpin
    // $('#search-field').pushpin({
    //     top: $('#search-container').offset().top,
    //     offset: 100
    // });

    // For Filter Container Pushpin
    $('#filter-container #collapsible-container').pushpin({
        top: $('#filter-container').offset().top,
        offset: 135
    });

    // Change left and right icons in Pagination
    if($('.pagination li:first-child a').length == 1)
        $('.pagination li:first-child a').html('<i class="material-icons">chevron_left</i>');
    else $('.pagination li:first-child').html('<a href="#!"><i class="material-icons">chevron_left</i></a>');

    if($('.pagination li:last-child a').length == 1)
        $('.pagination li:last-child a').html('<i class="material-icons">chevron_right</i>');
    else $('.pagination li:last-child').html('<a href="#!"><i class="material-icons">chevron_right</i></a>');

    // Put waves-effect on appropriate lis
    $('.pagination li').each(function(){
        if(!$(this).hasClass('disabled') && !$(this).hasClass('active')) $(this).addClass('waves-effect');
    });

    // Show search bar
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 220) $('#search-field').fadeIn(200);
        else $('#search-field').fadeOut(200);
    });

    // Redirect to designated link
    $("#filter-container input[type=checkbox], select").change(function(){
        filter.apply();
    });

    // Add product to Swine Cart
    $(".add-to-cart").click(function(e){
        e.preventDefault();
        console.log($(this).attr('data-product-id'));
    })

});
