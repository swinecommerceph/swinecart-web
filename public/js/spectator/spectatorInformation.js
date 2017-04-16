'use strict'
var spectator_layout = {
    show_spectator_information: function(){
        $.ajax({
          url: config.spectator_url+'/spectator_info',
          type: 'GET',
          cache:false,
          success: function(data){
              $('#this_user_information_content').empty();
              $('#this_user_information_content').html('\
                  <div class="col s12 m12 l12 xl12">\
                      <div class="row">\
                          <div class="col s6 m6 l6 xl6 current-user-label grey-text">\
                              ID\
                          </div>\
                          <div class="col s6 m6 l6 xl6 current-user-label grey-text">\
                              Spectator ID\
                          </div>\
                          <div class="col s6 m6 l6 xl6 current-user-data">\
                                '+data[0]+'\
                          </div>\
                          <div class="col s6 m6 l6 xl6 current-user-data">\
                                '+data[1]+'\
                          </div>\
                      </div>\
                      <div class="row">\
                          <div class="col s12 m12 l12 xl12 current-user-label grey-text">\
                              Name\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-data">\
                                '+data[2]+'\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-label grey-text">\
                              Email\
                          </div>\
                          <div class="col s12 m12 l12 xl12 current-user-data">\
                                '+data[3]+'\
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
