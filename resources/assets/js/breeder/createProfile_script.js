/*
 * Profile-related scripts
 */

$(document).ready(function(){
    /*
     * Create Profile specific
     */

    var select_province = function(i){
        // Dynamically produce select element with options based on provinces
        var selectElement = '<select name="farmAddress['+i+'][province]">';

        for(var key in provinces){
            selectElement += '<option value="' + key + '">' + key + '</option>';
        }

        selectElement += '</select>';

        return selectElement;
    };

    $('#create-profile #farm-tab').addClass('disabled');

    // Next and previous buttons
    $('#create-profile #next').click(function(e){
        e.preventDefault();
        if($('#farm-tab').hasClass('disabled')) $('#farm-tab').removeClass('disabled');
        $('ul.tabs').tabs('select_tab','farm-information');
    });

    $('#create-profile #previous').click(function(e){
        e.preventDefault();
        $('ul.tabs').tabs('select_tab','personal-information');
    });


  // Same address as office information feature
  $(".same-address-checker").change(function (e) {
    e.preventDefault();

    var farm_specific = $(this).attr('class').split(' ')[1];
    var farm_specific_province = "#" + farm_specific;
    farm_specific = "." + farm_specific;

    var office_address1 = $("#officeAddress_addressLine1").val();
    var office_address2 = $("#officeAddress_addressLine2").val();
    var office_province = $("#office_provinces").val();
    var office_postal_zip_code = $("#officeAddress_zipCode").val();
    var office_landline = $("#office_landline").val();
    var office_mobile = $("#office_mobile").val();

    if ($(".same-address-checker").is(":checked")) {
      // set values

      $(farm_specific + "-addressLine1")
        .val(office_address1)
        .addClass("input-show-hide")
      
      $(farm_specific + "-addressLine2")
        .val(office_address2)
        .addClass("input-show-hide")
      
      /* $(farm_specific_province)
        .find("input[class=select-dropdown]")
        .val(office_province)
        .change(); */

      $(farm_specific + "-zipCode")
        .val(office_postal_zip_code)
        .addClass("input-show-hide")

      $(farm_specific + "-landline")
        .val(office_landline)
        .addClass("input-show-hide")

      $(farm_specific + "-mobile")
        .val(office_mobile)
        .addClass("input-show-hide")
        
    } else {
      $(farm_specific + "-addressLine1")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-addressLine2")
        .val("")
        .removeClass("input-show-hide")

      // $(farm_specific_province).find('input[class=select-dropdown]').val('Abra')

      $(farm_specific + "-zipCode")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-farmType")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-landline")
        .val("")
        .removeClass("input-show-hide")

      $(farm_specific + "-mobile")
        .val("")
        .removeClass("input-show-hide")

    }
  });

});
