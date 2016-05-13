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

    // Show search bar
    $(window).scroll(function(){
        if ($(this).scrollTop() >= 170) $('#search-field').fadeIn(200);
        else $('#search-field').fadeOut(200);
    });

    // Redirect to designated link upon checkbox and select value change
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
