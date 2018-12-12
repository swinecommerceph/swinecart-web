$(document).ready(function () {

  /*
    Toggles eye icon button that shows/hide 
    password
  */
  $('.show-hide-password').click(function () {

    var password_field = $('.login-password');
    
    // change eye icon and show password text
    if ($('#show-hide-password-icon').text() === 'visibility') {
      $('#show-hide-password-icon').text('visibility_off');
      password_field.attr('type', 'text');
    }   

    // change eye icon and hide password text
    else {
      $('#show-hide-password-icon').text('visibility');
      password_field.attr('type', 'password');
    }
  });
});

//# sourceMappingURL=login.js.map
