$(document).ready(function(){

    var chips = '';

    // Find all ticked checkboxes for producing chips
    if($('input:checked').length > 0) chips += 'Filtered by: ';
    $('input:checked').each(function(){
        if($(this).attr('data-type')){
            chips +=
                '<div class="chip" style="text-transform:capitalize;">'+
                    'Type: '+$(this).attr('data-type')+
                    '<i class="material-icons" data-checkbox-id="'+$(this).attr('id')+'">close</i>'+
                '</div> ';
        }
        else if ($(this).attr('data-breed')) {
            chips +=
                '<div class="chip" style="text-transform:capitalize;">'+
                    'Breed: '+$(this).attr('data-breed')+
                    '<i class="material-icons" data-checkbox-id="'+$(this).attr('id')+'">close</i>'+
                '</div> ';
        }

    });

    // Append chip to #chip-container
    $('#chip-container').append(chips);

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
        if ($(this).scrollTop() >= 170) $('#search-field').fadeIn(200);
        else $('#search-field').fadeOut(200);
    });

    // Redirect to designated link upon checkbox value change
    $("#filter-container input[type=checkbox], select").change(function(){
        filter.apply();
    });

    // Redirect to designated link upon removing chips
    $(".chip i.material-icons").click(function(e){
        e.preventDefault();
        $("#"+$(this).attr('data-checkbox-id')).prop('checked', false);
        filter.apply();
    });

});
