'use strict';

var profile = {
    edit_farm_name: '',

    add: function(parent_form){
        config.preloader_progress.fadeIn();
        var farm_address = [];
        var data_values = {
            "_token" : parent_form.find('input[name=_token]').val()
        };

        farm_address.push({});
        $(parent_form).find('.add-farm').map(function (index) {
            var details = {
                'name': $(this).find('input[name="farmAddress[' + (index+1) + '][name]"]').val(),
                'addressLine1': $(this).find('input[name="farmAddress[' + (index+1) + '][addressLine1]"]').val(),
                'addressLine2': $(this).find('input[name="farmAddress[' + (index+1) + '][addressLine2]"]').val(),
                'province': $(this).find('select[name="farmAddress[' + (index+1) + '][province]"] option:checked').val(),
                'zipCode': $(this).find('input[name="farmAddress[' + (index+1) + '][zipCode]"]').val(),
                'farmType': $(this).find('input[name="farmAddress[' + (index+1) + '][farmType]"]').val(),
                'landline': $(this).find('input[name="farmAddress[' + (index+1) + '][landline]"]').val(),
                'mobile': $(this).find('input[name="farmAddress[' + (index+1) + '][mobile]"]').val()
            };
            farm_address.push(details);
        });

        data_values["farmAddress"] = farm_address;

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "POST",
            cache: false,
            data: data_values,
            success: function(data){
                var data = JSON.parse(data);
                Materialize.toast('Profile updated Success!', 1500, 'green lighten-1');
                window.setTimeout(function(){
                    config.preloader_progress.fadeOut();
                    location.reload(true);
                }, 1500);
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    },

    edit: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        $.when(
            parent_form.find('input, select').prop('disabled',false),
            parent_form.find('select').material_select()
        ).done(function(){
            profile.edit_farm_name = edit_button.attr('data-tooltip');
            // Edit tooltip animation to Done
            edit_button.attr('data-tooltip','Done');
            edit_button.attr('data-position','top');
            edit_button.html('<i class="material-icons">done</i>');
            $(".tooltipped").tooltip({delay:50});
            edit_button.prop('disabled', false);
            cancel_button.toggle();
            config.preloader_progress.fadeOut();
        });
    },

    update: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        var data_values;

        // Determine if form is of personal or farm information
        if(parent_form.attr('data-personal-id')){
            data_values = {
                "id": parent_form.attr('data-personal-id'),
                "address_addressLine1": parent_form.find('input[name=address_addressLine1]').val(),
                "address_addressLine2": parent_form.find('input[name=address_addressLine2]').val(),
                "address_province": parent_form.find('select[name=address_province] option:checked').val(),
                "address_zipCode": parent_form.find('input[name=address_zipCode]').val(),
                "landline": parent_form.find('input[name=landline]').val(),
                "mobile": parent_form.find('input[name=mobile]').val(),
                "_token": parent_form.find('input[name=_token]').val()
            };
        }
        else if (parent_form.attr('data-farm-id')) {
            var farm_address = [];
            var details = {
                "name": parent_form.find('input[name=name]').val(),
                "addressLine1": parent_form.find('input[name=addressLine1]').val(),
                "addressLine2": parent_form.find('input[name=addressLine2]').val(),
                "province": parent_form.find('select[name=province] option:checked').val(),
                "zipCode": parent_form.find('input[name=zipCode]').val(),
                "farmType": parent_form.find('input[name=farmType]').val(),
                "landline": parent_form.find('input[name=landline]').val(),
                "mobile": parent_form.find('input[name=mobile]').val(),
            };
            farm_address.push({});
            farm_address.push(details);

            data_values = {
                    "id": parent_form.attr('data-farm-id'),
                "_token": parent_form.find('input[name=_token]').val()
            };
            data_values["farmAddress"] = farm_address;
        }

        // Do AJAX
        $.ajax({
            url: parent_form.attr('action'),
            type: "PUT",
            cache: false,
            data: data_values,
            success: function(data){
                var data = JSON.parse(data);

                parent_form.find('input').removeClass('valid');
                parent_form.find('input, select').prop('disabled',true);
                parent_form.find('.caret').addClass('disabled');

                // Change the values of the input
                if(parent_form.attr('data-personal-id')){
                    parent_form.find('input[name=address_addressLine1]').val(data.address_addressLine1);
                    parent_form.find('input[name=address_addressLine2]').val(data.address_addressLine2);
                    parent_form.find('select[name=address_province]').val(data.address_province);
                    parent_form.find('input[name=address_zipCode]').val(data.address_zipCode);
                    parent_form.find('input[name=landline]').val(data.landline);
                    parent_form.find('input[name=mobile]').val(data.mobile);
                }
                else if(parent_form.attr('data-farm-id')){
                    parent_form.find('input[name=name]').val(data.name);
                    parent_form.find('.farm-title').html(data.name);
                    parent_form.find('input[name=addressLine1]').val(data.addressLine1);
                    parent_form.find('input[name=addressLine2]').val(data.addressLine2);
                    parent_form.find('select[name=province]').val(data.province);
                    parent_form.find('input[name=zipCode]').val(data.zipCode);
                    parent_form.find('input[name=farmType]').val(data.farmType);
                    parent_form.find('input[name=landline]').val(data.landline);
                    parent_form.find('input[name=mobile]').val(data.mobile);
                }

                // Re-initialize Materialize select
                parent_form.find('select').material_select();

                // Done tooltip animation to Edit
                edit_button.attr('data-tooltip',profile.edit_farm_name);
                edit_button.attr('data-position','left');
                edit_button.html('<i class="material-icons">mode_edit</i>');
                $(".tooltipped").tooltip({delay:50});
                $('.edit-button').removeClass('disabled');
                $('.cancel-button').removeClass('disabled');
                edit_button.prop('disabled', false);
                cancel_button.toggle();
                config.preloader_progress.fadeOut();
                Materialize.toast('Profile updated successfully!', 2000, 'green lighten-1');
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    },

    cancel: function(parent_form, edit_button, cancel_button){
        config.preloader_progress.fadeIn();
        cancel_button.tooltip('remove');
        $.when(
            parent_form.find('input').removeClass('valid'),
            parent_form.find('input, select').prop('disabled',true),
            parent_form.find('.caret').addClass('disabled')
        ).done(function(){
            // Done tooltip animation to Edit
            edit_button.attr('data-tooltip',profile.edit_farm_name);
            edit_button.attr('data-position','left');
            edit_button.html('<i class="material-icons">mode_edit</i>');
            $(".tooltipped").tooltip({delay:50});
            cancel_button.toggle();
            config.preloader_progress.fadeOut();
        });
    },

    remove: function(parent_form, row){
        config.preloader_progress.fadeIn();

        $.ajax({
            url: parent_form.attr('action'),
            type: "DELETE",
            cache: false,
            data: {
                "id": parent_form.attr('data-farm-id'),
                "_token": parent_form.find('input[name=_token]').val()
            },
            success: function(data){
                if(data == 'OK') {
                    row.remove();
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Farm information removed',2000);
                }
                else{
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Farm information removal unsuccessful', 2500, 'red');
                }
            },
            error: function(message){
                console.log(message['responseText']);
                config.preloader_progress.fadeOut();
            }
        });
    },

    change_password: function(parent_form){
        config.preloader_progress.fadeIn();

        $.ajax({
            url: parent_form.attr('action'),
            type: "PATCH",
            cache: false,
            data: {
                "_token": parent_form.find('input[name=_token]').val(),
                "current_password": parent_form.find('input[name=current_password]').val(),
                "new_password": parent_form.find('input[name=new_password]').val(),
                "new_password_confirmation": parent_form.find('input[name=new_password_confirmation]').val()
            },
            success: function(data){
                if(data === 'OK') {
                    parent_form.find('input[name=current_password], input[name=new_password], input[name=new_password_confirmation]').val('');
                    parent_form.find('label[for=current-password], label[for=new_password], label[for=new_password-confirm]').removeClass('active');
                    parent_form.find('input[name=current_password], input[name=new_password], input[name=new_password_confirmation]').removeClass('valid');

                    config.preloader_progress.fadeOut();
                    Materialize.toast('Password change successful', 2000, 'green lighten-1');
                }
                else{
                    config.preloader_progress.fadeOut();
                    Materialize.toast('Password change unsuccessful', 2500, 'red');
                }

                $('#password-error-container').hide();
                $('#change-password-button').removeClass('disabled');

            },
            error: function(message){
                var error_messages = JSON.parse(message['responseText']),
                    error_string = '';

                parent_form.find('input[name=current_password], input[name=new_password], input[name=new_password_confirmation]').val('');
                parent_form.find('label[for=current-password], label[for=new-password], label[for=new-password-confirm]').removeClass('active');
                parent_form.find('input[name=current_password], input[name=new_password], input[name=new_password_confirmation]').removeClass('valid');

                Object.keys(error_messages).forEach(function(element){
                    error_string += error_messages[element][0] + '<br>';
                });

                $('#password-error-container').html(error_string);
                $('#password-error-container').show();
                $('#change-password-button').removeClass('disabled');

                config.preloader_progress.fadeOut();
            }
        });

    },

    select_province: function(farmOrder){
        // Dynamically produce select element with options based on provinces
        var selectElement = '<select name="farmAddress[' + farmOrder + '][province]">';

        for(var key in provinces){
            selectElement += '<option value="' + key + '">' + key + '</option>';
        }

        selectElement += '</select>';

        return selectElement;
    }
};

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

"use strict";

// Place error on specific HTML input
var placeError = function(inputElement, errorMsg) {
  // Parse id of element if it contains '-' for the special
  // case of finding the input's respective
  // label on editProfile pages
  var inputId =
    inputElement.id.includes("-") && /\d/.test(inputElement.id)
      ? inputElement.id.split("-")[2]
      : inputElement.id;

  $(inputElement)
    .parents("form")
    .find("label[for='" + inputId + "']")
    .attr("data-error", errorMsg);

  setTimeout(function() {
    if (inputElement.id.includes("select")) {
      // For select input, find first its respective input text
      // then add the 'invalid' class
      $(inputElement)
        .parents(".select-wrapper")
        .find("input.select-dropdown")
        .addClass("invalid");

      if (inputId === "select-type") {
        /* Show the validation error  */
        $("#select-type-data-error").show();
        $("#select-type").on('change', function () {
          /* Remove validation error if an option is selected */
          $("#select-type-data-error").hide();
        });
      }
      else if (inputId === "select-farm") {
        /* Show the validation error  */
        $("#select-farm-data-error").show();
        $("#select-farm").on('change', function () {
          /* Remove validation error if an option is selected */
          $("#select-farm-data-error").hide();
        });
      }

    }
    else $(inputElement).addClass("invalid");
  }, 0);
};

// Place success from specific HTML input
var placeSuccess = function(inputElement) {
  // For select input, find first its respective input text
  // then add the needed classes
  var inputTextFromSelect = inputElement.id.includes("select")
    ? $(inputElement)
        .parents(".select-wrapper")
        .find("input.select-dropdown")
    : "";

  // Check first if it is invalid
  if (
    $(inputElement).hasClass("invalid") ||
    $(inputTextFromSelect).hasClass("invalid")
  ) {
    $(inputElement)
      .parents("form")
      .find("label[for='" + inputElement.id + "']")
      .attr("data-error", false);

    setTimeout(function() {
      if (inputElement.id.includes("select"))
        inputTextFromSelect.removeClass("invalid").addClass("valid");
      else
        $(inputElement)
          .removeClass("invalid")
          .addClass("valid");
    }, 0);
  } else {
    if (inputElement.id.includes("select"))
      inputTextFromSelect.addClass("valid");
    else $(inputElement).addClass("valid");
  }
};

var validationMethods = {
  // functions must return either true or the errorMsg only
  required: function(inputElement) {
    var errorMsg;
    if (inputElement.name === "name") errorMsg = "Please enter product name";
    else errorMsg = "This field is required";

    return inputElement.value ? true : errorMsg;
  },
  requiredIfRadio: function(inputElement, radioId) {
    var errorMsg;
    if (
      inputElement.name === "breed" ||
      inputElement.name === "fbreed" ||
      inputElement.name === "mbreed"
    ) {
      errorMsg = "Please enter swine breed";
    } else errorMsg = "This field is required.";

    var radioInputElement = document.getElementById(radioId);
    if (radioInputElement.checked) return inputElement.value ? true : errorMsg;
    else return true;
  },
  requiredDropdown: function(inputElement) {
    var errorMsg = "This field is required";
    return inputElement.value ? true : errorMsg;
  },
  email: function(inputElement) {
    var errorMsg = "Please enter a valid email address";
    return /\S+@\S+\.\S+/.test(inputElement.value) ? true : errorMsg;
  },
  minLength: function(inputElement, min) {
    var errorMsg = "Please enter " + min + " or more characters";
    return inputElement.value.length >= min ? true : errorMsg;
  },
  equalTo: function(inputElement, compareInputElementId) {
    var errorMsg = "Please enter the same value";
    var compareInputElement = document.getElementById(compareInputElementId);
    return inputElement.value === compareInputElement.value ? true : errorMsg;
  },
  zipCodePh: function(inputElement) {
    var errorMsg = "Please enter zipcode of 4 number characters";
    return /\d{4}/.test(inputElement.value) && inputElement.value.length === 4
      ? true
      : errorMsg;
  },
  phoneNumber: function(inputElement) {
    var errorMsg = "Please enter 11-digit phone number starting with 09";
    return /^09\d{9}/.test(inputElement.value) &&
      inputElement.value.length === 11
      ? true
      : errorMsg;
  }
};

'use strict';

var validateFunction = function(){

    return function(){
        var validateInput = function(inputElement){

            // Extract index from id of input element of existing(/to be added) farm information
            // to be used for the computed property
            // of validations object
            var index = 1;
            index = (inputElement.id.includes('-')) ? inputElement.id.match(/\d+/)[0]: index;
            index = (inputElement.id.includes('[')) ? inputElement.id.match(/\d+/)[0]: index;

            // Initialize needed validations
            var validations = {
                address_addressLine1: ['required'],
                address_addressLine2: ['required'],
                address_zipCode: ['required', 'zipCodePh'],
                // landline: ['landline'],
                mobile: ['required', 'phoneNumber'],
                ['farm-' + index + '-name']: ['required'],
                ['farm-' + index + '-addressLine1']: ['required'],
                ['farm-' + index + '-addressLine2']: ['required'],
                ['farm-' + index + '-zipCode']: ['required', 'zipCodePh'],
                ['farm-' + index + '-farmType']: ['required'],
                ['farm-' + index + '-mobile']: ['required', 'phoneNumber'],
                ['farmAddress[' + index + '][name]']: ['required'],
                ['farmAddress[' + index + '][addressLine1]']: ['required'],
                ['farmAddress[' + index + '][addressLine2]']: ['required'],
                ['farmAddress[' + index + '][zipCode]']: ['required', 'zipCodePh'],
                ['farmAddress[' + index + '][farmType]']: ['required'],
                ['farmAddress[' + index + '][mobile]']: ['required', 'phoneNumber'],
                'currentpassword': ['required'],
                'newpassword': ['required', 'minLength:8'],
                'newpasswordconfirm': ['required', 'equalTo:newpassword']
            };

            // Check if validation rules exist
            if(validations[inputElement.id]){
                var result = true;

                for (var i = 0; i < validations[inputElement.id].length; i++) {
                    var element = validations[inputElement.id][i];

                    // Split arguments if there are any
                    var method = element.includes(':') ? element.split(':') : element;

                    result = (typeof(method) === 'object')
                        ? (validationMethods[method[0]](inputElement, method[1]))
                        : (validationMethods[method](inputElement));

                    // Result would return to a string errorMsg if validation fails
                    if(result !== true){
                        placeError(inputElement, result);
                        return false;
                    }
                }

                // If all validations succeed then
                if(result === true){
                    placeSuccess(inputElement);
                    return true;
                }
            }
        };

        // onfocusout and keyup events on
        // personal-information and
        // farm-information
        // input only
        $('body').on('focusout keyup', '#personal-information input, #farm-information input', function(e){
            validateInput(this);
        });

        // keyup event on changing of password
        $('#password-information input').focusout(function(){
            if($(this).val()) validateInput(this);
        })

        $('#password-information input').keyup(function(){
            validateInput(this);
        });

        // Edit on Personal/Farm Information
        $('.edit-button').click(function(e){
            e.preventDefault();
            var edit_button = $(this);
            var cancel_button = edit_button.parents('.content-section').find('.cancel-button');
            var parent_form = edit_button.parents('form');

            edit_button.tooltip('remove');

            // If button is for editing the fields
            if(edit_button.attr('data-tooltip').includes('Edit'))profile.edit(parent_form, edit_button, cancel_button);

            // If button is ready for submission
            else {

                // Determine if form is of personal or farm information
                if (parent_form.attr('data-personal-id')) {

                    // Check if required fields are properly filled
                    var address_addressLine1 = validateInput(document.getElementById('address_addressLine1'));
                    var address_addressLine2 = validateInput(document.getElementById('address_addressLine2'));
                    var address_zipCode = validateInput(document.getElementById('address_zipCode'));
                    var mobile = validateInput(document.getElementById('mobile'));

                    // Submit if all validations are met
                    if(address_addressLine1 && address_addressLine2 && address_zipCode){
                        $('.edit-button').addClass('disabled');
                        $('.cancel-button').addClass('disabled');
                        profile.update(parent_form, edit_button, cancel_button);
                    }
                    else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');
                }
                else if(parent_form.attr('data-farm-id')){

                    // Check if required fields are properly filled
                    // Count how many current Farm Addresses are available
                    var farmNumber = parent_form.attr('data-farm-order');
                    var farmValid = true;

                    var farm_name = validateInput(document.getElementById('farm-' + farmNumber + '-name'));
                    var farm_addressLine1 = validateInput(document.getElementById('farm-' + farmNumber + '-addressLine1'));
                    var farm_addressLine2 = validateInput(document.getElementById('farm-' + farmNumber + '-addressLine2'));
                    var farm_zipCode = validateInput(document.getElementById('farm-' + farmNumber + '-zipCode'));
                    var farmType = validateInput(document.getElementById('farm-' + farmNumber + '-farmType'));
                    var farm_mobile = validateInput(document.getElementById('farm-' + farmNumber + '-mobile'));

                    farmValid = farmValid && farm_name && farm_addressLine1 && farm_addressLine2 && farm_zipCode && farmType && farm_mobile;

                    // Submit if all validations are met
                    if(farmValid){
                        $('.edit-button').addClass('disabled');
                        $('.cancel-button').addClass('disabled');
                        profile.update(parent_form, edit_button, cancel_button);
                    }
                    else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');
                }
            }

        });

        // Submit added farm information
        $('body').on('click', '#submit-button' ,function(e){
            e.preventDefault();

            // Count how many current Farm Addresses are available
            var farmNumber = $('#create-profile .add-farm').length+1;
            var farmValid = true;

            for (var i = 1; i < farmNumber; i++) {

                var farm_name = validateInput(document.getElementById('farmAddress[' + i + '][name]'));
                var farm_addressLine1 = validateInput(document.getElementById('farmAddress[' + i + '][addressLine1]'));
                var farm_addressLine2 = validateInput(document.getElementById('farmAddress[' + i + '][addressLine2]'));
                var farm_zipCode = validateInput(document.getElementById('farmAddress[' + i + '][zipCode]'));
                var farmType = validateInput(document.getElementById('farmAddress[' + i + '][farmType]'));
                var farm_mobile = validateInput(document.getElementById('farmAddress[' + i + '][mobile]'));

                farmValid = farmValid && farm_name && farm_addressLine1 && farm_addressLine2 && farm_zipCode && farmType && farm_mobile;
            }

            // Submit if all validations are met
            if(farmValid){
                $(this).addClass('disabled');
                profile.add($('#create-profile'));
            }
            else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');

        });

        // Change password
        $('#change-password-button').click(function(e){
            e.preventDefault();

            var currentPassword = validateInput(document.getElementById('currentpassword'));
            var newPassword = validateInput(document.getElementById('newpassword'));
            var newPasswordConfirm = validateInput(document.getElementById('newpasswordconfirm'));

            if(currentPassword && newPassword && newPasswordConfirm){
                $(this).addClass('disabled');
                profile.change_password($('#change-password-form'));
            }
        });

    }
}

$(document).ready(validateFunction());

$(document).ready(function () {

  /*
    Toggles eye icon button that shows/hide 
    password
  */
  $('.show-hide-password').click(function () {

    var password_field = $('.login-password');
    
    // change eye icon and show password text
    if ($('#show-hide-password-icon').text() === 'visibility') {
      $('#show-hide-password-icon').text('visibility_off');
      password_field.attr('type', 'text');
    }   

    // change eye icon and hide password text
    else {
      $('#show-hide-password-icon').text('visibility');
      password_field.attr('type', 'password');
    }
  });
});

//# sourceMappingURL=editProfile.js.map
