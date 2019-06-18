/*
 * Profile-related scripts
 */

$(document).ready(function () {
  /*
   *	Update Profile specific
   */

  Dropzone.options.logoDropzone = false;

  var logoDropzone = new Dropzone('#logo-dropzone', {
    paramName: 'logo',
    uploadMultiple: false,
    maxFiles: 1,
    maxFilesize: 5,
    acceptedFiles: "image/png, image/jpeg, image/jpg",
    dictDefaultMessage: "<h5 style='font-weight: 300;'> Drop image here to upload logo</h5>",
    previewTemplate: document.getElementById('custom-preview').innerHTML,
    init: function () {

      // Inject attributes upon success of file upload
      this.on('success', function (file, response) {
        var response = JSON.parse(response);
        var previewElement = file.previewElement;

        previewElement.setAttribute('data-image-id', response.id);
        file.name = response.name;
        $('.dz-filename span[data-dz-name]').html(response.name);

        $(".tooltipped").tooltip({ delay: 50 });
      });

      // Remove file from file system and database records
      this.on('removedfile', function (file) {
        console.log(file.previewElement);

        if (file.previewElement.getAttribute('data-image-id')) {
          // Do AJAX
          $.ajax({
            url: config.breederLogo_url,
            type: "DELETE",
            cache: false,
            data: {
              "_token": $('#logo-dropzone').find('input[name=_token]').val(),
              "imageId": file.previewElement.getAttribute('data-image-id')
            },
            success: function (data) {

            },
            error: function (message) {
              console.log(message['responseText']);
            }
          });
        }
      });

    }
  });

  
  // for enabling select tags
  $("select").material_select();

  // Same address as office information feature
  $(".same-address-checker").change(function (e) {
    e.preventDefault();

    var farm_specific = $(this).attr('class').split(' ')[1];
    farm_specific = "#" + farm_specific;

    var office_address1 = $("#officeAddress_addressLine1").val();
    var office_address2 = $("#officeAddress_addressLine2").val();
    var office_province = $("#office_provinces").val();
    var office_postal_zip_code = $("#officeAddress_zipCode").val();
    var office_landline = $("#office_landline").val();
    var office_mobile = $("#office_mobile").val();

    if ($(this).is(":checked")) {

      // set values
      $(farm_specific + "-addressLine1").val(office_address1).addClass('input-show-hide');
      $(farm_specific + "-addressLine2").val(office_address2).addClass('input-show-hide');
      $(farm_specific).find('input[class=select-dropdown]').val(office_province).addClass('input-show-hide');
      $(farm_specific + "-zipCode").val(office_postal_zip_code).addClass('input-show-hide');
      $(farm_specific + "-landline").val(office_landline).addClass('input-show-hide');
      $(farm_specific + "-mobile").val(office_mobile).addClass('input-show-hide');
    }
    else {
      $(farm_specific + "-addressLine1").val('').removeClass('input-show-hide');
      $(farm_specific + "-addressLine2").val('').removeClass('input-show-hide');
      // $(farm_specific).find('input[class=select-dropdown]').val('Abra').removeClass('input-show-hide');
      $(farm_specific + "-zipCode").val('').removeClass('input-show-hide');
      $(farm_specific + "-farmType").val('').removeClass('input-show-hide');
      $(farm_specific + "-landline").val('').removeClass('input-show-hide');
      $(farm_specific + "-mobile").val('').removeClass('input-show-hide');
    }
  });

  // Change Logo
  $("#change-logo").on('click', function (e) {
    e.preventDefault();

    $("#change-logo-modal").modal({ dismissible: false });
    $("#change-logo-modal").modal('open');
  });

  // Confirm Change Logo
  $("#confirm-change-logo").on('click', function (e) {
    e.preventDefault();

    $(this).html("Setting Logo...");
    $(this).addClass("disabled");

    profile.set_logo($('#logo-dropzone'), logoDropzone);
  });

  // Cancel on Editing a Personal/Farm Information
  $('.cancel-button').click(function (e) {
    e.preventDefault();
    var cancel_button = $(this);
    var edit_button = cancel_button.parents('.content-section').find('.edit-button');
    var parent_form = cancel_button.parents('form');

    profile.cancel(parent_form, edit_button, cancel_button);
  });

  // Remove an instance of the current farm information/s
  $('.remove-farm').click(function (e) {
    e.preventDefault();
    var remove_button = $(this);
    var parent_form = remove_button.parents('form');
    var row = remove_button.parents('.add-farm');

    //  Check if there are more than 1 farm information to remove
    if ($('#farm-address-body').find('.delete-farm .remove-farm').length > 1) {
      $('#confirmation-modal').modal('open');
      $('#confirm-remove').click(function (e) {
        e.preventDefault();
        profile.remove(parent_form, row);
      });
      location.href = '#';
    }
    else Materialize.toast('At least 1 Farm information required', 2500, 'orange accent-2');
  });

});
