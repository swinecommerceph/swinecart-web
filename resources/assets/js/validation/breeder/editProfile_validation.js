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
