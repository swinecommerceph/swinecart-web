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
      $("#birthdate").on('change', function () {
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

var validateFunction = function () {

  return function () {
    var validateInput = function (inputElement) {
      // Initialize needed validations
      var validations = {
        email: ['required', 'email'],
        password: ['required', 'minLength:8']
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

    // Focusout events
    $("input").focusout(function(e){
        e.preventDefault();

        validateInput(this);
    });

    // OnKeypressUp events
    $("input").keyup(function(e){
        if($(this).hasClass('invalid') || $(this).hasClass('valid')) validateInput(this);
    })

    $("button[type='submit']").click(function(e){
        e.preventDefault();
        
        var validEmail = validateInput(document.getElementById('email'));
        var validPassword = validateInput(document.getElementById('password'));

        if(validEmail && validPassword){
            $(this).addClass('disabled');
            $(this).parents('form').submit();
        }
    })
  }
};

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

//# sourceMappingURL=login.js.map
