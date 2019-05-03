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
