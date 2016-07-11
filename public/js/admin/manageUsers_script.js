$(document).ready(function(){
  $('#user-table').DataTable();
  // if all user tab is clicked display all approved users
  $('body').on('click', '#all', function(e){
      e.preventDefault();
      $('#admin-header-wrapper').empty();
      $('#admin-header-wrapper').append('<h4 id="admin-content-panel-header">All Users</h4>' );
      // $('#admin-header-wrapper').append(
      //   '<div class="col s6">'+
      //     '<h4 id="admin-content-panel-header">All Users</h4>'+
      //   '</div>'+
      //   '<div class="input-field col s6">'+
      //   '<form method="GET" action="'+config.search_user+'">'+
      //     '<input placeholder="Search" id="search" type="text" name = "search" class="validate">'+
      //     // '<a href="#!" id="search-button"></a>'+
      //   '</form>'+
      //    '</div>'+
      //  '</div>'
      // );
      users.show_all();
  });

  // if breeder tab is clicked display all approved breeders
    $('body').on('click', '#users-breeder',function(e){
      e.preventDefault();
      $('#admin-header-wrapper').empty();
      $('#admin-header-wrapper').append(
        '<div class="col s6">'+
          '<h4 id="admin-content-panel-header">Approved Breeders</h4>'+
        '</div>'+
        '<div class="input-field col s6">'+
         '<input placeholder="Search" id="search" type="text" class="validate">'+
         '</div>'+
       '</div>'
      );
      users.show_all_breeders();
    //$('admin-content-panel-header').text('Approved Breeder');
  });

  // if customer tab is clicked display all customers
  $('body').on('click', '#users-customer',function(e){
    e.preventDefault();
    $('#admin-header-wrapper').empty();
    $('#admin-header-wrapper').append(
      '<div class="col s6">'+
        '<h4 id="admin-content-panel-header">Approved Customers</h4>'+
      '</div>'+
      '<div class="input-field col s6">'+
       '<input placeholder="Search" id="search" type="text" class="validate">'+
       '</div>'+
     '</div>'
    );
    users.show_all_customers();
  });

  // if pending users tab is clicked display all pending customers
  $('#pending-breeder').click(function(e){
    e.preventDefault();
    $('#admin-header-wrapper').empty();
    $('#admin-header-wrapper').append(
      '<div class="col s6">'+
        '<h4 id="admin-content-panel-header">Pending Breeders</h4>'+
      '</div>'+
      '<div class="input-field col s6">'+
       '<input placeholder="Search" id="search" type="text" class="validate">'+
       '</div>'+
     '</div>'
    );
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

  // remove the data attribute value of the clicked row
  $('body').on('click', '#cancel-block', function (e){
    var block_button = $('body').find('a[data-clicked="clicked"]');
    block_button.attr('data-clicked', '');
  });

  $('body').on('click', '.approve-data', function (e) {
      e.preventDefault();

  });

  $('body').on('keypress', '#search', function(e){
      if(event.keyCode == 13){
        users.search_user();

      }
  });



  $('body').on('click', '.manage-button', function (e){
     $(this).attr('data-clicked', 'clicked');
     $('#manage-user-modal').openModal({
        dismissible: true,
        opacity: 0,
     });
     //console.log($(this).parents('td').siblings('.name-column').text());
     $('#manage-user-modal h4').text($(this).parents('td').siblings('.name-column').text());
     //console.log($(this).parents('td').siblings('.status-column').text());
     if($(this).parents('td').siblings('.status-column').text()==1){
        $('#block-icon').text('refresh');
        $('#block-label').text('Unblock');
     }
     else{
         $('#block-icon').text('block');
         $('#block-label').text('Block');
     }
  });



});
