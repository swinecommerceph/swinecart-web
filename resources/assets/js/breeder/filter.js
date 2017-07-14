'use strict';

var filter = {
    apply: function(){
        // URL search syntax: ?type=value[+value]*&status=value[+value]&sort=value*
        var filter_parameters = '?';
        var type_url = 'type=';
        var status_url = '&status=';
        var sort_url = '&sort=';

        // Type parameter
        type_url += $('#type-select option:selected').val();

        // Status parameter
        status_url += $('#status-select option:selected').val();

        // Sort parameter
        sort_url += $('#sort-select option:selected').val();

        filter_parameters += type_url + status_url + sort_url;

        window.location = config.manageProducts_url+filter_parameters;

    }
};
