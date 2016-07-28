$(document).ready(function(){

    // Hide certain elements

	// User
	$(".dropdown-button").dropdown({
        hover:true,
        constrainwidth:false,
        gutter: 0,
        belowOrigin: true,
        alignment: 'right'
    });

	$('.modal-trigger').leanModal();

    // Initialization for Sliders
    $('.slider').slider({full_width: true});

    // Initialization for Carousels
    $('.carousel').carousel();

    // Initialization for Material Boxes
    $('.materialboxed').materialbox();

    // Initialization for tooltips
    $(".tooltipped").tooltip({delay:50});

    // Initialization for Select tags
    $('select').material_select();
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

    var provinces = [
        // Negros Island Rregion
        'Negros Occidental',
        'Negros Oriental',
        // Cordillera Administrative Region
        'Mountain Province',
        'Ifugao',
        'Benguet',
        'Abra',
        'Apayao',
        'Kalinga',
        // Region I
        'La Union',
        'Ilocos Norte',
        'Ilocos Sur',
        'Pangasinan',
        // Region II
        'Nueva Vizcaya',
        'Cagayan',
        'Isabela',
        'Quirino',
        'Batanes',
        // Region III
        'Bataan',
        'Zambales',
        'Tarlac',
        'Pampanga',
        'Bulacan',
        'Nueva Ecija',
        'Aurora',
        // Region IV-A
        'Rizal',
        'Cavite',
        'Laguna',
        'Batangas',
        'Quezon',
        // Region IV-B
        'Occidental Mindoro',
        'Oriental Mindoro',
        'Romblon',
        'Palawan',
        'Marinduque',
        // Region V
        'Catanduanes',
        'Camarines Norte',
        'Sorsogon',
        'Albay',
        'Masbate',
        'Camarines Sur',
        // Region VI
        'Capiz',
        'Aklan',
        'Antique',
        'Iloilo',
        'Guimaras',
        // Region VII
        'Cebu',
        'Bohol',
        'Siquijor',
        // Region VIII
        'Southern Leyte',
        'Eastern Samar',
        'Northern Samar',
        'Western Samar',
        'Leyte',
        'Biliran',
        // Region IX
        'Zamboanga Sibugay',
        'Zamboanga del Norte',
        'Zamboanga del Sur',
        // Region X
        'Misamis Occidental',
        'Bukidnon',
        'Lanao del Norte',
        'Misamis Oriental',
        'Camiguin',
        // Region XI
        'Davao Oriental',
        'Compostela Valley',
        'Davao del Sur',
        'Davao Occidental',
        'Davao del Norte',
        // Region XII
        'South Cotabato',
        'Sultan Kudarat',
        'North Cotabato',
        'Sarangani',
        // Region XIII
        'Agusan del Norte',
        'Agusan del Sur',
        'Surigao del Sur',
        'Surigao del Norte',
        'Dinagat Islands',
        // ARMM
        'Tawi-tawi',
        'Basilan',
        'Sulu',
        'Maguindanao',
        'Lanao del Sur'
    ]

});
