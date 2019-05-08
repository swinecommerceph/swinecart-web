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


  // Same address as office information feature
  $(".same-address-checker").change(function (e) {
    e.preventDefault();

    var farm_specific = $(this).attr('class').split(' ')[1];
    var farm_specific_province = "#" + farm_specific;
    farm_specific = "." + farm_specific;

    var office_address1 = $("#officeAddress_addressLine1").val();
    var office_address2 = $("#officeAddress_addressLine2").val();
    var office_province = $("#office_provinces").val();
    var office_postal_zip_code = $("#officeAddress_zipCode").val();
    var office_landline = $("#office_landline").val();
    var office_mobile = $("#office_mobile").val();

    if ($(".same-address-checker").is(":checked")) {
      // set values

      $(farm_specific + "-addressLine1")
        .val(office_address1)
        .addClass("input-show-hide")
      
      $(farm_specific + "-addressLine2")
        .val(office_address2)
        .addClass("input-show-hide")
      
      /* $(farm_specific_province)
        .find("input[class=select-dropdown]")
        .val(office_province)
        .change(); */

      $(farm_specific + "-zipCode")
        .val(office_postal_zip_code)
        .addClass("input-show-hide")

      $(farm_specific + "-landline")
        .val(office_landline)
        .addClass("input-show-hide")

      $(farm_specific + "-mobile")
        .val(office_mobile)
        .addClass("input-show-hide")
        
    } else {
      $(farm_specific + "-addressLine1")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-addressLine2")
        .val("")
        .removeClass("input-show-hide")

      // $(farm_specific_province).find('input[class=select-dropdown]').val('Abra')

      $(farm_specific + "-zipCode")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-farmType")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-landline")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-mobile")
        .val("")
        .removeClass("input-show-hide")

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
    if (inputElement.name === "name")
      errorMsg = "Please enter product name"; 
    else 
      errorMsg = "This field is required"; 

    return inputElement.value ? true : errorMsg;
  },
  requiredIfRadio: function(inputElement, radioId) {
    var errorMsg = "This field is required";
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

"use strict";

var validateFunction = function() {
  return function() {
    var validateInput = function(inputElement) {
      // Extract index from id of input element of farm information
      // to be used for the computed property
      // of validations object
      var index = inputElement.id.includes("[")
        ? inputElement.id.match(/\d+/)[0]
        : 1;

      // Initialize needed validations
      var validations = {
        officeAddress_addressLine1: ["required"],
        officeAddress_addressLine2: ["required"],
        officeAddress_zipCode: ["required", "zipCodePh"],
        // landline: ['landline'],
        office_mobile: ["required", "phoneNumber"],
        contactPerson_name: ["required"],
        contactPerson_mobile: ["required", "phoneNumber"],
        ["farmAddress[" + index + "][addressLine1]"]: ["required"],
        ["farmAddress[" + index + "][addressLine2]"]: ["required"],
        ["farmAddress[" + index + "][zipCode]"]: ["required", "zipCodePh"],
        ["farmAddress[" + index + "][farmType]"]: ["required"],
        ["farmAddress[" + index + "][mobile]"]: ["required", "phoneNumber"]
      };

      // Check if validation rules exist
      if (validations[inputElement.id]) {
        var result = true;

        for (var i = 0; i < validations[inputElement.id].length; i++) {
          var element = validations[inputElement.id][i];

          // Split arguments if there are any
          var method = element.includes(":") ? element.split(":") : element;

          result =
            typeof method === "object"
              ? validationMethods[method[0]](inputElement, method[1])
              : validationMethods[method](inputElement);

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
    };

    // onfocusout events
    $("body").on("focusout", "input", function(e) {
      e.preventDefault();

      validateInput(this);
    });

    // onkeyup events
    $("body").on("keyup", "input", function(e) {
      if ($(this).hasClass("invalid") || $(this).hasClass("valid"))
        validateInput(this);
    });

    $("button[type='submit']").click(function(e) {
      e.preventDefault();

      var officeAddress_addressLine1 = validateInput(
        document.getElementById("officeAddress_addressLine1")
      );
      var officeAddress_addressLine2 = validateInput(
        document.getElementById("officeAddress_addressLine2")
      );
      var officeAddress_zipCode = validateInput(
        document.getElementById("officeAddress_zipCode")
      );
      var office_mobile = validateInput(
        document.getElementById("office_mobile")
      );
      var contactPerson_name = validateInput(
        document.getElementById("contactPerson_name")
      );
      var contactPerson_mobile = validateInput(
        document.getElementById("contactPerson_mobile")
      );

      // Count how many current Farm Addresses are available
      var farmNumber = $("#farm-address-body .add-farm").length + 1;
      var farmValid = true;

      for (var i = 1; i < farmNumber; i++) {

        var farm_addressLine1 = validateInput(
          document.getElementById("farmAddress[" + i + "][addressLine1]")
        );
        var farm_addressLine2 = validateInput(
          document.getElementById("farmAddress[" + i + "][addressLine2]")
        );
        var farm_zipCode = validateInput(
          document.getElementById("farmAddress[" + i + "][zipCode]")
        );
        var farmType = validateInput(
          document.getElementById("farmAddress[" + i + "][farmType]")
        );
        var farm_mobile = validateInput(
          document.getElementById("farmAddress[" + i + "][mobile]")
        );

        farmValid =
          farmValid &&
          farm_addressLine1 &&
          farm_addressLine2 &&
          farm_zipCode &&
          farmType &&
          farm_mobile;
      }

      // Submit if all validations are met
      if (
        officeAddress_addressLine1 &&
        officeAddress_addressLine2 &&
        officeAddress_zipCode &&
        office_mobile &&
        contactPerson_name &&
        contactPerson_mobile &&
        farmValid
      ) {
        $(this).addClass("disabled");
        $(this)
          .parents("form")
          .submit();
      } else Materialize.toast("Please properly fill all required fields.", 2500, "orange accent-2");
    });
  };
};

$(document).ready(validateFunction());

//# sourceMappingURL=createProfile.js.map
