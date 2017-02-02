'use strict'

var users = {

  // Show all of the approved user
  show_all: function(){
    $.ajax({
      url: 'home/userlist',
      type: 'GET',
      cache: false,
      success: function(data){
        $('#main-content').empty(); // clear content of the content pane/div
        $('#main-content').append(  // append the fetched content
          '<table class="display " id="user-table">'+
          '<thead>'+
            '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">Account Type'+
                '</th>'+
                '<th data-field="action">Action</th>'+
                '<th data-field="block-status">Status</th>'+
                '<th data-field="user-id">ID</th>'+
                '<th data-field="user-token">Token</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="table-content">'+

          '</tbody>'+
          '</table>'
       );
       // add the data table
       var table =  $('#user-table').DataTable({
            data: data,
            columns: [
                // initilize table columns
               {className: "name-column",data: 'name'},
               {className: "title-column", data: 'title'},
               {
                   // add manage button
                  "defaultContent": '<div class="center"><a class="waves-effect waves-light btn teal lighten-2 manage-button" data-clicked=""><i class="material-icons left">create</i>Manage</a></div>'
               },
               // extra attributes that are not displayed in the interface and are not searchable
               {className:"status-column" ,data: 'is_blocked', bSearchable: false},
               {className:"id-column", data: 'user_id', bSearchable:false},
               {className:"token-column",data: 'token', bSearchable:false}

            ]

         });
          $(".tooltipped").tooltip({delay:50});
          $('select').material_select(); // fix to integrate Materialize UI to the data table select
      }

    });
  },

  // Show all approved users (not used yet in the site but will be used for breeder transactions)
  show_all_breeders: function(){
    $.ajax({
      url: 'home/approved/breeder',
      type: 'GET',
      cache:false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class="highlight bordered">'+
          '<thead>'+
              '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">'+
                  '<a id="account-dropdown" class="dropdown-button white-text" data-beloworigin="true" href="#" data-activates="dropdown1">Account Type<i class="material-icons vertical-align">arrow_drop_down</i></a>'+
                  '</th>'+
                  '<ul id="dropdown1" class="dropdown-content">'+
                    '<li id="all"><a href="#!">All</a></li>'+
                    '<li class="divider"></li>'+
                    '<li id="users-customer"><a href="#!">Customer</a></li>'+
                    '<li id="users-breeder"><a href="#!" >Breeder</a></li>'+
                  '</ul>'+
                '<th data-field="action">Action</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="table-content">'+

          '</tbody>'+
          '</table>'
        );

        $('#account-dropdown').dropdown({
          hover: true, // Activate on hover
        });
        // to modify buttons in the user interface
        data.forEach(function(data){
          var status;
          var value;
          var icon_color;
          if(data.is_blocked == 1){
            status = 'undo';
            value = 'Unblock';
            icon_color = 'green-text';
          }
          else {
            status ='block';
            value = 'Block';
            icon_color = 'orange-text';
          };
          $('#table-content').append(
            '<tr>'+
              '<td><div>'+data.name+'</div></td>'+
              '<td><div>'+data.title+'</div></td>'+
              '<td>'+
                '<div class="row action-column">'+
                  '<div class="col s6">'+
                    '<form method="POST" action="'+config.block_user+'" class="block-form" data-user-id="'+data.user_id+'">'+
                      '<input name="_token" type="hidden" value="'+data.token+'">'+
                      '<input name="_method" type="hidden" value="PUT">'+
                      '<a href="#!" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="'+value+'" data-user-name = "'+data.name+'" data-clicked = ""><i class="material-icons block-icon '+icon_color+'"  >'+status+'</i></a>'+
                    '</form>'+
                  '</div>'+
                  '<div class="col s6">'+
                    '<form method="POST" action="'+config.delete_user+'" class="delete-form" data-user-id="'+data.user_id+'">'+
                      '<input name="_token" type="hidden" value="'+data.token+'">'+
                      '<input name="_method" type="hidden" value="DELETE">'+
                      '<a  class="tooltipped delete-data" href="#!" data-position="bottom" data-delay="50" data-tooltip="Delete" data-user-name = "'+data.name+'"><i class="material-icons red-text">delete</i></a>'+
                    '</form>'+
                  '</div>'+
                '</div>'+
              '</td>'+
            '</tr>'
          );

        });
          $(".tooltipped").tooltip({delay:50});

      },
      error: function(message){
          console.log(message['responseText']);
      }
    });
  },

  // Show all approved users with the customer title
  show_all_customers: function(){
    $.ajax({
      url: 'home/approved/customer',
      type: 'GET',
      cache:false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class="highlight bordered">'+
          '<thead>'+
              '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">'+
                  '<a id="account-dropdown" class="dropdown-button white-text" data-beloworigin="true" href="#" data-activates="dropdown1">Account Type<i class="material-icons vertical-align">arrow_drop_down</i></a>'+
                  '</th>'+
                  '<ul id="dropdown1" class="dropdown-content">'+
                    '<li id="all"><a href="#!">All</a></li>'+
                    '<li class="divider"></li>'+
                    '<li id="users-customer"><a href="#!">Customer</a></li>'+
                    '<li id="users-breeder"><a href="#!" >Breeder</a></li>'+
                  '</ul>'+
                '<th data-field="action">Action</th>'+
                '<th data-field="block-status">Status</th>'+
                '<th data-field="user-id">ID</th>'+
                '<th data-field="user-token">Token</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="table-content">'+

          '</tbody>'+
          '</table>'
        );

        $('#account-dropdown').dropdown({
          hover: true, // Activate on hover
        });
        // initialize data table instance
        var table =  $('#user-table').DataTable({
            data: data,
            columns: [
               {className: "name-column",data: 'name'},
               {className: "title-column", data: 'title'},
               {
                  "defaultContent": '<div class="center"><a class="waves-effect waves-light btn teal lighten-2 manage-button" data-clicked=""><i class="material-icons left">create</i>Manage</a></div>'
               },
               {className:"status-column" ,data: 'is_blocked', bSearchable: false},
               {className:"id-column", data: 'user_id', bSearchable:false},
               {className:"token-column",data: 'token', bSearchable:false}
            ]

         });
          $(".tooltipped").tooltip({delay:50});
      },
      error: function(message){
          console.log(message['responseText']);
      }
    });
  },

  // Show all breeders with pending email verification
  show_all_pending: function(){
    $.ajax({
      url: 'home/pending/users',
      type: 'GET',
      cache:false,
      success: function(data){
         $('#main-content').empty();
         $('#main-content').append(
           '<table class="display " id="user-table">'+
           '<thead>'+
             '<tr class="teal white-text">'+
                 '<th data-field="id">Name</th>'+
                 '<th data-field="type">Account Type</th>'+
                 '<th data-field="action">Action</th>'+
                 '<th data-field="block-status">Status</th>'+
                 '<th data-field="user-id">ID</th>'+
                 '<th data-field="user-token">Token</th>'+
             '</tr>'+
           '</thead>'+
           '<tbody id="table-content">'+

           '</tbody>'+
           '</table>'
         );
         // initialize data table
          var table =  $('#user-table').DataTable({
              data: data,
              columns: [
                 {className: "name-column",data: 'name'},
                 {className: "title-column", data: 'title'},
                 {
                    "defaultContent": '<div class="center"><a class="waves-effect waves-light btn teal lighten-2 manage-accept-button" data-manage-clicked=""><i class="material-icons left">create</i>Manage</a></div>'
                 },
                 {className:"status-column" ,data: 'approved', bSearchable: false},
                 {className:"id-column", data: 'user_id', bSearchable:false},
                 {className:"token-column",data: 'token', bSearchable:false}
              ]

      });

        $(".tooltipped").tooltip({delay:50});
        $('select').material_select(); // fix the problem with data table integration with materialize select
      },
      error: function(message){
          console.log(message['responseText']);
      }
    });
  },

  // show all blocked users
  show_blocked: function(){
    $.ajax({
      url: 'home/approved/blocked',
      type: 'GET',
      cache: false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class="display " id="user-table">'+
          '<thead >'+
            '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">Account Type</th>'+
                '<th data-field="action">Action</th>'+
                '<th data-field="block-status">Status</th>'+
                '<th data-field="user-id">ID</th>'+
               '<th data-field="user-token">Token</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="table-content">'+

          '</tbody>'+
          '</table>'
        );
        // initialize data table
        $('#user-table').DataTable({
           data: data,
           columns: [
             {className: "name-column",data: 'name'},
             {className: "title-column", data: 'title'},
             {
                 "defaultContent": '<div class="center"><a class="waves-effect waves-light btn teal lighten-2 manage-button" data-clicked="" data-show="1"><i class="material-icons left">create</i>Manage</a></div>'
             },
             {className:"status-column" ,data: 'is_blocked', bSearchable: false},
             {className:"id-column", data: 'user_id', bSearchable:false},
             {className:"token-column",data: 'token', bSearchable:false}
           ]
        });
         $('select').material_select();
         $(".tooltipped").tooltip({delay:50});
      }

    });
  },

  // Delete certain user account
  delete_user: function(button,name, change, token, id){
    $.ajax({
      url: 'home/delete',
      type: 'DELETE',
      cache: false,
      data:{
          "_token" : token,
          "userId": id
      },
      // for successful delete operation
      success: function(data){
         var table = $('#user-table').DataTable();
         table.row( change ).remove().draw();   // delete a row in the data table
         Materialize.toast(name + ' deleted', 2500, 'red accent-2');    // dispay toast notification

      },
      error: function(message){
        console.log(message['responseText']);
      }
    });

  },

  // block a user
  block_user: function(name, change, token, id){
    $.ajax({
      url: 'home/block',
      type: 'PUT',
      cache: false,
      data:{
          "_token" : token,
          "userId": id
      },
      // if block is successful change the status of the user interface to match database status
      success: function(data){
         $('#block-modal').modal('close');
          // modify status markers/indicators and user interface to assign changed values
          if($('tr').find('[data-clicked="clicked"]').parents('td').siblings('.status-column').text()==1){
             $('tr').find('[data-clicked="clicked"]').parents('td').siblings('.status-column').text(0);
             Materialize.toast(name + ' Unblocked', 2500, 'green accent');
          }
          else{
             $('tr').find('[data-clicked="clicked"]').parents('td').siblings('.status-column').text(1);
             Materialize.toast(name + ' Blocked', 2500, 'orange accent');
          }

         if($('tr').find('[data-clicked="clicked"]').parents('td').siblings('.status-column').text()==1){
           $('#block-icon').text('block');
           $('#block-label').text('Block');
         }
         else{
             $('#block-icon').text('refresh');
             $('#block-label').text('Unblock');
         }

      },
      error: function(message){
        console.log(message['responseText']);
      }
    });

  },

  add_new_breeder : function(){
   $.ajax({
      url: 'home/add',
      type: 'POST',
      cache: false,
      data:{

      },

   });
 },

    // @TODO: Remove row automatically after approval of request
    //  approve a pending breeder request
    approve_user: function(name,button, token, id){
        $.ajax({
          url: 'home/approve',
          type: 'PUT',
          cache: false,
          data:{
              "_token" : token,
              "userId": id
          },
          // if operation is successful
          success: function(data){
             Materialize.toast(name + "'s Application Accepted", 2500, 'green accent'); // display a toast notification
             var table = $('#user-table').DataTable();      // call the data table instance
             table.row( button ).remove().draw();           // add the row to the data table instance
          },
          error: function(message){
            console.log(message['responseText']);
          }
    });



    },

    // @TODO: Remove row automatically after rejection of request
    //  reject a pending breeder request
    reject_user: function(button,name, change, token, id){
        $.ajax({
        url: 'home/reject',
        type: 'DELETE',
        cache: false,
        data:{
         "_token" : token,
         "userId": id
        },
        success: function(data){
        var table = $('#user-table').DataTable();               // call data table instance
        table.row( change ).remove().draw();
        Materialize.toast(name + "'s Application Rejected", 2500, 'red accent-2');

        },
        error: function(message){
        console.log(message['responseText']);
        }
        });

    },



};
