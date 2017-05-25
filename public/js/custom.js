	$(document).ready(function(){

    // Hide certain elements
	$('.modal-trigger, .modal').modal({
		dismissible: false
	});

    // Initialization for Sliders
	$('.home-slider').slider({fullWidth: true});
    $('#homepage-slider').slider(
		{
			fullWidth: true,
			height: 500
		}
	);

    // Initialization for Carousels
    $('.carousel').carousel();

    // Initialization for Material Boxes
    $('.materialboxed').materialbox();

    // Initialization for tooltips
    $(".tooltipped").tooltip({delay:50});

    // Initialization for Select tags
    // $('select').material_select();
    $('select#other-breeds').material_select();

	// Disable buttons after submitting to prevent multiple requests
	$('button[type="submit"]').click(function(e){
        e.preventDefault();
        $(this).addClass('disabled');
        $(this).parents('form').submit();
    });

    $('.social-button').click(function(e){
        e.preventDefault();
        $(this).addClass('disabled');
        location.href = $(this).attr('href');
    });

    // Back to top button functionality
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 250) $('#back-to-top').fadeIn(200);
        else $('#back-to-top').fadeOut(200);
    });

	$(".button-collapse").sideNav();

    $('#back-to-top').click(function(){
        $('body,html').animate({
            scrollTop : 0
        }, 500);
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

});
