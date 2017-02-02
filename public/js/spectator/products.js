'use strict';

var products = {
    view: function(product_form){
        $.ajax({
            url: config.spectator_url + '/users',
            type: "GET",
            cache: false,
            data: {},
            success: function(data){
                if(data.length == 0){
                    Materialize.toast('Products load failed!', 4000);
                }else{
                    Materialize.toast('Products load success!', 4000);
                }
            },
        });
    },

    search: function(){
        $.ajax({
            url: config.spectator_url + '/search',
            type: "GET",
            cache: false,
            data: {},
            success: function(data){
                Materialize.toast('Search complete!', 4000);
            }
        });
    },

    advancedSearch: function(){
        $.ajax({
            url: config.spectator_url + '/advancedSearch',
            type: "GET",
            cache: false,
            data: {},
            success: function(data){
                Materialize.toast('Search complete!', 4000);
            }
        });
    }
};
