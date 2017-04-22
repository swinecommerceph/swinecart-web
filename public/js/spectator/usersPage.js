'use strict'

var usersPage = new Vue({
    el: '#spectator_users_page',
    data: {
    },
    methods: {
        clicked: function(id, role){
            users.fetch_user_info(id, role);
            
        }
    }

})
