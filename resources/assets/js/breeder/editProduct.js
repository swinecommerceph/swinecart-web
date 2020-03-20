function submitEditedProduct(parent_form, update_button) {
  const dateFromForm = parent_form.find("input[name='edit_birthdate']").val();
  let submittedBirthdate = null;

  if (dateFromForm) {
    submittedBirthdate = parent_form.find("input[name='edit_birthdate']").val();
  }

  var data_values = {
    id: product_data.id,
    name: parent_form.find("input[name='edit-name']").val(),
    type: parent_form.find("#edit-select-type").val(),

    min_price: parent_form.find("input[name=edit-min_price]").val(),
    max_price: parent_form.find("input[name=edit-max_price]").val(),

    farm_from_id: parent_form.find("#edit-select-farm").val(),
    birthdate: submittedBirthdate,
    birthweight: parent_form.find("input[name=edit-birthweight]").val(),

    house_type: parent_form.find("#edit-select-housetype").val(),

    // "price": parent_form.find("input[name='edit-price']").val(),
    adg: parent_form.find("input[name='edit-adg']").val(),
    fcr: parent_form.find("input[name='edit-fcr']").val(),
    backfat_thickness: parent_form
      .find("input[name='edit-backfat_thickness']")
      .val(),
    lsba: parent_form.find("input[name=edit-lsba]").val(),
    left_teats: parent_form.find("input[name=edit-left_teats]").val(),
    right_teats: parent_form.find("input[name=edit-right_teats]").val(),
    other_details: $("textarea#edit-other_details").val(),
    quantity: $(".edit-product-quantity").val(),
    _token: parent_form.find("input[name=_token]").val()
  };

  /* Check if the checkbox for product uniqueness is checked or not */
  if ($(".edit-product-unique-checker").is(":checked")) {
    data_values["is_unique"] = 1;
    data_values.quantity = 1;
  } else data_values["is_unique"] = 0;

  /* Set proper values for semen type */
  var edit_select_type_value = $("#edit-select-type option:selected").text();
  if (edit_select_type_value === "Semen") {
    data_values["is_unique"] = 0;
    data_values.quantity = -1;
  }

  // data_values.price = data_values.price.replace(",", "");
  data_values.min_price = data_values.min_price.replace(",", ""); // remove comma in price before storing
  data_values.max_price = data_values.max_price.replace(",", ""); // remove comma in price before storing

  // Transform breed syntax if crossbreed
  if ($("#edit-product input:checked").val() === "crossbreed") {
    var fbreed = parent_form.find("input[name='edit-fbreed']").val();
    var mbreed = parent_form.find("input[name='edit-mbreed']").val();

    data_values["breed"] =
      fbreed.toLowerCase().trim() + "+" + mbreed.toLowerCase().trim();
  } else
    data_values["breed"] = parent_form
      .find("input[name='edit-breed']")
      .val()
      .toLowerCase()
      .trim();

  /* data_values["other_details"] = ''; */

  $.when(
    // Wait for the update on the database
    // Do AJAX
    $.ajax({
      url: parent_form.attr("action"),
      type: "PUT",
      cache: false,
      data: data_values,
      success: function(data) {
        Materialize.toast("Product updated!", 1500, "green lighten-1");
        // $('#edit-product-modal').modal('close');
      },
      error: function(message) {
        // console.log(message['responseText']);
        console.log("Error in editing product");
      }
    })
  ).done(function() {
    // Enable update-button
    update_button.removeClass("disabled");
    update_button.html("Edit Product");

    // Then get the product summary
    //product.modal_history.push('#edit-product-modal');
    product.get_summary(product_data.id);
  });
}
