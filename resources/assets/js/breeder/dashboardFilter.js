$(document).ready(function () {

  // Redirect to designated link upon checkbox value change
  $("#select-farm").change(function () {
    
    const selectedFarm = $('#select-farm').val();
    
    window.location = (`${config.dashboard_url}?farm_address=${selectedFarm}`);

  });

});
