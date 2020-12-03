"use strict";

// Place error on specific HTML input
var placeError = function (inputElement, errorMsg) {
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

  setTimeout(function () {
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
var placeSuccess = function (inputElement) {
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

    setTimeout(function () {
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
  required: function (inputElement) {
    var errorMsg;
    if (inputElement.name === "name") errorMsg = "Please enter product name";
    else errorMsg = "This field is required";

    return inputElement.value ? true : errorMsg;
  },
  requiredUserName: function (inputElement) {
    var errorMsg = "This field is required";
    return inputElement.value ? true : errorMsg;
  },
  requiredIfRadio: function (inputElement, radioId) {
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
  requiredDropdown: function (inputElement) {
    var errorMsg = "This field is required";
    return inputElement.value ? true : errorMsg;
  },
  email: function (inputElement) {
    var errorMsg = "Please enter a valid email address";
    return /\S+@\S+\.\S+/.test(inputElement.value) ? true : errorMsg;
  },
  minLength: function (inputElement, min) {
    var errorMsg = "Please enter " + min + " or more characters";
    return inputElement.value.length >= min ? true : errorMsg;
  },
  equalTo: function (inputElement, compareInputElementId) {
    var errorMsg = "Please enter the same value";
    var compareInputElement = document.getElementById(compareInputElementId);
    return inputElement.value === compareInputElement.value ? true : errorMsg;
  },
  zipCodePh: function (inputElement) {
    var errorMsg = "Please enter zipcode of 4 number characters";
    return /\d{4}/.test(inputElement.value) && inputElement.value.length === 4
      ? true
      : errorMsg;
  },
  phoneNumber: function (inputElement) {
    var errorMsg = "Please enter 11-digit phone number starting with 09";
    return /^09\d{9}/.test(inputElement.value) &&
      inputElement.value.length === 11
      ? true
      : errorMsg;
  }
};

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
$(document).ready(function(){
  // initialization for select tags
  $('select').material_select();

  // initialization of Materialize's Date Picker
  $(".datepicker").pickadate({
    max: true,
    selectMonths: true,
    selectYears: 4,
    format: "mmmm d, yyyy"
  });

  // prevent the date picker from instatly closing upon clicking
  // Materialize bug? 
  $('.datepicker').on('mousedown', function (event) {
    event.preventDefault();
  });

  // prevent the dropdown from instantly closing upon clicking
  // Materialize bug?
  $('#select-province').on('click', function (event) {
    event.stopPropagation();
  });

  // disable the farm information tab until finished
  $('#breeder-register #farm-tab').addClass('disabled');

  // use the next button to go to farm-tab after properly validating the office tab
  $('#breeder-register #next').click((e) => {
    e.preventDefault();
    if($('#farm-tab').hasClass('disabled')) $('#farm-tab').removeClass('disabled');
    $('ul.tabs').tabs('select_tab', 'farm-information');
  });

  // use the previous button to go back to office tab
  $('#breeder-register #previous').click((e) => {
    e.preventDefault();
    $('ul.tabs').tabs('select_tab', 'personal-information');
  });
})

//# sourceMappingURL=breederRegister.js.map
