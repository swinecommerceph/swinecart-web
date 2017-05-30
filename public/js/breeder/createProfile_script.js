/*
 * Profile-related scripts
 */

$(document).ready(function(){
    /*
     * Create Profile specific
     */

    var select_province = function(i){
        // Dynamically produce select element with options based on provinces
        var selectElement = '<select name="farmAddress['+i+'][province]">';

        for(var key in provinces){
         selectElement += '<option value="' + key + '">' + key + '</option>';
        }

        selectElement += '</select>';

        return selectElement;
    };

    $('#create-profile #farm-tab').addClass('disabled');

    // Next and previous buttons
    $('#create-profile #next').click(function(e){
        e.preventDefault();
        if($('#farm-tab').hasClass('disabled')) $('#farm-tab').removeClass('disabled');
        $('ul.tabs').tabs('select_tab','farm-information');
    });

    $('#create-profile #previous').click(function(e){
        e.preventDefault();
        $('ul.tabs').tabs('select_tab','personal-information');
    });

    // Remove inputs for the respective Farm Address in the Farm Information form
    $('body').on('click', '#remove-farm' ,function(e){
        e.preventDefault();
        $('#remove-farm').tooltip('remove');

        // Count how many current Farm Addresses are available
        var i = $("#farm-address-body .add-farm").length;

        var parent = $(this).parentsUntil('#farm-address-body','.add-farm');
        var prev_remove_button_field = parent.prev().find(".remove-button-field");
        parent.remove().fadeOut('slow');

        if (i > 1){
            $('<a href="#" id="remove-farm" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped" data-position="left" data-delay="50" data-tooltip="Remove this Farm" style="display:none">'+
                '<i class="material-icons">remove</i>'+
            '</a>').appendTo(prev_remove_button_field).fadeIn('slow');
        }

        // Redirect to specified section
        location.href = '#farm-'+(i-1);
        $("#remove-farm").tooltip({delay:50});
        Materialize.toast('Farm Information removed', 2000);

    });

    // Add another Farm Address
    $("#add-farm").on('click',function(e){
        e.preventDefault();

        // Check if there is a #remove-farm button and remove it
        if($('#farm-address-body').has('#remove-farm')) $('#remove-farm').remove().fadeOut('slow');

        // Count how many current Farm Addresses are available
        var i = $("#farm-address-body .add-farm").length+1;

        // Append inputs for another Farm Address in the Farm Information form
        $('<div class="row add-farm" style="display:none;">'+
        '<div class="col s10 offset-s1">'+
            '<div id="farm-'+i+'" class="card-panel hoverable">'+
                '<h5 class="center-align"> Farm '+i+' </h5>'+

                '<div class="row">'+
                //  Farm Address: Name
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][name]" type="text">'+
                        '<label for="farmAddress['+i+'][name]">Name</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                // Farm Address: Street Address
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine1]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine1]">Address Line 1* : Street, Road, Subdivision</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                // Farm Address: Address Line 2
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine2]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine2]">Address Line 2* : Barangay, Town, City</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                    // Farm Address: Province
                    '<div class="input-field col s5 push-s1">'+
                        select_province(i) +
                        '<label for="farmAddress['+i+'][province]">Province*</label>'+
                    '</div>'+

                    // Farm Address: Zip Code
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][zipCode]" type="text">'+
                        '<label for="farmAddress['+i+'][zipCode]">Postal/ZIP Code*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Type
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][farmType]" type="text">'+
                        '<label for="farmAddress['+i+'][farmType]">Farm Type*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Landline
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][landline]" type="text">'+
                        '<label for="farmAddress['+i+'][landline]">Landline</label>'+
                    '</div>'+

                    // Farm Mobile
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][mobile]" type="text">'+
                        '<label for="farmAddress['+i+'][mobile]">Mobile*</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row ">'+
                    '<div class="col offset-s10 remove-button-field">'+
                        '<a href="#" id="remove-farm" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped" data-position="left" data-delay="50" data-tooltip="Remove this Farm">'+
                            '<i class="material-icons">remove</i>'+
                        '</a>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
        '</div>').appendTo('#farm-address-body').fadeIn('slow');

        $('#farm-address-body select').material_select();
        // Redirect to specified section
        location.href = '#farm-'+i;
        $("#remove-farm").tooltip({delay:50});
        Materialize.toast('Farm Information added', 2000);
    });
});
