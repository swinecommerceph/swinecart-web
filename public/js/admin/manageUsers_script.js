$(document).ready(function(){
  $('#user-table').DataTable();

  // if all user tab is clicked display all approved users
  $('body').on('click', '#all', function(e){
      e.preventDefault();
      $('#admin-header-wrapper').empty();
      $('#admin-header-wrapper').append('<h4 id="admin-content-panel-header">All Users</h4>' );
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

  // manage-user-modal triggers

  // on click of delete icon in manage-user-modal
  $('body').on('click', '#delete-data', function (e) {
      e.preventDefault();
      $('#manage-user-modal').closeModal();
      $('#delete-modal').openModal({
        dismissible: true,
        opacity: 0,
      });
  });

  $('#confirm-delete').click(function(){
     var button =  $('tr').find('[data-clicked="clicked"]');
     var change = $('tr').find('[data-clicked="clicked"]').parents('tr');
     var name = $('#manage-user-modal h4').text();
     var token = $('#delete-token').attr('value');
     var id = $('#delete-id').attr('value');
     users.delete_user(button, name, change, token, id);
 });



    // on click of block icon
  $('body').on('click', '#block-data', function (e) {
      e.preventDefault();
      $('#manage-user-modal').closeModal();
      $('#block-modal .modal-content h4').text($('#block-label').text() + " User");
      if($('#block-label').text() == 'Block'){
         $('#block-modal .modal-content p').text('Are you sure you want to block this user?');
      }else{
         $('#block-modal .modal-content p').text('Are you sure you want to unblock this user?');
      }
      $('#block-modal').openModal({
        dismissible: true,
        opacity: 0,
      });

      //users.block_user(name,change, token, id);
  });

  // onclick of confirmation for blocking
  $('body').on('click', '#confirm-block', function (e){
    var block_button = $('tr').find('[data-clicked="clicked"]');
    var block_name = $('#manage-user-modal h4').text();
    var block_token = $('#block-token').attr('value');
    var block_id = $('#block-id').attr('value');
    users.block_user(block_name,block_button, block_token, block_id);

  });

  // remove the data attribute value of the clicked row
  $('body').on('click', '#cancel-block', function (e){
     $('tr').find('[data-clicked="clicked"]').attr('data-clicked','');
  });

  $('body').on('click', '#cancel-delete', function (e){
     $('tr').find('[data-clicked="clicked"]').attr('data-clicked','');
  });

  // for accept and reject modal cancel
  $('body').on('click', '#cancel-accept-reject', function (e){
    $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked', '');
  });

  // for manage user modal cancel
  $('body').on('click', '#cancel-manage', function (e){
    $('tr').find('[data-clicked="clicked"]').attr('data-clicked', '');
  });


    $('body').on('click', '.manage-button', function (e){
       e.preventDefault();
       $(this).attr('data-clicked', 'clicked');
       $('#manage-user-modal').openModal({
          dismissible: false,
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
       $('#delete-token').attr('value', $(this).parents('td').siblings('.token-column').text());
       $('#delete-id').attr('value', $(this).parents('td').siblings('.id-column').text());
       $('#block-token').attr('value', $(this).parents('td').siblings('.token-column').text());
       $('#block-id').attr('value', $(this).parents('td').siblings('.id-column').text());
    });


  // accept and reject user modal triggers
  // open  accept-reject-modal
  $('body').on('click', '.manage-accept-button', function (e){
     e.preventDefault();
     $(this).attr('data-manage-clicked', 'clicked');
     $('#accept-reject-modal').openModal({
        dismissible: false,
        opacity: 0,
     });

     //console.log($(this).parents('td').siblings('.name-column').text());
     $('#accept-reject-modal h4').text($(this).parents('td').siblings('.name-column').text());
     //console.log($(this).parents('td').siblings('.status-column').text());
     $('#accept-icon').text('check');
     $('#accept-label').text('Accept');

     $('#reject-token').attr('value', $(this).parents('td').siblings('.token-column').text());
     $('#reject-id').attr('value', $(this).parents('td').siblings('.id-column').text());
     $('#accept-token').attr('value', $(this).parents('td').siblings('.token-column').text());
     $('#accept-id').attr('value', $(this).parents('td').siblings('.id-column').text());
  });

  $('body').on('click', '#accept-data', function (e) {
      e.preventDefault();
      $('#accept-reject-modal').closeModal();
      $('#accept-modal .modal-content h4').text($('#accept-label').text() + " User");
      $('#accept-modal').openModal({
      dismissible: true,
      opacity: 0,
    });
  });

  $('body').on('click', '#confirm-accept', function (e){
    var button = $('tr').find('[data-manage-clicked="clicked"]');
    var name = $('#accept-reject-modal h4').text();
    var token = $('#accept-token').attr('value');
    var id = $('#accept-id').attr('value');
    users.approve_user(name,button, token, id);
  });

  $('body').on('click', '#cancel-accept', function (e){
     $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked','');
  });

     // on click of delete icon in manage-user-modal
     $('body').on('click', '#reject-data', function (e) {
         e.preventDefault();
         $('#accept-reject-modal').closeModal();
         $('#reject-modal').openModal({
           dismissible: true,
           opacity: 0,
         });
     });

     $('#confirm-reject').click(function(){
        var button =  $('tr').find('[data-manage-clicked="clicked"]');
        var change = $('tr').find('[data-manage-clicked="clicked"]').parents('tr');
        var name = $('#accept-reject-modal h4').text();
        var token = $('#reject-token').attr('value');
        var id = $('#reject-id').attr('value');
        users.reject_user(button, name, change, token, id);
   });

  $('body').on('click', '#cancel-reject', function (e){
     $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked','');
  });




  // $('body').on('click', '#cancel-manage', function (e){
  //   $('tr').find('[data-clicked="clicked"]').attr('data-clicked', '');
  // });

});
