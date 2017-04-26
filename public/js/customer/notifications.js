'use strict';

var notificationsPage = new Vue({
    el: '#notification-page-collection',
    data:{
        token: '',
        notifications: [],
        notificationCount: 0
    },
    methods:{
        getNotificationInstances: function(){
            // Get notification instances of Customer

            // Do AJAX
            this.$http.get(
                config.customerNotifications_url + '/get',
                {}
            ).then(
                function(response){
                    $('#notification-preloader-circular').hide();

                    // Store fetched data in local component data
                    this.notifications = JSON.parse(response.body[0]);
                    this.token = response.body[1];
                },
                function(response){
                    console.log(response.statusText);
                }
            );
        },

        goToNotification: function(index){
            // View notification. Includes redirecting to designated link and marking it as read

            var vm = this;

            // Do AJAX
            this.$http.patch(
                config.customerNotifications_url + '/seen',
                {
                    _token: this.token,
                    notificationId: this.notifications[index].id,
                }
            ).then(
                function(response){
                    window.setTimeout(function(){
                        window.location = vm.notifications[index].data.url;
                    }, 500);
                },
                function(response){
                    console.log(response.statusText);
                }
            );

        }
    },
    created: function(){
        // Get notifications count
        this.getNotificationInstances();
    }
});
