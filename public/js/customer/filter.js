'use strict';

var filter = {
    apply: function(){
        // URL search syntax: ?type=value[+value]*&breed=value[+value]&sort=value*
        var types = [];
        var breeds = [];
        var filter_parameters = '?';
        var type_url = '';
        var breed_url = '';

        // Get all checked checkboxes regarding type of product
        $('.filter-type:checked').each(function(){
            types.push($(this).attr('data-type'));
        });

        // Get all checked checkboxes regarding breed of product
        $('.filter-breed:checked').each(function(){
            breeds.push($(this).attr('data-breed'));
        });

        // Join type filter values by '+' and append to filter_parameters
        if(types.length > 0){
            type_url = 'type=';
            type_url += types.join('+');
            filter_parameters += type_url;
        }

        // Join breed filter values by '+' and append to filter_parameters
        if(breeds.length > 0){
            breed_url = 'breed=';
            breed_url += breeds.join('+');
            if(types.length > 0) filter_parameters += '&'+breed_url;
            else filter_parameters += breed_url;
        }

        // Append sort parameters to filter_parameters
        if($('select option:selected').val() == ""){
            if(types.length == 0 && breeds.length == 0) filter_parameters += 'sort=none';
            else filter_parameters += '&sort=none';
        }
        else {
            if(types.length == 0 && breeds.length == 0) filter_parameters += 'sort='+$('select option:selected').val();
            else filter_parameters += '&sort='+$('select option:selected').val();
        }
        window.location = config.viewProducts_url+filter_parameters;

    }
};
