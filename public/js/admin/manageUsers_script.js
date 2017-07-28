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
            users.fetch_breeder_farm_information(id, role, userable);


        }
    }

});
