$(document).ready(function(){
  // show all users at page load
  //  users.show_all();

  // if all user tab is clicked display all approved users
  $('body').on('click', '#all', function(e){
      e.preventDefault();
      $('#admin-content-panel-header').text('All Users');
      users.show_all();
  });

  // if breeder tab is clicked display all approved breeders
    $('body').on('click', '#users-breeder',function(e){
      e.preventDefault();
      $('#admin-content-panel-header').text('Approved Breeders');
      users.show_all_breeders();
    //$('admin-content-panel-header').text('Approved Breeder');
  });

  // if customer tab is clicked display all customers
  $('body').on('click', '#users-customer',function(e){
    e.preventDefault();
    $('#admin-content-panel-header').text('Approved Customers');
    users.show_all_customers();
  });

  // if pending users tab is clicked display all pending customers
  $('#pending-breeder').click(function(e){
    e.preventDefault();
    $('#admin-content-panel-header').text('Pending Users');
    users.show_all_pending();
  });

  // if manage pages tab is clicked display the manage pages view
  $('#pages-home').click(function(e){
    e.preventDefault();
      $('#admin-content-panel-header').text('Manage Home Page');
      $('#main-content').empty();

  });

  $('#total-user-summary').click(function(e){
    e.preventDefault();
    $('#admin-content-panel-header').text('All Users');
    users.show_all();
  });

  $('#total-blocked-summary').click(function(e){
    e.preventDefault();
    $('#admin-content-panel-header').text('Blocked Users');
    users.show_blocked();
  });

  $('#total-pending-summary').click(function(e){
    e.preventDefault();
    $('#admin-content-panel-header').text('Pending Users');
    users.show_all_pending();
  });




  // on click of delete icon
  $('body').on('click', '.delete-data', function (e) {
      e.preventDefault();
      //console.log("delete");
      //var data = $(this).siblings().find('input[name=_token]').val();
      //console.log($(this).siblings().first().val());
      //var value = $(this).siblings().first().val();
      //console.log("TOKEN" + $(this).siblings().first().val() + " ID: " + $(this).parent('.delete-form').attr('data-user-id'));
      //var source =  $(this);

      //console.log($(this).parents('li').attr('class'));
      var button = $(this);
      var change = $(this).parents('tr');
      var name = $(this).attr('data-user-name');
      var token = $(this).siblings().val();
      var id = $(this).parent('.delete-form').attr('data-user-id');
      $('#delete-modal').openModal({
        dismissible: true,
        opacity: 0,
      });
      $('#confirm-delete').click(function(){
        users.delete_user(button, name, change, token, id);
      });

      // var color = $(this).parents('li').attr('class').split(' ')[2];
      //console.log($(this).nextAll().attr('class', 'collection-item avatar red');
      //$(this).parents('li').removeClass('teal lighten-4');
      //users.delete_user(button, name, change, $(this).siblings().val(), $(this).parent('.delete-form').attr('data-user-id'));
  });

    // on click of block icon
  $('body').on('click', '.block-data', function (e) {
      e.preventDefault();
      // var block_button= $(this);
      // var block_name = $(this).attr('data-user-name');
      // var block_token = $(this).siblings().val();
      // var block_id = $(this).parent('.block-form').attr('data-user-id');
      //console.log($(this));
      $(this).attr('data-clicked', 'clicked');
      // console.log($(this).attr('data-clicked'));
      $('#block-modal .modal-content h4').text($(this).attr('data-tooltip') + " User");
      $('#block-modal').openModal({
        dismissible: true,
        opacity: 0,
      });

      //users.block_user(name,change, token, id);
  });

  // onclick of confirmation for blocking
  $('body').on('click', '#confirm-block', function (e){
    var block_button = $('body').find('a[data-clicked="clicked"]');
    var block_name = block_button.attr('data-user-name');
    var block_token = block_button.siblings().val();
    var block_id = block_button.parent('.block-form').attr('data-user-id');
    users.block_user(block_name,block_button, block_token, block_id);
    block_button.attr('data-clicked', '');
    // console.log("www");
  });

  $('body').on('click', '#cancel-block', function (e){
    var block_button = $('body').find('a[data-clicked="clicked"]');
    //console.log(block_button.html());
    block_button.attr('data-clicked', '');
    //console.log("www");
  });



  $('body').on('click', '.approve-data', function (e) {
      e.preventDefault();

  });



});
