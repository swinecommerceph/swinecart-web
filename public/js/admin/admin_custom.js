// Admin page custom javascripts
$(document).ready(function(){
    var carousel_interval = 5000;

    // Modal trigger for admin
    $('.modal-trigger').modal({
        dismissible: true, // Modal can be dismissed by clicking outside of the modal
        opacity: .5, // Opacity of modal background
        inDuration: 300, // Transition in duration
        outDuration: 200, // Transition out duration
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
        fullWidth: true
    });

    setInterval(function(){
        $('.carousel').carousel('next');
    }, carousel_interval);


    $('#menu').pushpin({
        top:55, offset: 60
    });

    $('select').material_select();

    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 20, // Creates a dropdown of 15 years to control year
        format: 'yyyy-mm-dd'
    });

    $('#this_user_information_trigger').click(function(e){
        e.preventDefault();
        admin_layout.show_admin_information();
    });


    $('#breeder_radio').click(function() {
        $('#farm_name').removeAttr("disabled");
        $('#accredit_num').removeAttr("disabled");
    });

    $('#spectator_radio').click(function() {
        $('#farm_name').attr("disabled", "disabled");
        $('#accredit_num').attr("disabled", "disabled");
    });


});
