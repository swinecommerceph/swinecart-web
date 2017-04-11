'use strict'

// root vue instance in the user page
var usersPage = new Vue({
    el: '#admin-users-table',
    data: {
        
    },
    methods: {
        clicked: function(name, id, role, userable){
            users.fetch_user_info(id, role, userable);
            users.fetch_user_transaction(name, id, role, userable);
        }
    }

});

$(document).ready(function(){
    // on click of delete icon in manage-user-modal
    $('body').on('click', '.delete-button', function (e) {
      e.preventDefault();
      $('#form-delete-id').attr("value", $(this).attr("data-id"))
      $('#delete-modal').modal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
      $('#delete-modal').modal('open');
    });

    $('body').on('click', '.block-button', function (e) {
      e.preventDefault();
      $('#form-block-id').attr("value", $(this).attr("data-id"))
      $('#block-modal').modal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
      $('#block-modal').modal('open');
    });

    $('body').on('click', '.unblock-button', function (e) {
      e.preventDefault();
      $('#form-unblock-id').attr("value", $(this).attr("data-id"))
      $('#unblock-modal').modal({        // open the delete modal
        dismissible: true,
        opacity: 0,
      });
      $('#unblock-modal').modal('open');
    });

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
       $('#manage-user-modal').modal({
          dismissible: false,
          opacity: 0,
       });
       $('#manage-user-modal').modal('open');

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


    // on click of the accept button
    $('body').on('click', '.accept-button', function (e) {
      e.preventDefault();
       $('#form-accept-id').attr("value", $(this).attr("data-id"));
    //   $('#accept-reject-modal').closeModal();
    //   $('#accept-modal .modal-content h4').text($('#accept-label').text() + " User");
      $('#accept-modal').modal({
      dismissible: true,
      opacity: 0,
    });
      $('#accept-modal').modal('open');
    });


    // changing the data attribute to mark the clicked entry in the user interface
    $('body').on('click', '#cancel-accept', function (e){
     $('tr').find('[data-manage-clicked="clicked"]').attr('data-manage-clicked','');
    });

     // on click of reject icon in manage-user-modal
     $('body').on('click', '.reject-button', function (e) {
         e.preventDefault();
         $('#form-reject-id').attr("value", $(this).attr("data-id"));
        //  $('#accept-reject-modal').closeModal();
         $('#reject-modal').modal({
           dismissible: true,
           opacity: 0,
         });
         $('#reject-modal').modal('open');
     });

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
