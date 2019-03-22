'use strict';

var config = {
    host_url: window.hostUrl,
    authUser_url: '/home',
    customer_url: '/customer/home',
    breeder_url: '/breeder/home',
    customerNotifications_url: '/customer/notifications',
    breederNotifications_url: '/breeder/notifications',
    viewProducts_url: '/customer/view-products',
    manageProducts_url: '/breeder/products',
    swineCart_url: '/customer/swine-cart',
    breederLogo_url: '/breeder/edit-profile/logo-upload',
    productMedia_url: '/breeder/products/media',
    productSummary_url: '/breeder/products/product-summary',
    manageSelected_url: '/breeder/products/manage-selected',
    dashboard_url: '/breeder/dashboard',
    images_path: '/images',
    videos_path: '/videos',
    delete_user: '/admin/home/delete',
    block_user: '/admin/home/block',
    approve_user: 'home/approve',
    reject_user: 'home/reject',
    search_user: 'home/search',
    all_user: 'home/userlist',
    form: 'registration',
    spectator_url: '/spectator',
    admin_url: '/admin',
    productImages_path: '/images/product',
    productVideos_path: '/videos/product',
    pubsubWSServer: 'ws://' + window.location.hostname + '/pusher',
    pubsubWSSServer: 'wss://' + window.location.hostname + '/pusher',
    preloader_progress: $('#preloader-progress'),
    preloader_circular: $('#preloader-circular')

};

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
	$('#page-login button[type="submit"]').click(function(e){
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

	// Scroll animation on learn more
	$('#learn-more-breeder').click(function(){
		$('html,body').animate({
        	scrollTop: $("#breeder-features").offset().top - $('nav').height()
		}, 'slow');
	});

	$('#learn-more-customer').click(function(){
		$('html,body').animate({
        	scrollTop: $("#customer-features").offset().top - $('nav').height()
		}, 'slow');
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

//# sourceMappingURL=siteCustom.js.map
