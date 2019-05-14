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
                        '<input name="farmAddress['+i+'][name]" id="farmAddress['+i+'][name]" type="text">'+
                        '<label for="farmAddress['+i+'][name]">Name</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                // Farm Address: Street Address
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine1]" id="farmAddress['+i+'][addressLine1]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine1]">Address Line 1* : Street, Road, Subdivision</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                // Farm Address: Address Line 2
                    '<div class="input-field col s10 push-s1">'+
                        '<input name="farmAddress['+i+'][addressLine2]" id="farmAddress['+i+'][addressLine2]" type="text">'+
                        '<label for="farmAddress['+i+'][addressLine2]">Address Line 2* : Barangay, Town, City</label>'+
                    '</div>'+
                '</div>'+

                '<div class="row">'+
                    // Farm Address: Province
                    '<div class="input-field col s5 push-s1">'+
                        select_province(i) +
                        '<label>Province*</label>'+
                    '</div>'+

                    // Farm Address: Zip Code
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][zipCode]" id="farmAddress['+i+'][zipCode]" type="text">'+
                        '<label for="farmAddress['+i+'][zipCode]">Postal/ZIP Code*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Type
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][farmType]" id="farmAddress['+i+'][farmType]" type="text">'+
                        '<label for="farmAddress['+i+'][farmType]">Farm Type*</label>'+
                    '</div>'+
                '</div>'+


                '<div class="row">'+
                    // Farm Landline
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][landline]" id="farmAddress['+i+'][landline]" type="text">'+
                        '<label for="farmAddress['+i+'][landline]">Landline</label>'+
                    '</div>'+

                    // Farm Mobile
                    '<div class="input-field col s5 push-s1">'+
                        '<input name="farmAddress['+i+'][mobile]" id="farmAddress['+i+'][mobile]" type="text">'+
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

            // Extract index from id of input element of farm information
            // to be used for the computed property
            // of validations object
            var index = (inputElement.id.includes('[')) ? inputElement.id.match(/\d+/)[0]: 1;

            // Initialize needed validations
            var validations = {
                address_addressLine1: ['required'],
                address_addressLine2: ['required'],
                address_zipCode: ['required', 'zipCodePh'],
                // landline: ['landline'],
                mobile: ['required', 'phoneNumber'],
                ['farmAddress[' + index + '][name]']: ['required'],
                ['farmAddress[' + index + '][addressLine1]']: ['required'],
                ['farmAddress[' + index + '][addressLine2]']: ['required'],
                ['farmAddress[' + index + '][zipCode]']: ['required', 'zipCodePh'],
                ['farmAddress[' + index + '][farmType]']: ['required'],
                ['farmAddress[' + index + '][mobile]']: ['required', 'phoneNumber']
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

        // onfocusout events
        $('body').on('focusout', 'input', function(e){
            e.preventDefault();

            validateInput(this);
        });

        // onkeyup events
        $('body').on('keyup', 'input', function(e){
            if($(this).hasClass('invalid') || $(this).hasClass('valid')) validateInput(this);
        });

        $("button[type='submit']").click(function(e){
            e.preventDefault();

            var address_addressLine1 = validateInput(document.getElementById('address_addressLine1'));
            var address_addressLine2 = validateInput(document.getElementById('address_addressLine2'));
            var address_zipCode = validateInput(document.getElementById('address_zipCode'));
            var mobile = validateInput(document.getElementById('mobile'));

            // Count how many current Farm Addresses are available
            var farmNumber = $("#farm-address-body .add-farm").length+1;
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
            if(address_addressLine1 && address_addressLine2 && address_zipCode && mobile && farmValid){
                $(this).addClass('disabled');
                $(this).parents('form').submit();
            }
            else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');
        });

    }
}

$(document).ready(validateFunction());

//# sourceMappingURL=createProfile.js.map
