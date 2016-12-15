$(document).ready(function(){
    //  $('#user-table').DataTable();     // initialize data table

    // if all user tab is clicked display all approved users
    // $('body').on('click', '#all', function(e){
    //   e.preventDefault();
    //   $('#admin-header-wrapper').empty();
    //   $('#admin-header-wrapper').append('<h4 id="admin-content-panel-header">All Users</h4>' );     // change the header of the page
    //   //users.show_all();
    // });


    // if pending users tab is clicked display all pending customers
    // $('#pending-breeder').click(function(e){
    // e.preventDefault();
    // $('#admin-header-wrapper').empty();
    // $('#admin-header-wrapper').append(
    //   '<div class="col s6">'+
    //     '<h4 id="admin-content-panel-header">Pending Breeders</h4>'+
    //   '</div>'+
    //
    //  '</div>'
    // );
    // users.show_all_pending();
    // });

    // if the card for all user is clicked
    // $('#total-user-summary').click(function(e){
    // e.preventDefault();
    // $('#admin-content-panel-header').text('All Users');
    // users.show_all();
    // });

    // if the card for blocked user
    // $('#total-blocked-summary').click(function(e){
    // e.preventDefault();
    // $('#admin-content-panel-header').text('Blocked Users');
    // users.show_blocked();
    // });

    // if the card for pending breeders is clicked
    // $('#total-pending-summary').click(function(e){
    // e.preventDefault();
    // $('#admin-content-panel-header').text('Pending Users');
    // users.show_all_pending();
    // });

    // manage-user-modal triggers after manage button is clicked
    // on click of delete icon in manage-user-modal
    $('body').on('click', '.delete-button', function (e) {
      e.preventDefault();
      $('#form-delete-id').attr("value", $(this).attr("data-id"))
      $('#delete-modal').openModal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
    });

    $('body').on('click', '.block-button', function (e) {
      e.preventDefault();
      $('#form-block-id').attr("value", $(this).attr("data-id"))
      $('#block-modal').openModal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
    });

    $('body').on('click', '.unblock-button', function (e) {
      e.preventDefault();
      $('#form-unblock-id').attr("value", $(this).attr("data-id"))
      $('#unblock-modal').openModal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
    });

    // on click of the confirm button in the delete-modal
    // $('#confirm-delete').click(function(){
    //   // get the specific row by the markers in the tags
    //
    //  var button =  $('tr').find('[data-clicked="clicked"]');
    //  var change = $('tr').find('[data-clicked="clicked"]').parents('tr');
    //  var name = $('#manage-user-modal h4').text();
    //  var token = $('#delete-token').attr('value');
    //  var id = $('#delete-id').attr('value');
    //  users.delete_user(button, name, change, token, id);        // send the needed data to the called function to allow operation
    // });

    // on click of block icon in the block-modal
    // $('body').on('click', '#block-data', function (e) {
    //   e.preventDefault();
    //   $('#manage-user-modal').closeModal();  // close the modal
    //   // depending on the status of the user, select the dialog box text
    //   $('#block-modal .modal-content h4').text($('#block-label').text() + " User");
    //   if($('#block-label').text() == 'Block'){
    //      $('#block-modal .modal-content p').text('Are you sure you want to block this user?');
    //   }else{
    //      $('#block-modal .modal-content p').text('Are you sure you want to unblock this user?');
    //   }
    //   $('#block-modal').openModal({
    //     dismissible: true,
    //     opacity: 0,
    //   });
    // });

    // onclick of confirmation for blocking
    // $('body').on('click', '#confirm-block', function (e){
    // // get the specific row by the markers in the tags
    // var block_button = $('tr').find('[data-clicked="clicked"]');
    // var block_name = $('#manage-user-modal h4').text();
    // var block_token = $('#block-token').attr('value');
    // var block_id = $('#block-id').attr('value');
    // users.block_user(block_name,block_button, block_token, block_id);   // send the needed data to the called function to allow operation
    //
    // });
    // changing the values of some marker tags used to find a specific row clicked by the user
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

    // on click of the manage button
    $('body').on('click', '.manage-button', function (e){
       e.preventDefault();
       $(this).attr('data-clicked', 'clicked');
       $('#manage-user-modal').openModal({
          dismissible: false,
          opacity: 0,
       });
       // set the appearance of buttons in the user interface depending on the status of the user's attributes
       $('#manage-user-modal h4').text($(this).parents('td').siblings('.name-column').text());
       if($(this).parents('td').siblings('.status-column').text()==1){
          $('#block-icon').text('refresh');
          $('#block-label').text('Unblock');
       }
       else{
           $('#block-icon').text('block');
           $('#block-label').text('Block');
       }
       // change the value of the data attributes depending on the clicked entry in the table
       $('#delete-token').attr('value', $(this).parents('td').siblings('.token-column').text());
       $('#delete-id').attr('value', $(this).parents('td').siblings('.id-column').text());
       $('#block-token').attr('value', $(this).parents('td').siblings('.token-column').text());
       $('#block-id').attr('value', $(this).parents('td').siblings('.id-column').text());
    });


    // accept and reject user modal triggers
    // open  accept-reject-modal
    // $('body').on('click', '.manage-accept-button', function (e){
    //  e.preventDefault();
    //  $(this).attr('data-manage-clicked', 'clicked');
    //  $('#accept-reject-modal').openModal({
    //     dismissible: false,
    //     opacity: 0,
    //  });
    //  //
    //  $('#accept-reject-modal h4').text($(this).parents('td').siblings('.name-column').text());
    //  $('#accept-icon').text('check');
    //  $('#accept-label').text('Accept');
    //  // change the value of the data attributes depending on the clicked entry in the table
    //  $('#reject-token').attr('value', $(this).parents('td').siblings('.token-column').text());
    //  $('#reject-id').attr('value', $(this).parents('td').siblings('.id-column').text());
    //  $('#accept-token').attr('value', $(this).parents('td').siblings('.token-column').text());
    //  $('#accept-id').attr('value', $(this).parents('td').siblings('.id-column').text());
    // });

    // on click of the accept button
    $('body').on('click', '.accept-button', function (e) {
      e.preventDefault();
       $('#form-accept-id').attr("value", $(this).attr("data-id"));
    //   $('#accept-reject-modal').closeModal();
    //   $('#accept-modal .modal-content h4').text($('#accept-label').text() + " User");
      $('#accept-modal').openModal({
      dismissible: true,
      opacity: 0,
    });
    });

    // on click of the confirmation button in the accept modal
    // $('body').on('click', '#confirm-accept', function (e){
    // var button = $('tr').find('[data-manage-clicked="clicked"]');
    // var name = $('#accept-reject-modal h4').text();
    // var token = $('#accept-token').attr('value');
    // var id = $('#accept-id').attr('value');
    // users.approve_user(name,button, token, id);     // send the needed values in the calling function
    // });

    // changing the data attribute to mark the clicked entry in the user interface
    $('body').on('click', '#cancel-accept', function (e){
     $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked','');
    });

     // on click of reject icon in manage-user-modal
     $('body').on('click', '.reject-button', function (e) {
         e.preventDefault();
         $('#form-reject-id').attr("value", $(this).attr("data-id"));
        //  $('#accept-reject-modal').closeModal();
         $('#reject-modal').openModal({
           dismissible: true,
           opacity: 0,
         });
     });

     // in the confirmation of rejecting a pending request
   //   $('#confirm-reject').click(function(){
   //      var button =  $('tr').find('[data-manage-clicked="clicked"]');
   //      var change = $('tr').find('[data-manage-clicked="clicked"]').parents('tr');
   //      var name = $('#accept-reject-modal h4').text();
   //      var token = $('#reject-token').attr('value');
   //      var id = $('#reject-id').attr('value');
   //      users.reject_user(button, name, change, token, id);     // send the needed information to the calling function
   // });

    // if the cancel button is clicked remove the marker to the data attribute
    $('body').on('click', '#cancel-reject', function (e){
     $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked','');
    });

    // JQuery for admin forms
    $('#breeding-animals').click(function(e){
    e.preventDefault();
    $('#breeding-animals-wrapper').append(
       '<div class="input-field col s8">'+
          '<input placeholder="Breed" class="breed validate" type="text">'+
       '</div>'+
       '<div class="input-field col s2">'+
          '<input placeholder="Female" class="breed validate" type="number" min="0">'+
       '</div>'+
       '<div class="input-field col s2">'+
          '<input placeholder="Male" class="breed validate" type="number" min="0">'+
       '</div>'

    );
    });

    $('#testing-facilities').click(function(e){
    e.preventDefault();
    $('#testing-facilities-wrapper').append('<input placeholder="Testing Facility" type="text" class="validate">');
    });


});
