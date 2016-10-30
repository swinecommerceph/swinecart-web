'use strict'

var pages = {

    fetchImages: function(){
        $.ajax({
            url: 'home/manage/homepage',
            type: 'GET',
            cache: false,
            success: function(data){

            }// end of success

        });// end of .ajax
    }//end of fetchImages function

    


}; // end of pages variable
