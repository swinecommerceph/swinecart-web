'use strict'

var pages = {

    fetchImages: function(){
        $.ajax({
            url: 'home/manage/homepage',
            type: 'GET',
            cache: false,
            success: function(data){
                Materialize.toast(data.message, 2500, 'red accent-2');
            }// end of success

        });// end of .ajax
    },//end of fetchImages function

    show_all: function(){
      $.ajax({
        url: 'home/manage/return/userlist',
        type: 'GET',
        cache: false,
        success: function(data){
          $('#main-content').empty(); // clear content of the content pane/div

        }

      });
    },



}; // end of pages variable
