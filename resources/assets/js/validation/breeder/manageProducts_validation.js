"use strict";

var validateFunction = function() {
  return function() {
    var validateInput = function(inputElement, modal) {
      // Initialize needed validations
      var validations = {
        name: ["required"],
        breed: ["requiredIfRadio:purebreed"],
        fbreed: ["requiredIfRadio:crossbreed"],
        mbreed: ["requiredIfRadio:crossbreed"],
        "select-type": ["requiredDropdown"],
        birthdate: ["required"],
        "select-farm": ["requiredDropdown"],
        "edit-name": ["required"],
        "edit-breed": ["requiredIfRadio:edit-purebreed"],
        "edit-fbreed": ["requiredIfRadio:edit-crossbreed"],
        "edit-mbreed": ["requiredIfRadio:edit-crossbreed"],
        "edit-select-type": ["requiredDropdown"],
        "edit-select-farm": ["requiredDropdown"]
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

    // focusout events on add-product-modal
    $("body").on("focusout", "#add-product-modal input", function(e) {
      validateInput(this, "add-product-modal");
    });

    // keyup events on add-product-modal
    $("body").on("keyup", "#add-product-modal input", function(e) {
      if ($(this).hasClass("invalid") || $(this).hasClass("valid"))
        validateInput(this, "add-product-modal");
    });

    // focusout and keyup events on add-product-modal
    $("body").on("focusout keyup", "#edit-product-modal input", function(e) {
      validateInput(this, "edit-product-modal");
    });

    // select change events
    $("select").change(function() {
      validateInput(this);
    });

    // Remove respective 'invalid' class and input text value
    // of current radio when radio value changes
    $("#create-product input[name='radio-breed']").change(function() {
      if ($("#create-product input:checked").val() === "crossbreed") {
        $("input#breed").val("");
        $("input#breed").removeClass("valid invalid");
      } else {
        $("input#fbreed, input#mbreed").val("");
        $("input#fbreed, input#mbreed").removeClass("valid invalid");
      }
    });

    // Temporary fix for prompting 'valid' class after
    // value change on datepicker
    $("#birthdate, #edit-birthdate").change(function(e) {
      e.stopPropagation();
      $(this)
        .removeClass("invalid")
        .addClass("valid");
    });

    // Submit add product
    $("#create-product").submit(function(e) {
      e.preventDefault();

      var validName = validateInput(document.getElementById("name"));
      var validType = validateInput(document.getElementById("select-type"));
      var validFarmFrom = validateInput(document.getElementById("select-farm"));
      var validBirthdate = validateInput(document.getElementById("birthdate"));
      var validBreed = true;

      // Validate appropriate breed input/s according to chosen radio breed value
      if ($("#create-product input:checked").val() === "crossbreed") {
        var validFbreed = validateInput(document.getElementById("fbreed"));
        var validMbreed = validateInput(document.getElementById("mbreed"));
        validBreed = validBreed && validFbreed && validMbreed;
      } else validBreed = validateInput(document.getElementById("breed"));

      if (
        validName &&
        validType &&
        validFarmFrom &&
        validBirthdate &&
        validBreed
      ) {
        // Disable submit/add product button
        $("#submit-button").addClass("disabled");
        $("#submit-button").html("Adding Product ...");

        product.add($("#create-product"));
      } else
        Materialize.toast(
          "Please properly fill all required fields.",
          2500,
          "orange accent-2"
        );
    });

    // Update details of a product
    $(".update-button").click(function(e) {
      e.preventDefault();

      var validName = validateInput(document.getElementById("edit-name"));
      var validType = validateInput(
        document.getElementById("edit-select-type")
      );
      var validFarmFrom = validateInput(
        document.getElementById("edit-select-farm")
      );
      var validBreed = true;

      // Validate appropriate breed input/s according to chosen radio breed value
      if ($("#edit-product input:checked").val() === "crossbreed") {
        var validFbreed = validateInput(document.getElementById("edit-fbreed"));
        var validMbreed = validateInput(document.getElementById("edit-mbreed"));
        validBreed = validBreed && validFbreed && validMbreed;
      } else validBreed = validateInput(document.getElementById("edit-breed"));

      if (validName && validType && validFarmFrom && validBreed) {
        // Disable update-button
        $(this).addClass("disabled");
        $(this).html("Updating...");

        submitEditedProduct($("#edit-product"), $(this));
      } else Materialize.toast("Please properly fill all required fields.", 2500, "orange accent-2");
    });
  };
};

$(document).ready(validateFunction());
