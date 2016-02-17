/*
 * Profile-related scripts
 */

$(document).ready(function(){
    /*
     *	Update Profile specific
     */

    // Add another Farm Address
    $("#add-farm").on('click',function(e){
        e.preventDefault();

        // Count how many current Farm Addresses are available
        var i = $('#farm-address-body .add-farm').length+1;

        // Count how many current Farm Addresses will be added
        var j = $('#create-profile .add-farm').length+1;

        // Check if there is a #submit-button button and remove it
        if($('#create-profile').has('#submit-button')) $('#submit-button').remove().fadeOut('slow');

        // Append inputs for another Farm Address in the Farm Information form
        $('<div class="row add-farm" style="display:none;">'+
         '<div class="col s10 offset-s1">'+
             '<div id="farm-'+i+'" class="card-panel hoverable">'+
                 '<h5 class="center-align"> New Farm '+i+' </h5>'+

                 '<div class="row">'+
                 //  Farm Address: Name
                     '<div class="input-field col s10 push-s1">'+
                         '<input name="farmAddress[][name]" type="text">'+
                         '<label for="farmAddress[][name]">Name</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                 // Farm Address: Street Address
                     '<div class="input-field col s10 push-s1">'+
                         '<input name="farmAddress[][addressLine1]" type="text">'+
                         '<label for="farmAddress[][addressLine1]">Address Line 1* : Street, Road, Subdivision</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                 // Farm Address: Address Line 2
                     '<div class="input-field col s10 push-s1">'+
                         '<input name="farmAddress[][addressLine2]" type="text">'+
                         '<label for="farmAddress[][addressLine2]">Address Line 2* : Barangay, Town, City</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                     // Farm Address: Province
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress[][province]" type="text">'+
                         '<label for="farmAddress[][province]">Province*</label>'+
                     '</div>'+

                     // Farm Address: Zip Code
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress[][zipCode]" type="text">'+
                         '<label for="farmAddress[][zipCode]">Postal/ZIP Code*</label>'+
                     '</div>'+
                 '</div>'+


                 '<div class="row">'+
                     // Farm Type
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress[][farmType]" type="text">'+
                         '<label for="farmAddress[][farmType]">Farm Type*</label>'+
                     '</div>'+
                 '</div>'+


                 '<div class="row">'+
                     // Farm Landline
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress[][landline]" type="text">'+
                         '<label for="farmAddress[][landline]">Landline</label>'+
                     '</div>'+

                     // Farm Mobile
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress[][mobile]" type="text">'+
                         '<label for="farmAddress[][mobile]">Mobile*</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                   '<div class="col s10 offset-s1 content-section">'+
                       '<div class="col right submit-button-field">'+
                           '<button id="submit-button" class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped" data-position="left" data-delay="50" data-tooltip="Submit added farm/s">'+
                               '<i class="material-icons">send</i>'+
                           '</button>'+
                       '</div>'+
                   '</div>'+
                 '</div>'+
                 '<div class="row ">'+
                     '<div class="col offset-s10">'+
                         '<a href="#" class="btn-floating btn-medium waves-effect waves-light deep-orange tooltipped remove-farm on-create-farm" data-position="left" data-delay="50" data-tooltip="Remove this Farm">'+
                             '<i class="material-icons">remove</i>'+
                         '</a>'+
                     '</div>'+
                 '</div>'+
             '</div>'+
         '</div>'+
        '</div>').appendTo('#create-profile').fadeIn('slow');

        location.href = '#farm-'+i;
        $(".remove-farm, #submit-button").tooltip({delay:50});
        Materialize.toast('Farm Information added', 2000);
    });

    // Edit on Personal/Farm Information
    $('.edit-button').click(function(e){
        e.preventDefault();
        var edit_button = $(this);
        var cancel_button = edit_button.parents('.content-section').find('.cancel-button');
        var parent_form = edit_button.parents('form');

        edit_button.prop('disabled', true);
        edit_button.tooltip('remove');

        // If button is for editing the fields
        if(edit_button.attr('data-tooltip') == 'Edit')profile.edit(parent_form, edit_button, cancel_button);

        // If button is ready for submission
        else profile.update(parent_form, edit_button, cancel_button);

    });

    // Cancel on Editing a Personal/Farm Information
    $('.cancel-button').click(function(e){
        e.preventDefault();
        var cancel_button = $(this);
        var edit_button = cancel_button.parents('.content-section').find('.edit-button');
        var parent_form = cancel_button.parents('form');

        profile.cancel(parent_form, edit_button, cancel_button);
    });

    // Remove an instance of the current farm information/s
    $('.remove-farm').click(function(e){
        e.preventDefault();
        var remove_button = $(this);
        var parent_form = remove_button.parents('form');
        var row = remove_button.parents('.add-farm');

        //  Check if there are more than 1 farm information to remove
        if($('#farm-address-body').find('.delete-farm .remove-farm').length > 1){
            $('#confirmation-modal').openModal();
            $('#confirm-remove').click(function(e){
                e.preventDefault();
                profile.remove(parent_form,row);
            });
            location.href = '#';
        }
        else Materialize.toast('At least 1 Farm information required', 4000, 'orange accent-2');
    });

    // Remove an instance of added farm information
    $('body').on('click', '.remove-farm' ,function(e){
        e.preventDefault();
        var remove_button = $(this);
        remove_button.tooltip('remove');

        // Check if remove_button is on creating farm information
        if(remove_button.hasClass('on-create-farm')){
            var prev_farm, prevSubmitButtonField;
            var row = remove_button.parents('.add-farm');

            row.remove();
            prev_farm = $('#farm-address-body').find('.add-farm').last();
            prevSubmitButtonField = prev_farm.find(".submit-button-field");

            if (prevSubmitButtonField){
                $( '<button class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped submit-button" data-position="left" data-delay="50" data-tooltip="Submit added farms">'+
                     '<i class="material-icons">send</i>'+
                 '</button>').appendTo(prevSubmitButtonField).fadeIn('slow');
            }

            location.href = '#'+prev_farm.attr('id');
            Materialize.toast('Farm Information removed', 2000);
        }
    });

    // Submit added farm information
    $('body').on('click', '#submit-button' ,function(e){
        e.preventDefault();
        profile.add($('#create-profile'));
    });

    // Preloader
    $('<div class="valign-wrapper preloader-overlay">'+
        '<div class="preloader-wrapper big active center-align">'+
            '<div class="spinner-layer spinner-blue">'+
                '<div class="circle-clipper left">'+
                  '<div class="circle"></div>'+
                '</div><div class="gap-patch">'+
                  '<div class="circle"></div>'+
                '</div><div class="circle-clipper right">'+
                  '<div class="circle"></div>'+
                '</div>'+
            '</div>'+
            '<div class="spinner-layer spinner-red">'+
                '<div class="circle-clipper left">'+
                  '<div class="circle"></div>'+
                '</div><div class="gap-patch">'+
                  '<div class="circle"></div>'+
                '</div><div class="circle-clipper right">'+
                  '<div class="circle"></div>'+
                '</div>'+
            '</div>'+
            '<div class="spinner-layer spinner-green">'+
                '<div class="circle-clipper left">'+
                  '<div class="circle"></div>'+
                '</div><div class="gap-patch">'+
                  '<div class="circle"></div>'+
                '</div><div class="circle-clipper right">'+
                  '<div class="circle"></div>'+
                '</div>'+
            '</div>'+
        '</div>'+
    '</div>')
    .css({
        position: "absolute", width: "100%", height: "100%", top: 0,left: 0, background: "rgba(255,255,255,0.8)", display:"none"
    })
    .appendTo($("#personal-information").css("position", "relative"));

});
