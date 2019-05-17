'use strict';

var profile = {
    edit_farm_name: '',

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
                "officeAddress_addressLine1": parent_form.find('input[name=officeAddress_addressLine1]').val(),
                "officeAddress_addressLine2": parent_form.find('input[name=officeAddress_addressLine2]').val(),
                "officeAddress_province": parent_form.find('select[name=officeAddress_province] option:checked').val(),
                "officeAddress_zipCode": parent_form.find('input[name=officeAddress_zipCode]').val(),
                "office_landline": parent_form.find('input[name=office_landline]').val(),
                "office_mobile": parent_form.find('input[name=office_mobile]').val(),
                "contactPerson_name": parent_form.find('input[name=contactPerson_name]').val(),
                "contactPerson_mobile": parent_form.find('input[name=contactPerson_mobile]').val(),
                "website": parent_form.find('input[name=website]').val(),
                "produce": parent_form.find('input[name=produce]').val(),
                "_token": parent_form.find('input[name=_token]').val()
            };
        }
        else if (parent_form.attr('data-farm-id')) {
            var farm_address = [];
            var details = {
                "addressLine1": parent_form.find('input[name=addressLine1]').val(),
                "addressLine2": parent_form.find('input[name=addressLine2]').val(),
                "province": parent_form.find('input[class=select-dropdown]').val(),
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
                    parent_form.find('input[name=officeAddress_addressLine1]').val(data.officeAddress_addressLine1);
                    parent_form.find('input[name=officeAddress_addressLine2]').val(data.officeAddress_addressLine2);
                    parent_form.find('select[name=officeAddress_province]').val(data.officeAddress_province);
                    parent_form.find('input[name=officeAddress_zipCode]').val(data.officeAddress_zipCode);
                    parent_form.find('input[name=office_landline]').val(data.office_landline);
                    parent_form.find('input[name=office_mobile]').val(data.office_mobile);
                    parent_form.find('input[name=contactPerson_name]').val(data.contactPerson_name);
                    parent_form.find('input[name=contactPerson_mobile]').val(data.contactPerson_mobile);
                    parent_form.find('input[name=website]').val(data.website);
                    parent_form.find('input[name=produce]').val(data.produce);

                }
                else if(parent_form.attr('data-farm-id')){
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
                else {
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

        // Do AJAX
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

    set_logo: function(parent_form, logo_dropzone){

        // Check if there is image uploaded
        if($('.dz-image-preview').first().attr('data-image-id')){

            // Do AJAX
            $.ajax({
                url: config.breederLogo_url,
                type: "PATCH",
                cache: true,
                data: {
                    "_token": parent_form.find('input[name=_token]').val(),
                    "imageId": $('.dz-image-preview').first().attr('data-image-id')
                },
                success: function(data){

                    $('#logo-card .card-image img').attr('src', data);

                    $('#change-logo-modal').modal('close');
                    $('#confirm-change-logo').html('Set Logo');
                    $('#confirm-change-logo').removeClass('disabled');

                    // Clear the Dropzone
                    var dropzoneFiles = logo_dropzone.files;
                    for(var i = 0; i < dropzoneFiles.length; i++){
                        if(dropzoneFiles[i].previewElement){
                            var _ref = dropzoneFiles[i].previewElement;
                            _ref.parentNode.removeChild(dropzoneFiles[i].previewElement);
                        }
                    }
                    logo_dropzone.files = [];
                    logo_dropzone.emit('reset');

                    Materialize.toast('Logo updated', 2000, 'green lighten-1');
                },
                error: function(message){
                    console.log(message['responseText']);
                }
            });
        }
        else{
            $('#confirm-change-logo').html('Set Logo');
            $('#confirm-change-logo').removeClass('disabled');
        }

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

$(document).ready(function () {
  /*
   *	Update Profile specific
   */

  Dropzone.options.logoDropzone = false;

  var logoDropzone = new Dropzone('#logo-dropzone', {
    paramName: 'logo',
    uploadMultiple: false,
    maxFiles: 1,
    maxFilesize: 5,
    acceptedFiles: "image/png, image/jpeg, image/jpg",
    dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop image here to upload logo</h5>",
    previewTemplate: document.getElementById('custom-preview').innerHTML,
    init: function () {

      // Inject attributes upon success of file upload
      this.on('success', function (file, response) {
        var response = JSON.parse(response);
        var previewElement = file.previewElement;

        previewElement.setAttribute('data-image-id', response.id);
        file.name = response.name;
        $('.dz-filename span[data-dz-name]').html(response.name);

        $(".tooltipped").tooltip({ delay: 50 });
      });

      // Remove file from file system and database records
      this.on('removedfile', function (file) {
        console.log(file.previewElement);

        if (file.previewElement.getAttribute('data-image-id')) {
          // Do AJAX
          $.ajax({
            url: config.breederLogo_url,
            type: "DELETE",
            cache: false,
            data: {
              "_token": $('#logo-dropzone').find('input[name=_token]').val(),
              "imageId": file.previewElement.getAttribute('data-image-id')
            },
            success: function (data) {

            },
            error: function (message) {
              console.log(message['responseText']);
            }
          });
        }
      });

    }
  });

  
  // for enabling select tags
  $("select").material_select();

  // Same address as office information feature
  $(".same-address-checker").change(function (e) {
    e.preventDefault();

    var farm_specific = $(this).attr('class').split(' ')[1];
    farm_specific = "#" + farm_specific;

    var office_address1 = $("#officeAddress_addressLine1").val();
    var office_address2 = $("#officeAddress_addressLine2").val();
    var office_province = $("#office_provinces").val();
    var office_postal_zip_code = $("#officeAddress_zipCode").val();
    var office_landline = $("#office_landline").val();
    var office_mobile = $("#office_mobile").val();

    if ($(this).is(":checked")) {

      // set values
      $(farm_specific + "-addressLine1").val(office_address1).addClass('input-show-hide');
      $(farm_specific + "-addressLine2").val(office_address2).addClass('input-show-hide');
      $(farm_specific).find('input[class=select-dropdown]').val(office_province).addClass('input-show-hide');
      $(farm_specific + "-zipCode").val(office_postal_zip_code).addClass('input-show-hide');
      $(farm_specific + "-landline").val(office_landline).addClass('input-show-hide');
      $(farm_specific + "-mobile").val(office_mobile).addClass('input-show-hide');
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

  // Change Logo
  $("#change-logo").on('click', function (e) {
    e.preventDefault();

    $("#change-logo-modal").modal({ dismissible: false });
    $("#change-logo-modal").modal('open');
  });

  // Confirm Change Logo
  $("#confirm-change-logo").on('click', function (e) {
    e.preventDefault();

    $(this).html("Setting Logo...");
    $(this).addClass("disabled");

    profile.set_logo($('#logo-dropzone'), logoDropzone);
  });

  // Cancel on Editing a Personal/Farm Information
  $('.cancel-button').click(function (e) {
    e.preventDefault();
    var cancel_button = $(this);
    var edit_button = cancel_button.parents('.content-section').find('.edit-button');
    var parent_form = cancel_button.parents('form');

    profile.cancel(parent_form, edit_button, cancel_button);
  });

  // Remove an instance of the current farm information/s
  $('.remove-farm').click(function (e) {
    e.preventDefault();
    var remove_button = $(this);
    var parent_form = remove_button.parents('form');
    var row = remove_button.parents('.add-farm');

    //  Check if there are more than 1 farm information to remove
    if ($('#farm-address-body').find('.delete-farm .remove-farm').length > 1) {
      $('#confirmation-modal').modal('open');
      $('#confirm-remove').click(function (e) {
        e.preventDefault();
        profile.remove(parent_form, row);
      });
      location.href = '#';
    }
    else Materialize.toast('At least 1 Farm information required', 2500, 'orange accent-2');
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

    } else if (inputElement.id.includes("birthdate")) {
      $("#birthdate-data-error").show();
      $("#birthdate , #edit_birthdate").on('change', function () {
        /* Remove validation error if an option is selected */
        $("#birthdate-data-error").hide();
      });
      $(inputElement).addClass("invalid");
    } else $(inputElement).addClass("invalid");
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
                officeAddress_addressLine1: ['required'],
                officeAddress_addressLine2: ['required'],
                officeAddress_zipCode: ['required', 'zipCodePh'],
                // landline: ['landline'],
                office_mobile: ['required', 'phoneNumber'],
                contactPerson_name: ['required'],
                contactPerson_mobile: ['required', 'phoneNumber'],
                ['farm-' + index + '-addressLine1']: ['required'],
                ['farm-' + index + '-addressLine2']: ['required'],
                ['farm-' + index + '-zipCode']: ['required', 'zipCodePh'],
                ['farm-' + index + '-farmType']: ['required'],
                ['farm-' + index + '-mobile']: ['required', 'phoneNumber'],
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
                    var officeAddress_addressLine1 = validateInput(document.getElementById('officeAddress_addressLine1'));
                    var officeAddress_addressLine2 = validateInput(document.getElementById('officeAddress_addressLine2'));
                    var officeAddress_zipCode = validateInput(document.getElementById('officeAddress_zipCode'));
                    var office_mobile = validateInput(document.getElementById('office_mobile'));
                    var contactPerson_name = validateInput(document.getElementById('contactPerson_name'));
                    var contactPerson_mobile = validateInput(document.getElementById('contactPerson_mobile'));

                    // Submit if all validations are met
                    if(officeAddress_addressLine1 && officeAddress_addressLine2 && officeAddress_zipCode && contactPerson_name && contactPerson_mobile){
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

                    var farm_addressLine1 = validateInput(document.getElementById('farm-' + farmNumber + '-addressLine1'));
                    var farm_addressLine2 = validateInput(document.getElementById('farm-' + farmNumber + '-addressLine2'));
                    var farm_zipCode = validateInput(document.getElementById('farm-' + farmNumber + '-zipCode'));
                    var farmType = validateInput(document.getElementById('farm-' + farmNumber + '-farmType'));
                    var farm_mobile = validateInput(document.getElementById('farm-' + farmNumber + '-mobile'));

                    farmValid = farmValid && farm_addressLine1 && farm_addressLine2 && farm_zipCode && farmType && farm_mobile;

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
