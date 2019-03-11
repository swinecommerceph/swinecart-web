$(document).ready(function () {

  /** 
   *  Used for filling the input fields of the product with the initial data from the database
  */

  // SWINE INFORMATION
  $('#edit-name').val(product.name);
  $('#edit-select-type').val(product.type.toLowerCase());
  $('#edit-select-farm').val(product.farm_from_id);
  $('#edit-price').val(product.price);

  // BREED INFORMATION

  // For the breed initialization
  if (product.breed.includes('x')) {
    console.log('here');
    var crossbreed = product.breed.split('x');

    // Check the crossbreed radio
    $('.crossbreed').prop('checked', true);

    $('#edit-fbreed').val(crossbreed[0].toString().trim());
    $('#edit-mbreed').val(crossbreed[1].toString().trim());
    setTimeout(function () {
      $('.input-purebreed-container').hide();
      $('.input-crossbreed-container').show();
    }, 100);

  }
  else {
    // Check the purebreed radio
    $('.purebreed').prop('checked', true);

    $('#edit-breed').val(product.breed);
    $('.input-crossbreed-container').hide();
    $('.input-purebreed-container').show();
  }

  // setting the birthdate differently since simple val() does not work
  var birthdatePicker = $('#edit-birthdate').pickadate();
  var picker = birthdatePicker.pickadate('picker');
  picker.set('select', new Date(product.birthdate));

  $('#edit-adg').val(product.adg);
  $('#edit-fcr').val(product.fcr);
  $('#edit-backfat_thickness').val(product.backfat_thickness);

  // for enabling select tags
  $('select').material_select();

});

function submitEditedProduct(parent_form, update_button) {
  var data_values = {
    "id": product.id,
    "name": parent_form.find("input[name='edit-name']").val(),
    "type": parent_form.find('#edit-select-type').val(),
    "farm_from_id": parent_form.find('#edit-select-farm').val(),
    "birthdate": parent_form.find("input[name='edit-birthdate']").val(),
    "price": parent_form.find("input[name='edit-price']").val(),
    "adg": parent_form.find("input[name='edit-adg']").val(),
    "fcr": parent_form.find("input[name='edit-fcr']").val(),
    "backfat_thickness": parent_form.find("input[name='edit-backfat_thickness']").val(),
    "_token": parent_form.find('input[name=_token]').val(),
  };

  data_values.price = data_values.price.replace(",", "");

  // Transform breed syntax if crossbreed
  if ($("#edit-product input:checked").val() === 'crossbreed') {
    var fbreed = parent_form.find("input[name='edit-fbreed']").val();
    var mbreed = parent_form.find("input[name='edit-mbreed']").val();

    data_values["breed"] = fbreed.toLowerCase().trim() + '+' + mbreed.toLowerCase().trim();
  }
  else data_values["breed"] = parent_form.find("input[name='edit-breed']").val().toLowerCase().trim();

  data_values["other_details"] = '';

  $.when(
    // Wait for the update on the database
    // Do AJAX
    $.ajax({
      url: parent_form.attr('action'),
      type: "PUT",
      cache: false,
      data: data_values,
      success: function (data) {
        Materialize.toast('Product updated!', 1500, 'green lighten-1');
        // $('#edit-product-modal').modal('close');
      },
      error: function (message) {
        // console.log(message['responseText']);
        console.log('Error in editing product');
      }
    })
  ).done(function () {
    // Enable update-button
    update_button.removeClass('disabled');
    update_button.html('Update Product');

    // Then get the product summary
    //product.modal_history.push('#edit-product-modal');
    //product.get_summary($('#edit-product').find('input[name="productId"]').val());
  });
}
