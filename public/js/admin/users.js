'use strict'

var users = {

  // Show all of the approved user
  show_all: function(){
    $.ajax({
      url: 'home/userlist',
      type: 'GET',
      cache: false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class="display " id="user-table">'+
          '<thead>'+
            '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">Account Type'+
                  // '<a id="account-dropdown" class="dropdown-button white-text" data-beloworigin="true" href="#" data-activates="dropdown1">Account Type<i class="material-icons vertical-align">arrow_drop_down</i></a>'+
                  '</th>'+
                  // '<ul id="dropdown1" class="dropdown-content">'+
                  //   '<li id="all"><a href="#!">All</a></li>'+
                  //   '<li class="divider"></li>'+
                  //   '<li id="users-customer"><a href="#!">Customer</a></li>'+
                  //   '<li id="users-breeder"><a href="#!" >Breeder</a></li>'+
                  // '</ul>'+
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
      //   $('#account-dropdown').dropdown({
      //     hover: true, // Activate on hover
      //   });

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

         // Old database access without data tables
      //   data.forEach(function(data){
      //     var status;
      //     var value;
      //     var icon_color;
      //     if(data.is_blocked == 1){
      //       status = 'undo';
      //       value = 'Unblock';
      //       icon_color = 'green-text';
      //     }
      //     else {
      //       status ='block';
      //       value = 'Block';
      //       icon_color = 'orange-text';
      //     };
      //     $('#table-content').append(
      //           '<tr>'+
      //             '<td><div>'+data.name+'</div></td>'+
      //             '<td><div>'+data.title+'</div></td>'+
      //             '<td>'+
      //               '<div class="row action-column">'+
      //                 '<div class="col s6">'+
      //                   '<form method="POST" action="'+config.block_user+'" class="block-form" data-user-id="'+data.user_id+'">'+
      //                     '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                     '<input name="_method" type="hidden" value="PUT">'+
      //                     '<a href="#!" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="'+value+'" data-user-name = "'+data.name+'" data-clicked = ""><i class="material-icons block-icon '+icon_color+'"  >'+status+'</i></a>'+
      //                   '</form>'+
      //                 '</div>'+
      //                 '<div class="col s6">'+
      //                   '<form method="POST" action="'+config.delete_user+'" class="delete-form" data-user-id="'+data.user_id+'">'+
      //                     '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                     '<input name="_method" type="hidden" value="DELETE">'+
      //                     '<a  class="tooltipped delete-data" href="#!" data-position="bottom" data-delay="50" data-tooltip="Delete" data-user-name = "'+data.name+'"><i class="material-icons red-text">delete</i></a>'+
      //                   '</form>'+
        //
      //                 '</div>'+
      //               '</div>'+
      //             '</td>'+
      //           '</tr>'
      //     );
        //
      //   });
          $(".tooltipped").tooltip({delay:50});
          $('select').material_select();
      }

    });
  },

  // Show all approved users with the breeder title
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
      //   data.forEach(function(data){
      //     var status;
      //     var value;
      //     var icon_color;
      //     if(data.is_blocked == 1){
      //       status = 'undo';
      //       value = 'Unblock';
      //       icon_color = 'green-text';
      //     }
      //     else {
      //       status ='block';
      //       value = 'Block';
      //       icon_color = 'orange-text';
      //     };
      //     $('#table-content').append(
      //       '<tr>'+
      //         '<td><div>'+data.name+'</div></td>'+
      //         '<td><div>'+data.title+'</div></td>'+
      //         '<td>'+
      //           '<div class="row action-column">'+
      //             '<div class="col s6">'+
      //               '<form method="POST" action="'+config.block_user+'" class="block-form" data-user-id="'+data.user_id+'">'+
      //                 '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                 '<input name="_method" type="hidden" value="PUT">'+
      //                 '<a href="#!" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="'+value+'" data-user-name = "'+data.name+'" data-clicked = ""><i class="material-icons block-icon '+icon_color+'"  >'+status+'</i></a>'+
      //               '</form>'+
      //             '</div>'+
      //             '<div class="col s6">'+
      //               '<form method="POST" action="'+config.delete_user+'" class="delete-form" data-user-id="'+data.user_id+'">'+
      //                 '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                 '<input name="_method" type="hidden" value="DELETE">'+
      //                 '<a  class="tooltipped delete-data" href="#!" data-position="bottom" data-delay="50" data-tooltip="Delete" data-user-name = "'+data.name+'"><i class="material-icons red-text">delete</i></a>'+
      //               '</form>'+
      //             '</div>'+
      //           '</div>'+
      //         '</td>'+
      //       '</tr>'
      //     );
        //
      //   });
          $(".tooltipped").tooltip({delay:50});
      },
      error: function(message){
          console.log(message['responseText']);
      }
    });
  },

  // Show all users with pending email verification
  show_all_pending: function(){
    $.ajax({
      url: 'home/pending/users',
      type: 'GET',
      cache:false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class=" highlight bordered">'+
          '<thead>'+
            '<tr class="teal white-text">'+
                '<th data-field="id">Name</th>'+
                '<th data-field="type">Account Type</th>'+
                '<th data-field="action">Action</th>'+
            '</tr>'+
          '</thead>'+
          '<tbody id="table-content">'+

          '</tbody>'+
          '</table>'
        );
        data.forEach(function(data){
          $('#table-content').append(
                '<tr>'+
                  '<td>'+data.name+'</td>'+
                  '<td>'+data.title+'</td>'+
                  '<td>'+
                    '<div class="row action-column">'+
                      '<div class="col s6">'+
                        '<form method="POST" action="'+config.block_user+'" class="block-form" data-user-id="'+data.user_id+'">'+
                          '<input name="_token" type="hidden" value="'+data.token+'">'+
                          '<input name="_method" type="hidden" value="PUT">'+
                          '<a href="#!" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="Approve" data-user-name = "'+data.name+'" data-clicked = ""><i class="material-icons block-icon green-text">check</i></a>'+
                        '</form>'+
                      '</div>'+
                      '<div class="col s6">'+
                        '<form method="POST" action="'+config.delete_user+'" class="delete-form" data-user-id="'+data.user_id+'">'+
                          '<input name="_token" type="hidden" value="'+data.token+'">'+
                          '<input name="_method" type="hidden" value="DELETE">'+
                          '<a  class="tooltipped delete-data" href="#!" data-position="bottom" data-delay="50" data-tooltip="Reject" data-user-name = "'+data.name+'"><i class="material-icons red-text">close</i></a>'+
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
      //   data.data.forEach(function(data){
      //     var status;
      //     var value;
      //     var icon_color;
      //     if(data.is_blocked == 1){
      //       status = 'undo';
      //       value = 'Unblock';
      //       icon_color = 'green-text';
      //     }
      //     else {
      //       status ='block';
      //       value = 'Block';
      //       icon_color = 'orange-text';
      //     };
      //     $('#table-content').append(
      //           '<tr>'+
      //             '<td>'+data.name+'</td>'+
      //             '<td>'+data.title+'</td>'+
      //             '<td>'+
      //               '<div class="row action-column">'+
      //                 '<div class="col s6">'+
      //                   '<form method="POST" action="'+config.block_user+'" class="block-form" data-user-id="'+data.user_id+'">'+
      //                     '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                     '<input name="_method" type="hidden" value="PUT">'+
      //                     '<a href="#" class="tooltipped block-data" data-position="bottom" data-delay="50" data-tooltip="'+value+'" data-user-name = "'+data.name+'"><i class="material-icons block-icon '+icon_color+'"  >'+status+'</i></a>'+
      //                   '</form>'+
      //                 '</div>'+
      //                 '<div class="col s6">'+
      //                   '<form method="POST" action="'+config.delete_user+'" class="delete-form" data-user-id="'+data.user_id+'">'+
      //                     '<input name="_token" type="hidden" value="'+data.token+'">'+
      //                     '<input name="_method" type="hidden" value="DELETE">'+
      //                     '<a href="#" class="tooltipped delete-data" data-position="bottom" data-delay="50" data-tooltip="Delete" data-user-name = "'+data.name+'"><i class="material-icons red-text">delete</i></a>'+
      //                   '</form>'+
      //                 '</div>'+
      //               '</div>'+
      //             '</td>'+
      //           '</tr>'
      //     );
        //
      //   });
         $('select').material_select();
         $(".tooltipped").tooltip({delay:50});
      }

    });
  },

     delete_user: function(button,name, change, token, id){
    $.ajax({
      url: 'home/delete',
      type: 'DELETE',
      cache: false,
      data:{
          "_token" : token,
          "userId": id
      },
      success: function(data){
         var table = $('#user-table').DataTable();
         table.row( change ).remove().draw();
        //change.remove().draw();
        Materialize.toast(name + ' deleted', 2500, 'red accent-2');

      },
      error: function(message){
        console.log(message['responseText']);
      }
    });

  },

  block_user: function(name, change, token, id){
    $.ajax({
      url: 'home/block',
      type: 'PUT',
      cache: false,
      data:{
          "_token" : token,
          "userId": id
      },
      success: function(data){
         $('#block-modal').closeModal();
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

  search_user: function(){
    $.ajax({
      url: 'home/search',
      type: 'GET',
      cache: false,
      success: function(data){
        $('#main-content').empty();
        $('#main-content').append(
          '<table class="highlight bordered" id="user-table">'+
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


  approve_user: function(){
    $.ajax({
      url: 'home/approve',
      type: 'PUT',
      cache: false,
      data:{
          "_token" : token,
          "userId": id
      },
      success: function(data){


      },
      error: function(message){
        console.log(message['responseText']);
      }
    });



  }


};
