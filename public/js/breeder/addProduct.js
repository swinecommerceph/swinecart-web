'use strict';

var product = {

  add: function (parent_form) {

    var data_values = {
      "name": parent_form.find('input[name=name]').val(),
      "type": parent_form.find('#select-type').val(),
      "farm_from_id": parent_form.find('#select-farm').val(),
      "birthdate": parent_form.find('input[name=birthdate]').val(),
      "price": parent_form.find('input[name=price]').val(),
      "adg": parent_form.find('input[name=adg]').val(),
      "fcr": parent_form.find('input[name=fcr]').val(),
      "backfat_thickness": parent_form.find('input[name=backfat_thickness]').val(),
      "_token": parent_form.find('input[name=_token]').val(),
    };

    data_values.price = data_values.price.replace(",", ""); // remove comma in price before storing

    // Transform breed syntax if crossbreed
    if ($("#create-product input:checked").val() === 'crossbreed') {
      var fbreed = parent_form.find('input[name=fbreed]').val();
      var mbreed = parent_form.find('input[name=mbreed]').val();

      data_values["breed"] = fbreed.toLowerCase().trim() + '+' + mbreed.toLowerCase().trim();
    }
    else data_values["breed"] = parent_form.find('input[name=breed]').val().toLowerCase().trim();

    data_values["other_details"] = '';


    // Do AJAX
    $.ajax({
      url: parent_form.attr('action'),
      type: "POST",
      cache: false,
      data: data_values,
      success: function (data) {

        Materialize.toast('Product added!', 2500, 'green lighten-1');
        location.href = location.origin + '/breeder/products'; // redirect to Show Products page
      },
      error: function (message) {
        console.log(message['responseText']);
        $('#overlay-preloader-circular').remove();
      }
    });
  }
};

$(document).ready(function () {

  // Hide certain elements
  $('.input-crossbreed-container').hide();

  // initialization of Materialize's Date Picker
  $('.datepicker').pickadate({
    max: true,
    selectMonths: true,
    selectYears: 4,
    format: 'mmmm d, yyyy'
  });

  $('#add-media-button').click(function () {
    // Open Add Media Modal
    $('#add-media-modal').modal({
      dismissible: false,
      ready: function () {
        // Resize media-dropzone's height
        var content_height = $('#add-media-modal .modal-content').height();
        var header_height = $('#add-media-modal h4').height();
        $('#media-dropzone').css({ 'height': content_height - header_height });

        $(window).resize(function () {
          var content_height = $('#add-media-modal .modal-content').height();
          var header_height = $('#add-media-modal h4').height();
          $('#media-dropzone').css({ 'height': content_height - header_height });
        });
      }
    });
    $('#add-media-modal').modal('open');
  });

  /* ----------- Form functionalities ----------- */
  // Breed radio
  $("input.purebreed").on('click', function () {
    $(this).parents('form').find('.input-crossbreed-container').hide();
    $(this).parents('form').find('.input-purebreed-container').fadeIn(300);
  });
  $("input.crossbreed").on('click', function () {
    $(this).parents('form').find('.input-purebreed-container').hide();
    $(this).parents('form').find('.input-crossbreed-container').fadeIn(300);
  });
});
'use strict';

// Place error on specific HTML input
var placeError = function(inputElement, errorMsg){
    // Parse id of element if it contains '-' for the special
    // case of finding the input's respective
    // label on editProfile pages
    var inputId = (inputElement.id.includes('-') && /\d/.test(inputElement.id))
        ? (inputElement.id.split('-')[2])
        : inputElement.id;

    $(inputElement)
        .parents("form")
        .find("label[for='" + inputId + "']")
        .attr('data-error', errorMsg);

    setTimeout(function(){
        if(inputElement.id.includes('select')){
            // For select input, find first its respective input text
            // then add the 'invalid' class
            $(inputElement)
                .parents('.select-wrapper')
                .find('input.select-dropdown')
                .addClass('invalid');
        }
        else $(inputElement).addClass('invalid');
    },0);
};

// Place success from specific HTML input
var placeSuccess = function(inputElement){

    // For select input, find first its respective input text
    // then add the needed classes
    var inputTextFromSelect = (inputElement.id.includes('select')) ? $(inputElement).parents('.select-wrapper').find('input.select-dropdown') : '';

    // Check first if it is invalid
    if($(inputElement).hasClass('invalid') || $(inputTextFromSelect).hasClass('invalid')){
        $(inputElement)
            .parents("form")
            .find("label[for='" + inputElement.id + "']")
            .attr('data-error', false);

        setTimeout(function(){
            if(inputElement.id.includes('select')) inputTextFromSelect.removeClass('invalid').addClass('valid');
            else $(inputElement).removeClass('invalid').addClass('valid');
        },0);
    }
    else {
        if(inputElement.id.includes('select')) inputTextFromSelect.addClass('valid');
        else $(inputElement).addClass('valid');
    }
}

var validationMethods = {
    // functions must return either true or the errorMsg only
    required: function(inputElement){
        var errorMsg = 'This field is required';
        return inputElement.value ? true : errorMsg;
    },
    requiredIfRadio: function(inputElement, radioId){
        var errorMsg = 'This field is required';
        var radioInputElement = document.getElementById(radioId);
        if(radioInputElement.checked) return inputElement.value ? true : errorMsg;
        else return true;
    },
    requiredDropdown: function(inputElement){
        var errorMsg = 'This field is required';
        return inputElement.value ? true : errorMsg;
    },
    email: function(inputElement){
        var errorMsg = 'Please enter a valid email address';
        return /\S+@\S+\.\S+/.test(inputElement.value) ? true : errorMsg;
    },
    minLength: function(inputElement, min){
        var errorMsg = 'Please enter ' + min + ' or more characters';
        return (inputElement.value.length >= min) ? true : errorMsg;
    },
    equalTo: function(inputElement, compareInputElementId){
        var errorMsg = 'Please enter the same value';
        var compareInputElement = document.getElementById(compareInputElementId);
        return (inputElement.value === compareInputElement.value) ? true : errorMsg;
    },
    zipCodePh: function(inputElement){
        var errorMsg = 'Please enter zipcode of 4 number characters';
        return (/\d{4}/.test(inputElement.value) && inputElement.value.length === 4) ? true : errorMsg;
    },
    phoneNumber: function(inputElement){
        var errorMsg = 'Please enter 11-digit phone number starting with 09';
        return (/^09\d{9}/.test(inputElement.value) && inputElement.value.length === 11)  ? true : errorMsg;
    }

};

'use strict';

var validateFunction = function () {

  return function () {
    var validateInput = function (inputElement, modal) {

      // Initialize needed validations
      var validations = {
        name: ['required'],
        breed: ['requiredIfRadio:purebreed'],
        fbreed: ['requiredIfRadio:crossbreed'],
        mbreed: ['requiredIfRadio:crossbreed'],
        'select-type': ['requiredDropdown'],
        'select-farm': ['requiredDropdown'],
        'edit-name': ['required'],
        'edit-breed': ['requiredIfRadio:edit-purebreed'],
        'edit-fbreed': ['requiredIfRadio:edit-crossbreed'],
        'edit-mbreed': ['requiredIfRadio:edit-crossbreed'],
        'edit-select-type': ['requiredDropdown'],
        'edit-select-farm': ['requiredDropdown'],
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
    };

    // focusout events on add-product-modal
    $('body').on('focusout', '#add-product-modal input', function (e) {
      validateInput(this, 'add-product-modal');
    });

    // keyup events on add-product-modal
    $('body').on('keyup', '#add-product-modal input', function (e) {
      if ($(this).hasClass('invalid') || $(this).hasClass('valid')) validateInput(this, 'add-product-modal');
    });

    // focusout and keyup events on add-product-modal
    $('body').on('focusout keyup', '#edit-product-modal input', function (e) {
      validateInput(this, 'edit-product-modal');
    });

    // select change events
    $('select').change(function () {
      validateInput(this);
    });

    // Remove respective 'invalid' class and input text value
    // of current radio when radio value changes
    $("#create-product input[name='radio-breed']").change(function () {
      if ($("#create-product input:checked").val() === 'crossbreed') {
        $('input#breed').val('');
        $('input#breed').removeClass('valid invalid');
      }
      else {
        $('input#fbreed, input#mbreed').val('');
        $('input#fbreed, input#mbreed').removeClass('valid invalid');
      }
    });

    // Temporary fix for prompting 'valid' class after
    // value change on datepicker
    $('#birthdate, #edit-birthdate').change(function (e) {
      e.stopPropagation();
      $(this).removeClass('invalid').addClass('valid');
    });

    // Submit add product
    $("#create-product").submit(function (e) {
      e.preventDefault();

      var validName = validateInput(document.getElementById('name'));
      var validType = validateInput(document.getElementById('select-type'));
      var validFarmFrom = validateInput(document.getElementById('select-farm'));
      var validBreed = true;

      // Validate appropriate breed input/s according to chosen radio breed value
      if ($('#create-product input:checked').val() === 'crossbreed') {
        var validFbreed = validateInput(document.getElementById('fbreed'));
        var validMbreed = validateInput(document.getElementById('mbreed'));
        validBreed = validBreed && validFbreed && validMbreed;
      }
      else validBreed = validateInput(document.getElementById('breed'));

      if (validName && validType && validFarmFrom && validBreed) {
        // Disable submit/add product button
        $('#submit-button').addClass('disabled');
        $('#submit-button').html('Adding Product ...');

        product.add($('#create-product'));
      }
      else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');

    });

    // Update details of a product
    $('.update-button').click(function (e) {
      e.preventDefault();

      var validName = validateInput(document.getElementById('edit-name'));
      var validType = validateInput(document.getElementById('edit-select-type'));
      var validFarmFrom = validateInput(document.getElementById('edit-select-farm'));
      var validBreed = true;

      // Validate appropriate breed input/s according to chosen radio breed value
      if ($('#edit-product input:checked').val() === 'crossbreed') {
        var validFbreed = validateInput(document.getElementById('edit-fbreed'));
        var validMbreed = validateInput(document.getElementById('edit-mbreed'));
        validBreed = validBreed && validFbreed && validMbreed;
      }
      else validBreed = validateInput(document.getElementById('edit-breed'));

      if (validName && validType && validFarmFrom && validBreed) {
        // Disable update-button
        $(this).addClass('disabled');
        $(this).html('Updating...');

        product.edit($('#edit-product'), $(this));
      }
      else Materialize.toast('Please properly fill all required fields.', 2500, 'orange accent-2');

    });
  }
}

$(document).ready(validateFunction());
//# sourceMappingURL=addProduct.js.map
