'use strict';

var product = {
  add : function(parent_form){

    var data_values = {
        "name": parent_form.find('input[name=name]').val(),
        "type": parent_form.find('#select-type').val(),
        "farm_from_id": parent_form.find('#select-farm').val(),
        "birthdate": parent_form.find('input[name=birthdate]').val(),
        "price": parent_form.find('input[name=price]').val(),
        "adg": parent_form.find('input[name=adg]').val(),
        "fcr": parent_form.find('input[name=fcr]').val(),
        "backfat_thickness": parent_form.find('input[name=backfat_thickness]').val(),
        "_token" : parent_form.find('input[name=_token]').val(),
    };

    data_values.price = data_values.price.replace(",", ""); // remove comma in price before storing

    // Transform breed syntax if crossbreed
    if($("#create-product input:checked").val() === 'crossbreed'){
        var fbreed = parent_form.find('input[name=fbreed]').val();
        var mbreed = parent_form.find('input[name=mbreed]').val();

        data_values["breed"] = fbreed.toLowerCase().trim()+'+'+mbreed.toLowerCase().trim();
    }
    else data_values["breed"] = parent_form.find('input[name=breed]').val().toLowerCase().trim();

    data_values["other_details"] = '';

    // Do AJAX
    $.ajax({
        url: parent_form.attr('action'),
        type: "POST",
        cache: false,
        data: data_values,
        success: function(data){
      
            Materialize.toast('Product added!', 2500, 'green lighten-1');
            location.href = location.origin + '/breeder/products'; // redirect to Show Products page
        },
        error: function(message){
            console.log(message['responseText']);
            $('#overlay-preloader-circular').remove();
        }
    });
  },


  manage_necessary_fields: function(parent_form, type){

      if(type === 'semen'){
          if(product.before_select_value === 'sow' || product.before_select_value === 'gilt'){
              parent_form.find('.other-details-container').html('');
              $(product.other_details_default).prependTo(parent_form.find(".other-details-container")).fadeIn(300);
          }
          product.before_select_value = 'semen';
      }
      // Provide default values in other_details category for sow
      else if(type === 'sow' || type === 'gilt'){
          parent_form.find('.other-details-container').html('');
          $('<div class="detail-container">'+
                  '<div class="input-field col s6">'+
                      '<input class="validate valid" name="characteristic[]" type="text" value="Litter Size">'+
                      '<label for="characteristic[]" class="active">Characteristic</label>'+
                  '</div>'+
                  '<div class="input-field col s5">'+
                      '<input class="validate" name="value[]" type="text" value="">'+
                      '<label for="value[]" class="active">Value</label>'+
                  '</div>'+
                  '<div class="input-field col s1 remove-button-container">'+
                      '<a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                          '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                      '</a>'+
                  '</div>'+
              '</div>'+
          '<div class="detail-container">'+
                  '<div class="input-field col s6">'+
                      '<input class="validate valid" name="characteristic[]" type="text" value="Number of teats">'+
                      '<label for="characteristic[]" class="active">Characteristic</label>'+
                  '</div>'+
                  '<div class="input-field col s5">'+
                      '<input class="validate" name="value[]" type="text" value="">'+
                      '<label for="value[]" class="active">Value</label>'+
                  '</div>'+
                  '<div class="input-field col s1 remove-button-container">'+
      '                <a href="#" class="tooltipped remove-detail" data-position="top" data-delay="50" data-tooltip="Remove detail">'+
                          '<i class="material-icons grey-text text-lighten-1">remove_circle</i>'+
                      '</a>'+
                  '</div>'+
              '</div>').hide().prependTo(parent_form.find(".other-details-container")).fadeIn(300);

          parent_form.find('.remove-detail').tooltip({delay:50});
          product.before_select_value = type;
      }

      // Boar
      else{
          if(product.before_select_value === 'sow' || product.before_select_value === 'gilt'){
              parent_form.find('.other-details-container').html('');
              $(product.other_details_default).prependTo(parent_form.find(".other-details-container")).fadeIn(300);
          }
          product.before_select_value = 'boar';
      }
  }
}

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

  /* ----------- Form functionalities ----------- */
    // Breed radio
    $("input.purebreed").on('click', function(){
      $(this).parents('form').find('.input-crossbreed-container').hide();
      $(this).parents('form').find('.input-purebreed-container').fadeIn(300);
  });
  $("input.crossbreed").on('click', function(){
      $(this).parents('form').find('.input-purebreed-container').hide();
      $(this).parents('form').find('.input-crossbreed-container').fadeIn(300);
  });

  // Manage necessary fields depending on product type
  $("#select-type").on('change', function(){
      product.manage_necessary_fields($(this).parents('form'), $(this).val());
  });
  $("#edit-select-type").on('change', function(){
      product.manage_necessary_fields($(this).parents('form'), $(this).val());
  });
});