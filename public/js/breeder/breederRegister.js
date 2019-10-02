$(document).ready(function(){
  // initialization for select tags
  $('select').material_select();

  // initialization of Materialize's Date Picker
  $(".datepicker").pickadate({
    max: true,
    selectMonths: true,
    selectYears: 4,
    format: "mmmm d, yyyy"
  });

  // prevent the date picker from instatly closing upon clicking
  // Materialize bug? 
  $('.datepicker').on('mousedown', function (event) {
    event.preventDefault();
  });

  // prevent the dropdown from instantly closing upon clicking
  // Materialize bug?
  $('#select-province').on('click', function (event) {
    event.stopPropagation();
  });

  // disable the farm information tab until finished
  $('#breeder-register #farm-tab').addClass('disabled');

  // use the next button to go to farm-tab after properly validating the office tab
  $('#breeder-register #next').click((e) => {
    e.preventDefault();
    if($('#farm-tab').hasClass('disabled')) $('#farm-tab').removeClass('disabled');
    $('ul.tabs').tabs('select_tab', 'farm-information');
  });

  // use the previous button to go back to office tab
  $('#breeder-register #previous').click((e) => {
    e.preventDefault();
    $('ul.tabs').tabs('select_tab', 'personal-information');
  });
})

//# sourceMappingURL=breederRegister.js.map
