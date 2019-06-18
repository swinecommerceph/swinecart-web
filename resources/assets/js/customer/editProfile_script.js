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
                         '<input name="farmAddress['+j+'][name]" id="farmAddress['+j+'][name]" type="text">'+
                         '<label for="farmAddress['+j+'][name]">Name</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                 // Farm Address: Street Address
                     '<div class="input-field col s10 push-s1">'+
                         '<input name="farmAddress['+j+'][addressLine1]" id="farmAddress['+j+'][addressLine1]" type="text">'+
                         '<label for="farmAddress['+j+'][addressLine1]">Address Line 1* : Street, Road, Subdivision</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                 // Farm Address: Address Line 2
                     '<div class="input-field col s10 push-s1">'+
                         '<input name="farmAddress['+j+'][addressLine2]" id="farmAddress['+j+'][addressLine2]" type="text">'+
                         '<label for="farmAddress['+j+'][addressLine2]">Address Line 2* : Barangay, Town, City</label>'+
                     '</div>'+
                 '</div>'+

                 '<div class="row">'+
                     // Farm Address: Province
                     '<div class="input-field col s5 push-s1">'+
                         profile.select_province(j) +
                         '<label>Province*</label>'+
                     '</div>'+

                     // Farm Address: Zip Code
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress['+j+'][zipCode]" id="farmAddress['+j+'][zipCode]" type="text">'+
                         '<label for="farmAddress['+j+'][zipCode]">Postal/ZIP Code*</label>'+
                     '</div>'+
                 '</div>'+


                 '<div class="row">'+
                     // Farm Type
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress['+j+'][farmType]" id="farmAddress['+j+'][farmType]" type="text">'+
                         '<label for="farmAddress['+j+'][farmType]">Farm Type*</label>'+
                     '</div>'+
                 '</div>'+


                 '<div class="row">'+
                     // Farm Landline
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress['+j+'][landline]" id="farmAddress['+j+'][landline]" type="text">'+
                         '<label for="farmAddress['+j+'][landline]">Landline</label>'+
                     '</div>'+

                     // Farm Mobile
                     '<div class="input-field col s5 push-s1">'+
                         '<input name="farmAddress['+j+'][mobile]" id="farmAddress['+j+'][mobile]" type="text">'+
                         '<label for="farmAddress['+j+'][mobile]">Mobile*</label>'+
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
                         '<a href="#" class="btn-floating btn-medium waves-effect waves-light grey tooltipped remove-farm on-create-farm" data-position="left" data-delay="50" data-tooltip="Remove New Farm '+i+'">'+
                             '<i class="material-icons">remove</i>'+
                         '</a>'+
                     '</div>'+
                 '</div>'+
             '</div>'+
         '</div>'+
        '</div>').appendTo('#create-profile').fadeIn('slow');

        $('#create-profile select').material_select();
        location.href = '#farm-'+i;
        $(".remove-farm, #submit-button").tooltip({delay:50});
        Materialize.toast('New Farm Information added', 2000);
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
            $('#confirmation-modal').modal('open');
            $('#confirm-remove').click(function(e){
                e.preventDefault();
                profile.remove(parent_form,row);
            });
            location.href = '#';
        }
        else Materialize.toast('At least 1 Farm information required', 2500, 'orange accent-2');
    });

    // Remove an instance of added farm information
    $('body').on('click', '.remove-farm' ,function(e){
        e.preventDefault();
        var remove_button = $(this);
        remove_button.tooltip('remove');

        // Check if remove_button is on creating farm information
        if(remove_button.hasClass('on-create-farm')){
            var prev_farm, prev_submit_button_field;
            var row = remove_button.parents('.add-farm');
            var name = row.find('h5').html();

            row.remove().done;
            prev_farm = $('#farm-address-body').find('.add-farm').last();
            prev_submit_button_field = prev_farm.find(".submit-button-field");

            if (prev_submit_button_field){
                $( '<button class="btn-floating btn-medium waves-effect waves-light teal darken-1 tooltipped submit-button" data-position="left" data-delay="50" data-tooltip="Submit added farms">'+
                     '<i class="material-icons">send</i>'+
                 '</button>').appendTo(prev_submit_button_field).fadeIn('slow');
                $('.tooltipped').tooltip({delay:50});
            }

            location.href = '#'+prev_farm.find('.card-panel').attr('id');
            Materialize.toast(name+' Information removed', 2000);
        }
    });

    // for enabling select tags
    $("select").material_select();

    // Same address as office information feature
    $(".same-address-checker").change(function (e) {
      e.preventDefault();

      var farm_specific = $(this).attr('class').split(' ')[1];
      farm_specific = "#" + farm_specific;

      var address_address1 = $("#address_addressLine1").val();
      var address_address2 = $("#address_addressLine2").val();
      var address_province = $("#address_province").val();
      var address_postal_zip_code = $("#address_zipCode").val();
      var address_landline = $("#landline").val();
      var address_mobile = $("#mobile").val();

      if ($(this).is(":checked")) {

        // set values
        $(farm_specific + "-addressLine1").val(address_address1).addClass('input-show-hide');
        $(farm_specific + "-addressLine2").val(address_address2).addClass('input-show-hide');
        $(farm_specific).find('input[class=select-dropdown]').val(address_province).addClass('input-show-hide');
        $(farm_specific + "-zipCode").val(address_postal_zip_code).addClass('input-show-hide');
        $(farm_specific + "-landline").val(address_landline).addClass('input-show-hide');
        $(farm_specific + "-mobile").val(address_mobile).addClass('input-show-hide');
      }
      else {
        $(farm_specific + "-addressLine1").val('').removeClass('input-show-hide');
        $(farm_specific + "-addressLine2").val('').removeClass('input-show-hide');
        // $(farm_specific).find('input[class=select-dropdown]').val('Abra').removeClass('input-show-hide');
        $(farm_specific + "-zipCode").val('').removeClass('input-show-hide');
        $(farm_specific + "-farmType").val('').removeClass('input-show-hide');
        $(farm_specific + "-landline").val('').removeClass('input-show-hide');
        $(farm_specific + "-mobile").val('').removeClass('input-show-hide');
      }
    });

});
