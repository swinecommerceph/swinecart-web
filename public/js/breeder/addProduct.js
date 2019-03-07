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
        var data = JSON.parse(data);
        var hidden_inputs =
          '<input name="productId" type="hidden" value="' + data.product_id + '">' +
          '<input name="name" type="hidden" value="' + data.name + '">' +
          '<input name="type" type="hidden" value="' + data.type + '">' +
          '<input name="breed" type="hidden" value="' + data.breed + '">';

        Materialize.toast('Product added!', 2500, 'green lighten-1');


        $('#media-dropzone').append(hidden_inputs);
        $('#add-media-modal h4').append(' to ' + "'" + data.name + "'");
        $('.add-product-button').attr('href', '#add-media-modal');
        $('#overlay-preloader-circular').remove();
        $('#add-product-modal').modal('close');
        parent_form.find('#submit-button').removeClass('disabled');

        $('#submit-button').removeClass('disabled');
        $('#submit-button').html('Add Product');

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

  /* $('#add-media-button').click(function () {

  }); */

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
$(document).ready(function () {

  // Variable for checking if all products
  // are selected or not
  var all_checked = false;

  $('#add-media-modal').modal({ dismissible: false });

  // Hide certain elements
  $('.input-crossbreed-container').hide();

  // initialization of Materialize's Date Picker
  $('.datepicker').pickadate({
    max: true,
    selectMonths: true,
    selectYears: 4,
    format: 'mmmm d, yyyy'
  });

  /* ----------- Manage Products page general functionalities ----------- */
  // Always showing FAB
  $('#action-button').show();

  // Back to top button functionality
  /*$(window).scroll(function(){
      if ($(this).scrollTop() >= 250) $('#action-button').fadeIn(200);
      else{
          $('.fixed-action-btn').closeFAB();
          $('#action-button').fadeOut(200);
      }
  });*/

  // Giving a border on product card/s when checkbox is clicked
  $('.single-checkbox').change(function (e) {
    e.preventDefault();

    // Iterates all the product cards
    $('#view-products-container input[type=checkbox]').each(function () {

      // Locates the checked card/s and retrieves the id/s for jQuery
      var string = "#product-";
      var product_id = $(this).attr('data-product-id');
      var div_id = string + product_id;

      // Apply the border on the element with class of 'card hoverable'
      var card_element = div_id + ">div";

      // Apply the border/s if checked, else remove the blue border
      if ($(this).is(':checked')) {
        $(card_element).css({
          "border": "solid 4px #00705E"
        });
      }
      else {
        $(card_element).css({
          "border": "solid 4px transparent"
        });
      }
    });
  });

  // Select All Products
  $('.select-all-button').click(function (e) {
    e.preventDefault();

    if (!all_checked) {
      // Check all checkboxes
      $('#view-products-container input[type=checkbox]').prop('checked', true);

      // Add border to all cards
      $('.card.hoverable').each(function () {
        $(this).css({
          "border": "solid 4px #00705E"
        });
      });

      $('.select-all-button i').html('event_busy');
      $('.select-all-button').attr('data-tooltip', 'Unselect all Products');
      all_checked = true;
    }
    else {
      // Uncheck all checkboxes
      $('#view-products-container input[type=checkbox]').prop('checked', false);

      // Remove the added border to all cards
      $('.card.hoverable').each(function () {
        $(this).css({
          "border": "solid 4px transparent"
        });
      });
      $('.select-all-button i').html('event_available');
      $('.select-all-button').attr('data-tooltip', 'Select all Products');
      all_checked = false;
    }

    $('.tooltipped').tooltip();
  });

  // Display Selected Button
  $('.display-selected-button').click(function (e) {
    e.preventDefault();
    var checked_products = [];

    $('#view-products-container input[type=checkbox]:checked').each(function () {
      checked_products.push($(this).attr('data-product-id'));
    });
    product.update_selected($('#manage-selected-form'), '', checked_products, 'display');
  });

  // Hide Selected Button
  $('.hide-selected-button').click(function (e) {
    e.preventDefault();
    var checked_products = [];

    $('#view-products-container input[type=checkbox]:checked').each(function () {
      checked_products.push($(this).attr('data-product-id'));
    });
    product.update_selected($('#manage-selected-form'), '', checked_products, 'hide');
  });

  // Delete selected products
  $(".delete-selected-button").click(function (e) {
    e.preventDefault();
    var checked_products = [];

    $('#view-products-container input[type=checkbox]:checked').each(function () {
      checked_products.push($(this).attr('data-product-id'));
    });
    product.delete_selected($('#manage-selected-form'), checked_products, $('#view-products-container'));
  });

  // Display chosen product
  $('body').on('click', '.display-product-button', function (e) {
    e.preventDefault();
    $(this).tooltip('remove');
    product.update_selected($('#manage-selected-form'), $(this), [$(this).attr('data-product-id')], 'display');
  });

  // Hide chosen product
  $('body').on('click', '.hide-product-button', function (e) {
    e.preventDefault();
    $(this).tooltip('remove');
    product.update_selected($('#manage-selected-form'), $(this), [$(this).attr('data-product-id')], 'hide');
  });

  // Add a product
  $('.add-product-button').click(function () {
    $('#add-product-modal').modal({
      ready: function () {
        // Programmatically select th 'swine-information' tab
        $('#add-product-modal ul.tabs').tabs('select_tab', 'swine-information');
      }
    });
    $('#add-product-modal').modal('open');
    product.modal_history.push('#add-product-modal');
  });

  // Edit chosen product
  /* $('.edit-product-button').click(function () {
    $('#edit-product-modal').modal({
      ready: function () {
        // Programmatically select the 'edit-swine-information' tab
        $('#edit-product-modal ul.tabs').tabs('select_tab', 'edit-swine-information');
      }
    });
    $('#edit-product-modal').modal('open');
    product.get_product($(this).attr('data-product-id'));
  }); */

  // Delete chosen product
  $('.delete-product-button').click(function (e) {
    e.preventDefault();
    product.delete_selected($('#manage-selected-form'), [$(this).attr('data-product-id')], $('#view-products-container'));
  });

  // Redirect to designated link upon checkbox value change
  $("#dropdown-container select").change(function () {
    filter.apply();
  });

  // Back button on modals
  $('.back-button').click(function (e) {
    e.preventDefault();

    $(product.modal_history.pop()).modal('close');

    // If going back to add-product-modal it must be directed to edit-product-modal
    if (product.modal_history_tos() === '#add-product-modal') {
      product.get_product($('#add-media-modal form').find('input[name="productId"]').val());

      // Set-up first modal action buttons
      if (product.modal_history_tos().includes('add')) {
        $('.from-add-process').show();
        $('.from-edit-process').hide();
      }
      else {
        $('.from-add-process').hide();
        $('.from-edit-process').show();
      }
    }
    else $(product.modal_history_tos()).modal('open');
  });

  /* ----------- Add Product Modal functionalities ----------- */
  $("#add-product-modal #other-details-tab").click(function (e) {
    $('#submit-button').show();
  });

  /* ----------- Add Media Modal functionalities ----------- */
  // Move to Product Summary Modal
  $('#next-button').click(function (e) {
    e.preventDefault();
    product.get_summary($('#add-media-modal form').find('input[name="productId"]').val());
  });

  // media-dropzone initialization and configuration
  Dropzone.options.mediaDropzone = {
    paramName: 'media',
    uploadMultiple: true,
    parallelUploads: 1,
    maxFiles: 12,
    maxFilesize: 50,
    acceptedFiles: "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
    dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop images/videos here to upload </h5>" +
      "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>" +
      "<br> <h5 style='font-weight: 300;'> Or just click anywhere in this container to choose file </h5>",
    previewTemplate: document.getElementById('custom-preview').innerHTML,
    init: function () {
      // Listen to events
      // Set default thumbnail for videos
      this.on("addedfile", function (file) {
        if (file.type.match(/video.*/)) this.emit("thumbnail", file, config.images_path + '/video-icon.png');
      });

      // Inject attributes on element upon success of multiple uploads
      this.on('successmultiple', function (files, response) {
        response = JSON.parse(response);
        var item = 0;
        response.forEach(function (element) {
          var preview_element = files[item].previewElement;
          preview_element.setAttribute('data-media-id', element.id);
          preview_element.getElementsByClassName('dz-filename')[0].getElementsByTagName('span')[0].innerHTML = element.name;
          item++;
        });

        $(".tooltipped").tooltip({ delay: 50 });
      });

      // Remove file from file system and database records
      this.on('removedfile', function (file) {
        var mime_type = file.type.split('/');
        var media_type = mime_type[0];
        // Do AJAX
        $.ajax({
          url: config.productMedia_url + '/delete',
          type: "DELETE",
          cache: false,
          data: {
            "_token": $('#media-dropzone').find('input[name=_token]').val(),
            "mediaId": file.previewElement.getAttribute('data-media-id'),
            "mediaType": media_type
          },
          success: function (data) {

          },
          error: function (message) {
            console.log(message['responseText']);
          }
        });
      });
    }
  };

  /* ----------- Product Summary Product Modal functionalities ----------- */
  // Save as Draft the Product created
  $('#save-draft-button').click(function (e) {
    e.preventDefault();

    // Disable save-draft-button and display-button
    $('#display-button').addClass('disabled');
    $(this).addClass('disabled');
    $(this).html('Saving as Draft ...');

    window.setTimeout(function () {
      location.reload(true);
    }, 1200);

  });

  // Display Product created
  $('#display-button').click(function (e) {
    e.preventDefault();

    // Disable display-button and save-draft-button
    $('#save-draft-button').addClass('disabled');
    $(this).addClass('disabled');
    $(this).html('Displaying ...');

    product.display_product($(this).parents('form'));
  });

  // Change html of set-display-photo anchor tag if it is a display photo
  $('body').on('click', '.set-display-photo', function (e) {
    e.preventDefault();

    // Check first if chosen image not the current primary picture
    if (product.current_display_photo != $(this).attr('data-img-id')) {
      product.set_display_photo($(this), $(this).parents('form'), $(this).attr('data-product-id'), $(this).attr('data-img-id'));
    }
  });

  $('#save-button').click(function (e) {
    e.preventDefault();

    // Disable save-button
    $(this).addClass('disabled');
    $(this).html('Saving ...');

    window.setTimeout(function () {
      location.reload(true);
    }, 1200);
  });

  /* ----------- Edit Product Modal functionalities ----------- */
  // Open Edit Media Modal
  $('#edit-media-button').click(function (e) {
    e.preventDefault();
    $('#edit-product-modal').modal('close');
    $('#edit-media-modal').modal({ dismissible: false });
    $('#edit-media-modal').modal('open');
    product.modal_history.push('#edit-media-modal')
  });

  // Open Add Media Modal
  $('#add-media-button').click(function (e) {
    e.preventDefault();
    $('#edit-product-modal').modal('close');
    $('#add-media-modal').modal({
      dismissible: false,
      ready: function () {
        product.modal_history.push('#add-media-modal');
      }
    });
    $('#add-media-modal').modal('open');
  });

  /* ----------- Edit Media Modal ----------- */
  // edit-media-dropzone initialization and configuration
  Dropzone.options.editMediaDropzone = {
    paramName: 'media',
    uploadMultiple: true,
    parallelUploads: 1,
    maxFiles: 12,
    maxFilesize: 50,
    acceptedFiles: "image/png, image/jpeg, image/jpg, video/avi, video/mp4, video/flv, video/mov",
    dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop images/videos here to upload </h5>" +
      "<i class='material-icons'>insert_photo</i> <i class='material-icons'>movie</i>" +
      "<br> <h5 style='font-weight: 300;'> Or just click anywhere in this container to choose file </h5>",
    previewTemplate: document.getElementById('custom-preview').innerHTML,
    init: function () {
      // Listen to events

      // Set default thumbnail for videos
      this.on("addedfile", function (file) {
        if (file.type.match(/video.*/)) this.emit("thumbnail", file, config.images_path + '/video-icon.png');
      });

      // Inject attributes on element upon success of multiple uploads
      this.on('successmultiple', function (files, response) {
        response = JSON.parse(response);
        var item = 0;
        response.forEach(function (element) {
          var preview_element = files[item].previewElement;
          preview_element.setAttribute('data-media-id', element.id);
          preview_element.getElementsByClassName('dz-filename')[0].getElementsByTagName('span')[0].innerHTML = element.name;
          item++;
        });

        $(".tooltipped").tooltip({ delay: 50 });
      });

      // Remove file from file system and database records
      this.on('removedfile', function (file) {
        var mime_type = file.type.split('/');
        var media_type = mime_type[0];
        // Do AJAX
        $.ajax({
          url: config.productMedia_url + '/delete',
          type: "DELETE",
          cache: false,
          data: {
            "_token": $('#media-dropzone').find('input[name=_token]').val(),
            "mediaId": file.previewElement.getAttribute('data-media-id'),
            "mediaType": media_type
          },
          success: function (data) {

          },
          error: function (message) {
            console.log(message['responseText']);
          }
        });
      });
    }
  };

  // Delete image / Delete video button
  $('body').on('click', '.delete-image, .delete-video', function (e) {
    e.preventDefault();

    // Disable delete-image/delete-video button
    $(this).addClass('disabled');
    $(this).html('Deleting ...');

    var card_container = $(this).parents('.card').first().parent();
    var data_values = {
      "_token": $('#media-dropzone').find('input[name=_token]').val(),
      "mediaId": $(this).attr('data-media-id')
    };

    // Check if the chosen media is an image and is the current display photo
    if ($(this).hasClass('delete-image') && $(this).attr('data-media-id') == product.current_display_photo) {
      Materialize.toast('Cannot delete display photo!', 1500, 'orange accent-2');

      // Enable delete-image/delete-video button
      $(this).removeClass('disabled');
      $(this).html('Delete');
    }
    else {
      // Initialize mediaType value
      if ($(this).hasClass('delete-image')) data_values["mediaType"] = 'image';
      else data_values["mediaType"] = 'video';

      // Do AJAX
      $.ajax({
        url: config.productMedia_url + '/delete',
        type: "DELETE",
        cache: false,
        data: data_values,
        success: function (data) {

          card_container.remove(); // remove the deleted card

          // added an AJAX prompt when video list is empty
          if ($('.delete-video').length == 0) {
            var empty_video_prompt = '<p class="grey-text">(No uploaded videos)</p>';
            $('#edit-videos-summary .card-content .row').html(empty_video_prompt);
          }
        },
        error: function (message) {
          console.log(message['responseText']);
        }
      });
    }

  });


  /* ----------- Form functionalities ----------- */
  // Breed radio
  $("input.purebreed , input.edit-purebreed").on('click', function () {
    $(this).parents('form').find('.input-crossbreed-container').hide();
    $(this).parents('form').find('.input-purebreed-container').fadeIn(300);
  });
  $("input.crossbreed , input.edit-crossbreed").on('click', function () {
    $(this).parents('form').find('.input-purebreed-container').hide();
    $(this).parents('form').find('.input-crossbreed-container').fadeIn(300);
  });

  $("input.purebreed , input.edit-purebreed").change(function () {
    if (this.checked) {
      $(this).parents('form').find('.input-crossbreed-container').hide();
      $(this).parents('form').find('.input-purebreed-container').fadeIn(300);
    }
  });
  $("input.crossbreed , input.edit-crossbreed").change(function () {
    if (this.checked) {
      $(this).parents('form').find('.input-purebreed-container').hide();
      $(this).parents('form').find('.input-crossbreed-container').fadeIn(300);
    }
  });

  // Manage necessary fields depending on product type
  $("#select-type").on('change', function () {
    product.manage_necessary_fields($(this).parents('form'), $(this).val());
  });
  $("#edit-select-type").on('change', function () {
    product.manage_necessary_fields($(this).parents('form'), $(this).val());
  });

  // Add other details button
  $(".add-other-details").click(function (e) {
    e.preventDefault();
    product.add_other_detail($(this).parents('form'));
  });

  // Remove a detail from other details section
  $('body').on('click', '.remove-detail', function (e) {
    e.preventDefault();
    product.remove_other_detail($(this));
  });


});

'use strict';

// Place error on specific HTML input
var placeError = function (inputElement, errorMsg) {
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

  setTimeout(function () {
    if (inputElement.id.includes('select')) {
      // For select input, find first its respective input text
      // then add the 'invalid' class
      $(inputElement)
        .parents('.select-wrapper')
        .find('input.select-dropdown')
        .addClass('invalid');
    }
    else $(inputElement).addClass('invalid');
  }, 0);
};

// Place success from specific HTML input
var placeSuccess = function (inputElement) {

  // For select input, find first its respective input text
  // then add the needed classes
  var inputTextFromSelect = (inputElement.id.includes('select')) ? $(inputElement).parents('.select-wrapper').find('input.select-dropdown') : '';

  // Check first if it is invalid
  if ($(inputElement).hasClass('invalid') || $(inputTextFromSelect).hasClass('invalid')) {
    $(inputElement)
      .parents("form")
      .find("label[for='" + inputElement.id + "']")
      .attr('data-error', false);

    setTimeout(function () {
      if (inputElement.id.includes('select')) inputTextFromSelect.removeClass('invalid').addClass('valid');
      else $(inputElement).removeClass('invalid').addClass('valid');
    }, 0);
  }
  else {
    if (inputElement.id.includes('select')) inputTextFromSelect.addClass('valid');
    else $(inputElement).addClass('valid');
  }
}

var validationMethods = {
  // functions must return either true or the errorMsg only
  required: function (inputElement) {
    var errorMsg = 'This field is required';
    return inputElement.value ? true : errorMsg;
  },
  requiredIfRadio: function (inputElement, radioId) {
    var errorMsg = 'This field is required';
    var radioInputElement = document.getElementById(radioId);
    if (radioInputElement.checked) return inputElement.value ? true : errorMsg;
    else return true;
  },
  requiredDropdown: function (inputElement) {
    var errorMsg = 'This field is required';
    return inputElement.value ? true : errorMsg;
  },
  email: function (inputElement) {
    var errorMsg = 'Please enter a valid email address';
    return /\S+@\S+\.\S+/.test(inputElement.value) ? true : errorMsg;
  },
  minLength: function (inputElement, min) {
    var errorMsg = 'Please enter ' + min + ' or more characters';
    return (inputElement.value.length >= min) ? true : errorMsg;
  },
  equalTo: function (inputElement, compareInputElementId) {
    var errorMsg = 'Please enter the same value';
    var compareInputElement = document.getElementById(compareInputElementId);
    return (inputElement.value === compareInputElement.value) ? true : errorMsg;
  },
  zipCodePh: function (inputElement) {
    var errorMsg = 'Please enter zipcode of 4 number characters';
    return (/\d{4}/.test(inputElement.value) && inputElement.value.length === 4) ? true : errorMsg;
  },
  phoneNumber: function (inputElement) {
    var errorMsg = 'Please enter 11-digit phone number starting with 09';
    return (/^09\d{9}/.test(inputElement.value) && inputElement.value.length === 11) ? true : errorMsg;
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
