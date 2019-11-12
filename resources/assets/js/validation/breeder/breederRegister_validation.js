"use strict";

let validateFunction = function() {

  return function() {
    let validateInput = function(inputElement) {

      /* Extract index from id of input element of existing(/to be added) farm information
      to be used for the computed property
      of validations object */

      // Initialize needed validations
      let validations = {

        // first tab of form breeder registration
        breederName: ['required'],
        email: ['required', 'email'],
        officeAddress_addressLine1: ['required'],
        officeAddress_addressLine2: ['required'],
        officeAddress_zipCode: ['required', 'zipCodePh'],
        office_mobile: ['required', 'phoneNumber'],
        contactPerson_name: ['required'],
        contactPerson_mobile: ['required', 'phoneNumber'],

        // second tab of form breeder registration
        farm_name: ['required'],
        farm_accreditation_number: ['required'],
        // acc_date_evaluated: ['required'],
        // acc_date_expiry: ['required'],
        farmAddress_1_addressLine1: ['required'],
        farmAddress_1_addressLine2: ['required'],
        farmAddress_1_zipCode: ['required', 'zipCodePh'],
        farmAddress_1_farmType: ['required'],
        farmAddress_1_mobile: ['required', 'phoneNumber']
      };

      // Check if validation rules exist
      if (validations[inputElement.id]) {
        var result = true;

        for (var i = 0; i < validations[inputElement.id].length; i++) {
          var element = validations[inputElement.id][i];

          // Split arguments if there are any
          var method = element.includes(':') ? element.split(':') : element;

          result = (typeof (method) === 'object')
            ? (validationMethods[method[0]](inputElement, method[1]))
            : (validationMethods[method](inputElement));

          // Result would return to a string errorMsg if validation fails
          if (result !== true) {
            placeError(inputElement, result);
            return false;
          }
        }

        // If all validations succeed then
        if (result === true) {
          placeSuccess(inputElement);
          return true;
        }
      }
    }

    /* onfocusout and keyup events on
    personal-information and
    farm-information
    input only */

    $('body').on(
      'focusout keyup',
      '#personal-information input, #farm-information input',
      function () {
        validateInput(this);
    });

    $("button[type='submit']").click(function (e) {
      e.preventDefault();
    
      const breeder_name = validateInput(document.querySelector('#breederName'));
      const email = validateInput(document.querySelector('#email'));
      const office_add1 = validateInput(document.querySelector('#officeAddress_addressLine1'));
      const office_add2 = validateInput(document.querySelector('#officeAddress_addressLine2'));
      const office_zipCode = validateInput(document.querySelector('#officeAddress_zipCode'));
      const office_contactPerson = validateInput(document.querySelector('#contactPerson_name'));
      const office_contactPersonMobile = validateInput(document.querySelector('#contactPerson_mobile'));
      const farm_name = validateInput(document.querySelector('#farm_name'));
      const farm_acc_number = validateInput(document.querySelector('#farm_accreditation_number'));
      const farm_add1 = validateInput(document.querySelector('#farmAddress_1_addressLine1'));
      const farm_add2 = validateInput(document.querySelector('#farmAddress_1_addressLine2'));
      const farm_zipCode = validateInput(document.querySelector('#farmAddress_1_zipCode'));
      const farm_type = validateInput(document.querySelector('#farmAddress_1_farmType'));
      const farm_mobile = validateInput(document.querySelector('#farmAddress_1_mobile'));

      if (
        breeder_name &&
        email &&
        office_add1 &&
        office_add2 &&
        office_zipCode &&
        office_contactPerson &&
        office_contactPersonMobile &&
        farm_name &&
        farm_acc_number &&
        farm_add1 &&
        farm_add2 &&
        farm_zipCode &&
        farm_type &&
        farm_mobile
        ) {
        $(this).parents('form').submit();
      }
      else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');
    });
  }
}

$(document).ready(validateFunction());