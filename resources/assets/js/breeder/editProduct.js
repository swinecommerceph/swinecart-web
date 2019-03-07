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
