'use strict'
var admin_layout = {
    show_admin_information: function(){
        $.ajax({
          url: config.admin_url+'/admin_info',
          type: 'GET',
          cache:false,
          success: function(data){
              $('#this_user_information_content').empty();
              $('#this_user_information_content').html('\
                  <div class="col s12 m12 l12 xl12">\
                      <div class="row">\
                          <div class="col s12 m12 l12 xl12 current-user-label grey-text">\
                              Name\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-data">\
                                '+data[0]+'\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-label grey-text">\
                              Email\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-data">\
                                '+data[1]+'\
                          </div>\
                      </div>\
                  </div>\
              ');
          },
          error: function(message){
              console.log(message['responseText']);
          }
        });
    }
};
