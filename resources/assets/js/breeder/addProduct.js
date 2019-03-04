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