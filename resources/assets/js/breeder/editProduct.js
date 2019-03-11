function submitEditedProduct(parent_form, update_button) {
  var data_values = {
    "id": product_data.id,
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
    update_button.html('Edit Product');

    // Then get the product summary
    //product.modal_history.push('#edit-product-modal');
    product.get_summary(product_data.id);
  });
}
